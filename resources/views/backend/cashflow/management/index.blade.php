@extends('backend.layouts.base')))
@section('action-content')
<!-- Main content -->
<section class="content">
  <div class="box">
    <div class="box-header">
      <div class="row">
        <div class="col-sm-12">
          @if(Session::has('message'))
            <div class="alert alert-success text-center" style="margin-bottom: 10 px; padding: 6px" role="alert">
                {{Session::get('message')}}
            </div>
          @endif
        </div>

        <div class="col-md-4">
          <a class="btn btn-danger" href="{{ route('cash.flow.transaction.withdrawal')}}">
            <span class="glyphicon glyphicon-export" aria-hidden="true"></span>
            @lang('lang.withdrawal')
          </a>
          |
          <a class="btn btn-success" href="{{ route('cash.flow.transaction.deposit') }}">
            <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
            @lang('lang.deposit')
          </a>
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">
          <a class="btn btn-primary" href="{{ route('cash.flow.daily.report') }}">
            @lang('lang.daily.reports')
          </a>
        </div>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
      </div>
      <form method="get" action="{{ route('cash.flow.home') }}">
        {{ csrf_field() }}
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">{{isset($title) ? $title : 'Recherche'}}</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <div class="col-md-12">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="date" autocomplete="off"  value="{{$searchingVals['dateStart']}}" name="dateStart" class="form-control pull-right" id="dateStart" required>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <div class="col-md-12">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="date" autocomplete="off"  value="{{$searchingVals['dateEnd']}}" name="dateEnd" class="form-control pull-right" id="dateEnd" required>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-2">
                <div class="form-group">
                  <label  class="col-sm-12 control-label" align="right">Client</label>
                  <div class="col-sm-6">
                    <select name="idClient" id="idClient" required>
                      <option value="">@lang('lang.select.a.customer')</option>
                      @foreach ($clients as $client)
                        @if($searchingVals['idClient'] == $client->id_client)
                        <option value="{{$client->id_client}}" selected>{{$client->firstname}}</option>
                        @else
                          <option value="{{$client->id_client}}">{{$client->firstname}}</option>
                        @endif
                      @endforeach
                      <option value="Transfert">@lang('lang.transfer')</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label  class="col-sm-6 control-label"  align="right" >@lang('lang.operation')</label>
                  <div class="col-sm-6">
                    <select name="type" id="type">
                      <option value="">@lang('lang.all.operations')</option>
                      @if($searchingVals['type'] == 'depot')
                        <option value="depot" selected>@lang('lang.deposit')</option>
                      @else
                        <option value="depot">@lang('lang.deposit')</option>
                      @endif
                      @if($searchingVals['type'] == 'retrait')
                        <option value="retrait" selected>@lang('lang.withdrawal')</option>
                      @else
                        <option value="retrait">@lang('lang.withdrawal')</option>
                      @endif
                    </select>

                  </div>
                </div>
              </div>
              <div class="col-md-2">
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit"  class="btn btn-warning" name="search" value="submitSearch">
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
              @lang('lang.search')
            </button>
            @if(Auth::user()->user_type =='admin' || Auth::user()->user_type =='cash' )
              <button type="submit" class="btn btn-primary" name="search" value="excel">
                <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                  @lang('lang.export.to.excel')
              </button>
            @endif
          </div>
        </div>
      </form>

      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
          @if($caches->total())
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="1%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.type')</th>
                  <th width="2%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.operator')</th>
                  <th width="10%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">@lang('lang.from.for')</th>
                  <th width="15%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">@lang('lang.note')</th>
                  <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">@lang('lang.eur')</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending"> @lang('lang.mru')</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">@lang('lang.balance.before') </th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">@lang('lang.balance.after')</th>
                  <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">@lang('lang.date')</th>
                  <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">@lang('lang.invoices')</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($caches as $indexKey => $cache)
                  @if($indexKey % 2 )
                    <tr role="row" class="odd" style="background : #ddd">
                  @else
                    <tr role="row" class="odd">
                  @endif
                  @if($cache->operation== 'depot' )
                    <td class="sorting_1" style="background: #00a65a; color: white">Dépot</td>
                  @else
                    <td class="sorting_1" style="background: #dd4b39; color: white">Rétrait </td>
                  @endif
                  <td class="sorting_1">{{$cache->expediteur }} </td>
                  <td class="sorting_1"> {{$cache->nom_benef}}</td>
                  <td class="hidden-xs">{{ $cache->phone_benef  }}</td>
                  <td class="hidden-xs"> 
                    @if($cache->montant_euro!=0)
                      {{ $cache->montant_euro  }} €
                    @endif</td>
                  @if($cache->operation== 'depot' )
                    <td class="hidden-xs" style="color: green;"> + {{  number_format(floor($cache->montant ))  }} @lang('lang.mru')</td>
                  @else
                    <td class="hidden-xs" style="color: red;"> - {{ number_format($cache->montant) }} @lang('lang.mru')</td>
                  @endif
                  <td class="hidden-xs">{{ number_format(round($cache->solde_avant, 1))   }} @lang('lang.mru')</td>
                  <td class="hidden-xs">
                    {{ number_format(round($cache->solde_apres, 1)) }} @lang('lang.mru')
                  </td>
                  <td class="hidden-xs">
                    {{ $cache->created_at }}+9
                  </td>
                  <td class="hidden-xs">
                    @if ($cache->invoices != "")
                      @foreach(explode(',', $cache->invoices) as $invoice)
                        <a href="{{ route('cash.flow.invoices', ['path' => $invoice]) }}" target="_blank">{{$invoice}}</a>
                      @endforeach
                    @endif
                      <br/>
                      <a href="{{route('cash.flow.add.files', ['idCash'=>$cache->id])}}">@lang('lang.add.invoice')</a>
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
            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($caches)}} of {{count($caches)}} entries</div>
          </div>
          <div class="col-sm-7">
            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
              {{ $caches->appends(request()->input())->links() }}
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