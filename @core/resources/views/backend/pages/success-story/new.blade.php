@extends('backend.admin-master')
@section('style')
    <x-summernote.css/>
    <link rel="stylesheet" href="{{asset('assets/backend/css/bootstrap-tagsinput.css')}}">
    <x-media.css/>
@endsection
@section('site-title')
    {{__('New Success Story ')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <!-- basic form start -->
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-msg.success/>
                <x-msg.error/>
            </div>
            <div class="col-lg-12 mt-t">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrapp">
                            <h4 class="header-title">{{__('Add New Success Story ')}}  </h4>
                            <div class="header-title">
                                <a href="{{ route('admin.success.story') }}"
                                   class="btn btn-primary mt-4 pr-4 pl-4">{{__('All Success Story')}}</a>
                            </div>
                        </div>
                        <form action="{{route('admin.success.story.new')}}" method="post" enctype="multipart/form-data">@csrf

                            <div class="form-group">
                                <label for="title">{{__('Title')}}</label>
                                <input type="text" class="form-control" name="title" placeholder="{{__('Title')}}" id="title">
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
                                <label>{{__('Content')}}</label>
                                <input type="hidden" name="story_content">
                                <div class="summernote"></div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="meta_tags">{{__('Meta Tags')}}</label>
                                    <input type="text" class="form-control" name="meta_tags"
                                           data-role="tagsinput">
                                </div>
                            </div>




                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="title">{{__('Excerpt')}}</label>
                                    <textarea name="excerpt" id="excerpt" class="form-control max-height-150" cols="30" rows="10"></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="meta_title">{{__('Meta Title')}}</label>
                                    <input type="text" class="form-control" name="meta_title">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="og_meta_title">{{__('Og Meta Title')}}</label>
                                    <input type="text" class="form-control" name="og_meta_title">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="meta_description">{{__('Meta Description')}}</label>
                                    <textarea type="text" class="form-control" name="meta_description"
                                              rows="5" cols="10"> </textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="og_meta_description">{{__('Og Meta Description')}}</label>
                                    <textarea type="text" class="form-control"
                                              name="og_meta_description" rows="5"
                                              cols="10"> </textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="image">{{__('Success Story Image')}}</label>
                                    <div class="media-upload-btn-wrapper">
                                        <div class="img-wrap"></div>
                                        <input type="hidden" name="image">
                                        <button type="button" class="btn btn-info media_upload_form_btn"
                                                data-btntitle="{{__('Select Image')}}"
                                                data-modaltitle="{{__('Upload Image')}}" data-toggle="modal"
                                                data-target="#media_upload_modal">
                                            {{__('Upload Image')}}
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="og_meta_image">{{__('OG Meta Image')}}</label>
                                    <div class="media-upload-btn-wrapper">
                                        <div class="img-wrap"></div>
                                        <input type="hidden" name="og_meta_image">
                                        <button type="button" class="btn btn-info media_upload_form_btn"
                                                data-btntitle="{{__('Select Image')}}"
                                                data-modaltitle="{{__('Upload Image')}}" data-toggle="modal"
                                                data-target="#media_upload_modal">
                                            {{__('Upload Image')}}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div id="category_list" class="form-group col-md-6">
                                    <label for="category">{{__('Category')}}</label>
                                    <select name="category" class="form-control">
                                        <option value="">{{__("Select Category")}}</option>
                                        @foreach($all_category as $category)
                                            <option value="{{$category->id}}">{{purify_html($category->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="status">{{__('Status')}}</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="draft">{{__("Draft")}}</option>
                                        <option value="publish">{{__("Publish")}}</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" id="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Add New Story')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-media.markup/>
@endsection
@section('script')
    <script src="{{asset('assets/backend/js/bootstrap-tagsinput.js')}}"></script>
    <x-summernote.js/>
    <x-media.js/>
    <script>
        (function ($) {
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
                    var url = `{{url('/success-story/')}}/` + slug;
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
                    var url = `{{url('/success-story/')}}/` + slug;
                    $('#slug_show').text(url);
                    $('.blog_slug').hide();
                });
                <x-btn.submit/>
            });
        })(jQuery)
    </script>
@endsection
