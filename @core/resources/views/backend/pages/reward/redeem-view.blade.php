@extends('backend.admin-master')
@section('site-title')
    {{__('Reward Redeem View')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex justify-content-between">
                            <h4 class="header-title">{{__('Redeem Details')}}</h4>
                            <div class="btn-wrapper">
                                <a class="btn btn-info" href="{{route('admin.reward.all.redeem.request')}}">{{__('All Redeem')}}</a>
                            </div>
                        </div>
                        <ul class="margin-top-20">
                            <li><strong>{{__('Requested By')}}:</strong> {{optional($redeem->user)->name }}</li>
                            <li><strong>{{__('Total Rewarded Amount')}}:</strong>{{amount_with_currency_symbol($redeem_balance)}} </li>
                            @if($redeem->payment_status === 'pending')
                            @php
                                $redeem_able_amount = $redeem_balance - $redeem->where('payment_status','!=','reject')->pluck('withdraw_request_amount')->sum();
                            @endphp
                                <li><strong>{{__('Available For Withdraw Amount')}}:</strong>{{amount_with_currency_symbol($redeem_able_amount)}} </li>
                            @endif
                            <li><strong>{{__('Requested Redeemable Amount')}}:</strong> {{amount_with_currency_symbol($redeem->withdraw_request_amount)}} </li>
                            <li><strong>{{__('Payment Gateway')}}:</strong> {{$redeem->payment_gateway}} </li>
                            <li><strong>{{__('Payment Status')}}:</strong> {{$redeem->payment_status}} </li>
                            <li><strong>{{__('Date')}}:</strong> {{$redeem->created_at->format('D, d M Y')}} </li>
                            @if($redeem->payment_status === 'approved')
                                <li><strong>{{__('Approved Date')}}:</strong> {{$redeem->updated_at->format('D, d M Y')}} </li>
                            @endif
                            <li><strong>{{__('Payment Account Details ')}}:</strong> {{$redeem->payment_account_details}} </li>
                            <li><strong>{{__('Additional Comment by user')}}:</strong> {{$redeem->additional_comment_by_user}} </li>
                        </ul>
                        <h3 class="header-title margin-top-40">{{__('Admin Response')}}</h3>
                        <ul class="margin-top-20">
                            <li><strong>{{__('Transaction Id')}}:</strong> {{$redeem->transaction_id}} </li>
                            <li><strong>{{__('Payment Receipt')}}:</strong>
                                @if($redeem->payment_receipt && file_exists('assets/uploads/reward-redeem/'.$redeem->payment_receipt))
                                    <a href="{{asset('assets/uploads/reward-redeem/'.$redeem->payment_receipt)}}" download="">{{$redeem->payment_receipt}}</a>
                                @else
                                    {{$redeem->payment_receipt}}
                                @endif
                            </li>
                            <li><strong>{{__('Payment information')}}:</strong> {{$redeem->payment_information}} </li>
                            <li><strong>{{__('Additional Comment by Admin')}}:</strong> {{$redeem->additional_comment_by_admin}} </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection