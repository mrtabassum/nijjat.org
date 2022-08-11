@extends('frontend.user.dashboard.user-master')
@section('site-title')
    {{__('Tax Information')}}
@endsection

@section('style')
    <x-media.css/>
@endsection

@section('section')
    <div class="dashboard-form-wrapper">

        <div class="row">
            <div class="col-md-12">
                <h2 class="title">{{__('Tax Information')}}

                </h2>

            </div>
        </div>
        <form action="{{route('user.home.tax.information.update')}}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">

            <div class="form-group col-md-6">
                <label for="name">{{get_static_option('monthly_income_label') ?? __('Monthly Income')}}</label>
                <input type="number" class="form-control" id="name" name="monthly_income" value="{{$user_details->monthly_income}}">
            </div>
            <div class="form-group col-md-6">
                <label for="email">{{get_static_option('annual_icome_label') ?? __('Annual Income')}}</label>
                <input type="number" class="form-control" id="email" name="annual_income" value="{{$user_details->annual_income}}">
            </div>

            <div class="form-group col-md-12">
                <label for="email">{{get_static_option('income_source_label') ??__('Income Source')}}</label>
                <input type="text" class="form-control" id="email" name="income_source" value="{{$user_details->income_source}}">
            </div>

            <div class="form-group mt-5 col-md-4">
                <label for="image">{{get_static_option('nid_image_label') ??__('NID Image')}}</label>
                <div class="media-upload-btn-wrapper">
                    <div class="img-wrap">
                         {!! render_attachment_preview_for_admin($user_details->nid_image) !!}
                    </div>
                    <input type="hidden" name="nid_image" value="{{ $user_details->nid_image }}">
                    <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="{{__('Select Image')}}" data-modaltitle="{{__('Upload Image')}}" data-toggle="modal" data-target="#media_upload_modal">
                        {{__('Upload Image')}}
                    </button>
                </div>
                <small>{{__('Recommended image size 150x150')}}</small>
            </div>

            <div class="form-group col-md-4 mt-5">
                <label for="image">{{get_static_option('driving_license_image_label') ?? __('Driving License Image')}}</label>
                <div class="media-upload-btn-wrapper">
                    <div class="img-wrap">
                        {!! render_attachment_preview_for_admin($user_details->driving_license_image) !!}
                    </div>
                    <input type="hidden" name="driving_license_image" value="{{ $user_details->driving_license_image }}">
                    <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="{{__('Select Image')}}" data-modaltitle="{{__('Upload Image')}}" data-toggle="modal" data-target="#media_upload_modal">
                        {{__('Upload Image')}}
                    </button>
                </div>
                <small>{{__('Recommended image size 150x150')}}</small>
            </div>

            <div class="form-group col-md-4 mt-5">
                <label for="image">{{get_static_option('passport_image_label')  ?? __('Passport Image')}}</label>
                <div class="media-upload-btn-wrapper">
                    <div class="img-wrap">
                        {!! render_attachment_preview_for_admin($user_details->passport_image) !!}
                    </div>
                    <input type="hidden" name="passport_image" value="{{ $user_details->passport_image }}">
                    <button type="button" class="btn btn-info media_upload_form_btn" data-btntitle="{{__('Select Image')}}" data-modaltitle="{{__('Upload Image')}}" data-toggle="modal" data-target="#media_upload_modal">
                        {{__('Upload Image')}}
                    </button>
                </div>
                <small>{{__('Recommended image size 150x150')}}</small>
            </div>

            </div>

            <div class="btn-wrapper">
                <button id="update" type="submit" class="boxed-btn reverse-color">{{__('Save changes')}}</button>
            </div>
        </form>
   </div>



    <x-media.markup
            :userUpload="true"
            :imageUploadRoute="route('user.upload.media.file')">
    </x-media.markup>
@endsection

@section('scripts')
    <script>
        <x-btn.update/>
    </script>
    <x-media.js
            :deleteRoute="route('user.upload.media.file.delete')"
            :imgAltChangeRoute="route('user.upload.media.file.alt.change')"
            :allImageLoadRoute="route('user.upload.media.file.all')">
    </x-media.js>
@endsection
