@extends('backend.admin-master')

@section('site-title')
    {{__('All Rewards')}}
@endsection

@section('style')
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
                        <h4 class="header-title"> {{__('All Rewards')}}</h4>
                        <div class="bulk-delete-wrapper">
                            @can('reward-delete')
                                <div class="select-box-wrap">
                                    <x-bulk-action/>
                                </div>
                            @endcan
                            @can('reward-create')
                                <div class="btn-wrapper">
                                    <a href="" data-toggle="modal" data-target="#new_reward_modal"
                                       class="btn btn-info pull-right mb-4 .new_reward_btn">{{__('Add New')}}</a>
                                </div>
                            @endcan
                        </div>

                        <small class="text-primary">{{__('Please put your rewards like (1-20, 21-50, 51-100 and so on like this sequence)')}}</small>

                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-th/>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('From')}}</th>
                                <th>{{__('To')}}</th>
                                <th>{{__('Reward Point')}}</th>
                                <th>{{__('Reward Amount')}}</th>
                                <th>{{__('Reward Expire Date')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_reward as $data)
                                    <tr>
                                        <td>
                                            <x-bulk-delete-checkbox :id="$data->id"/>
                                        </td>
                                        <td>{{$data->id}}</td>
                                        <td>{{$data->reward_title}}</td>
                                        <td>{{amount_with_currency_symbol($data->reward_goal_from)}}</td>
                                        <td>{{amount_with_currency_symbol($data->reward_goal_to) }}</td>
                                        <td>{{$data->reward_point}}</td>
                                        <td>{{amount_with_currency_symbol($data->reward_amount) }}</td>
                                        <td>{{date('d-m-Y',strtotime($data->reward_expire_date)) }}</td>
                                        <td>
                                            <x-status-span :status="$data->status"/>
                                        </td>
                                        <td>
                                            @can('reward-delete')
                                                <x-delete-popover :url="route('admin.reward.delete',$data->id)"/>
                                            @endcan

                                            @can('reward-edit')
                                                <a href="#"
                                                   data-toggle="modal"
                                                   data-target="#reward_item_edit_modal"
                                                   class="btn btn-primary btn-xs mb-3 mr-1 reward_edit_btn"
                                                   data-id="{{$data->id}}"
                                                   data-reward_title="{{$data->reward_title}}"
                                                   data-reward_goal_from="{{$data->reward_goal_from}}"
                                                   data-reward_goal_to="{{$data->reward_goal_to}}"
                                                   data-reward_amount="{{$data->reward_amount}}"
                                                   data-reward_point="{{$data->reward_point}}"
                                                   data-reward_expire_date="{{ date('d-m-Y',strtotime($data->reward_expire_date)) }}"
                                                   data-status="{{$data->status}}">
                                                    <i class="ti-pencil"></i>
                                                </a>

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
            @can('reward-create')
                <div class="modal fade" id="new_reward_modal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{__('New Reward Item')}}</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                            </div>
                            <form action="{{route('admin.reward')}}" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    @csrf
                                    <div class="form-group">
                                        <label for="edit_name">{{__('Title')}}</label>
                                        <input type="text" class="form-control" name="reward_title"
                                               placeholder="{{__('Title')}}">
                                    </div>

                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="edit_designation">{{__('Reward Goal From')}}</label>
                                        <input type="number" class="form-control reward_goal_from_add" name="reward_goal_from"
                                               placeholder="{{__('Reward Goal From')}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>{{__('Reward Goal To')}}</label>
                                        <input type="number" class="form-control reward_goal_to_add" name="reward_goal_to"
                                               placeholder="{{__('Reward Goal To')}}">
                                    </div>
                                </div>

                                    <div class="form-group">
                                        <label>{{__('Reward Point')}}</label>
                                        <input type="number" class="form-control reward_point_add" name="reward_point"
                                               placeholder="{{__('Reward Point')}}">
                                    </div>

                                    <input type="hidden" class="form-control reward_amount_add_database"
                                           placeholder="{{__('Reward Amount')}}" name="reward_amount" >

                                    <div class="form-group">
                                        <label>{{__('Reward Amount')}}</label>
                                        <input type="text" class="form-control reward_amount_add_show"
                                               placeholder="{{__('Reward Amount')}}" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label>{{__('Reward Expire Date')}}</label>
                                        <input type="date" class="form-control date-flat" name="reward_expire_date"
                                               placeholder="{{__('Reward Expire Date')}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_status">{{__('Status')}}</label>
                                        <select name="status" class="form-control">
                                            <option value="publish">{{__('Publish')}}</option>
                                            <option value="draft">{{__('Draft')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">{{__('Close')}}</button>
                                    <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan

            @can('reward-edit')
                <div class="modal fade" id="reward_item_edit_modal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{__('Edit Reward Item')}}</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                            </div>
                            <form action="{{route('admin.reward.update')}}" id="reward_edit_modal_form" method="post"
                                  enctype="multipart/form-data">
                                <div class="modal-body">
                                    @csrf
                                    <input type="hidden" name="id" id="reward_id" value="">
                                    <div class="form-group">
                                        <label for="edit_name">{{__('Title')}}</label>
                                        <input type="text" class="form-control" name="reward_title" id="reward_title"
                                               placeholder="{{__('Title')}}">
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="edit_designation">{{__('Reward Goal From')}}</label>
                                            <input type="number" class="form-control" name="reward_goal_from" id="reward_goal_from"
                                                   placeholder="{{__('Reward Goal From')}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>{{__('Reward Goal To')}}</label>
                                            <input type="number" class="form-control" name="reward_goal_to" id="reward_goal_to"
                                                   placeholder="{{__('Reward Goal To')}}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>{{__('Reward Point')}}</label>
                                        <input type="text" class="form-control reward_point_edit" name="reward_point" id="reward_point"
                                               placeholder="{{__('Reward Point')}}">
                                    </div>

                                    <input type="hidden" class="form-control reward_amount_edit_database" name="reward_amount"
                                           placeholder="{{__('Reward Amount')}}">
                                    <div class="form-group">
                                        <label>{{__('Reward Amount')}}</label>
                                        <input type="text" class="form-control reward_amount_edit" id="reward_amount"
                                               placeholder="{{__('Reward Amount')}}" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label>{{__('Reward Expire Date')}}</label>
                                        <input type="date" class="form-control reward_expire_date date-flat" name="reward_expire_date"
                                               placeholder="{{__('Reward Expire Date')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_status">{{__('Status')}}</label>
                                        <select name="status" class="form-control" id="edit_status">
                                            <option value="publish">{{__('Publish')}}</option>
                                            <option value="draft">{{__('Draft')}}</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">{{__('Close')}}</button>
                                    <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endcan
@endsection

 @section('script')

<script src="{{asset('assets/backend/js/flatpickr.js')}}"></script>
<script>
    //Date Picker
    flatpickr('.date-flat', {
        enableTime: false,
        dateFormat: "Y-m-d",
    });
</script>
        <script>
            (function ($) {
                "use strict";

                $(document).ready(function () {
                    $('.reward_amount_add_show').prop('disabled', true);
                    <x-bulk-action-js :url="route('admin.reward.bulk.action')" />
                    <x-btn.submit/>
                    <x-btn.update/>

                    //Add Point Amount
                    $(document).on('keyup','.reward_point_add',function(e){
                        e.preventDefault();
                        let po = $(this).val();

                        let global_point = "{{get_static_option('reward_amount_for_point')}}";
                        let amount_calculation = po / global_point;
                         $('.reward_amount_add_show').val('{{site_currency_symbol()}}' + amount_calculation);
                         $('.reward_amount_add_database').val(amount_calculation);
                    })

                    //Edit Point Amount
                    $(document).on('keyup','.reward_point_edit',function(e){
                        e.preventDefault();
                        let po = $(this).val();
                        let global_point = "{{get_static_option('reward_amount_for_point')}}";
                        let amount_calculation = po / global_point;

                        $('.reward_amount_edit').val('{{site_currency_symbol()}}' + amount_calculation);
                        $('.reward_amount_edit_database').val(amount_calculation);
                    })

                    $(document).on('click', '.reward_edit_btn', function () {
                        var el = $(this);
                        var id = el.data('id');

                        var title = el.data('reward_title');
                        var goal_from = el.data('reward_goal_from');
                        var goal_to = el.data('reward_goal_to');
                        var amount = el.data('reward_amount');
                        var point = el.data('reward_point');
                        var date = el.data('reward_expire_date');
                        var status = el.data('status');
                        var action = el.data('action');

                        console.log(action)

                        var form = $('#reward_item_edit_modal');
                        form.attr('action', action);
                        form.find('#reward_id').val(id);

                        form.find('#reward_title').val(title);
                        form.find('#reward_goal_from').val(goal_from);
                        form.find('#reward_goal_to').val(goal_to);
                        form.find('.reward_amount_edit').val('{{site_currency_symbol()}}' +amount);
                        form.find('#reward_point').val(point);
                        form.find('.reward_expire_date').val(date);
                        form.find('#edit_status option[value="'+status+'"]').attr('selected',true);

                    });
                });
            })(jQuery)



        </script>

        <x-datatable.js/>
        <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
@endsection
