<?php

namespace App\Http\Controllers\Frontend;

use App\Cause;
use App\CauseLogs;
use App\Helpers\DonationHelpers;
use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\EventAttendance;
use App\EventPaymentLogs;
use App\Events;
use App\Mail\DonationMessage;
use App\Mail\PaymentSuccess;
use App\Notification;
use App\PaymentGateway\PaymentGatewaySetup;
use App\PaymentLogs;
use App\Reward;
use Billow\Contracts\PaymentProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use KingFlamez\Rave\Facades\Rave;
use Mollie\Laravel\Facades\Mollie;
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
use Stripe\Stripe;
use Unicodeveloper\Paystack\Facades\Paystack;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;


class CausesLogController extends Controller
{
    const SUCCESS_ROUTE = 'frontend.donation.payment.success';
    const CANCEL_ROUTE = 'frontend.donation.payment.cancel';


    public function store_donation_logs(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'cause_id' => 'required|string',
            'amount' => 'required|string',
            'anonymous' => 'nullable|string',
            'selected_payment_gateway' => 'required|string',
        ],
            [
                'name.required' => __('Name field is required'),
                'email.required' => __('Email field is required'),
                'amount.required' => __('Amount field is required'),
            ]
        );

        $minimum_donation_amount = get_static_option('minimum_donation_amount');
        $msg = __('Minimum Donation Amount is : ');
        if (!empty($minimum_donation_amount) && $request->amount < $minimum_donation_amount) {
            return back()->with(FlashMsg::settings_delete($msg . amount_with_currency_symbol($minimum_donation_amount)));
        }

        if (empty(get_static_option($request->selected_payment_gateway . '_gateway'))) {
            return back()->with(['msg' => __('your selected payment gateway is disable, please select avialble payment gateway'), 'type' => 'danger']);
        }

        $donation_charge_button_status = get_static_option('donation_charge_active_deactive_button');
        $cause_details = Cause::find($request->cause_id);
        if (empty($cause_details)) {
            return back()->with(['msg' => __('donation cause not found'), 'type' => 'danger']);
        }
        $admin_charge = $request->has('admin_tip') ? $request->admin_tip : DonationHelpers::get_donation_charge($request->amount, false);

        $amount = $request->amount;
        $minimum_goal_amount = Reward::where('status','publish')->orderBy('reward_goal_from','asc')->get()->min('reward_goal_from');

        if($cause_details->reward == 'on' && auth()->guard('web')->check() && $amount >= $minimum_goal_amount){
            $reward_point = Reward::select('reward_point')
                ->where('status', 'publish')
                ->where('reward_goal_from', '<=', $amount)
                ->where('reward_goal_to', '>=', $amount)
                ->first();

             $reward_point = optional($reward_point)->reward_point ?? 0;
             $reward_amount = $reward_point / get_static_option('reward_amount_for_point');
        }



        if (!empty($request->order_id)) {
            $payment_log_id = $request->order_id;
        } else {
            $payment_log_id = CauseLogs::create([
                'email' => $request->email,
                'name' => $request->name,
                'cause_id' => $request->cause_id,
                'amount' => $request->amount,
                'admin_charge' => $admin_charge,
                'reward_point' => $reward_point ?? null,
                'reward_amount' => $reward_amount ?? null,
                'anonymous' => !empty($request->anonymous) ? 1 : 0,
                'payment_gateway' => $request->selected_payment_gateway,
                'user_id' => auth()->check() ? auth()->user()->id : '',
                'status' => 'pending',
                'track' => Str::random(10) . Str::random(10),
            ])->id;
        }

        $donation_payment_details = CauseLogs::find($payment_log_id);
        $total_amount = DonationHelpers::get_donation_total($request->amount, false, $request->admin_tip ?? null);

        if(!empty($payment_log_id)){
           Notification::create([
               'cause_log_id'=>$payment_log_id,
               'title'=> 'New donation payment done',
               'type' =>'cause_log',
           ]);
        }

        //have to work on below code
        if ($request->selected_payment_gateway === 'paypal') {

            $redirect_url = XgPaymentGateway::paypal()->charge_customer(
                $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.donation.paypal.ipn'))
            );
            session()->put('donation_log_id', $donation_payment_details->id);
            return redirect()->away($redirect_url);


        } elseif ($request->selected_payment_gateway === 'paytm') {

            $redirect_url = XgPaymentGateway::paytm()->charge_customer(
                $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.donation.paytm.ipn'))
            );
            return $redirect_url;

        } elseif ($request->selected_payment_gateway === 'manual_payment') {
            $this->validate($request, [
                'manual_payment_attachment' => 'required|file'
            ], ['manual_payment_attachment.required' => __('Bank Attachment Required')]);

            $fileName = time().'.'.$request->manual_payment_attachment->extension();
            $request->manual_payment_attachment->move('assets/uploads/attachment/', $fileName);

            CauseLogs::where('cause_id', $request->cause_id)->update(['manual_payment_attachment' => $fileName]);
            $order_id = Str::random(6) . $donation_payment_details->id . Str::random(6);
            return redirect()->route(self::SUCCESS_ROUTE, $order_id);

        } elseif ($request->selected_payment_gateway === 'stripe') {

            $redirect_url = XgPaymentGateway::stripe()->charge_customer(
                $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.donation.stripe.ipn'))
            );
            return $redirect_url;

        } elseif ($request->selected_payment_gateway === 'razorpay') {

            $redirect_url = XgPaymentGateway::razorpay()->charge_customer(
                $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.donation.razorpay.ipn'))
            );
            return $redirect_url;

        } elseif ($request->selected_payment_gateway === 'paystack') {

            $redirect_url = XgPaymentGateway::paystack()->charge_customer(
                $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.event.paystack.ipn'),'donation')
            );
            return $redirect_url;


        } elseif ($request->selected_payment_gateway === 'mollie') {

            $redirect_url = XgPaymentGateway::mollie()->charge_customer(
                $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.donation.mollie.ipn'))
            );
            return $redirect_url;

        } elseif ($request->selected_payment_gateway === 'flutterwave') {

            $redirect_url = XgPaymentGateway::flutterwave()->charge_customer(
                $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.donation.flutterwave.ipn'))
            );
            return $redirect_url;

        } elseif ($request->selected_payment_gateway === 'payfast') {

            $redirect_url = XgPaymentGateway::payfast()->charge_customer(
                $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.donation.payfast.ipn'))
            );
            session()->put('donation_log_id', $donation_payment_details->id);
            return $redirect_url;


          } elseif ($request->selected_payment_gateway === 'midtrans') {

            $redirect_url = XgPaymentGateway::midtrans()->charge_customer(
                  $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.donation.midtrans.ipn'))
                );
            return $redirect_url;
         }

        elseif ($request->selected_payment_gateway === 'cashfree') {

            $redirect_url = XgPaymentGateway::cashfree()->charge_customer(
                $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.donation.cashfree.ipn'))
            );
            return $redirect_url;
        }

        elseif ($request->selected_payment_gateway === 'instamojo') {

            $redirect_url = XgPaymentGateway::instamojo()->charge_customer(
                $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.donation.instamojo.ipn'))
            );
            return $redirect_url;
        }

        elseif ($request->selected_payment_gateway === 'marcadopago') {

            $redirect_url = XgPaymentGateway::marcadopago()->charge_customer(
                $this->common_charge_customer_data($total_amount,$donation_payment_details,route('frontend.donation.marcadopago.ipn'))
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

    public function flutterwave_ipn(Request $request)
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



    private function common_charge_customer_data($total_amount,$donation_payment_details, $ipn_route, $payment_type = null)
    {
        $data = [
                'amount' => $total_amount,
                'title' => __('Payment For Donation:') . ' ' . optional($donation_payment_details->cause)->title ?? '',
                'description' => __('Payment For Donation:') . ' ' . optional($donation_payment_details->cause)->title ?? '' . ' #' . $donation_payment_details->id,
                'order_id' => $donation_payment_details->id,
                'track' => $donation_payment_details->track,
                'cancel_url' => route(self::CANCEL_ROUTE, $donation_payment_details->id),
                'success_url' => route(self::SUCCESS_ROUTE, random_int(333333, 999999) . $donation_payment_details->id . random_int(333333, 999999)),
                'email' => $donation_payment_details->email, // user email
                'name' => $donation_payment_details->name, // user name
                'payment_type' => $payment_type, // which kind of payment your are receiving
                'ipn_url' => $ipn_route
             ];
        return $data;
    }

    private function common_ipn_data($payment_data)
    {


        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            event(new Events\DonationSuccess([
                'donation_log_id' => $payment_data['order_id'],
                'transaction_id' => $payment_data['transaction_id'],
            ]));
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
            return redirect()->route(self::SUCCESS_ROUTE, $order_id);
        }

        return redirect()->route(self::CANCEL_ROUTE);
    }




}
