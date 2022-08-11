@extends('backend.admin-master')
@section('site-title')
    {{__('All Notifications')}}
@endsection
@section('style')
    <x-datatable.css/>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.success/>
                <x-msg.error/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrapp">
                            <h4 class="header-title">{{__('All Notifications')}}  </h4>
                        </div>

                            <x-bulk-action/>

                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <th class="no-sort">
                                    <div class="mark-all-checkbox">
                                        <input type="checkbox" class="all-checkbox">
                                    </div>
                                </th>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Seen Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_notifications as $data)
                                    <tr class="{{ $data['id']}}">
                                        <td>
                                            <x-bulk-delete-checkbox :id="$data->id"/>
                                        </td>
                                        <td>{{$data->id}}</td>
                                        <td>{{ str_replace('_', ' ', ucwords($data->type)) }}</td>
                                        <td>{{($data->title) ?? __('Untitled')}}</td>
                                        <td>{{$data->created_at->diffForHumans()}}</td>

                                        <td>
                                            @if($data->seen == 0)
                                                <span class="badge badge-primary">{{__('New')}}</span>
                                            @else
                                                <span class="badge badge-secondary">{{__('Seen')}}</span>
                                            @endif
                                        </td>

                                        <td>
                                            <x-delete-popover :url="route('admin.notification.delete',$data->id)"/>
                                            <x-view-icon :url="route('admin.notification.view',$data->id)"/>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('backend.partials.datatable.script-enqueue')
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                <x-bulk-action-js :url="route('admin.notification.bulk.action')" />
            });
        })(jQuery);
    </script>
@endsection
