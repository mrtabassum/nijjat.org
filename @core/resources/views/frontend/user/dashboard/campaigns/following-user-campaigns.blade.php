@extends('frontend.user.dashboard.user-master')
@section('style')
    <x-datatable.css/>
@endsection
@section('site-title')
    {{__('Following Users')}}
@endsection
@section('section')

 <div class="form-header-wrap margin-bottom-50 d-flex justify-content-between">
     <h3 class="mb-3">    {{__('Following Users')}}</h3>

 </div>
  <div class="table-wrap table-responsive all-user-campaign-table">
      <table class="table table-defaul" id="all_blog_table">
          <thead class="bg-dark text-light" style="margin-bottom:20px;">
          <th>{{__('Info')}}</th>
          <th>{{__('Action')}}</th>
          </thead>
          <tbody>


          @foreach($all_follower_donations as $data)

                @php
                    if($data->created_by === 'user'):
                     $user = $data->user;
                    else:
                     $user = $data->admin;
                    endif;
                @endphp
              <tr>
                  <td>
                      <ul>
                          <li><strong>{{__('Followed Campaign Owner ')}}:</strong> {{$user->name}}</li>
                      </ul>
                  </td>
                  <td>

                    <a @if(!empty($user->id)) href="{{route('frontend.user.created.donations',['user' => $data->created_by,'id' => $user->id ])}}" @endif
                       class="btn btn-dark text-white btn-sm my-2" target="_blank"> <i class="fa fa-eye"></i>
                    </a>


                  </td>
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
