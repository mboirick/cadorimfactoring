@extends('backend.layouts.base')
@section('action-content')
<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <a href="">
          <span class="info-box-icon bg-yellow">
            <i class="fa fa-calculator" aria-hidden="true"></i>
          </span>
        </a>
        <div class="info-box-content">
          <span class="info-box-text">Nombre de transactions</span>
          <span class="info-box-number" style="font-size: x-large;">{{$atlpay[1]}}<small></small></span>
          <span class="info-box-text">From: {{ $searchingVals['from']}} - To: {{ $searchingVals['to']}} </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <a href="#"> <span class="info-box-icon bg-blue"><i class="fa fa-money" aria-hidden="true"></i></span></a>
        <div class="info-box-content">
          <span class="info-box-text">Solde brute </span>
          <span class="info-box-number" style="font-size: x-large;">{{ strrev(wordwrap(strrev($atlpay[2]), 3, ' ', true)) }} € <small></small></span>
          <span class="info-box-text">From: {{ $searchingVals['from']}} - To: {{ $searchingVals['to']}} </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <a href=""><span class="info-box-icon bg-red"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></span></a>
        <div class="info-box-content">
          <span class="info-box-text">ATL Frais</span>
          <span class="info-box-number" style="font-size: x-large; color: #dd4b39"> - {{ strrev(wordwrap(strrev($atlpay[3]), 3, ' ', true)) }} € <small>
            </small></span>
          <span class="info-box-text">From: {{ $searchingVals['from']}} - To: {{ $searchingVals['to']}} </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <a href=""><span class="info-box-icon bg-green"><i class="fa fa-money" aria-hidden="true"></i></span></a>
        <div class="info-box-content">
          <span class="info-box-text"> Solde Net </span>
          <span class="info-box-number" style="font-size: x-large;">{{ strrev(wordwrap(strrev($atlpay[4]), 3, ' ', true)) }} €<small></small> </span>
          <span class="info-box-text">From: {{ $searchingVals['from']}} - To: {{ $searchingVals['to']}} </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
  </div>
  <div class="box" style="border-top: #f39c12 solid 6px">
    <div class="box-header">
      <div class="row">
        <div class="col-sm-8">
          <h3 class="box-title">ATLPAY</h3>
        </div>
        <div class="col-sm-4">
          <a href="{{route('atlpay.home')}}" class="btn btn-info">
            <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> Actualiser
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
      <form method="GET" action="{{ route('atlpay.home') }}">
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
              <div class="col-md-3">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group date">
                            <input type="date" autocomplete="off"  value="{{$searchingVals['from']}}" name="from" class="form-control pull-right" id="from" placeholder="From" required>
                        </div>
                    </div>
                  </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group date">
                            <input type="date" autocomplete="off"  value="{{$searchingVals['to']}}" name="to" class="form-control pull-right" id="to" placeholder="From" required>
                        </div>
                    </div>
                  </div>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <button type="submit"  class="btn btn-warning" name="search" value="recherche">
                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                Search
              </button>
            @if(Auth::user()->user_type =='admin' || Auth::user()->user_type =='cash' )
              <button type="submit" class="btn btn-primary" name="search" value="excel">
                <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                To Excel
              </button>
              @endif
            </div>
          </div>
      </form>
      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #dd4b39 ; color :#fff">
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Date</th>
                  <th width="4%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Transaction</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Moyenne €</th>
                  <th width="8%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Montant Brute</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">ATLPAY frais</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Montant Net </th>

                  <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Action</th>


                </tr>
              </thead>
              <tbody>
                @foreach ($atlpay[0] as $indexKey => $cache)
                @if($indexKey % 2 )
                <tr role="row" class="odd" style="background : #ddd">
                  @else
                <tr role="row" class="odd">
                  @endif
                  <td class="sorting_1">{{ $cache->date }} </td>
                  <td class="sorting_1">{{$cache->nbr_transaction }} </td>
                  <td class="hidden-xs">{{ round($cache->somme_brut-$cache->nbr_transaction)}} € </td>
                  <td class="sorting_1"> {{$cache->somme_brut}} € </td>
                  <td class="hidden-xs" style="color: red"> - {{$cache->frais_atl}} €</td>
                  <td class="hidden-xs">{{$cache->somme_net}} € </td>

                  <td class="hidden-xs">
                    <a href="{{ route('atlpay.detail', ['date'=> $cache->date])}} " class="btn btn-primary">
                      Details
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>

              </tfoot>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-5">
            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($atlpay[0])}} of {{count($atlpay[0])}} entries</div>
          </div>
          <div class="col-sm-7">
            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
              {{ $atlpay[0]->links() }}
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