@extends('backend.layouts.base')
@section('action-content')
<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <a href=""><span class="info-box-icon bg-yellow"><i class="fa fa-calculator" aria-hidden="true"></i></span></a>


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

      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000 ; color :#fff">

                  <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Nom </th>
                  <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Email</th>
                  <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">ID_Commande</th>
                  <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Transaction</th>
                  <th width="6%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Montant Brute</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">ATLPAY frais</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Montant Net </th>

                  <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Date</th>


                </tr>
              </thead>
              <tbody>



                @foreach ($atlpay[0] as $indexKey => $cache)
                @if($indexKey % 2 )
                <tr role="row" class="odd" style="background : #ddd">
                  @else
                <tr role="row" class="odd">
                  @endif
                  <td class="sorting_1">{{$cache->address_name }} </td>
                  <td class="sorting_1">{{$cache->payer_email }} </td>
                  <td class="sorting_1">{{$cache->id_commande }} </td>
                  <td class="sorting_1">{{$cache->payment_id }} </td>
                  <td class="sorting_1"> {{$cache->payment_amount}} € </td>
                  <td class="hidden-xs" style="color: red"> - {{ ($cache->payment_amount)*0.015+0.4 }} €</td>
                  <td class="hidden-xs" style="color: #0d730d ">{{ $cache->payment_amount - (($cache->payment_amount)*0.015+0.4)  }} € </td>
                  <td class="hidden-xs">

                    {{$cache->updated_at }}

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