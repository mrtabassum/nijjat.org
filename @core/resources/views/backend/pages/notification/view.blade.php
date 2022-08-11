@extends('backend.admin-master')

@section('site-title')
    {{__('View Notification')}}
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
                            <h4 class="header-title">{{__('View Notification Page')}}  </h4>
                            <div class="header-title">
                                <a href="{{ route('admin.notification') }}"
                                   class="btn btn-primary mt-4 pr-4 pl-4">{{__('All Notifications')}}</a>
                            </div>
                        </div>

                        <ul>
                             @if($notification->type == 'cause_log')
                           <h3 class="mb-3">{{$notification->title ?? ''}}</h3>
                            <li>{{__('Cause Title : ')}} <strong>{{optional(optional($notification->cause_log)->cause)->title }}</strong> </li>
                            <li>{{__('Name:')}}  <strong>{{ optional($notification->cause_log)->name }}</strong> </li>
                            <li>{{__('Email:')}}  <strong>{{optional($notification->cause_log)->email}}</strong> </li>
                            <li>{{__('Amount:')}}  <strong>{{ amount_with_currency_symbol(optional($notification->cause_log)->amount)}}</strong> </li>
                             <li>{{__('Payment Gateway:')}}  <strong>{{optional($notification->cause_log)->payment_gateway}}</strong> </li>
                              <li>{{__('Date : ')}}  <strong>{{optional($notification->cause_log)->created_at->diffForHumans()}}</strong> </li>
                            
                            @elseif($notification->type == 'user_campaign')
                              <h3 class="mb-3">{{$notification->title ?? ''}}</h3>
                             <li>{{__('Cause Title:')}} <strong> {{optional($notification->user_campaign)->title }}</strong> </li>
                             <li>{{__('Created At:')}} <strong> {{optional($notification->user_campaign)->created_at->diffForHumans() }}</strong> </li>
                             <li>{{__('Create By:')}} <strong> {{optional($notification->user_campaign)->created_by }}</strong> </li>
                             <li>{{__('Goal:')}} <strong> {{ amount_with_currency_symbol(optional($notification->user_campaign)->amount) }}</strong> </li>
                             <li>{{__('Raised:')}} <strong> {{optional($notification->user_campaign)->raised }}</strong> </li>
                             <li>{{__('Status :')}} <strong> {{optional($notification->user_campaign)->status }}</strong> </li>

                            @else
                            
                            @php
                               $withdraw_able_amount_without_admin_charge = optional(optional($notification->cause_withdraw)->cause)->raised - optional(optional($notification->cause_withdraw)->cause)->withdraws->where('payment_status' ,'!=', 'reject')->pluck('withdraw_request_amount')->sum() ?? '';
                    
                            @endphp
                              <h3 class="mb-3">{{$notification->title ?? ''}}</h3>
                              <li>{{__('Cause Title:')}} <strong> {{optional(optional($notification->cause_withdraw)->cause)->title }}</strong> </li>
                              <li>{{__('Requested By:')}} <strong> {{optional(optional($notification->cause_withdraw)->user)->name }}</strong> </li>
                              <li>{{__('Available Widtdraw Amount:')}} <strong> {{ amount_with_currency_symbol($withdraw_able_amount_without_admin_charge) }}</strong> </li>
                              <li>{{__('Requested Widtdraw Amount:')}} <strong> {{ amount_with_currency_symbol(optional($notification->cause_withdraw)->withdraw_request_amount) }}</strong> </li>
                              <li>{{__('Payment Gateway:')}} <strong> {{optional($notification->cause_withdraw)->payment_gateway }}</strong> </li>
                              <li>{{__('Payment Status :')}} <strong> {{optional($notification->cause_withdraw)->payment_status }}</strong> </li>
                              <li>{{__('Date:')}} <strong> {{optional($notification->cause_withdraw)->created_at->diffForHumans() }}</strong> </li>
                            
                            @endif
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

