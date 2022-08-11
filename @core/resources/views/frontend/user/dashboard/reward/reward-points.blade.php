@extends('frontend.user.dashboard.user-master')
@section('style')
    <x-datatable.css/>
@endsection
@section('site-title')
    {{__('User Reward Points')}}
@endsection
@section('section')

  <div class="table-wrap table-responsive all-user-campaign-table">

      <h4 class="mb-3">{{__('Your Reward Points')}}</h4>

      <table class="table table-defaul" id="all_blog_table">
          <thead class="bg-dark text-light" style="margin-bottom:20px;">
          <th>{{__('ID')}}</th>
          <th>{{__('Title')}}</th>
          <th>{{__('Point')}}</th>
          <th>{{__('Amount')}}</th>
          <th>{{__('Date')}}</th>
          </thead>
          <tbody>

          @foreach($all_rewards as $data)
                @php
                    if($data->created_by === 'user'):
                     $user = $data->user;
                    else:
                     $user = $data->admin;
                    endif;
                @endphp
              <tr>
                  <td>{{$data->id}}</td>
                  <td>{{optional($data->cause)->title}}</td>
                  <td>{{$data->reward_point}}</td>
                  <td>{{ amount_with_currency_symbol($data->reward_amount) }}</td>
                  <td>{{date('d-m-Y',strtotime($data->created_at))}}</td>
              </tr>
          @endforeach
          </tbody>
      </table>
  </div>
@endsection

@section('scripts')
    <script src="{{asset('assets/backend/js/sweetalert2.js')}}"></script>

    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                
                $(document).on('click','.mobile_nav',function(e){
                  e.preventDefault(); 
                   $(this).parent().toggleClass('show');
               });
               
              $(document).on('click','.swal_delete_button',function(e){
                e.preventDefault();
                  Swal.fire({
                    title: '{{__("Are you sure?")}}',
                    text: '{{__("You would not be able to revert this item!")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      $(this).next().find('.swal_form_submit_btn').trigger('click');
                    }
                  });
              });
            })


        })(jQuery)
    </script>

    <x-datatable.js/>
@endsection
