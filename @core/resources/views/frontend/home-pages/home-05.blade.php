@php
    $home_page_variant = $home_page ?? get_static_option('home_page_variant');
@endphp
<div class="header-style-01 home-page-variant-{{$home_page_variant}}">
    <div class="topbar-area style-02 home-page-four">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="topbar-inner style-01">
                        <div class="left-contnet">
                            <ul class="info-items">
                                @php
                                    $all_icon_fields =  filter_static_option_value('home_page_01_topbar_info_list_icon_icon',$global_static_field_data);
                                    $all_icon_fields =  !empty($all_icon_fields) ? unserialize($all_icon_fields,['class' => false]) : [];
                                    $all_title_fields = filter_static_option_value('home_page_01_topbar_info_list_text',$global_static_field_data);
                                    $all_title_fields = !empty($all_title_fields) ? unserialize($all_title_fields,['class' => false]) : [];
                                @endphp
                                @foreach($all_icon_fields as $index => $icon)
                                    <li><i class="{{$icon}} "></i> {{isset($all_title_fields[$index]) ? $all_title_fields[$index] : ''}}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="right-contnet">
                            <div class="social-link">
                                <ul>
                                    @foreach($all_social_item as $data)
                                        <li><a href="{{$data->url}}"><i class="{{$data->icon}}"></i></a></li>
                                    @endforeach
                                    <x-front-user-login-li/>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- support bar area end -->
    <nav class="navbar navbar-area navbar-expand-lg charity-nav-05 has-topbar has-topbar-04 nav-style-02">
        <div class="container nav-container">
            <div class="responsive-mobile-menu">
                <div class="logo-wrapper">
                    <a href="{{url('/')}}" class="logo">
                        @if(!empty(filter_static_option_value('site_white_logo',$global_static_field_data)))
                            {!! render_image_markup_by_attachment_id(filter_static_option_value('site_white_logo',$global_static_field_data)) !!}
                        @else
                            <h2 class="site-title">{{filter_static_option_value('site_title',$global_static_field_data)}}</h2>
                        @endif
                    </a>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bizcoxx_main_menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="bizcoxx_main_menu">
                <ul class="navbar-nav">
                    {!! render_frontend_menu($primary_menu) !!}
                </ul>
            </div>
            <div class="nav-right-content">
                <ul>
                    <li>
                  @if(!empty(get_static_option('home_page_navbar_search_show_hide')))
                        <div class="search navbar-search position-relative">
                            <div class="search-open">
                                <i class="las la-search icon"></i>
                            </div>

                            <div class="search-bar">
                                <form class="menu-search-form" action="{{ route('frontend.donation.search') }}">
                                    <div class="search-close"> <i class="las la-times"></i> </div>
                                    <input class="item-search" name="search" id="search" type="text" placeholder="Search Here.....">
                                    <button type="submit"> Search</button>

                                </form>
                            </div>
                        </div>
                        @endif
                    </li>
                    <x-front-donate-btn-last-three-home/>
                </ul>
            </div>
        </div>
    </nav>

    <!-- navbar area end -->
</div>

@if(get_static_option('home_page_header_slider_area_05_section_status'))
<div class="header-slider-one">
@foreach($all_header_slider as $data)
    <div class="header-area style-02 header-bg-04 banner-padiing-02" {!! render_background_image_markup_by_attachment_id($data->image) !!}>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-7 col-sm-9">
                    <div class="header-inner-05 desktop-center">
                        @php
                            $title_arr = explode(" ", $data->title);
                            $firstWord = $title_arr[0];
                            array_shift($title_arr);
                        @endphp
                        <p class="animate-style-02">{{$data->subtitle ?? ''}}</p>
                        <h1 class="title animate-style"><span>{{$firstWord}}</span> {{ implode(' ',$title_arr) }}</h1>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endforeach
</div>
@endif

@if(get_static_option('home_page_rise_area_05_section_status'))
<section class="rise-area">
    <div class="top-shapes">
        <img src="{{asset('assets/frontend/img/category/top-shapes.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="rise-flex-contents">
                <div class="single-donate margin-bottom-30">
                    <h2 class="title"> {!! filter_static_option_value('home_page_05_rise_area_heading_title',$static_field_data) !!} </h2>
                </div>
                <div class="single-donate margin-bottom-30">
                    <div class="nice-selects">
                        <select id="donation_select">
                            @foreach($all_donation as $donation)
                            <option value="{{$donation->id}}" data-url="{{route('frontend.donation.in.separate.page',$donation->id)}}"> {{ \Illuminate\Support\Str::words($donation->title,4)  }} </option>
                             @endforeach

                        </select>
                    </div>
                </div>
                <div class="single-donate margin-bottom-30">
                    <input class="form-control user_input_number" type="number" value="200">
                </div>
                <div class="single-donate margin-bottom-30">
                    <button type="submit" class="boxed-btn donate-btn donation_redirect_button"> {!! filter_static_option_value('home_page_05_rise_area_button_text',$static_field_data) !!} </button>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@php
    $classes = ['reverse-color','btn-color-three','btn-dander','btn-color-three'];
@endphp
@if(get_static_option('home_page_feature_area_05_section_status'))
<section class="featured-area-three padding-top-90 padding-bottom-140">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-four b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{!! filter_static_option_value('home_page_05_feature_area_title',$static_field_data) !!}</span>
                    <h2 class="title">{!! filter_static_option_value('home_page_05_feature_area_subtitle',$static_field_data) !!} <img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="featured-slider">
                    @foreach($feature_cause as $key=> $data)
                    <div class="single-featured-items">

                        <div class="single-featured-02 single-featured">
                            <div class="featured-image">
                                {!! render_image_markup_by_attachment_id($data->image,'','grid') !!}
                                <div class="award-flex-position">
                                    @if($data->featured === 'on')
                                        <div class="award-new-icon">
                                            <i class="las la-award"></i>
                                        </div>
                                    @endif

                                    @if($data->reward === 'on')
                                        <div class="award-new-icon">
                                            <i class="las la-gift"></i>
                                        </div>
                                    @endif
                                </div>

                            </div>


                            <div class="progress-item">
                                <div class="single-progressbar">
                                    <div class="donation-progress" data-percentage="{{get_percentage($data->amount,$data->raised)}}"></div>
                                </div>
                            </div>
                            <div class="featured-contents">

                                <h3 class="title">
                                    <a href="{{route('frontend.donations.single',$data->slug)}}">{{$data->title ?? ''}}</a>
                                </h3>
                                <div class="feature-flex">
                                    <div class="goal">
                                        <h4 class="raised">{{__('Raised')}}:  <span class="main-color-three">{{amount_with_currency_symbol($data->raised ?? 0 )}} </span></h4>
                                    </div>
                                    <div class="goal">
                                        <h4 class="raised">{{__('Goal')}}: <span class="danger-color">{{amount_with_currency_symbol($data->amount)}}</span></h4>
                                    </div>
                                    
                                    <div class="btn-wrapper">
                                        <a href="{{route('frontend.donations.single',$data->slug)}}" class="boxed-btn btn-rounded {{ $classes[$key % count($classes)] }} "> {!! filter_static_option_value('home_page_05_feature_area_donation_button_text',$static_field_data) !!} </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
@endif

@if(get_static_option('home_page_category_area_05_section_status'))
<section class="category-area section-bg-3 padding-top-90 padding-bottom-80">
    <div class="section-shapes">
        <img src="{{asset('assets/frontend/img/bg/top-shapes2.png')}}" alt="">
        <img src="{{asset('assets/frontend/img/bg/bottom-shapes2.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-four b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{!! filter_static_option_value('home_page_05_category_area_title',$static_field_data) !!}</span>
                    <h2 class="title"> {!! filter_static_option_value('home_page_05_category_area_subtitle',$static_field_data) !!} <img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="category-slider">
                    @foreach($all_donation_category as $data)
                    <div class="single-category-items">
                        <div class="single-category">
                            <div class="category-image">
                                {!! render_image_markup_by_attachment_id($data->image,'thumb') !!}
                                <div class="category-shape">
                                    <img src="{{asset('assets/frontend/img/category/shape1.png')}}" alt="">
                                </div>
                            </div>
                            <div class="category-content">
                                <h4 class="category-para"> <a href="{{route('frontend.donations.category',['id' => $data->id,'any' => Str::slug($data->title) ?? '' ])}}">  {{$data->title ?? ''}} </a> </h4>
                            </div>
                        </div>
                    </div>
                     @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if(get_static_option('home_page_success_story_area_05_section_status'))
<section class="success-area-two padding-top-140 padding-bottom-140">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-four b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{!! filter_static_option_value('home_page_05_success_story_area_title',$static_field_data) !!} </span>
                    <h2 class="title"> {!! filter_static_option_value('home_page_05_success_story_area_subtitle',$static_field_data) !!}  <img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="success-slider">
                    @foreach($all_success_stories as $key=> $data)
                    <div class="single-success-items">
                        <div class="row align-items-center">
                            <div class="col-lg-6 order-2 order-lg-1">
                                <div class="success-contents margin-bottom-30">
                                    <div class="section-title section-title-four padding-top-25 margin-bottom-40">
                                        <span>{{$data->category->name ?? ''}}</span>
                                        <h4 class="success-titles"> <a href="{{route('frontend.success.story.single',$data->slug)}}">{{$data->title ?? ''}}</a> </h4>
                                    </div>
                                    <p>{{purify_html($data->excerpt) }}</p>

                                    <div class="btn-wrapper">
                                        <a href="{{route('frontend.success.story.single',$data->slug)}}" class="boxed-btn {{$classes[$key % count($classes)]}} btn-rounded"> {!! filter_static_option_value('home_page_05_success_story_area_button_text',$static_field_data) !!} </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 offset-lg-1 order-1 order-lg-2">
                                <div class="success-mask margin-bottom-30">
                                    {!! render_image_markup_by_attachment_id($data->image) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if(get_static_option('home_page_counterup_area_05_section_status'))
<div class="counterup-area section-bg-3 position-relative padding-bottom-90 padding-top-120">
    <div class="section-shapes">
        <img src="{{asset('assets/frontend/img/bg/top-shapes2.png')}}" alt="">
        <img src="{{asset('assets/frontend/img/bg/bottom-shapes2.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row">
            @foreach($all_counterup as $data)
            <div class="col-lg-3 col-sm-6 counter-child">
                <div class="single-counterup-02 margin-bottom-30">
                    <div class="counter-contents">
                        <div class="icon">
                            <div class="icon-shapes">
                                <img src="{{asset('assets/frontend/img/about/about-counter-s.png')}}" alt="">
                                <img src="{{asset('assets/frontend/img/about/about-counter-s2.png')}}" alt="">
                            </div>
                            <i class="{{$data->icon}}"></i>
                        </div>
                        <div class="content">
                            <div class="count-wrap"><span class="count-num">{{$data->number}}</span>{{$data->extra_text}}</div>
                            <p class="title">{{$data->title}}</p>
                        </div>
                    </div>
                </div>
            </div>
           @endforeach
        </div>
    </div>
</div>
@endif

@if(get_static_option('home_page_recent_cause_area_05_section_status'))
<section class="recent-area-two padding-top-140 padding-bottom-170">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-four b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{!! filter_static_option_value('home_page_05_recent_causes_area_title',$static_field_data) !!}</span>
                    <h2 class="title"> {!! filter_static_option_value('home_page_05_recent_causes_area_subtitle',$static_field_data) !!} <img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($all_recent_donation as $key=> $data)
            <div class="col-lg-4 col-md-6 recent-childs">
                <div class="single-recent-02 margin-bottom-30">
                    <div class="recent-image">
                        {!! render_image_markup_by_attachment_id($data->image,'','grid') !!}

                        <div class="award-flex-position">
                            @if($data->featured === 'on')
                                <div class="award-new-icon">
                                    <i class="las la-award"></i>
                                </div>
                            @endif

                            @if($data->reward === 'on')
                                <div class="award-new-icon">
                                    <i class="las la-gift"></i>
                                </div>
                            @endif
                        </div>


                    </div>
                    <div class="recent-contents">
                        <h3 class="title">
                            <a href="{{route('frontend.donations.single',$data->slug)}}"> {{ $data->title ?? '' }} </a>
                        </h3>
                        <div class="recent-flex">
                            <div class="goal">
                                <h4 class="raised">{{__('Raised')}}:  <span class="main-color-three">{{amount_with_currency_symbol($data->raised ?? 0 )}} </span></h4>
                            </div>
                            <div class="goal">
                                <h4 class="raised">{{__('Goal')}}: <span class="danger-color">{{amount_with_currency_symbol($data->amount)}}</span></h4>
                            </div>
                            <div class="progress-item">
                                <div class="single-progressbar">
                                    <div class="donation-progress" data-percentage="{{get_percentage($data->amount,$data->raised)}}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-lg-12">
                <div class="btn-wrapper text-center">
                    <a href="{{route('frontend.donations')}}" class="boxed-btn reverse-color btn-rounded ">{!! filter_static_option_value('home_page_05_recent_causes_area_see_all_button_text',$static_field_data) !!} </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if(get_static_option('home_page_events_area_05_section_status'))
<section class="events-area-two position-relative section-bg-3 padding-top-90 padding-bottom-120">
    <div class="section-shapes">
        <img src="{{asset('assets/frontend/img/bg/top-shapes2.png')}}" alt="">
        <img src="{{asset('assets/frontend/img/bg/bottom-shapes2.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-four b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{!! filter_static_option_value('home_page_05_events_area_title',$static_field_data) !!}</span>
                    <h2 class="title"> {!! filter_static_option_value('home_page_05_events_area_subtitle',$static_field_data) !!} <img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-xl-6">
                <div class="events-thumbs-man">
                   {!! render_image_markup_by_attachment_id(filter_static_option_value('home_page_05_events_area_left_image',$static_field_data)) !!}
                </div>
            </div>
            <div class="col-xl-6">
                @foreach($all_recent_events as $data)
                <div class="single-events style-02 margin-bottom-50">
                    <div class="events-flex-contents">
                        <div class="events-date">
                            <div class="events-boxe">
                                <span class="events-title"> {{ date('d', strtotime($data->date)) }} </span>
                                <p class="event-para"> {{ date('M', strtotime($data->date)) }} </p>
                            </div>
                        </div>
                        <div class="events-content">
                            <h3 class="title"><a href="{{route('frontend.events.single',$data->slug)}}">{{$data->title}}</a></h3>
                        
                            <span class="event-place"> {{$data->venue_location}} </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@if(get_static_option('home_page_blog_area_05_section_status'))
<section class="blog-area-two padding-top-140 padding-bottom-110">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-four b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{!! filter_static_option_value('home_page_05_recent_blog_area_title',$static_field_data) !!}</span>
                    <h2 class="title"> {!! filter_static_option_value('home_page_05_recent_blog_area_subtitle',$static_field_data) !!} <img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="blog-slider">
                    @foreach($all_blog as $key=> $data)
                        <div class="single-blog-items blog-childs">
                            <div class="single-blog style-02 margin-bottom-30">
                                <div class="blog-thums">
                                    {!! render_image_markup_by_attachment_id($data->image,'','grid') !!}
                                </div>
                                <div class="blog-flexs">
                                    <div class="blog-date-content">
                                        <div class="blog-dates">
                                            <h4> {{ date('d',strtotime($data->created_at)) }} </h4>
                                            <span>{{ date('M',strtotime($data->created_at)) }}</span>
                                        </div>
                                    </div>
                                    <div class="blog-contents">
                                        <span class="blog-tag"> {!! get_blog_category_by_id($data->blog_categories_id,'link') !!} </span>
                                        <h4 class="blog-title"> <a href="{{route('frontend.blog.single',$data->slug)}}"> {{$data->title  ?? __("Anonymous")}} </a> </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@include('frontend.partials.client-area')

@section('scripts')
        <script>
            $(document).on('click','.donation_redirect_button',function(e){
                e.preventDefault();
                var select = $('#donation_select');
                var donationId = select.val();
                var paymentPageUrl = $('#donation_select option[value="'+donationId+'"]').data('url');
                var amount = $('.user_input_number').val();
                window.location = paymentPageUrl+'?number='+amount;
            });

        </script>
@endsection