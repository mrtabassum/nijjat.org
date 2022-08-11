@extends('backend.admin-master')
@section('site-title')
    {{__('Reward Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">

            <div class="col-12 mt-5">
                <x-msg.success/>
                <x-msg.error/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Reward Settings")}}</h4>
                        <form action="{{route('admin.reward.settings')}}" method="POST"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="donation_deadline_text">{{__('Reward Point ')}}</label>
                                <input type="number" name="reward_amount_for_point" class="form-control" id="donation_reward_amount" value="{{get_static_option('reward_amount_for_point')}}">
                                <small class="text-primary">{{__('This point will be added for amount (1)')}}</small><br>
                                <small class="text-success">{{__('For Example (if you input 10 points in the field that will be consider for reward ($1)')}}</small>
                            </div>


                            <button id="update" type="submit"
                                    class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        <x-btn.update/>
    </script>
@endsection
