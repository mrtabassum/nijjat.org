@extends('backend.admin-master')
@section('site-title')
    {{__('Register Page Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                @include('backend.partials.message')
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title"> {{__('Register Page Settings')}}</h4>
                        <form action="{{route('admin.register.page.settings')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="site_title">{{__('Terms and ConditionURL')}}</label>
                                <input type="text" name="register_page_terms_of_service_url"  class="form-control" value="{{get_static_option('register_page_terms_of_service_url')}}">
                            </div>

                            <div class="form-group">
                                <label for="site_tag_line">{{__('Privacy Policy URL')}}</label>
                                <input type="text" name="register_page_privacy_policy_url"  class="form-control" value="{{get_static_option('register_page_privacy_policy_url')}}">
                            </div>

                            <button id="update" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

    <script>
        (function($){
            "use strict";
            $(document).ready(function(){
                <x-btn.update/>
            });
        }(jQuery));
    </script>
@endsection
