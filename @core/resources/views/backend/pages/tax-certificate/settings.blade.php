@extends('backend.admin-master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/colorpicker.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
    <x-media.css/>
@endsection
@section('site-title')
    {{__('Tax Information Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Tax Information Settings')}}</h4>
                        <form action="{{route('admin.tax.information.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                                <div class="form-group">
                                    <label >{{__('Company Name')}}</label >
                                    <input type="text" name="company_name"  class="form-control" value="{{get_static_option('company_name')}}" id="company_name">
                                </div>
                                <div class="form-group">
                                    <label>{{__('Address')}}</label>
                                    <input type="text" name="company_address"  class="form-control" value="{{get_static_option('company_address')}}" id="company_address">
                                </div>
                                <div class="form-group">
                                    <label>{{__('Contact')}}</label>
                                    <input type="text" name="company_contact"  class="form-control" value="{{get_static_option('company_contact')}}" id="company_contact">
                                </div>

                                  <label>{{__('Signature Image')}}</label>
                                <x-image :id="'company_signature_image'" name="company_signature_image" :value="'company_signature_image'" :title="'CompanySignature Image'"/>


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
