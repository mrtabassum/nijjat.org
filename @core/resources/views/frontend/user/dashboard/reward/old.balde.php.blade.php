@extends('frontend.user.dashboard.user-master')
@section('section')
    <div class="heading-wrap d-flex justify-content-between margin-bottom-25">
        <h4 class="title">{{__('All Redeems')}}</h4>
        <div class="btn-wrapper">
            <a href="#" data-toggle="modal" data-target="#redeem_modal" class="boxed-btn reverse-color">{{__('New Redeem')}}</a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">{{__('Information')}}</th>
                <th scope="col"> {{__('Redeem Status')}}</th>
                <th scope="col">{{__('Actions')}}</th>
            </tr>
            </thead>
            <tbody>

            @foreach($reward_redeem_logs as $data)
                <tr>
                    <td>
                        <ul>
                            <li><strong>{{__("Cause")}}:</strong> {{optional($data->cause)->title}}</li>
                            <li><strong>{{__("Amount")}}:</strong> {{amount_with_currency_symbol($data->withdraw_request_amount)}}</li>
                            <li><strong>{{__("Payment Gateway")}}:</strong> {{$data->payment_gateway}}</li>
                        </ul>
                    </td>
                    <td><x-status-span :status="$data->payment_status"/></td>
                    <td>
                        <a href="{{route('user.reward.redeem.view',$data->id)}}" target="_blank" class="btn btn-info"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="blog-pagination">
        {{ $reward_redeem_logs->links() }}
    </div>

    {{-- Redeem Modal --}}
    <div class="modal fade" id="redeem_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>{{__('User Reward Redeem')}}</h4>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route('user.reward.redeem.submit')}}" method="post" id="reward_redeem_form">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="user_id" value="" id="user_id">
                        <input type="hidden" name="reward_id" value="" id="reward_id">
                        <div class="withdraw_modal_msg_wrap" ></div>
                        <div class="form-group">
                            <label for="edit_name">{{__('Select Rewarded Cause')}}</label>
                            <select class="form-control" name="log_id">
                                <option value="">{{__("select rewared cause")}}</option>
                                @foreach($causes as $log)
                                    <option value="{{$log->id}}" data-cause_id="{{optional($log->cause)->id}}">{{optional($log->cause)->title}}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="c_id" class="c_id">
                        </div>
                        <div class="field_wrap d-none">
                            <div class="form-group">
                                <label for="edit_name">{{__('Withdraw Amount')}}</label>
                                <input type="number" class="form-control" name="withdraw_request_amount" id="withdraw_amount">
                                <div id="withdraw_able_amount_wrap"></div>
                            </div>
                            <div class="form-group">
                                <label for="edit_name">{{__('Payment Gateway')}}</label>
                                <select class="form-control" name="payment_gateway">
                                    {!! render_payment_gateway_select() !!}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="edit_name">{{__('Payment Account Details')}}</label>
                                <textarea name="payment_account_details" cols="4" rows="4" class="form-control"></textarea>
                                <span class="info-text">{{__('enter your selected payment gateway account details, where admin will send your withdrawal amount')}}</span>
                            </div>

                            <div class="form-group">
                                <label for="edit_name">{{__('Additional Comment ')}}</label>
                                <textarea name="additional_comment_by_user" cols="4" rows="4" class="form-control"></textarea>
                                <span class="info-text">{{__('leave any additional comment if you have any')}}</span>
                            </div>
                            <button type="submit" class="submit-btn">{{__('Submit')}}</button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        <x-btn.submit/>
        (function($){
            "use strict";

            $(document).ready(function(){

                $(document).on('click','.mobile_nav',function(e){
                    e.preventDefault();
                    $(this).parent().toggleClass('show');
                });

                var withdrawAbleAmount = 0;
                $(document).on('keyup','input[name="withdraw_request_amount"]',function (){
                    var value = $(this).val();
                    var formContainer = $('#reward_redeem_form');
                    var amountWrap = $('#withdraw_able_amount_wrap');

                    if(value <= withdrawAbleAmount){
                        amountWrap.find('.text-danger').remove();
                        formContainer.find('button[type="submit"]').attr('disabled',false);
                    }else{
                        amountWrap.find('.text-danger').remove();
                        amountWrap.append('<p class="text-danger">{{__('you can not redeem more than')}} '+withdrawAbleAmount+'{{get_static_option('site_global_currency')}}</p>');
                        formContainer.find('button[type="submit"]').attr('disabled',true);
                    }
                });

                $(document).on('change','select[name="log_id"]',function (){
                    var modalForm = $('#reward_redeem_form');

                    var logID = $(this).val();
                    var cause_id = $(this).children("option:selected").data('cause_id');
                    $('.c_id').val(cause_id);



                    $.ajax({
                        type: 'POST',
                        url: "{{route('user.reward.redeem.check')}}",
                        data: {
                            id: logID,
                            _token : "{{csrf_token()}}"
                        },
                        success: function (data){
                            console.log(data)
                            withdrawAbleAmount = data.available_amount;
                            modalForm.find('.withdraw_modal_msg_wrap').html('');
                            if (data.available_amount > 0){
                                modalForm.find('.field_wrap').removeClass('d-none').addClass('d-block');
                                modalForm.find('#withdraw_able_amount_wrap').html('<p class="text-success text-capitalize">{{__('withdraw able balance')}} ' +data.available_amount+ '{{get_static_option('site_global_currency')}}</p>');
                            }else{
                                modalForm.find('.withdraw_modal_msg_wrap').append('<p class="text-danger text-capitalize">{{__('does not have amount to redeem from this cause')}}</p>');
                                modalForm.find('.field_wrap').removeClass('d-block').addClass('d-none');
                            }
                        }
                    });
                });

            })
        })(jQuery);

    </script>
@endsection
