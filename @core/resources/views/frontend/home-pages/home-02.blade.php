@php
    $home_page_variant = $home_page ?? get_static_option('home_page_variant');
@endphp
<div class="header-style-01 home-page-variant-{{$home_page_variant}}">
    <div class="topbar-area style-01">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="topbar-inner">
                        <div class="left-contnet">
                            <ul class="info-items">
                                @php
                                    $all_icon_fields =  filter_static_option_value('home_page_01_topbar_info_list_icon_icon',$global_static_field_data);
                                    $all_icon_fields =  !empty($all_icon_fields) ? unserialize($all_icon_fields) : [];
                                    $all_title_fields = filter_static_option_value('home_page_01_topbar_info_list_text',$global_static_field_data);
                                    $all_title_fields = !empty($all_title_fields) ? unserialize($all_title_fields) : [];
                                @endphp
                                @foreach($all_icon_fields as $index => $icon)
                                    <li><i class="{{$icon}}"></i> {{isset($all_title_fields[$index]) ? $all_title_fields[$index] : ''}}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="right-contnet">
                            <div class="social-link">
                                <ul>
                                    @foreach($all_social_item as $data)
                                        <li><a href="{{$data->url}}"><i class="{{$data->icon}}"></i></a></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="volunteer-right">
                                <ul class="info-items-02">
                                    <x-front-user-login-li/>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-area navbar-expand-lg charity-nav-02 has-topbar nav-style-02">
        <div class="container nav-container">
            <div class="responsive-mobile-menu">
                <div class="logo-wrapper">
                    @if(filter_static_option_value('site_logo',$global_static_field_data) || filter_static_option_value('site_white_logo',$global_static_field_data))
                    <a href="{{route('homepage')}}" class="logo-pc">
                        {!! render_image_markup_by_attachment_id(filter_static_option_value('site_logo',$global_static_field_data)) !!}
                    </a>
                    <a href="{{route('homepage')}}" class="mobile-logo">
                        {!! render_image_markup_by_attachment_id(filter_static_option_value('site_white_logo',$global_static_field_data)) !!}
                    </a>
                    @else
                        <a href="{{route('homepage')}}">
                           <span class="site-title">{{filter_static_option_value('site_title',$global_static_field_data)}}</span>
                        </a>
                    @endif
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
            @if(!empty(filter_static_option_value('home_page_navbar_button_status',$global_static_field_data)))
                <div class="nav-right-content">
                    <ul>
                        <li>
                            <x-front-donate-btn :home="$home_page_variant"/>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </nav>

</div>

@if(get_static_option('home_page_header_slider_section_status'))
    <div class="global-carousel-init header-slider-one"
         data-loop="true"
         data-desktopitem="1"
         data-mobileitem="1"
         data-tabletitem="1"
         data-nav="true"
         data-autoplay="true"
    >
    @foreach($all_header_slider as $data)
    <div class="header-area style-01 header-bg-02" {!! render_background_image_markup_by_attachment_id($data->image) !!}>
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="header-inner-02">
                        @if(!empty($data->subtitle))
                            <p class="subtitle">{{$data->subtitle}}</p>
                        @endif
                        @if(!empty($data->title))
                            <h1 class="title">{{$data->title}}</h1>
                        @endif
                        @if(!empty($data->description))
                            <p class="description">{{$data->description}}</p>
                        @endif
                        @if(!empty($data->btn_01_status))
                            <div class="btn-wrapper  desktop-left padding-top-30">
                                <a href="{{$data->btn_01_url}}" class="boxed-btn reverse-color">{{$data->btn_01_text}}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@if(get_static_option('home_page_key_feature_section_status'))
<div class="volunteer-area m-top">
    <div class="container">
        <div class="row">
            @php
                $homepage_01_feature_section_icon = filter_static_option_value('homepage_01_feature_section_icon',$static_field_data);
                $all_icon_fields = !empty($homepage_01_feature_section_icon) ? unserialize($homepage_01_feature_section_icon) : [];
                $all_title_fields = filter_static_option_value('homepage_01_feature_section_title',$static_field_data);
                $all_title_fields = !empty($all_title_fields) ? unserialize($all_title_fields) : [];
                $all_description_fields = filter_static_option_value('homepage_01_feature_section_description',$static_field_data);
                $all_description_fields = !empty($all_description_fields) ? unserialize($all_description_fields) : [];
                $all_url_fields =  filter_static_option_value('homepage_01_feature_section_url',$static_field_data);
                $all_url_fields = !empty($all_url_fields) ? unserialize($all_url_fields) : [];
            @endphp
            @foreach($all_icon_fields as $icon)
                <div class="col-lg-6 col-xl-4 col-md-6">
                    <div class="volunteer-single-item no-border margin-bottom-30 style-{{$loop->index}} bg-image"
                         style="background-image: url({{asset('assets/frontend/img/shape/volunteer-bg.png')}});">
                        <div class="icon">
                            <i class="{{$icon}}"></i>
                        </div>
                        <div class="content">
                            <h4 class="title">
                                <a href="{{$all_url_fields[$loop->index] ?? ''}}">{{$all_title_fields[$loop->index] ?? ''}}</a>
                            </h4>
                            <p>{{$all_description_fields[$loop->index] ?? ''}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif


@if(get_static_option('home_page_about_us_section_status'))
<div class="header-bottom-area bg-white padding-bottom-120 padding-top-90">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-xl-4">
                <div class="help-and-support-right">
                    <div class="support-img-02 bg-image"
                    {!! render_background_image_markup_by_attachment_id(filter_static_option_value('home_page_02_about_us_left_image',$static_field_data)) !!}
                    ></div>
                </div>
            </div>
            <div class="col-lg-6 col-xl-4">
                <div class="help-and-support-left">
                    <div class="section-title margin-bottom-35">
                        <span>{{filter_static_option_value('home_page_01_about_us_subtitle',$static_field_data)}}</span>
                        <h2 class="title"> {!! render_colored_text(filter_static_option_value('home_page_01_about_us_title',$static_field_data)) !!}</h2>
                        <div>{!!  filter_static_option_value('home_page_01_about_us_description',$static_field_data) !!}</div>
                    </div>
                    <div class="content">
                        @php
                            $_about_us_lists = filter_static_option_value('home_page_01_about_us_lists',$static_field_data) ?? [];
                            $_about_us_list = explode("\n",$_about_us_lists);
                        @endphp
                        <ul>
                            @foreach($_about_us_list as $item)
                                <li><i class="fas fa-check"></i> {{$item}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-4">
                <div class="support-single-item">
                    <div class="icon">
                        <i class="{{filter_static_option_value('home_page_02_about_us_icon',$static_field_data)}}"></i>
                    </div>
                    <div class="content">
                        <h3 class="title"> {{filter_static_option_value('home_page_01_about_us_total_donation',$static_field_data)}}</h3>
                        <div class="details">{{filter_static_option_value('home_page_02_about_us_donation_text',$static_field_data)}}</div>
                    </div>
                </div>
                <div>{!!  filter_static_option_value('home_page_02_about_us_short_description',$static_field_data) !!}</div>
                <div class="support-single-img padding-top-25">
                    {!! render_image_markup_by_attachment_id(filter_static_option_value('home_page_02_about_us_right_bottom_image',$static_field_data)) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endif


@if(get_static_option('home_page_cause_category_section_status'))
<div class="our-latest-area padding-bottom-90 bg-image padding-top-90"
style="background-image: url({{asset('assets/frontend/img/shape/02.png')}})"
>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{{filter_static_option_value('home_page_01_donation_category_subtitle',$static_field_data)}}</span>
                    <h2 class="title">{!! render_colored_text(filter_static_option_value('home_page_01_donation_category_title',$static_field_data)) !!}</h2>
                </div>
            </div>
        </div>
        <div class="row padding-bottom-120">
            <div class="col-lg-12">
                <div class="global-carousel-init slider-dots"
                     data-loop="true"
                     data-desktopitem="4"
                     data-mobileitem="1"
                     data-tabletitem="2"
                     data-dots="true"
                     data-margin="30"
                     data-autoplay="true"
                >
                    @foreach($all_donation_category as $data)
                        <div class="single-donation-category-item"
                                {!! render_background_image_markup_by_attachment_id($data->image,'grid') !!}
                        >
                            <a href="{{route('frontend.donations.category',['id' => $data->id,'any' => Str::slug($data->title) ?? '' ])}}">

                                <div class="hover-content">
                                    <h3 class="title">{{$data->title}} <span class="count">({{$data->donation->count()}})</span>
                                    </h3>
                                    <p class="description">{{$data->description}}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif


        @if(get_static_option('home_page_feature_cause_section_status'))
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{{filter_static_option_value('home_page_01_featured_cause_area_subtitle',$static_field_data)}}</span>
                    <h2 class="title">{!! render_colored_text(filter_static_option_value('home_page_01_featured_cause_area_title',$static_field_data)) !!}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="global-carousel-init slider-dots"
                     data-loop="true"
                     data-desktopitem="3"
                     data-mobileitem="1"
                     data-tabletitem="2"
                     data-dots="true"
                     data-margin="30"
                     data-autoplay="true"
                >
                    @foreach($feature_cause as $data)
                        <x-frontend.donation.grid
                                :featured="$data->featured"
                                :image="$data->image"
                                :amount="$data->amount"
                                :raised="$data->raised"
                                :slug="$data->slug"
                                :title="$data->title"
                                :excerpt="$data->excerpt"
                                :reward="$data->reward"
                                :buttontext="filter_static_option_value('home_page_01_featured_cause_area_button_text',$static_field_data)">
                        </x-frontend.donation.grid>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif


@if(get_static_option('home_page_recent_cause_section_status'))
<section class="problem-area padding-top-90 padding-bottom-85">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title b-top desktop-center padding-top-25 margin-bottom-55">
                    <span>{{filter_static_option_value('home_page_01_latest_cause_subtitle',$static_field_data)}}</span>
                    <h2 class="title">{!! render_colored_text(filter_static_option_value('home_page_01_latest_cause_title',$static_field_data)) !!}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($all_recent_donation as $data)
                <div class="col-lg-4 col-md-6">
                    <x-frontend.donation.grid
                            :featured="$data->featured"
                            :image="$data->image"
                            :amount="$data->amount"
                            :raised="$data->raised"
                            :slug="$data->slug"
                            :title="$data->title"
                            :excerpt="$data->excerpt"
                            :reward="$data->reward"
                            :buttontext="filter_static_option_value('home_page_01_featured_cause_area_button_text',$static_field_data)">
                    </x-frontend.donation.grid>
                </div>
            @endforeach
            <div class="col-lg-12">
                <div class="btn-wrapper text-center">
                    <a href="{{route('frontend.donations')}}" class="boxed-btn reverse-color">{{filter_static_option_value('home_page_01_latest_cause_button_text',$static_field_data)}}</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@if(get_static_option('home_page_video_section_status'))
<div class="work-towards-area bg-image padding-bottom-120 padding-top-105"
{!! render_background_image_markup_by_attachment_id(filter_static_option_value('home_page_01_cta_area_background_image',$static_field_data)) !!}
>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="left-content">
                    <div class="inner-section-title desktop-center">
                        <h2 class="title">{{filter_static_option_value('home_page_01_cta_area_title',$static_field_data)}}</h2>
                    </div>
                </div>
                <div class="right-content style-01">
                    <div class="vdo-btn">
                        <a class="video-play mfp-iframe" href="{{filter_static_option_value('home_page_01_cta_area_video_url',$static_field_data)}}"><i class="fas fa-play"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif


@if(get_static_option('home_page_team_member_section_status'))
<section class="volunteer-area padding-bottom-90 padding-top-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="volunteer-title-content margin-bottom-50">
                    <div class="section-title desktop-left">
                        <span>{{filter_static_option_value('home_page_01_team_member_section_subtitle',$static_field_data)}}</span>
                        <h3 class="title">{!! render_colored_text(filter_static_option_value('home_page_01_team_member_section_title',$static_field_data)) !!}</h3>
                    </div>
                    <div class="section-paragraph volunteer-slider-container">

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="global-carousel-init slider-dots"
                     data-loop="true"
                     data-desktopitem="4"
                     data-mobileitem="1"
                     data-tabletitem="2"
                     data-dots="true"
                     data-nav="true"
                     data-margin="30"
                     data-autoplay="true"
                     data-navcontainer=".volunteer-slider-container"
                >
                    @foreach($all_team_members as $data)
                        <x-frontend.team.grid
                                :image="$data->image"
                                :index="$loop->index"
                                :name="$data->name"
                                :iconone="$data->icon_one"
                                :icononeurl="$data->icon_one_url"
                                :icontwo="$data->icon_two"
                                :icontwourl="$data->icon_two_url"
                                :iconthree="$data->icon_three"
                                :iconthreeurl="$data->icon_three_url">
                        </x-frontend.team.grid>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@if(get_static_option('home_page_what_we_do_section_status'))
<section class="problem-area margin-top-90 padding-top-120 padding-bottom-120">
    <div class="bg-img" {!! render_background_image_markup_by_attachment_id(filter_static_option_value('home_page_02_what_we_do_left_image',$static_field_data)) !!}></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-4">
                <div class="problem-area-wrapper">
                    <div class="row">
                        <div class="col-lg-12 col-sm-11 col-11">
                            <div class="section-title margin-bottom-55">
                                <span>{{filter_static_option_value('home_page_02_what_we_do_area_subtitle',$static_field_data)}}</span>
                                <h2 class="title">{!! render_colored_text(filter_static_option_value('home_page_02_what_we_do_area_title',$static_field_data)) !!}</h2>
                            </div>
                        </div>
                        @php
                            $all_icon_fields =  get_static_option('homepage_02_what_we_do_section_icon');
                            $all_icon_fields = !empty($all_icon_fields) ? unserialize($all_icon_fields) : [];
                            $all_title_fields = get_static_option('homepage_02_what_we_do_section_title');
                            $all_title_fields = !empty($all_title_fields) ? unserialize($all_title_fields) : [];
                            $all_description_fields = get_static_option('homepage_02_what_we_do_section_description');
                            $all_description_fields = !empty($all_description_fields) ? unserialize($all_description_fields) : [];
                            $all_url_fields =  get_static_option('homepage_02_what_we_do_section_url');
                            $all_url_fields = !empty($all_url_fields) ? unserialize($all_url_fields) : [];
                        @endphp
                        @foreach($all_icon_fields as $icon)
                        <div class="col-lg-6 col-md-6">
                            <div class="problem-single-item style-01 margin-bottom-30">
                                <div class="icon">
                                    <i class="{{$icon}}"></i>
                                </div>
                                <div class="content">
                                    <h4 class="title">
                                        <a href="{{$all_url_fields[$loop->index] ?? ''}}">{{$all_title_fields[$loop->index] ?? ''}}</a>
                                    </h4>
                                    <p>{{$all_description_fields[$loop->index] ?? ''}}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@if(get_static_option('home_page_testimonial_section_status'))
<!-- testimonial area start  -->
<section class="testimonial-area padding-bottom-100 padding-top-115">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title desktop-center margin-bottom-50">
                    <span class="subtitle">{{filter_static_option_value('home_page_01_testimonial_section_subtitle',$static_field_data)}}</span>
                    <h3 class="title">{!! render_colored_text(filter_static_option_value('home_page_01_testimonial_section_title',$static_field_data)) !!}</h3>
                </div>
            </div>
        </div>
        <div class="row no-gutters justify-content-center">
            <div class="col-lg-7">
                <div class="global-carousel-init slider-dots"
                     data-loop="true"
                     data-desktopitem="1"
                     data-mobileitem="1"
                     data-tabletitem="1"
                     data-dots="true"
                     data-margin="0"
                     data-autoplay="true"
                >
                    @foreach($all_testimonial as $data)
                        <div class="single-testimonial-item dark">
                            <div class="thumb bg-image" {!! render_background_image_markup_by_attachment_id($data->image) !!}>
                                <div class="icon">
                                    <i class="fas fa-quote-right"></i>
                                </div>
                            </div>
                            <div class="content">
                                <div class="author-details">
                                    <div class="author-meta">
                                        <h4 class="title">{{$data->name}}</h4>
                                        <span class="designation">{{$data->designation}}</span>
                                    </div>
                                </div>
                                <p class="description">{{$data->description}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif


@if(get_static_option('home_page_counterup_section_status'))
<div class="counterup-area bg-image padding-bottom-105 padding-top-90"
     {!! render_background_image_markup_by_attachment_id(filter_static_option_value('home_page_02_coutnerup_background_image',$static_field_data)) !!} }>
    <div class="container">
        <div class="row">
            @foreach($all_counterup as $data)
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="single-counterup-01">
                        <div class="icon">
                            <i class="{{$data->icon}}"></i>
                        </div>
                        <div class="content">
                            <div class="count-wrap"><span class="count-num">{{$data->number}}</span>{{$data->extra_text}}</div>
                            <h4 class="title">{{$data->title}}</h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif


@if(get_static_option('home_page_latest_events_section_status'))
<section class="attend-events-area m-top bg-image padding-top-115 padding-bottom-90"
         style="background-image: url({{asset('assets/frontend/img/shape/02.png')}})"
>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="attend-title-content margin-bottom-50">
                    <div class="section-title desktop-left">
                        <span>{{filter_static_option_value('home_page_01_latest_event_subtitle',$static_field_data)}}</span>
                        <h3 class="title">{!! render_colored_text(filter_static_option_value('home_page_01_latest_event_title',$static_field_data)) !!}</h3>
                    </div>
                    <div class="section-paragraph">
                        <div class="event-nav-container"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="global-carousel-init slider-dots"
                 data-loop="true"
                 data-desktopitem="2"
                 data-mobileitem="1"
                 data-tabletitem="1"
                 data-nav="true"
                 data-navcontainer=".event-nav-container"
                 data-dots="true"
                 data-margin="30"
                 data-autoplay="true"
            >
                @foreach($all_recent_events as $data)
                    <x-frontend.event.grid
                            :image="$data->image"
                            :date="$data->date"
                            :slug="$data->slug"
                            :title="$data->title"
                            :time="$data->time"
                            :cost="$data->cost"
                            :venuelocation="$data->venue_location"
                            :buttontext="filter_static_option_value('home_page_01_latest_event_button_text',$static_field_data)"
                    >
                    </x-frontend.event.grid>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@if(get_static_option('home_page_latest_blog_section_status'))
<section class="blog-area bg-white padding-top-120 padding-bottom-90">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title desktop-center margin-bottom-50">
                    <span>{{filter_static_option_value('home_page_01_latest_news_subtitle',$static_field_data)}}</span>
                    <h3 class="title">{!! render_colored_text(filter_static_option_value('home_page_01_latest_news_title',$static_field_data)) !!}</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="global-carousel-init slider-dots"
                     data-loop="true"
                     data-desktopitem="3"
                     data-mobileitem="1"
                     data-tabletitem="2"
                     data-dots="true"
                     data-margin="30"
                     data-autoplay="true"
                >
                    @foreach($all_blog as $data)
                        <x-frontend.blog.grid01
                                :image="$data->image"
                                :date="$data->created_at"
                                :author="$data->author"
                                :catid="$data->blog_categories_id"
                                :slug="$data->slug"
                                :title="$data->title">
                        </x-frontend.blog.grid01>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</section>
@endif