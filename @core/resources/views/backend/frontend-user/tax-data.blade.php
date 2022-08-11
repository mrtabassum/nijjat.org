@extends('backend.admin-master')
@section('site-title')
    {{__('User Tax Data')}}
@endsection

@section('style')
    <x-media.css/>
@endsection
@section('content')
            <div class="col-lg-12 col-ml-12 padding-bottom-30">
                <div class="row">
                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="header-title"> {{__('User Tax Data')}}
                                    <a class="btn btn-info btn-xs pull-right" href="{{route('admin.all.frontend.user')}}"> {{__('All User')}}</a>
                                </h4>

                                <ul>
                                    <li class="list-item my-2">{{__('ID : ')}} {{$user_tax->id}}</li>
                                    <li class="list-item my-2">{{get_static_option('monthly_income_label') ??__('Monthly Income : ')}} {{$user_tax->monthly_income}}</li>
                                    <li class="list-item my-2 mb-3">{{get_static_option('annual_icome_label') ?? __('Annual Income : ')}} {{$user_tax->annual_income}}</li>
                                    <li class="list-item my-2 mb-3">{{get_static_option('income_source_label') ?? __(' Income Source : ')}} {{$user_tax->income_source}}</li>

                                    <li class="list-item my-2">{{get_static_option('nid_image_label') ?? __('NID Image : ')}}
                                        {!! render_attachment_preview_for_admin($user_tax->nid_image) !!}
                                    </li>
                                    <li class="list-item my-2">{{get_static_option('driving_license_image_label') ?? __('Driving License Image : ')}}
                                        {!! render_attachment_preview_for_admin($user_tax->driving_license_image) !!}
                                    </li>
                                    <li class="list-item my-2">{{get_static_option('passport_image_label') ?? __('Passport Image : ')}}
                                        {!! render_attachment_preview_for_admin($user_tax->passport_image) !!}
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <x-media.markup/>
@endsection

@section('script')
    <x-media.js/>
@endsection

