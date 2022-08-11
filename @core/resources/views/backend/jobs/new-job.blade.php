@extends('backend.admin-master')
@section('site-title')
    {{__('New Job Post')}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootstrap-tagsinput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/dropzone.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/media-uploader.css')}}">
    <link rel="stylesheet" href="{{asset('assets/backend/css/summernote-bs4.css')}}">
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <!-- basic form start -->
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
               <x-msg.error/>
               <x-msg.success/>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('Add New Job Post')}}
                            <a class="btn btn-info btn-sm pull-right mb-3" href="{{route('admin.jobs.all')}}">{{__('All Jobs ')}}</a>
                        </h4>
                        <form action="{{route('admin.jobs.new')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">

                                    <div class="form-group">
                                        <label for="title">{{__('Title')}}</label>
                                        <input type="text" class="form-control"  id="title" name="title" value="{{old('title')}}" placeholder="{{__('Title')}}">
                                    </div>


                                    <div class="form-group permalink_label">
                                        <label class="text-dark">{{__('Permalink / Slug * : ')}}
                                            <span id="slug_show" class="display-inline"></span>
                                            <span id="slug_edit" class="display-inline">
                                         <button class="btn btn-warning btn-sm ml-1 px-2 py-1 slug_edit_button"> <i class="fas fa-edit"></i> </button>
                                        <input type="text" name="slug" class="form-control blog_slug mt-2" style="display: none">
                                          <button class="btn btn-info btn-sm slug_update_button mt-2 px-2 py-1" style="display: none">{{__('Update')}}</button>
                                    </span>
                                    </label>
                                  </div>


                                    <div class="form-group">
                                        <label for="meta_tags">{{__('Meta Tags')}}</label>
                                        <input type="text" name="meta_tags"  class="form-control" data-role="tagsinput" id="meta_tags">
                                    </div>
                                    <div class="form-group">
                                        <label for="meta_description">{{__('Meta Description')}}</label>
                                        <textarea name="meta_description"  class="form-control" rows="5" id="meta_description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="position">{{__('Job Position')}}</label>
                                        <input type="text" class="form-control"  id="position" name="position" value="{{old('position')}}" placeholder="{{__('Position')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="company_name">{{__('Company Name')}}</label>
                                        <input type="text" class="form-control"  id="company_name" value="{{old('company_name')}}"  name="company_name" placeholder="{{__('Company Name')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="category">{{__('Category')}}</label>
                                        <select name="category_id" class="form-control" id="category">
                                            <option value="">{{__("Select Category")}}</option>
                                            @foreach($all_category as $category)
                                                <option value="{{$category->id}}">{{$category->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="vacancy">{{__('Vacancy')}}</label>
                                        <input type="number" class="form-control"  id="vacancy" value="{{old('vacancy')}}" name="vacancy" placeholder="{{__('Vacancy')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="job_context">{{__('Job Context')}}</label>
                                        <input type="hidden" name="job_context" >
                                        <div class="summernote" ></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="job_responsibility">{{__('Job Responsibility')}}</label>
                                        <input type="hidden" name="job_responsibility" >
                                        <div class="summernote" ></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="education_requirement">{{__('Educational Requirements')}}</label>
                                        <input type="hidden" name="education_requirement">
                                        <div class="summernote"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="experience_requirement">{{__('Experience Requirements')}}</label>
                                        <input type="hidden" name="experience_requirement">
                                        <div class="summernote"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="additional_requirement">{{__('Additional Requirements')}}</label>
                                        <input type="hidden" name="additional_requirement" >
                                        <div class="summernote" ></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="employment_status">{{__('Employment Status')}}</label>
                                        <select name="employment_status" id="employment_status"  class="form-control">
                                            <option value="full_time">{{__('Full-Time')}}</option>
                                            <option value="part_time">{{__('Part-Time')}}</option>
                                            <option value="project_based">{{__('Project Based')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="job_location">{{__('Job Location')}}</label>
                                        <input type="text" class="form-control"  id="job_location" name="job_location" value="{{old('job_location')}}" placeholder="{{__('Job Location')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="other_benefits">{{__('Compensation & Other Benefits')}}</label>
                                        <input type="hidden" name="other_benefits">
                                        <div class="summernote" ></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="salary">{{__('Salary')}}</label>
                                        <input type="text" class="form-control"  id="salary" name="salary" value="{{old('salary')}}" placeholder="{{__('Salary')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="deadline">{{__('Deadline')}}</label>
                                        <input type="date" class="form-control"  id="deadline" name="deadline" placeholder="{{__('Deadline')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="application_fee_status"><strong>{{__('Enable Application Fee')}}</strong></label>
                                        <label class="switch">
                                            <input type="checkbox" name="application_fee_status">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="application_fee">{{__('Application Fee')}}</label>
                                        <input type="number" class="form-control" name="application_fee" value="{{old('application_fee')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="status">{{__('Status')}}</label>
                                        <select name="status" id="status"  class="form-control">
                                            <option value="publish">{{__('Publish')}}</option>
                                            <option value="draft">{{__('Draft')}}</option>
                                        </select>
                                    </div>
                                    <button id="submit" type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New Job')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.partials.media-upload.media-upload-markup')
@endsection
@section('script')
    <script src="{{asset('assets/backend/js/bootstrap-tagsinput.js')}}"></script>
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    <script src="{{asset('assets/backend/js/summernote-bs4.js')}}"></script>
    @include('backend.partials.media-upload.media-js')
    <script>
        <x-btn.submit/>
        (function($){
            "use strict";
            $(document).ready(function () {

                function converToSlug(slug){
                    let finalSlug = slug.replace(/[^a-zA-Z0-9]/g, ' ');
                    finalSlug = slug.replace(/  +/g, ' ');
                    finalSlug = slug.replace(/\s/g, '-').toLowerCase().replace(/[^\w-]+/g, '-');
                    return finalSlug;
                }

                //Permalink Code
                $('.permalink_label').hide();
                $(document).on('keyup', '#title', function (e) {
                    var slug = converToSlug($(this).val());
                    var url = `{{url('/career/')}}/` + slug;
                    $('.permalink_label').show();
                    var data = $('#slug_show').text(url).css('color', 'blue');
                    $('.blog_slug').val(slug);

                });

                //Slug Edit Code
                $(document).on('click', '.slug_edit_button', function (e) {
                    e.preventDefault();
                    $('.blog_slug').show();
                    $(this).hide();
                    $('.slug_update_button').show();
                });

                //Slug Update Code
                $(document).on('click', '.slug_update_button', function (e) {
                    e.preventDefault();
                    $(this).hide();
                    $('.slug_edit_button').show();
                    var update_input = $('.blog_slug').val();
                    var slug = converToSlug(update_input);
                    var url = `{{url('/career/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.blog_slug').hide();
                });



                $('.summernote').summernote({
                    height: 200,   //set editable area's height
                    codemirror: { // codemirror options
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function(contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });

                $(document).on('change','#language',function(e){
                    e.preventDefault();
                    var selectedLang = $(this).val();
                    $.ajax({
                        url: "{{route('admin.jobs.category.by.lang')}}",
                        type: "POST",
                        data: {
                            _token : "{{csrf_token()}}",
                            lang : selectedLang
                        },
                        success:function (data) {
                            $('#category').html('<option value="">Select Category</option>');
                            $.each(data,function(index,value){
                                $('#category').append('<option value="'+value.id+'">'+value.title+'</option>')
                            });
                        }
                    });
                });
            });
        })(jQuery);
    </script>
@endsection
