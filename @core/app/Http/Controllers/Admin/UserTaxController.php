<?php

namespace App\Http\Controllers\Admin;

use App\CauseLogs;
use App\Helpers\FlashMsg;
use App\Http\Controllers\Controller;
use App\TaxLog;
use App\Testimonial;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTaxController extends Controller
{
    public function __construct(){
        $this->middleware('auth:admin');
        $this->middleware('permission:user-tax-list|user-tax-delete');
    }

    public function all_tax_requests(){
        $all_certificate_requests = TaxLog::all();
        return view('backend.pages.tax-certificate.all-requests',compact('all_certificate_requests'));
    }

    public function generate_certificate(Request $request)
    {

        $this->validate($request, [
            'title' => 'required|max:191',
            'description' => 'required|string',
        ]);

        $request_user_details = TaxLog::find($request->request_id);
        $user_id =  $request_user_details->user_id;
        $user_request_start_date =  $request_user_details->start_date;
        $user_request_end_date =  $request_user_details->end_date;


        $dateS = new Carbon($user_request_start_date);
        $dateE = new Carbon($user_request_end_date);
        $donation_details = CauseLogs::where('user_id',$user_id)->whereBetween('created_at', [$dateS->format('Y-m-d')." 00:00:00", $dateE->format('Y-m-d')." 23:59:59"])->get();

        $tax_title = $request->title;
        $tax_description =$request->description;
        $tax_note =$request->note;
        $certificate_date =$request->date;

        $pdf = PDF::loadView('backend.pages.tax-certificate.certificate-invoice', compact('request_user_details','tax_title','tax_description','tax_note','certificate_date','donation_details'));
        return $pdf->download('tax-certificate.pdf');
    }

    public function send_generated_certificate(Request $request)
    {
        $this->validate($request, [
            'attachment' => 'required',
        ]);

        $data = TaxLog::find($request->id);
        if (file_exists('assets/uploads/certificate/'.$data->attachment) && !is_dir('assets/uploads/certificate/' . $data->attachment)) {
            unlink('assets/uploads/certificate/'.$data->attachment);
        }

        $fileName = time().'.'.$request->attachment->extension();
        $request->attachment->move('assets/uploads/certificate/', $fileName);
        TaxLog::where('id',$request->id)->update(['attachment'=>$fileName]);

        if($data->status == 'pending') {
            $data->status = 'submited';
            $data->save();
        }else{
            $data->status = 'submited';
            $data->save();
        }

        return redirect()->back()->with(FlashMsg::settings_update('Certificate Sent Successfully'));
    }


    public function delete($id){
        TaxLog::find($id)->delete();
        return redirect()->back()->with(FlashMsg::item_delete('Caertificate Request Deleted'));
    }

    public function bulk_action(Request $request){
        $all = TaxLog::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }


    public function tax_settings_page(Request $request)
    {
        return view('backend.pages.tax-certificate.settings');
    }

    public function tax_settings_update(Request $request)
    {
        $this->validate($request, [
            'company_name' => 'required|string',
            'company_address' => 'required|string',
            'company_contact' => 'required|string',
            'company_signature_image' => 'required|string',
        ]);

        $data = [
            'company_name',
            'company_address',
            'company_contact',
            'company_signature_image',
        ];

        foreach ($data as $item){
            if($request->has($item)){
                update_static_option($item,$request->$item);
            }
        }

        return redirect()->back()->with(FlashMsg::settings_update('Data Updated Successfully'));

    }


    public function tax_label_settings_page(Request $request)
    {
        return view('backend.pages.tax-certificate.others-settings');
    }

    public function tax_label_settings_update(Request $request)
    {
        $this->validate($request, [
            'monthly_income_label' => 'nullable|string',
            'annual_icome_label' => 'nullable|string',
            'income_source_label' => 'nullable|string',
            'nid_image_label' => 'nullable|string',
            'driving_license_image_label' => 'nullable|string',
            'passport_image_label' => 'nullable|string',
        ]);

        $data = [
            'monthly_income_label',
            'annual_icome_label',
            'income_source_label',
            'nid_image_label',
            'driving_license_image_label',
            'passport_image_label',
        ];

        foreach ($data as $item){
           update_static_option($item,$request->$item);
        }

        return redirect()->back()->with(FlashMsg::settings_update('Data Updated Successfully'));

    }







}
