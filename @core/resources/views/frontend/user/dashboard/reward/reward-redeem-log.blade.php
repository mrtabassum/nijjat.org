@extends('frontend.user.dashboard.user-master')
@section('section')
    @php
        $text1 = __('Total Rewarded Amount till now :');
    $text2 = __('Available Redeemable Amount :');
    @endphp
    <div class="heading-wrap d-flex justify-content-between margin-bottom-25">
        <h4 class="title">{{__('All Redeems')}}</h4>
        <br>
        @if(!empty($redeem_balance) && ($am = $redeem_balance - $total_requested_amount) > 0 ? $am : 0 )
        <div class="info">
            <h6>{{$text1 . amount_with_currency_symbol($redeem_balance)}}</h6>
            <h6>{{$text2. amount_with_currency_symbol($am)}}</h6>
        </div>
        @endif
        <div class="btn-wrapper">
            <a href="#" data-toggle="modal" data-target="#redeem_modal" class="boxed-btn reverse-color new_redeem_button">{{__('New Redeem')}}</a>
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
                                <li><strong>{{__("Redeem Request Amount")}}:</strong> {{amount_with_currency_symbol($data->withdraw_request_amount)}}</li>
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
                            <label for="edit_name">{{__('Redeemable Amount')}}</label>
                            <input type="text" readonly class="redeemable_amount form-control">
                            <input type="hidden" name="r_amount" class="r_amount" value="{{$redeem_balance}}">
                        </div>
                        <div class="field_wrap d-block">
                            <div class="form-group">
                                <label for="edit_name">{{__('Withdraw Amount')}}</label>
                                <input type="number" min="1" class="form-control" name="withdraw_request_amount" id="withdraw_amount">
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

      (function($){
        "use strict";

        $(document).ready(function(){
            
       $(document).on('click','.mobile_nav',function(e){
          e.preventDefault(); 
           $(this).parent().toggleClass('show');
       });
            <x-btn.submit/>

         var withdrawAbleAmount = 0;
            $(document).on('click','.new_redeem_button',function(){
                var r_amount =  $('.r_amount').val();
                var value = $(this).val();
                var modalForm = $('#reward_redeem_form');
                $.ajax({
                    type: 'POST',
                    url: "{{route('user.reward.redeem.check')}}",
                    data: {
                        amount: r_amount,
                        _token : "{{csrf_token()}}"
                    },
                    success: function (data){
                        $('.redeemable_amount').val( '{{site_currency_symbol()}}' +data.available_amount);

                        if(data.available_amount < 1){
                            modalForm.find('.withdraw_modal_msg_wrap').append('<p class="text-danger text-capitalize">{{__('does not have amount to withdraw from this cause')}}</p>');
                            modalForm.find('.field_wrap').removeClass('d-block').addClass('d-none');
                        }
                    }
                });

            });

        $(document).on('keyup','#withdraw_amount',function (){
            var value = $(this).val();
            var modalForm = $('#reward_redeem_form');
            var amountWrap = $('#withdraw_able_amount_wrap');
            var r_amount =  $('.r_amount').val();


            $.ajax({
                type: 'POST',
                url: "{{route('user.reward.redeem.check')}}",
                data: {
                    amount: r_amount,
                    _token : "{{csrf_token()}}"
                },
                success: function (data){
                    withdrawAbleAmount = data.available_amount;

                    if (withdrawAbleAmount < value){
                        modalForm.find('#withdraw_able_amount_wrap').html('<p class="text-danger">{{__('You can not redeem avobe')}} ' +withdrawAbleAmount+ '{{get_static_option('site_global_currency')}}</p>');
                    }else{
                        modalForm.find('#withdraw_able_amount_wrap').html('');
                    }

                    if(value > withdrawAbleAmount){
                        modalForm.find('button[type="submit"]').attr('disabled',true);
                    }else{
                        modalForm.find('button[type="submit"]').attr('disabled',false);
                    }
                }
            });
        });


        })
      })(jQuery);

    </script>
@endsection
