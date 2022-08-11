@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/colorpicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
    <x-media.css/>
@endsection
@section('site-title')
    {{__('Tax Label Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">  {{__('Tax Label Settings')}}</h4>
                        <form action="{{route('admin.tax.information.label.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                                <div class="form-group">
                                    <label >{{__('Monthly Income Label')}}</label >
                                    <input type="text" name="monthly_income_label"  class="form-control" value="{{get_static_option('monthly_income_label')}}" id="company_name">
                                </div>
                                <div class="form-group">
                                    <label>{{__('Annual Income Label')}}</label>
                                    <input type="text" name="annual_icome_label"  class="form-control" value="{{get_static_option('annual_icome_label')}}" id="company_address">
                                </div>
                                <div class="form-group">
                                    <label>{{__('Income Source Label')}}</label>
                                    <input type="text" name="income_source_label"  class="form-control" value="{{get_static_option('income_source_label')}}" id="company_contact">
                                </div>

                                <div class="form-group">
                                    <label>{{__('NID Image Label')}}</label>
                                    <input type="text" name="nid_image_label"  class="form-control" value="{{get_static_option('nid_image_label')}}" id="company_contact">
                                </div>

                                <div class="form-group">
                                    <label>{{__('Driving License Image Label')}}</label>
                                    <input type="text" name="driving_license_image_label"  class="form-control" value="{{get_static_option('driving_license_image_label')}}" id="company_contact">
                                </div>

                                <div class="form-group">
                                    <label>{{__('Passport Image Label')}}</label>
                                    <input type="text" name="passport_image_label"  class="form-control" value="{{get_static_option('passport_image_label')}}" id="company_contact">
                                </div>



                            <button id="update" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
@section('script')
    <script src="{{asset('assets/backend/js/colorpicker.js')}}"></script>
    <x-media.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                <x-btn.update/>
            });
        }(jQuery));
    </script>
@endsection
