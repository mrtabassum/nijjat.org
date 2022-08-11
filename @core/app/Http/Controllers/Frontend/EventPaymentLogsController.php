<?php

namespace App\Http\Controllers\Frontend;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\EventAttendance;
use App\EventPaymentLogs;
use App\Events;
use App\Events\JobApplication;
use App\Helpers\DonationHelpers;
use App\Http\Controllers\Controller;
use App\Http\Traits\PaytmTrait;
use App\Mail\ContactMessage;
use App\Mail\PaymentSuccess;
use App\Order;
use App\PaymentGateway\PaymentGatewaySetup;
use App\PaymentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use KingFlamez\Rave\Facades\Rave;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Razorpay\Api\Api;
use Stripe\Charge;
use Mollie\Laravel\Facades\Mollie;
use Stripe\Stripe;
use Unicodeveloper\Paystack\Facades\Paystack;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;
use function App\Http\Traits\getChecksumFromArray;

class EventPaymentLogsController extends Controller
{
    private const CANCEL_ROUTE = 'frontend.event.payment.cancel';
    private const SUCCESS_ROUTE = 'frontend.event.payment.success';

    const DONATION_SUCCESS_ROUTE = 'frontend.donation.payment.success';
    const DONATION_CANCEL_ROUTE = 'frontend.donation.payment.cancel';

    private const JOB_CANCEL_ROUTE = 'frontend.job.payment.cancel';
    private const JOB_SUCCESS_ROUTE = 'frontend.job.payment.success';

    public function booking_payment_form(Request $request){
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'attendance_id' => 'required|string'
        ],
            [
                'name.required' => __('Name field is required'),
                'email.required' => __('Email field is required')
            ]);

        if (!get_static_option('disable_guest_mode_for_event_module') && !auth()->guard('web')->check()){
            return back()->with(['type' => 'warning','msg' => __('login to place an order')]);
        }

        $event_details = EventAttendance::find($request->attendance_id);
        $event_info = Events::find($event_details->event_id);
        $event_payment_details = EventPaymentLogs::where('attendance_id',$request->attendance_id)->first();

        if (!empty($event_info->cost) && $event_info->cost > 0){
            $this->validate($request,[
                'payment_gateway' => 'required|string'
            ],[
                'payment_gateway.required' => __('Select A Payment Method')
            ]);
        }

        if (empty($event_payment_details)){
            $payment_log_id = EventPaymentLogs::create([
                'email' =>  $request->email,
                'name' =>  $request->name,
                'event_name' =>  $event_details->event_name,
                'event_cost' =>  ($event_details->event_cost * $event_details->quantity),
                'package_gateway' =>  $request->payment_gateway,
                'attendance_id' =>  $request->attendance_id,
                'status' =>  'pending',
                'track' =>  Str::random(10). Str::random(10),
            ])->id;
            $event_payment_details = EventPaymentLogs::find($payment_log_id);
        }

        //have to work on below code
        if ($request->payment_gateway === 'paypal'){

            $redirect_url = XgPaymentGateway::paypal()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.paypal.ipn'))
            );
            session()->put('attendance_id',$event_details->id);
            return redirect()->away($redirect_url);

        }elseif ($request->payment_gateway === 'paytm'){

            $redirect_url = XgPaymentGateway::paytm()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.paytm.ipn'))
            );
            return $redirect_url;

        }elseif ($request->payment_gateway === 'manual_payment'){
            //fire event
            event(new Events\AttendanceBooking([
                'attendance_id' => $request->attendance_id,
                'transaction_id' => $request->trasaction_id
            ]));

            $order_id = Str::random(6).$event_payment_details->attendance_id.Str::random(6);
            return redirect()->route(self::SUCCESS_ROUTE,$order_id);

        }elseif ($request->payment_gateway === 'stripe'){

            $redirect_url = XgPaymentGateway::stripe()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.stripe.ipn'))
            );
            return $redirect_url;
        }
        elseif ($request->payment_gateway === 'razorpay'){

            $redirect_url = XgPaymentGateway::razorpay()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.razorpay.ipn'))
            );
            return $redirect_url;

        }
        elseif ($request->payment_gateway == 'paystack'){

            $redirect_url = XgPaymentGateway::paystack()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.paystack.ipn'),'event')
            );
            return $redirect_url;

        }
        elseif ($request->payment_gateway == 'mollie'){

            $redirect_url = XgPaymentGateway::mollie()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.mollie.ipn'))
            );
            return $redirect_url;

        }elseif ($request->payment_gateway == 'flutterwave'){

            $redirect_url = XgPaymentGateway::flutterwave()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.flutterwave.ipn'))
            );
            return $redirect_url;

        }elseif ($request->payment_gateway === 'payfast') {

            $redirect_url = XgPaymentGateway::payfast()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.payfast.ipn'))
            );
            return $redirect_url;

        } elseif ($request->payment_gateway === 'midtrans') {

            $redirect_url = XgPaymentGateway::midtrans()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.midtrans.ipn'))
            );
            return $redirect_url;
        }

        elseif ($request->payment_gateway === 'cashfree') {

            $redirect_url = XgPaymentGateway::cashfree()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.cashfree.ipn'))
            );
            return $redirect_url;
        }

        elseif ($request->payment_gateway === 'instamojo') {

            $redirect_url = XgPaymentGateway::instamojo()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.instamojo.ipn'))
            );
            return $redirect_url;
        }

        elseif ($request->payment_gateway === 'marcadopago') {

            $redirect_url = XgPaymentGateway::marcadopago()->charge_customer(
                $this->common_charge_customer_data($event_details,$event_payment_details,route('frontend.event.marcadopago.ipn'))
            );
            return $redirect_url;
        }

        return redirect()->route('homepage');
    }



    public function paypal_ipn()
    {
        $payment_data = XgPaymentGateway::paypal()->ipn_response();
        return $this->common_ipn_data($payment_data);
    }


    public function paytm_ipn()
    {
        $payment_data = XgPaymentGateway::paytm()->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function flutterwave_ipn()
    {
        $payment_data = XgPaymentGateway::flutterwave()->ipn_response();
        return $this->common_ipn_data($payment_data);
    }


    public function stripe_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::stripe()->ipn_response();
        return $this->common_ipn_data($payment_data);
    }


    public function razorpay_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::razorpay()->ipn_response();
        return $this->common_ipn_data($payment_data);
    }


    public function payfast_ipn(Request $request)
    {
        $payment_data = XgPaymentGateway::payfast()->ipn_response();
        return $this->common_ipn_data($payment_data);
    }


    public function mollie_ipn()
    {
        $payment_data = XgPaymentGateway::mollie()->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function paystack_ipn()
    {
        $payment_data = XgPaymentGateway::paystack()->ipn_response();

        if ($payment_data['type'] === 'event'){
            return $this->common_ipn_data($payment_data);

        }elseif ($payment_data['type'] === 'donation'){

            return $this->common_ipn_data_donation($payment_data);

        } else{
            return $this->common_ipn_data_job($payment_data);
        }
    }

    public function midtrans_ipn()
    {
        $payment_data = XgPaymentGateway::midtrans()->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function cashfree_ipn()
    {

        $payment_data = XgPaymentGateway::cashfree()->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function instamojo_ipn()
    {
        $payment_data = XgPaymentGateway::instamojo()->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function marcadopago_ipn()
    {
        $payment_data = XgPaymentGateway::marcadopago()->ipn_response();
        return $this->common_ipn_data($payment_data);
    }



   private function common_charge_customer_data($event_details,$event_payment_details,$ipn_route,$payment_type = null)
   {
        $data = [
            'amount' =>$event_details->event_cost * $event_details->quantity,
            'title' =>  $event_payment_details->name ?? '',
            'description' => 'Payment For Event Attendance Id: #'.$event_details->id.' Payer Name: '.$event_payment_details->name.' Payer Email:'.$event_payment_details->email,
            'order_id' =>$event_details->id,
            'track' =>  $event_payment_details->track,
            'cancel_url' => route(self::CANCEL_ROUTE, $event_payment_details->attendance_id),
            'success_url' => route(self::SUCCESS_ROUTE, random_int(333333,999999).$event_payment_details->attendance_id.random_int(333333,999999)),
            'email' => $event_payment_details->email,
            'name' => $event_payment_details->name,
            'payment_type' => $payment_type,
            'ipn_url' => $ipn_route
        ];

        return $data;
    }

   private function common_ipn_data($payment_data)
    {
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            event(new Events\AttendanceBooking([
                'attendance_id' => $payment_data['order_id'],
                'transaction_id' => $payment_data['transaction_id']
            ]));
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(10);
            return redirect()->route(self::SUCCESS_ROUTE, $order_id);
        }

        $order_id = Str::random(6) . $payment_data['order_id']. Str::random(10);
        return redirect()->route(self::CANCEL_ROUTE, $order_id);
    }

    private function common_ipn_data_donation($payment_data)
    {
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            event(new Events\DonationSuccess([
                'donation_log_id' => $payment_data['order_id'],
                'transaction_id' => $payment_data['transaction_id'],
            ]));
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
            return redirect()->route(self::DONATION_SUCCESS_ROUTE, $order_id);
        }
        $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
        return redirect()->route(self::DONATION_CANCEL_ROUTE, $order_id);
    }

    public function common_ipn_data_job($payment_data){

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            event(new JobApplication([
                'transaction_id' => $payment_data['transaction_id'],
                'job_application_id' =>$payment_data['order_id']
            ]));
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
            return redirect()->route(self::JOB_SUCCESS_ROUTE,$order_id);
        }
        $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
        return redirect()->route(self::JOB_CANCEL_ROUTE,$order_id);
    }

}
