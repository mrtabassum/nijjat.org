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
    <nav class="navbar navbar-area navbar-expand-lg charity-nav-04 has-topbar has-topbar-04 nav-style-02">
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
                    <li class="search-lists">
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
                </ul>
            </div>
            <div class="nav-right-content">
                <ul>
                    <x-front-donate-btn-last-three-home/>
                </ul>
            </div>
        </div>
    </nav>
</div>


@php
    $classes = ['reverse-color','btn-color-three','btn-dander','btn-color-three'];
@endphp

@if(get_static_option('home_page_header_area_04_section_status'))
<div class=" header-slider-four">
    <div class="header-area style-02 header-bg-04 banner-padiing" {!! render_background_image_markup_by_attachment_id(filter_static_option_value('home_page_04_header_area_bg_image',$static_field_data)) !!}>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="header-inner-02 header-inner-04">
                        <p class="animate-style-02">{!! filter_static_option_value('home_page_04_header_area_title',$static_field_data) !!}</p>
                        <h1 class="title animate-style">{!! filter_static_option_value('home_page_04_header_area_subtitle',$static_field_data) !!}
                            {!! render_image_markup_by_attachment_id(filter_static_option_value('home_page_04_header_area_line_image',$static_field_data)) !!}
                        </h1>
                        @php
                            $url = get_static_option('home_page_04_donate_button_text_url') ?? route('frontend.donations');
                        @endphp
                        <div class="btn-wrapper padding-top-30">
                            <a href="{{$url}}" class="boxed-btn secondary-color-two btn-rounded">{{  filter_static_option_value('home_page_04_donate_button_text',$static_field_data) }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <div class="inner-right-contents">
                        <span class="donate-title"> {!! filter_static_option_value('home_page_04_right_side_heading',$static_field_data) !!} </span>
                        <div class="inner-content-all">
                            <div class="nice-selects">
                                <select id="donation_cause_id">
                                    @foreach($all_donation as $donation)
                                    <option value="{{$donation->id}}" data-url="{{route('frontend.donation.in.separate.page',$donation->id)}}"> {{ Str::words($donation->title,8) }}</option>
                                     @endforeach
                                </select>
                            </div>
                            <div class="donate-input">
                                <input class="form--control donation-amount" type="number" value="500">
                                @php
                                    $custom_amounts =  [50,100,150,200];
                                @endphp

                                <ul class="donate-lists">
                                    @foreach($custom_amounts as $amount)
                                         <li data-value="{{trim($amount)}}" class=""> {{amount_with_currency_symbol($amount)}} </li>
                                     @endforeach

                                </ul>
                            </div>
                            <a href="#" class="boxed-btn donate-btn" id="donation_redirect_btn">  {!! filter_static_option_value('home_page_04_right_side_donate_button_text',$static_field_data) !!} </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


@if(get_static_option('home_page_category_area_04_section_status'))
<section class="category-area padding-top-120 padding-bottom-125">
    <div class="top-shapes">
        <img src="{{asset('assets/frontend/img/category/top-shapes.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="category-slider">
                    @foreach($all_donation_category as $data)
                    <div class="single-category-01">
                        <div class="category-01-shape">
                            <img src="{{asset('assets/frontend/img/category/health-shape1.png')}}" alt="">
                            <img src="{{asset('assets/frontend/img/category/health-shape2.png')}}" alt="">
                        </div>
                        <div class="category-01-image">
                            {!! render_image_markup_by_attachment_id($data->image,'thumb') !!}
                        </div>
                        <div class="category-01-content">
                            <h4 class="category-para"> <a href="{{route('frontend.donations.category',['id' => $data->id,'any' => Str::slug($data->title) ?? '' ])}}"> {{$data->title}} </a> </h4>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if(get_static_option('home_page_feature_area_04_section_status'))
<section class="featured-area section-bg-2 padding-top-90 padding-bottom-120">
    <div class="section-shapes">
        <img src="{{asset('assets/frontend/img/bg/top-shapes.png')}}" alt="">
        <img src="{{asset('assets/frontend/img/bg/bottom-shapes.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-four b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{!! filter_static_option_value('home_page_04_feature_area_title',$static_field_data) !!}</span>
                    <h2 class="title">{!! filter_static_option_value('home_page_04_feature_area_subtitle',$static_field_data) !!} <img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="featured-slider">
                    @foreach($feature_cause as $key=> $data)
                    <div class="single-featured-items">
                        <div class="single-featured">
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
                                    <a href="{{route('frontend.donations.single',$data->slug)}}">{{$data->title}}</a>
                                </h3>
                                <p class="excpert">
                                    {{$data->excerpt}}
                                </p>

                                <div class="goal">
                                    <h4 class="raised">{{__('Raised')}}:  <span class="main-color-three">{{amount_with_currency_symbol($data->raised ?? 0 )}} </span></h4>
                                    <h4 class="raised">{{__('Goal')}}: <span class="danger-color">{{amount_with_currency_symbol($data->amount)}}</span></h4>
                                </div>
                                @php
                                    $classes = ['reverse-color','btn-color-three','btn-dander','btn-color-three'];
                                @endphp
                                <div class="btn-wrapper">
                                    <a href="{{route('frontend.donation.in.separate.page',$data->id)}}" class="boxed-btn {{$classes[$key % count($classes)]  }}"> {!! filter_static_option_value('home_page_04_feature_area_donation_button_text',$static_field_data) !!} </a>
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


@if(get_static_option('home_page_success_story_area_04_section_status'))
<section class="success-area padding-top-140 padding-bottom-140">
    <div class="success-icon-shapes">
        <img src="{{asset('assets/frontend/img/success/success-icon-s.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-four b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{!! filter_static_option_value('home_page_04_success_story_area_title',$static_field_data) !!} </span>
                    <h2 class="title"> {!! filter_static_option_value('home_page_04_success_story_area_subtitle',$static_field_data) !!}<img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="success-slider">
                    @foreach($all_success_stories as $key=> $data)
                    <div class="single-success-items">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="success-thums success-thums-two margin-bottom-30">
                                   {!! render_image_markup_by_attachment_id($data->image) !!}
                                </div>
                            </div>
                            <div class="col-lg-5 offset-lg-1">
                                <div class="success-contents margin-bottom-30">
                                   <h4 class="success-titles"> <a href="{{route('frontend.success.story.single',$data->slug)}}"> {{$data->title ?? ''}}</a></h4>

                                     <p>{{purify_html($data->excerpt) }}</p>

                                    <div class="btn-wrapper">
                                        <a href="{{route('frontend.success.story.single',$data->slug)}}" class="boxed-btn {{$classes[$key % count($classes)]}}"> {!! filter_static_option_value('home_page_04_success_story_area_button_text',$static_field_data) !!}</a>
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


@if(get_static_option('home_page_aboutus_area_04_section_status'))
<section class="about-area section-bg-2 padding-top-120 padding-bottom-90">
    <div class="section-shapes">
        <img src="{{asset('assets/frontend/img/bg/top-shapes.png')}}" alt="">
        <img src="{{asset('assets/frontend/img/bg/bottom-shapes.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 margin-bottom-30">
                <div class="about-text-contents">
                    <div class="section-title section-title-four padding-top-25 margin-bottom-40">
                        <span>{{ filter_static_option_value('home_page_04_about_us_area_title',$static_field_data) }}</span>
                        <h2 class="title">{{ filter_static_option_value('home_page_04_about_us_area_subtitle',$static_field_data) }}<img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                    </div>
                    <p> {!! purify_html( filter_static_option_value('home_page_04_about_us_area_description',$static_field_data)) !!} </p>
                    <p>

                    <div class="btn-wrapper">
                        <a href="{!! purify_html( filter_static_option_value('home_page_04_about_us_area_button_url',$static_field_data)) !!}" class="boxed-btn btn-color-three"> {!! purify_html( filter_static_option_value('home_page_04_about_us_area_button_text',$static_field_data)) !!} </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-lg-6 offset-xl-1 margin-bottom-30">
                <div class="row p-0">
                    @foreach($all_counterup as $data)
                    <div class="col-lg-6 col-md-6 p-0 counter-child">
                        <div class="single-counterup-02">
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
    </div>
</section>
@endif

@if(get_static_option('home_page_events_area_04_section_status'))
<section class="events-area-two padding-top-140 padding-bottom-140">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-four b-top desktop-center padding-top-25 margin-bottom-55">
                    <span> {!! filter_static_option_value('home_page_04_events_area_title',$static_field_data) !!} </span>
                    <h2 class="title"> {!! filter_static_option_value('home_page_04_events_area_subtitle',$static_field_data) !!} <img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                </div>
            </div>
        </div>
        <div class="row">
           @foreach($all_recent_events as $data)
            <div class="col-lg-6 event-child">
                <div class="single-events margin-bottom-50">
                    <div class="events-flex-contents">
                        <div class="events-date">
                            <div class="events-boxe">
                                <span class="events-title"> {{ date('d', strtotime($data->date)) }} </span>
                                <p class="event-para">{{date('M', strtotime($data->date))}} </p>
                            </div>
                        </div>
                        <div class="events-content">
                            <h3 class="title"><a href="{{route('frontend.events.single',$data->slug)}}">{{$data->title}}</a></h3>
                            <p class="content-para"> {!! Str::words(purify_html( strip_tags($data->content)),12) !!} </p>
                            <span class="event-place"> {{$data->venue_location}} </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif



@if(get_static_option('home_page_recent_cause_area_04_section_status'))
<section class="recent-area section-bg-2 padding-top-90 padding-bottom-120">
    <div class="section-shapes">
        <img src="{{asset('assets/frontend/img/bg/top-shapes.png')}}" alt="">
        <img src="{{asset('assets/frontend/img/bg/bottom-shapes.png')}}" alt="">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-four b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{!! filter_static_option_value('home_page_04_recent_causes_area_title',$static_field_data) !!}</span>
                    <h2 class="title">{!! filter_static_option_value('home_page_04_recent_causes_area_subtitle',$static_field_data) !!}<img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                </div>
            </div>
        </div>
        <div class="row">

            @foreach($all_recent_donation as $key=> $data)
            <div class="col-lg-4 col-md-6">
                <div class="recent-single margin-bottom-30">
                    <div class="recent-thumb">
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
                       <div class="description margin-bottom-40">
                            {!! Str::words(purify_html_raw(strip_tags($data->cause_content)),15) !!}
                        </div>
                        <div class="progress-item">
                            <div class="single-progressbar">
                                <div class="donation-progress" data-percentage="{{get_percentage($data->amount,$data->raised)}}"></div>
                            </div>
                        </div>
                        <div class="goal">
                            <h4 class="raised">{{__('Raised')}}:  <span class="main-color-three">{{amount_with_currency_symbol($data->raised ?? 0 )}} </span></h4>
                            <h4 class="raised">{{__('Goal')}}: <span class="danger-color">{{amount_with_currency_symbol($data->amount)}}</span></h4>
                        </div>

                        <div class="btn-wrapper">
                            <a href="{{route('frontend.donations.single',$data->slug)}}" class="boxed-btn {{$classes[$key % count($classes)]}}"> {!! filter_static_option_value('home_page_04_recent_causes_area_button_text',$static_field_data) !!} </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="col-lg-12">
                <div class="btn-wrapper text-center">
                    <a href="{{route('frontend.donations')}}" class="boxed-btn btn-color-three">{!! filter_static_option_value('home_page_04_recent_causes_area_see_all_button_text',$static_field_data) !!} </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if(get_static_option('home_page_blog_area_04_section_status'))
<section class="blog-area-two padding-top-140 padding-bottom-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-four b-top desktop-center padding-top-25 margin-bottom-55">
                    <span> {!! filter_static_option_value('home_page_04_recent_blog_area_title',$static_field_data) !!}</span>
                    <h2 class="title"> {!! filter_static_option_value('home_page_04_recent_blog_area_subtitle',$static_field_data) !!} <img src="{{asset('assets/frontend/img/section-line-shape.png')}}" alt=""> </h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="blog-slider">
                    @foreach($all_blog as $key=> $data)
                    <div class="single-blog-items">
                        <div class="single-blog">
                            @php
                                $blogClasses = ['color-two','color-three'];
                            @endphp
                            <div class="blog-thums">
                               {!! render_image_markup_by_attachment_id($data->image,'','grid') !!}
                                <div class="blog-date-content {{ $blogClasses[$key % count($blogClasses)] }} ">
                                    <div class="blog-dates">
                                        <h4> {{ date('d', strtotime($data->created_at)) }} </h4>
                                        <span>{{date('M',strtotime($data->created_at))}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-contents">
                                <span class="blog-tag"> {!! get_blog_category_by_id($data->blog_categories_id,'link') !!} </span>
                                <h4 class="blog-title"> <a href="{{route('frontend.blog.single',$data->slug)}}"> {{$data->title  ?? __("Anonymous")}} </a> </h4>
                                <p> {!! Str::words(purify_html_raw($data->blog_content),15) !!} </p>
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
        (function ($){
            "use strict";
            $(document).on('click','#donation_redirect_btn',function (e){
                e.preventDefault();

                var select = $('#donation_cause_id');
                var causeId = select.val();
                var causeUrl = $('#donation_cause_id option[value="'+causeId+'"]').data('url');
                var amount = $(this).parent().find('input[type="number"]').val();
                 window.location = causeUrl+'?number='+amount;
            });

        })(jQuery);
    </script>
@endsection

