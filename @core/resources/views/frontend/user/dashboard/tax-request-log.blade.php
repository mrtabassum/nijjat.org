@extends('frontend.user.dashboard.user-master')
@section('site-title')
    {{__('Tax Request Logs')}}
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/flatpickr.min.css')}}">
@endsection

@section('section')
 <div class="form-header-wrap margin-bottom-20 d-flex justify-content-between">
     <h3 class="mb-3">{{__('Tax Request Logs')}}</h3>

     <div class="btn-wrapper pull-right">
         <a href="#" data-toggle="modal" data-target="#submit_tax_request_modal" class="btn btn-info btn-sm">{{__('Request Certificate')}}</a>
     </div>
 </div>

 <div class="table-wrap table-responsive all-user-campaign-table">
     <table class="table table-defaul" id="all_blog_table">
         <thead class="bg-dark text-light" style="margin-bottom:20px;">
             <th>{{__('Log ID ')}}</th>
             <th>{{__('User ID ')}}</th>
             <th>{{__('Title')}}</th>
             <th>{{__('Required Date')}}</th>
             <th>{{__('Status')}}</th>
             <th>{{__('Certificate')}}</th>
         </thead>
         <tbody>
         @foreach($all_request_tax_logs as $data)
             <tr>
                 <td>{{$data->id}}</td>
                 <td>{{$data->user_id}}</td>
                 <td>{{$data->title}}</td>

                 @php
                    $start_date = date('d-m-Y',strtotime($data->start_date));
                    $end_date = date('d-m-Y',strtotime($data->end_date));
                 @endphp
                 <td>{{ $start_date . ' to ' . $end_date}}</td>
                 <td>
                        @if($data->status == 'pending')
                            <span class="badge badge-warning">{{__('Requested')}}</span>
                        @else
                            <span class="badge badge-success">{{__('Received')}}</span>
                        @endif
                 </td>
                 <td>
                     @if(!is_null($data->attachment))
                         <a class="btn btn-success" download="" href="{{ url('assets/uploads/certificate/'.$data->attachment)}}">{{__('Download')}}</a>
                     @endif
                 </td>
             </tr>
         @endforeach
         </tbody>
     </table>
 </div>


 <div class="modal fade" id="submit_tax_request_modal" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-header">
                 <h4>{{__('Request For Tax Certificate')}}</h4>
                 <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
             </div>
             <form action="{{route('user.home.tax.request.store')}}" method="post" id="donation_withdraw_form">
                 <div class="modal-body">
                     @csrf
                     <input type="hidden" name="user_id" value="{{ auth()->guard('web')->user()->id }}">
                     <div class="form-group">
                         <label for="edit_name">{{__('Title')}}</label>
                         <input type="text" class="form-control" name="title" placeholder="Title">
                     </div>

                     <div class="form-group">
                         <label for="edit_name">{{__('Start Date')}}</label>
                         <input type="date" class="form-control date" name="start_date" >
                     </div>

                     <div class="form-group">
                         <label for="edit_name">{{__('End Date')}}</label>
                         <input type="date" class="form-control date" name="end_date" >
                     </div>

                     <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>

                 </div>
             </form>
         </div>
     </div>
 </div>

@endsection

@section('scripts')
    <script src="{{asset('assets/backend/js/flatpickr.js')}}"></script>
    <script>
        //Date Picker
        flatpickr('.date', {
            enableTime: false,
            dateFormat: "Y-m-d",
        });
    </script>
@endsection