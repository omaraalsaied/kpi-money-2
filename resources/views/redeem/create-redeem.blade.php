@extends('layouts.main') 
@section('title', 'Redeem Option')
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
                        <i class="ik ik-user-plus bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Add Redeem')}}</h5>
                            <span>{{ __('Create new Redeem Option')}}</span>
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
                        <h3>{{ __('Fill Details')}}</h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="/payment-options/create" enctype= "multipart/form-data">
                        @csrf
                            <div class="form-group row">
                                <label for="exampleInputUsername2" class="col-sm-3 col-form-label">{{ __('Redeem Name')}}</label>
                                <div class="col-sm-9">
                                     <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="" placeholder="Name" required>
                                <div class="help-block with-errors"></div>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="exampleInputEmail2" class="col-sm-3 col-form-label">{{ __('Coin Required for Redeem')}}</label>
                                <div class="col-sm-9">
                                    <input id="coin" type="number" class="form-control @error('coin') is-invalid @enderror" name="coin" value="" placeholder="eg 1000" required>
                                        <div class="help-block with-errors" ></div>
                                        @error('coin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                     </div>
                             </div>

                            <div class="form-group row">
                                <label for="exampleInputMobile" class="col-sm-3 col-form-label">{{ __('Set Currency: ( $1 USD )')}}</label>
                                <div class="col-sm-9">
                                    <input id="currency" type="text" class="form-control @error('currency') is-invalid @enderror" name="currency" placeholder="Set Currency eg 1USD" required>
                                        <div class="help-block with-errors"></div>
                                        @error('package')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                             </div>

                             <div class="form-group row">
                                <label for="exampleInputMobile" class="col-sm-3 col-form-label">{{ __('Details:')}}</label>
                                <div class="col-sm-9">
                                    <input id="detail" type="text" class="form-control @error('detail') is-invalid @enderror" name="detail" placeholder="eg: 1000 coin = 1 USD" required>
                                        <div class="help-block with-errors"></div>
                                        @error('package')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                             </div>


                            <div class="form-group row">
                                <label for="exampleInputConfirmPassword2" class="col-sm-3 col-form-label">{{ __('Redeem Icon')}}</label>
                                <div class="col-sm-9">
                                    <input id="icon" type="file" class="form-control @error('icon') is-invalid @enderror" name="icon" placeholder="Select App ICON" required>
                                        <div class="help-block with-errors"></div>

                                        @error('icon')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                </div>
                            </div>
                           
                            <button type="submit" class="btn btn-primary mr-2">{{ __('Submit')}}</button>
                            <button class="btn btn-light">{{ __('Cancel')}}</button>
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
