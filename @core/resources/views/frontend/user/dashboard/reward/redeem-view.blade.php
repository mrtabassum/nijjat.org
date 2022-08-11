@extends('frontend.user.dashboard.user-master')
@section('site-title')
    {{__('Redeem Details')}}
@endsection
@section('section')
 <div class="form-header-wrap margin-bottom-20 d-flex justify-content-between">
     <h3 class="mb-3">{{__('Redeem Details')}}</h3>
     <a href="{{route('user.home.reward.redeem.log')}}"
        class="btn btn-info btn-sm mb-3 campaign-title" >{{__('All Redeem')}}</a>
 </div>
  <div class="table-wrap table-responsive all-user-campaign-table">
      <ul class="margin-top-20">
          <li><strong>{{__('Requested By')}}:</strong> {{Auth::guard('web')->user()->name}}</li>
          @if($redeem->payment_status === 'pending')

               @php
                $available_withdraw =  $total_reward_amount - $redeem->where('payment_status' ,'!=', 'reject')->pluck('withdraw_request_amount')->sum();
               @endphp
              
              <li><strong>{{__('Available For Redeem Amount')}}:</strong>{{amount_with_currency_symbol($available_withdraw)}} </li>
          @endif
          <li><strong>{{__('Requested Redeem Amount')}}:</strong> {{amount_with_currency_symbol($redeem->withdraw_request_amount)}} </li>
          <li><strong>{{__('Payment Gateway')}}:</strong> {{$redeem->payment_gateway}} </li>
          <li><strong>{{__('Payment Status')}}:</strong> {{$redeem->payment_status}} </li>
          <li><strong>{{__('Request Date')}}:</strong> {{$redeem->created_at->format('D, d M Y')}} </li>
          @if($redeem->payment_status === 'approved')
              <li><strong>{{__('Approved Date')}}:</strong> {{$redeem->updated_at->format('D, d M Y')}} </li>
          @endif
          <li><strong>{{__('Payment Account Details ')}}:</strong> {{$redeem->payment_account_details}} </li>
          <li><strong>{{__('Additional Comment by You')}}:</strong> {{$redeem->additional_comment_by_user}} </li>
      </ul>

      <h3 class="header-title margin-top-40">{{__('Admin Response')}}</h3>
      <ul class="margin-top-20">
          <li><strong>{{__('Transaction Id')}}:</strong> {{$redeem->transaction_id}} </li>
          <li><strong>{{__('Payment Receipt')}}:</strong>
              @if($redeem->payment_receipt && file_exists('assets/uploads/reward-redeem/'.$redeem->payment_receipt))
                  <a class="text-primary" href="{{asset('assets/uploads/reward-redeem/'.$redeem->payment_receipt)}}" download="">{{$redeem->payment_receipt}}</a>
              @endif
          </li>
          <li><strong>{{__('Payment information')}}:</strong> {{$redeem->payment_information}} </li>
          <li><strong>{{__('Additional Comment by Admin')}}:</strong> {{$redeem->additional_comment_by_admin}} </li>
      </ul>

  </div>
@endsection
