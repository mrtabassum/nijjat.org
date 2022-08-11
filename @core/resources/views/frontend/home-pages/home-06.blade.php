@php
    $home_page_variant = $home_page ?? get_static_option('home_page_variant');
@endphp
<div class="header-style-01  home-page-variant-{{$home_page_variant}}">
    <div class="topbar-area style-02 home-page-six">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="topbar-inner style-01 home-six-topbar">
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
@php
    $classes = ['reverse-color','btn-color-three','btn-dander','btn-color-three'];
@endphp
    <nav class="navbar navbar-area navbar-expand-lg charity-nav-06 has-topbar has-topbar-04 nav-style-02">
        <div class="container-fluid nav-container">
            <div class="responsive-mobile-menu">
                <div class="logo-wrapper">
                    <a href="{{url('/')}}" class="logo">
                        @if(!empty(filter_static_option_value('site_logo',$global_static_field_data)))
                            {!! render_image_markup_by_attachment_id(filter_static_option_value('site_logo',$global_static_field_data)) !!}
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
                                    <button type="submit" class="search-btn"> {{__('Search')}}</button>
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

</div>

@if(get_static_option('home_page_header_slider_area_06_section_status'))
<div class="header-slider-new">
    @php
        $all_title_fields = get_static_option('home_page_06_header_area_title');
        $all_title_fields = !empty($all_title_fields) ? unserialize($all_title_fields) : [];

        $all_sub_fields = get_static_option('home_page_06_header_area_subtitle');
        $all_sub_fields = !empty($all_sub_fields) ? unserialize($all_sub_fields) : [];

        $all_donate_button_title_fields = get_static_option('home_page_06_header_area_donate_button_title');
        $all_donate_button_title_fields = !empty($all_donate_button_title_fields) ? unserialize($all_donate_button_title_fields) : [];

        $all_donate_button_title_url_fields = get_static_option('home_page_06_header_area_donate_button_url');
        $all_donate_button_title_url_fields = !empty($all_donate_button_title_url_fields) ? unserialize($all_donate_button_title_url_fields) : [];

        $all_read_more_button_title_fields = get_static_option('home_page_06_header_area_read_more_button_title');
        $all_read_more_button_title_fields = !empty($all_read_more_button_title_fields) ? unserialize($all_read_more_button_title_fields) : [];

        $all_read_more_button_title_url_fields =  get_static_option('home_page_06_header_area_read_more_button_url');
        $all_read_more_button_title_url_fields = !empty($all_read_more_button_title_url_fields) ? unserialize($all_read_more_button_title_url_fields) : [];

        $all_image_fields =  get_static_option('home_page_06_header_area_image');
        $all_image_fields = !empty($all_image_fields) ? unserialize($all_image_fields,['class' => false]) : [];
        $home_page_06_header_area_donation =  get_static_option('home_page_06_header_area_donation');
        $home_page_06_header_area_donation = !empty($home_page_06_header_area_donation) ? unserialize($home_page_06_header_area_donation,['class' => false]) : [''];


    @endphp

    @foreach($all_title_fields as $key=> $title)
    <div class="header-area inner-padding header-bg-05" {!! render_background_image_markup_by_attachment_id(get_static_option('home_page_06_header_area_bg_image')) !!}>
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-xl-5 col-lg-12 order-2 order-xl-1">
                    <div class="banner-contents-slider">
                        <div class="header-inner-02 header-inner-04 header-inner-06">
                            <p class="animate-style-02">{{$title}}</p>
                            @php
                                $donation_cause_id = $home_page_06_header_area_donation[$loop->index] ?? '';
                                $donation_cause = \App\Cause::find($donation_cause_id);
                                $content = $all_sub_fields[$key];
                                $subExplode = explode(' ',$content);
                                $lastWord = array_pop($subExplode);
                                $first_word = implode(' ',$subExplode);
                            @endphp

                            <h1 class="title animate-style">{{$first_word ?? ''}}<span>{{$lastWord ?? ''}}</span></h1>

                            @if(!is_null($donation_cause))
                            <div class="progress-wrapper">
                                 <div class="progress-item">
                                    <div class="single-progressbar">
                                        <div class="donation-progress" data-percentage="{{get_percentage($donation_cause->amount,$donation_cause->raised)}}"></div>
                                    </div>
                                </div>
                                <span class="targets"> {{__('Targets:')}} {{ amount_with_currency_symbol($donation_cause->raised)  }} </span>
                            </div>
                            <div class="btn-wrapper padding-top-30">
                                @php
                                    $button_title_url = !empty($all_donate_button_title_url_fields[$loop->index]) ? $all_donate_button_title_url_fields[$loop->index] : route('frontend.donation.in.separate.page',$donation_cause->id);
                                    $button_read_more_url = !empty($all_read_more_button_title_url_fields[$loop->index]) ? $all_read_more_button_title_url_fields[$loop->index] : route('frontend.donations.single',$donation_cause->slug);
                                @endphp
                                <a href="{{route('frontend.donation.in.separate.page',$donation_cause->id)}}" class="boxed-btn btn-color-five btn-rounded">
                                    {{ $all_donate_button_title_fields[$loop->index] ?? '' }}
                                </a>
                                <a href="{{route('frontend.donations.single',$donation_cause->slug)}}" class="boxed-btn btn-rounded btn-outline-white ml-3">
                                    {{$all_read_more_button_title_fields[$loop->index] ?? ''}}
                                </a>
                            </div>
                            @endif
                        </div>

                    </div>

                </div>
                <div class="col-xl-7 col-lg-12 order-1 order-xl-2">
                    <div class="banner-mask-slider">
                        <div class="banner-mask-contents">
                            <div class="banner-mask-image">
                                {!! render_image_markup_by_attachment_id($all_image_fields[$loop->index]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
</div>
@endif


@if(get_static_option('home_page_rise_area_06_section_status'))
<div class="overlay-gradient">
    <section class="rise-area style_02">
        <div class="container">
            <div class="row">
                <div class="rise-flex-contents">
                    <div class="single-donate margin-bottom-30">
                        <h2 class="title"> {!! filter_static_option_value('home_page_06_rise_area_heading_title',$static_field_data) !!} </h2>
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
                        <button type="submit" class="boxed-btn donate-btn donation_redirect_button"> {!! filter_static_option_value('home_page_06_rise_area_button_text',$static_field_data) !!}  </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

  @if(get_static_option('home_page_feature_area_06_section_status'))
    <section class="featured-area padding-top-100 padding-bottom-75">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-sm-11 col-11">
                    <div class="section-title section-title-five desktop-center padding-top-25 margin-bottom-80">
                        @php
                            $title = filter_static_option_value('home_page_06_feature_area_title',$static_field_data);
                            $ex = explode(' ',$title);
                            $first_word = $ex[0];
                             array_shift($ex)
                        @endphp
                        <h2 class="title">{{$first_word}} <span> {{ implode(' ',$ex) }} </span> </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="featured-slider">
                        @foreach($feature_cause as $key=> $data)
                        <div class="single-featured-items">
                            <div class="single-featured style-02">
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
                                <div class="featured-contents">
                                    <h3 class="title">
                                        <a href="{{route('frontend.donations.single',$data->slug)}}">{{$data->title ?? ''}}</a>
                                    </h3>
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
                                        <a href="{{route('frontend.donations.single',$data->slug)}}" class="boxed-btn btn-rounded {{ $classes[$key % count($classes)] }} ">{!! filter_static_option_value('home_page_06_feature_area_donation_button_text',$static_field_data) !!}  </a>
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


    @if(get_static_option('home_page_category_area_06_section_status'))
    <section class="category-area padding-top-25 padding-bottom-35">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-sm-11 col-11">
                    <div class="section-title section-title-five desktop-center padding-top-25 margin-bottom-80">
                        @php
                            $caTtitle = filter_static_option_value( 'home_page_06_category_area_title',$static_field_data);
                            $catEx = explode(' ',$caTtitle);
                            $first_word = $catEx[0];
                            array_shift($catEx);
                        @endphp
                        <h2 class="title"> {{$first_word}} <span> {{ implode(' ',$catEx) }} </span> </h2>
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
                                        <img src="{{asset('assets/frontend/img/category/category-sh.png')}}" alt="">
                                    </div>
                                </div>
                                <div class="category-content color-five">
                                    <h4 class="category-para"> <a href="{{route('frontend.donations.category',['id' => $data->id,'any' => Str::slug($data->title) ?? '' ])}}"> {{$data->title ?? ''}} </a> </h4>
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
</div>

@if(get_static_option('home_page_recent_cause_area_06_section_status'))
<section class="recent-area padding-top-25 padding-bottom-45">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-five desktop-center padding-top-25 margin-bottom-80">
                    @php
                        $recentCausetitle = filter_static_option_value( 'home_page_06_recent_causes_area_title',$static_field_data);
                        $recentCausetitleEx = explode(' ',$recentCausetitle);
                        $first_wordcause = $recentCausetitleEx[0];
                        array_shift($recentCausetitleEx);
                    @endphp
                    <h2 class="title">{{$first_wordcause }}<span> {{ implode(' ',$recentCausetitleEx )  }} </span> </h2>
                </div>
            </div>
        </div>
        <div class="row">
           @foreach($all_recent_donation as $key=> $data)
            <div class="col-lg-4 col-md-6">
                <div class="recent-single style-02 margin-bottom-30">
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
                            <a href="{{route('frontend.donations.single',$data->slug)}}" class="boxed-btn btn-rounded {{$classes[$key % count($classes)]}}"> {!! filter_static_option_value( 'home_page_06_recent_causes_area_button_text',$static_field_data) !!} </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(get_static_option('home_page_success_story_area_06_section_status'))
<section class="success-area-two padding-top-25 padding-bottom-45">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="success-slider">
                    @foreach($all_success_stories as $key=> $data)
                    <div class="single-success-items">
                        <div class="row align-items-center">
                            <div class="col-lg-5">
                                <div class="success-mask2 margin-bottom-30">
                                    {!! render_image_markup_by_attachment_id($data->image) !!}
                                </div>
                            </div>
                            <div class="col-lg-6 offset-lg-1">
                                <div class="success-contents margin-bottom-30">
                                    <div class="section-title section-title-five padding-top-25 margin-bottom-40">
                                        @php
                                            $successtitle = $data->title;
                                            $sucTitleEx = explode(' ',$successtitle);
                                            $first_succTitleWord = $sucTitleEx[0];
                                            array_shift($sucTitleEx);
                                        @endphp
                               
                                        <h2 class="title"> <a href="{{route('frontend.success.story.single',$data->slug)}}">{{$first_succTitleWord}} <span> {{ implode(' ',$sucTitleEx ) }} </span> </a> </h2>
                                    </div>
                                    <p>{{purify_html($data->excerpt) }}</p>
                                    <div class="btn-wrapper">
                                        <a href="{{route('frontend.success.story.single',$data->slug)}}" class="boxed-btn {{$classes[$key % count($classes)]}} btn-rounded"> {!! filter_static_option_value('home_page_06_success_story_area_button_text',$static_field_data) !!} </a>
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

@if(get_static_option('home_page_aboutus_area_06_section_status'))
<section class="about-area padding-bottom-25 padding-top-75">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 margin-bottom-30">
                <div class="about-text-contents">
                    <div class="section-title section-title-five padding-top-25 margin-bottom-40">
                        @php
                            $AboutTitle = filter_static_option_value('home_page_06_about_us_area_title',$static_field_data);
                            $AboutTitleEx = explode(' ', $AboutTitle);
                            $firstAboutTitleWord = $AboutTitleEx[0];
                            array_shift($AboutTitleEx);
                        @endphp
                        <h2 class="title"> {{$firstAboutTitleWord}} <span> {{implode(' ',$AboutTitleEx)}} </span> </h2>
                    </div>
                    <p>{!! purify_html(filter_static_option_value('home_page_06_about_us_area_description',$static_field_data)) !!}</p>

                    <div class="btn-wrapper">
                        <a href="{!! purify_html(filter_static_option_value('home_page_06_about_us_area_button_url',$static_field_data)) !!}" class="boxed-btn btn-rounded btn-color-three"> {!! purify_html(filter_static_option_value('home_page_06_about_us_area_button_text',$static_field_data)) !!} </a>
                    </div>
                </div>
            </div>
        @endif

            @if(get_static_option('home_page_counterup_area_06_section_status'))
            <div class="col-lg-6 margin-bottom-30">
                <div class="row">
                    @foreach($all_counterup as $data)
                    <div class="col-lg-6 col-sm-6 counter-child">
                        <div class="single-counterup-02 style_02">
                            <div class="counter-contents">
                                <div class="icon">
                                    <div class="icon-shapes">
                                        <img src="{{asset('assets/frontend/img/about/about-counter-s.png')}}" alt="">
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
            @endif
        </div>
    </div>
</section>



@if(get_static_option('home_page_events_area_06_section_status'))
<section class="events-area-two padding-top-25 padding-bottom-90">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-sm-11 col-11">
                <div class="section-title section-title-five desktop-center padding-top-25 margin-bottom-80">
                    @php
                        $EventTitle = filter_static_option_value('home_page_06_events_area_title',$static_field_data);
                        $EventTitleEx = explode(' ', $EventTitle);
                        $firstEventTitleWord = $EventTitleEx[0];
                        array_shift($EventTitleEx);
                    @endphp
                    <h2 class="title"> {{ $firstEventTitleWord }} <span> {{ implode(' ',$EventTitleEx ) }} </span> </h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($all_recent_events as $key=> $data)
            <div class="col-lg-6 event-child">
                <div class="single-events style-03 margin-bottom-50">
                    <div class="events-flex-contents">
                        <div class="events-date">
                            @php
                                $imgNumber = $key + 1;
                            @endphp
                            <img src="{{asset('assets/frontend/img/events/e'.$imgNumber.'.png')}}" alt="">
                            <div class="events-boxe">
                                <span class="events-title"> {{ date('d', strtotime($data->date)) }} </span>
                                <p class="event-para"> {{ date('M', strtotime($data->date)) }} </p>
                            </div>
                        </div>
                        <div class="events-content">
                            <h3 class="title"><a href="{{route('frontend.events.single',$data->slug)}}">{{$data->title}}</a></h3>
                            <p class="content-para"> {!! Str::words(purify_html( $data->content),18) !!} </p>
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
</div>

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