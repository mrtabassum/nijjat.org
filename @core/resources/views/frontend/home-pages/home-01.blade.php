@include('frontend.partials.navbar')
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
        <div class="header-area header-bg"
                {!! render_background_image_markup_by_attachment_id($data->image) !!}
        >
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="header-inner">
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
                                    <a href="{{$data->btn_01_url}}" class="boxed-btn ">{{$data->btn_01_text}}</a>
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


@if(get_static_option('home_page_about_us_section_status'))
<div class="header-bottom-area m-top bg-image padding-bottom-120 padding-top-120"
     style="background-image: url('{{asset('assets/frontend/img/shape/02.png')}}');">
    <div class="container">
        <div class="row">
            <div class="col-xl-5 col-lg-12">
                <div class="help-and-support-left">
                    <div class="section-title margin-bottom-35">
                        <span>{{filter_static_option_value('home_page_01_about_us_subtitle',$static_field_data)}}</span>

                        <h2 class="title">
                            {!! render_colored_text(filter_static_option_value('home_page_01_about_us_title',$static_field_data)) !!}
                        </h2>
                        <div class="description">{!!  filter_static_option_value('home_page_01_about_us_description',$static_field_data) !!}</div>
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
            <div class="col-xl-6 col-lg-12 offset-xl-1">
                <div class="help-and-support-right bg-image"
                     style="background-image: url({{asset('assets/frontend/img/shape/04.png')}});">
                    <div class="support-img">
                        <div class="thumb">
                            {!! render_image_markup_by_attachment_id(filter_static_option_value('home_page_01_about_us_right_image',$static_field_data)) !!}
                        </div>
                        @if(!empty(filter_static_option_value('home_page_01_about_us_total_donation',$static_field_data)))
                        <div class="donation-content">
                            <h3 class="price">{{filter_static_option_value('home_page_01_about_us_total_donation',$static_field_data)}}</h3>
                            <span>{{filter_static_option_value('home_page_01_about_us_donation_text',$static_field_data)}}</span>
                        </div>
                       @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(get_static_option('home_page_key_feature_section_status'))
        <div class="volunteer-area padding-top-160">
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
                        <div class="volunteer-single-item margin-bottom-30 style-{{$loop->index}} bg-image"
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
      @endif
    </div>
</div>


<div class="our-latest-area padding-bottom-90 padding-top-90">
    <div class="container">
        @if(get_static_option('home_page_cause_category_section_status'))
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
                        <x-frontend.donation.category
                        :id="$data->id"
                        :image="$data->image"
                        :title="$data->title"
                        :count="$data->donation->count()"
                        :description="$data->description"
                        :reward="$data->reward">
                        </x-frontend.donation.category>
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
        @endif
    </div>
</div>


@if(get_static_option('home_page_video_section_status'))
<div class="work-towards-area bg-image padding-bottom-115 padding-top-120"
{!! render_background_image_markup_by_attachment_id(filter_static_option_value('home_page_01_cta_area_background_image',$static_field_data)) !!}
>
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <div class="left-content">
                    <div class="inner-section-title padding-top-160 bg-image"
                    {!! render_background_image_markup_by_attachment_id(filter_static_option_value('home_page_01_cta_area_signature_image',$static_field_data)) !!}
                    >
                        <h2 class="title">{{filter_static_option_value('home_page_01_cta_area_title',$static_field_data)}}</h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="right-content">
                    <div class="vdo-btn">
                        <a class="video-play mfp-iframe" href="{{filter_static_option_value('home_page_01_cta_area_video_url',$static_field_data)}}"><i
                                    class="fas fa-play"></i></a>
                    </div>
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


@if(get_static_option('home_page_team_member_section_status'))
<section class="volunteer-area bg-image padding-bottom-90 padding-top-120"
         style="background-image: url({{asset('assets/frontend/img/bg/shape-bg-01.png')}});">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="volunteer-title-content margin-bottom-50">
                    <div class="section-title desktop-left">
                        <span>{{filter_static_option_value('home_page_01_team_member_section_subtitle',$static_field_data)}}</span>
                        <h3 class="title">{!! render_colored_text(filter_static_option_value('home_page_01_team_member_section_title',$static_field_data)) !!}</h3>
                    </div>
                    <div class="section-paragraph volunteer-slider-container"></div>
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



<section class="testimonial-area bg-image padding-top-105"
@if(get_static_option('home_page_testimonial_section_status'))
    {!! render_background_image_markup_by_attachment_id(filter_static_option_value('home_01_testimonial_bg',$static_field_data)) !!}
        >
        <div class="container">
            <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-title white desktop-center margin-bottom-50">
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
                    <div class="single-testimonial-item">
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
  @endif

@if(get_static_option('home_page_counterup_section_status'))
    <div class="counterup-area padding-bottom-120 padding-top-100">
        <div class="container">
            <div class="counterup-area-wrapper">
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
    </div>
    @endif
</section>


@if(get_static_option('home_page_latest_events_section_status'))
<section class="attend-events-area padding-top-115 padding-bottom-90">
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
            <div class="col-lg-12">
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
    </div>
</section>
@endif


@if(get_static_option('home_page_latest_blog_section_status'))
<section class="blog-area bg-image padding-top-120 padding-bottom-90"
         style="background-image: url({{asset('assets/frontend/img/bg/news-bg.png')}});">
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
