@extends('layouts.main') 
@section('title', 'General Setting')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
    @endpush

    
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-edit bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('General Setting')}}</h5>
                            <span>{{ __('')}}</span>
                        </div>
                        </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{url('dashboard')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <!-- <a href="#">{{ __('Add User')}}</a> -->
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- start message area-->
            @include('include.message')
            <!-- end message area-->
            <div class="col-md-12">
                <div class="card ">
                    <div class="card-header">
                        <h3>{{ __('General Setting')}}</h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="/setting/update" enctype= "multipart/form-data">
                        @csrf
                            <input type=hidden name="oldicon" value="{{$data[0]->app_icon}}"/>
                            <input type=hidden name="type" value="general"/>

                            <div class="form-group row">
                                <label for="exampleInputUsername2" class="col-sm-3 col-form-label">{{ __('App Name')}}</label>
                                <div class="col-sm-9">
                                     <input id="name" type="text" class="form-control " name="app_name" value="{{$data[0]->app_name}}" placeholder="App Name" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">{{ __('App Version')}}</label>
                                <div class="col-sm-9">
                                    <input id="version" type="text" class="form-control" name="version" value="{{$data[0]->app_version}}" placeholder="App Version" required>
                                     </div>
                             </div>

                            <div class="form-group row">
                                <label for="exampleInputMobile" class="col-sm-3 col-form-label">{{ __('Author')}}</label>
                                <div class="col-sm-9">
                                    <input id="author" type="text" class="form-control " value="{{$data[0]->app_author}}"name="author" placeholder="Author" required>
                                    </div>
                             </div>

                            <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-3 col-form-label">{{ __('Email')}}</label>
                                <div class="col-sm-9">
                                    <input id="email" type="text" class="form-control" name="email" value="{{$data[0]->app_email}}" placeholder="Email" required>
                                    </div>
                            </div>

                            <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-3 col-form-label">{{ __('Website')}}</label>
                                <div class="col-sm-9">
                                    <input id="website" type="text" class="form-control" value="{{$data[0]->app_website}}" name="website" placeholder="https//example.com" required>
                                    </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-3 col-form-label">{{ __('Privacy Policy URL')}}</label>
                                <div class="col-sm-9">
                                    <input id="website" type="text" class="form-control" value="{{$data[0]->privacy_policy}}" name="privacy_policy" placeholder="https//example.com" >
                                    </div>
                            </div>

                            <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-3 col-form-label">{{ __('Facebook Page')}}</label>
                                <div class="col-sm-9">
                                    <input id="website" type="text" class="form-control " value="{{$data[0]->fb}}" name="fb" placeholder="https//example.com" required>
                                    </div>
                            </div>

                            <div class="form-group row">
                                <label for="exampleInputPassword2" class="col-sm-3 col-form-label">{{ __('Telegram Link')}}</label>
                                <div class="col-sm-9">
                                    <input id="website" type="text" class="form-control" value="{{$data[0]->telegram}}" name="telegram" placeholder="https//example.com" required>
                                    </div>
                            </div>

                            <div class="form-group row">
                                <label for="exampleInputConfirmPassword2" class="col-sm-3 col-form-label">{{ __('App ICON')}}</label>
                                <div class="col-sm-9">
                                    <input id="icon" type="file" class="form-control" name="icon" placeholder="Select App ICON">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="exampleInputConfirmPassword2" class="col-sm-3 col-form-label">{{ __('App Share Message')}}</label>
                                <div class="col-sm-9">
                                <textarea class="ckeditor form-control" name="share_msg" required>{{$data[0]->share_msg}}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="exampleInputConfirmPassword2" class="col-sm-3 col-form-label">{{ __('About Us')}}</label>
                                <div class="col-sm-9">
                                <textarea class="ckeditor form-control" name="detail" required>{{$data[0]->app_description}}</textarea>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary mr-2 float-right">{{ __('Update')}}</button>
                        </form>
                    </div>
                  </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script') 
       <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
        <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
         <!--get role wise permissiom ajax script-->
    @endpush
@endsection
