<?php

namespace App\Http\Controllers\Frontend;
use App\Events\JobApplication;
use App\Helpers\DonationHelpers;
use App\PaymentGateway\PaymentGatewaySetup;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\JobApplicant;
use App\Jobs;
use App\Mail\BasicMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use KingFlamez\Rave\Facades\Rave;
use Mollie\Laravel\Facades\Mollie;
use Razorpay\Api\Api;
use Stripe\Charge;
use Stripe\Stripe;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;


class JobPaymentController extends Controller
{
    private const CANCEL_ROUTE = 'frontend.job.payment.cancel';
    private const SUCCESS_ROUTE = 'frontend.job.payment.success';

    public function store_jobs_applicant_data(Request $request)
    {
        $jobs_details = Jobs::find($request->job_id);
        $this->validate($request,[
            'email' => 'required|email',
            'name' => 'required|string',
            'job_id' => 'required',
        ],[
            'email.required' => __('email is required'),
            'email.email' => __('enter valid email'),
            'name.required' => __('name is required'),
            'job_id.required' => __('must apply to any job'),
        ]);
        if (!empty($jobs_details->application_fee_status) && $jobs_details->application_fee > 0){
            $this->validate($request,[
                'selected_payment_gateway' => 'required|string'
            ],
                ['selected_payment_gateway.required' => __('You must have to select a payment gateway')]);
        }

        if (!empty($jobs_details->application_fee_status) && $jobs_details->application_fee > 0 && $request->selected_payment_gateway == 'manual_payment'){
            $this->validate($request,[
                'transaction_id' => 'required|string'
            ],
                ['transaction_id.required' => __('You must have to provide your transaction id')]);
        }

        $job_applicant_id = JobApplicant::create([
            'jobs_id' => $request->job_id,
            'payment_gateway' => $request->selected_payment_gateway,
            'email' => $request->email,
            'name' => $request->name,
            'application_fee' => $request->application_fee,
            'track' => Str::random(30),
            'payment_status' => 'pending',
        ])->id;

        $all_attachment = [];
        $all_quote_form_fields = (array) json_decode(get_static_option('apply_job_page_form_fields'));
        $all_field_type = isset($all_quote_form_fields['field_type']) ? $all_quote_form_fields['field_type'] : [];
        $all_field_name = isset($all_quote_form_fields['field_name']) ? $all_quote_form_fields['field_name'] : [];
        $all_field_required = isset($all_quote_form_fields['field_required']) ? $all_quote_form_fields['field_required'] : [];
        $all_field_required = (object) $all_field_required;
        $all_field_mimes_type = isset($all_quote_form_fields['mimes_type']) ? $all_quote_form_fields['mimes_type'] : [];
        $all_field_mimes_type = (object) $all_field_mimes_type;

        //get field details from, form request
        $all_field_serialize_data = $request->all();
        unset($all_field_serialize_data['_token'],$all_field_serialize_data['job_id'],$all_field_serialize_data['name'],$all_field_serialize_data['email'],$all_field_serialize_data['selected_payment_gateway']);

        if (!empty($all_field_name)){
            foreach ($all_field_name as $index => $field){
                $is_required = property_exists($all_field_required,$index) ? $all_field_required->$index : '';
                $mime_type = property_exists($all_field_mimes_type,$index) ? $all_field_mimes_type->$index : '';
                $field_type = isset($all_field_type[$index]) ? $all_field_type[$index] : '';
                if (!empty($field_type) && $field_type == 'file'){
                    unset($all_field_serialize_data[$field]);
                }
                $validation_rules = !empty($is_required) ? 'required|': '';
                $validation_rules .= !empty($mime_type) ? $mime_type : '';

                $this->validate($request,[
                    $field => $validation_rules
                ]);

                if ($field_type == 'file' && $request->hasFile($field)) {
                    $filed_instance = $request->file($field);
                    $file_extenstion = $filed_instance->getClientOriginalExtension();
                    $attachment_name = 'attachment-'.$job_applicant_id.'-'. $field .'.'. $file_extenstion;
                    $filed_instance->move('assets/uploads/attachment/applicant', $attachment_name);
                    $all_attachment[$field] = 'assets/uploads/attachment/applicant/' . $attachment_name;
                }
            }
        }


        //update database
        JobApplicant::where('id',$job_applicant_id)->update([
            'form_content' => serialize($all_field_serialize_data),
            'attachment' => serialize($all_attachment)
        ]);
        $job_applicant_details = JobApplicant::where('id',$job_applicant_id)->first();

        //check it application fee applicable or not
        if (!empty($jobs_details->application_fee_status) && $jobs_details->application_fee > 0){
            //have to redirect  to payment gateway route

            if($job_applicant_details->payment_gateway === 'paypal'){

                $redirect_url = XgPaymentGateway::paypal()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.job.paypal.ipn'))
                );

                session()->put('job_application_id',$job_applicant_details->id);
                return redirect()->away($redirect_url);


            }elseif ($job_applicant_details->payment_gateway === 'paytm'){

                $redirect_url = XgPaymentGateway::paytm()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.job.paytm.ipn'))
                );
                return $redirect_url;

            }elseif ($job_applicant_details->payment_gateway === 'manual_payment'){

                event(new JobApplication([
                    'transaction_id' => $request->transaction_id,
                    'job_application_id' => $job_applicant_details->id
                ]));

                return redirect()->route(self::SUCCESS_ROUTE,random_int(666666,999999).$job_applicant_details->id.random_int(999999,999999));

            }elseif ($job_applicant_details->payment_gateway === 'stripe'){

                $redirect_url = XgPaymentGateway::stripe()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.job.stripe.ipn'))
                );
                return $redirect_url;

            }elseif ($job_applicant_details->payment_gateway === 'razorpay'){

                $redirect_url = XgPaymentGateway::razorpay()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.job.razorpay.ipn'))
                );
                return $redirect_url;

            }elseif ($job_applicant_details->payment_gateway === 'paystack'){

                $redirect_url = XgPaymentGateway::paystack()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.event.paystack.ipn'),'job')
                );
                return $redirect_url;

            }elseif ($job_applicant_details->payment_gateway === 'mollie'){

                $redirect_url = XgPaymentGateway::mollie()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.job.mollie.ipn'))
                );
                return $redirect_url;

            }elseif ($job_applicant_details->payment_gateway === 'flutterwave'){

                $redirect_url = XgPaymentGateway::flutterwave()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.job.flutterwave.ipn'))
                );
                return $redirect_url;

            }elseif ($job_applicant_details->payment_gateway === 'payfast') {

                $redirect_url = XgPaymentGateway::payfast()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.job.payfast.ipn'))
                );
                return $redirect_url;

            } elseif ($job_applicant_details->payment_gateway === 'midtrans') {

                $redirect_url = XgPaymentGateway::midtrans()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.job.midtrans.ipn'))
                );
                return $redirect_url;
            }

            elseif ($job_applicant_details->payment_gateway === 'cashfree') {

                $redirect_url = XgPaymentGateway::cashfree()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.job.cashfree.ipn'))
                );
                return $redirect_url;
            }

            elseif ($job_applicant_details->payment_gateway === 'instamojo') {

                $redirect_url = XgPaymentGateway::instamojo()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.job.instamojo.ipn'))
                );
                return $redirect_url;
            }

            elseif ($job_applicant_details->payment_gateway === 'marcadopago') {

                $redirect_url = XgPaymentGateway::marcadopago()->charge_customer(
                    $this->common_charge_customer_data($job_applicant_details,$jobs_details,route('frontend.job.marcadopago.ipn'))
                );
                return $redirect_url;
            }

            return redirect()->route('homepage');

        }else{
            $succ_msg = get_static_option('apply_job_success_message');
            $success_message = !empty($succ_msg) ? $succ_msg : __('Your Application Is Submitted Successfully!!');

            event(new JobApplication([
                'transaction_id' => null,
                'job_application_id' => $job_applicant_details->id
            ]));
            return redirect()->back()->with(['msg' => $success_message, 'type' => 'success']);
        }
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

    private function common_charge_customer_data($job_applicant_details,$jobs_details,$ipn_route,$payment_type = null)
    {
        $data = [
            'amount' => $job_applicant_details->application_fee,
            'title' =>   $job_applicant_details->name,
            'order_id' => $jobs_details->id,
            'track' => $job_applicant_details->track,
            'cancel_url' => route(self::CANCEL_ROUTE, $jobs_details->id),
            'success_url' =>  route(self::SUCCESS_ROUTE, random_int(333333,999999).$jobs_details->id.random_int(333333,999999)),
            'email' =>  $job_applicant_details->email,
            'name' =>  $job_applicant_details->name,
            'payment_type' => $payment_type,
            'ipn_url' => $ipn_route,
            'description' => __('Payment For Job Application Id:'). '#'.$job_applicant_details->id.' '.__('Job Title:').' '.$jobs_details->title.' 
            '.__('Applicant Name:').' '.$job_applicant_details->name.' '.__('Applicant Email:').' '.$job_applicant_details->email,
        ];

        return $data;
    }

    private function common_ipn_data($payment_data){

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            event(new JobApplication([
                'transaction_id' => $payment_data['transaction_id'],
                'job_application_id' =>$payment_data['order_id']
            ]));
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
            return redirect()->route(self::SUCCESS_ROUTE,$order_id);
        }
        $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
        return redirect()->route(self::CANCEL_ROUTE,$order_id);
    }


}
