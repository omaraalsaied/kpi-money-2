@extends('layouts.main') 
@section('title', 'User Transaction')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
    @endpush

    
    <div class="container-fluid">
    	<div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __('Transaction')}}</a>
                                <input type="text" id="id" value="{{$id}}"/>
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
                <div class="card p-3">
                    <div class="card-header"><h3>{{ __('Rewards Transaction')}}</h3></div>
                    <div class="card-body">
                        <table id="user_redeem" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('sr No.')}}</th>
                                    <th>{{ __('User')}}</th>
                                    <th>{{ __('Request To')}}</th>
                                    <th>{{ __('Coin Used')}}</th>
                                    <th>{{ __('Amount')}}</th>
                                    <th>{{ __('Type')}}</th>
                                    <th>{{ __('Request Date')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-header"><h3>{{ __('User Transaction')}}</h3></div>
                    <div class="card-body">
                        <table id="user_transe" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('sr no.')}}</th>
                                    <th>{{ __('Name')}}</th>
                                    <th>{{ __('Amount')}}</th>
                                    <th>{{ __('Type')}}</th>
                                    <th>{{ __('Remained Coin')}}</th>
                                    <th>{{ __('Remark')}}</th>
                                    <th>{{ __('Date')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script')
    <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <!--server side users table script-->
    <script>
        
    // user pending request
        var id= $('#id').val();
        console.log('after redeem id'+id);
            
            var searchable = [];
            var selectable = []; 
            
            var dTable = $('#user_redeem').DataTable({
                    
                order: [],
                lengthMenu: [[5, 10, 20, 30, -1], [5, 10, 20, 30, "All"]],
                processing: true,
                responsive: false,
                serverSide: true,
                processing: true,
                language: {
                  processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
                },
                scroller: {
                    loadingIndicator: false
                },
                pagingType: "full_numbers",
                dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                ajax: {
                    url: '/request/'+id,
                    type: "get"
                },
                columns: [
                    {data:'DT_RowIndex', name: 'DT_RowIndex', "searchable": false},
                    {data:'name', name: 'name', "searchable": false},
                    {data:'mobile_no', name: 'mobile_no', "searchable": true},
                    {data:'amount', name: 'amount', "searchable": false}, // add column name
                    {data:'orginal_amount', name: 'orginal_amount', "searchable": false},
                    {data:'type', name: 'type', "searchable": true},
                    {data:'date', name: 'date', "searchable": false}
        
                ],
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn-sm btn-info',
                        title: 'Users Transaction',
                        header: false,
                        footer: true,
                        exportOptions: {
                            // columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn-sm btn-success',
                        title: 'Pending Request',
                        header: false,
                        footer: true,
                        exportOptions: {
                            // columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn-sm btn-warning',
                        title: 'Pending Request',
                        header: false,
                        footer: true,
                        exportOptions: {
                            // columns: ':visible',
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn-sm btn-primary',
                        title: 'Pending Request',
                        pageSize: 'A2',
                        header: false,
                        footer: true,
                        exportOptions: {
                            // columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn-sm btn-default',
                        title: 'Pending Request',
                        // orientation:'landscape',
                        pageSize: 'A2',
                        header: true,
                        footer: false,
                        orientation: 'landscape',
                        exportOptions: {
                            // columns: ':visible',
                            stripHtml: false
                        }
                    }
                ]
            });
       
     
    //  user transactions
        var searchable = [];
        var selectable = []; 
        var dTable = $('#user_transe').DataTable({
    
            order: [],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            processing: true,
            responsive: false,
            serverSide: true,
            processing: true,
            language: {
              processing: '<i class="ace-icon fa fa-spinner fa-spin orange bigger-500" style="font-size:60px;margin-top:50px;"></i>'
            },
            scroller: {
                loadingIndicator: false
            },
            pagingType: "full_numbers",
            dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
            ajax: {
                url: "{{env('APP_URL')}}" +'transaction/'+id,
                type: "get"
            },
            columns: [
                {data:'DT_RowIndex', name: 'DT_RowIndex', "searchable": false},
                {data:'name', name: 'name', "searchable": false},
                {data:'amount', name: 'amount', "searchable": false},
                {data:'tran_type', name: 'tran_type', "searchable": false}, // add column name
                {data:'remained_balance', name: 'remained_balance', "searchable": false},
                {data:'remarks', name: 'remarks', "searchable": false},
                {data:'inserted_at', name: 'inserted_at', "searchable": false}
    
            ],
            buttons: [
                {
                    extend: 'copy',
                    className: 'btn-sm btn-info',
                    title: 'Users Transaction',
                    header: false,
                    footer: true,
                    exportOptions: {
                        // columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    className: 'btn-sm btn-success',
                    title: 'Users Transaction',
                    header: false,
                    footer: true,
                    exportOptions: {
                        // columns: ':visible'
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn-sm btn-warning',
                    title: 'Users Transaction',
                    header: false,
                    footer: true,
                    exportOptions: {
                        // columns: ':visible',
                    }
                },
                {
                    extend: 'pdf',
                    className: 'btn-sm btn-primary',
                    title: 'Users Transaction',
                    pageSize: 'A2',
                    header: false,
                    footer: true,
                    exportOptions: {
                        // columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    className: 'btn-sm btn-default',
                    title: 'Users Transaction',
                    // orientation:'landscape',
                    pageSize: 'A2',
                    header: true,
                    footer: false,
                    orientation: 'landscape',
                    exportOptions: {
                        // columns: ':visible',
                        stripHtml: false
                    }
                }
            ]
        });
    </script>
   
    @endpush
@endsection
