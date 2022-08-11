@extends('backend.admin-master')
@section('site-title')
    {{__('Tax Certificate Requests')}}
@endsection
@section('style')
    <x-media.css/>
    <x-datatable.css/>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.error/>
                <x-msg.success/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('All Tax Certificate Request Items')}}</h4>
                        <div class="note ">
                            <p class="text-success">Note : {{__('You need to generate a certificate first than download it, after that you have to attach the downloaded file with clicking (Send Certificate) to send your  user.')}}</p>
                        </div>

                        @can('user-tax-delete')
                        <div class="bulk-delete-wrapper">
                                <div class="select-box-wrap">
                                    <x-bulk-action/>
                                </div>
                        </div>
                        @endcan

                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-th/>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Username')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Start Date')}}</th>
                                <th>{{__('End Date ')}}</th>
                                <th>{{__('Certificate ')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_certificate_requests as $data)
                                    <tr>
                                        <td><x-bulk-delete-checkbox :id="$data->id"/></td>
                                        <td>{{$data->id}}</td>
                                        <td>{{optional($data->user)->name}}</td>
                                        <td>{{$data->title}}</td>
                                        <td>{{date('d-m-Y',strtotime($data->start_date))}}</td>
                                        <td>{{date('d-m-Y',strtotime($data->end_date))}}</td>
                                        <td>
                                            @if(!is_null($data->attachment))
                                                <a class="btn btn-success" download="" href="{{ url('assets/uploads/certificate/'.$data->attachment)}}">{{__('Download')}}</a>
                                                <a class="btn btn-info" target="_blank" href="{{ url('assets/uploads/certificate/'.$data->attachment)}}"><i class="fas fa-eye"></i></a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->status == 'pending')
                                                <span class="badge badge-warning">{{__('Pending')}}</span>
                                            @else
                                                <span class="badge badge-success">{{__('Submited')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="btn btn-warning btn-sm mb-3 certificate_modal_button"
                                               data-toggle="modal"
                                               data-id="{{$data->id}}"
                                               data-start_date="{{ date('d-m-Y',strtotime($data->start_date)) }}"
                                               data-end_date="{{date('d-m-Y',strtotime($data->end_date))}}"
                                               data-target="#certificate_modal">{{__('Create Certificate')}}</a>

                                            <a class="btn btn-info text-white mb-3 send_certificate_modal"
                                               data-toggle="modal"
                                               data-id="{{$data->id}}"
                                               data-name="{{optional($data->user)->name}}"
                                               data-target="#certificate_send_modal">{{__('Send Certificate')}}</a>

                                            @can('user-tax-delete')
                                            <x-delete-popover :url="route('admin.tax.request.delete',$data->id)"/>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


    {{--Generate Cetificate Modal--}}
        <div class="modal fade" id="certificate_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Generate Certificate')}}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                    </div>
                    <div class="another-content ml-4 my-2">
                        <span>{{__('According :')}} <strong class="show_start_date"></strong> to  <strong class="show_end_date"></strong> </span>
                    </div>
                    <form action="{{route('admin.tax.generate.certificate')}}" method="get" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" id="request_id" name="request_id">
                            <div class="form-group">
                                <label for="edit_name">{{__('Title')}}</label>
                                <input type="text" class="form-control" name="title"
                                       placeholder="{{__('Title')}}">
                            </div>

                            <div class="form-group">
                                <label for="edit_description">{{__('Description')}}</label>
                                <textarea class="form-control" name="description"
                                          placeholder="{{__('Description')}}" cols="30" rows="5"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="edit_description">{{__('Note')}}</label>
                                <textarea class="form-control" name="note"
                                          placeholder="{{__('Note Details')}}" cols="30" rows="4"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="edit_description">{{__('Certificate Date')}}</label>
                                <input type="date" class="form-control date" name="date">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Generate Certificate')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{--Send Certificate Modal--}}
            <div class="modal fade" id="certificate_send_modal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{__('Send Certificate')}}</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                        </div>
                        <div class="another-content ml-4 my-2">
                            <span>{{__('Send To :')}} <strong class="send_user_name"></strong> </span>
                        </div>
                        <form action="{{route('admin.tax.generate.certificate.send')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" id="certificate_id" name="id">
                                <div class="form-group">
                                    <label for="edit_name">{{__('Attach Certificate')}}</label>
                                    <input type="file" class="form-control" name="attachment">
                                    <small>{{__('Please generate a certificate than attach here and send..!')}}</small>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{__('Close')}}</button>
                                <button type="submit" class="btn btn-primary">{{__('Send Certificate')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

 @endsection

@section('script')

    <x-datatable.js/>
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>

    <script>
        (function($){
            "use strict";
            $(document).ready(function () {
                <x-bulk-action-js :url="route('admin.tax.request.bulk.action')"/>
                    $(document).on('click', '.certificate_modal_button', function () {
                        var el = $(this);
                        var id = el.data('id');
                        var start = el.data('start_date');
                        var end = el.data('end_date');
                        $('.show_start_date').text(start);
                        $('.show_end_date').text(end);
                        var modal = $('#certificate_modal');
                        modal.find('#request_id').val(id);

                    });

                $(document).on('click', '.send_certificate_modal', function () {
                    var el = $(this);
                    var id = el.data('id');
                    var username = el.data('name');
                    $('.send_user_name').text(username);
                    var modal = $('#certificate_send_modal');
                    modal.find('#certificate_id').val(id);

                });
            });
        })(jQuery);
    </script>
@endsection
