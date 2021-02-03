@extends('backend.layouts.base')
@section('action-content')
    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-university" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">@lang('agency.number')</span>
                        <span class="info-box-number" style="font-size: x-large;"> {{ Session::get('solde')['nbr_compte']}} <small> Agences</small></span>
                        <span class="info-box-text"> </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><h3> MRU </h3></span>
                    <div class="info-box-content">
                        <span class="info-box-text">@lang('agency.global.balance.agency')</span>
                        <span class="info-box-number" style="font-size: x-large; "> {{$balance}}    <small>MRU</small></span>
                        <span class="info-box-text"></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-money" aria-hidden="true"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">@lang('agency.global.balance')</span>
                        <span class="info-box-number" style="font-size: x-large;"> {{$availableBalance}} <small>MRU</small> </span>
                        <span class="info-box-text"> </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <div class="box">
            <div class="box-header">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4"></div>
                </div>
                <form method="GET" action="{{route('agencies.search')}}">
                    {{ csrf_field() }}
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">

                                                <div class="col-sm-12">
                                                    <input value="{{$company}}" type="text" class="form-control" name="company" id="inputsociete" placeholder="societe">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">

                                                <div class="col-sm-12">
                                                    <input value="{{$email}}" type="text" class="form-control" name="email" id="inputemail" placeholder="Email">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">

                                                <div class="col-sm-12">
                                                    <input value="{{$phone}}" type="text" class="form-control" name="phone" id="inputtelephone" placeholder="Telephone">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-warning" name="search" value="recherche">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        @lang('lang.search')
                                    </button>

                                    @if(Auth::user()->user_type=='admin')
                                        |
                                        <a  class="btn btn-primary" href="{{route('agencies.export.agency')}}">
                                            <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                                            @lang('lang.in.excel')
                                        </a>
                                    @endif
                                </div>
                                <div class="col-md-2">

                                    <a class="btn btn-success" href="{{ route('agencies.transaction.deposit')}}">
                                        <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
                                        @lang('lang.deposit')
                                    </a>
                                    |
                                    <a class="btn btn-danger" href="{{ route('agencies.transaction.withdrawal')}}">
                                        <span class="glyphicon glyphicon-export" aria-hidden="true"></span>
                                        @lang('lang.withdrawal')
                                    </a>
                                </div>

                                <div class="col-md-1">
                                    <a class="btn btn-primary" href="{{ route('agency.add') }}">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                        @lang('lang.account')
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->

                    </div>
                </form>
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-12">
                            @if($clients->total())
                                <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                    <thead>
                                        <tr role="row" style="background: #000; color :#fff">
                                            <th width="4%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('agency.name')</th>
                                            <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.name') @lang('lang.first.name')</th>
                                            <th width="8%" class="" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">@lang('lang.email')</th>
                                            <th width="8%" class=" hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">@lang('lang.phone')</th>
                                            <th width="6%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">@lang('lang.city')</th>
                                            <th width="8%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">@lang('lang.district')</th>
                                            <th width="6%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">@lang('lang.balance.mru')</th>
                                            <th width="10%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">@lang('lang.operations')</th>
                                            <th width="10%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">@lang('lang.historical')</th>
                                            <th width="2%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">@lang('lang.edit')</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($clients as $indexKey => $client)
                                        @if($indexKey % 2 )
                                        <tr role="row" class="odd" style="background : #ddd">
                                        @else
                                        <tr role="row" class="odd">
                                        @endif
                                            <td class="sorting_1">{{$client->firstname }} </td>
                                            <td class="sorting_1"> {{$client->name}} {{$client->lastname}}</td>
                                            <td class="hidden-xs">{{ $client->email  }}</td>
                                            <td class="hidden-xs">{{ $client->phone  }}</td>
                                            <td class="hidden-xs">{{ $client->ville  }}</td>
                                            <td class="hidden-xs">{{ $client->quartier  }}</td>
                                            <td class="hidden-xs"> {{ $client->solde_mru }} </td>
                                            <td class="hidden-xs">
                                                <a href=" {{ route('agencies.transaction.credit', ['id' => $client->id_client]) }}" class="btn btn-success">
                                                    @lang('agency.credit')
                                                </a> |
                                                <a href=" {{ route('agencies.transaction.debit', ['id' => $client->id_client]) }}" class="btn btn-warning">
                                                    @lang('agency.debite')
                                                </a>
                                            </td>
                                            <td class="hidden-xs">
                                                <a href=" {{ route('agencies.transaction.story', ['id' => $client->id_client]) }}" class="btn btn-info">
                                                    @lang('agency.balance')
                                                </a> |
                                                <a href=" {{ route('agencies.transaction.operation.story', ['id' => $client->id_client]) }}" class="btn btn-primary">
                                                    @lang('agency.operation')
                                                </a>
                                            </td>

                                            <td class="hidden-xs">
                                                <a href=" {{ route('agency.edit', ['id' => $client->id_client]) }}" class="btn btn-danger">
                                                @lang('lang.edit')
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h3 align='center' style="color: #dd4b39;"> @lang('lang.no.result')</h3>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($clients)}} of {{count($clients)}} entries</div>
                        </div>
                        <div class="col-sm-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                {{ $clients->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </section>
    <!-- /.content -->
    </div>
@endsection