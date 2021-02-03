@extends('backend.layouts.base')
@section('action-content')
<!-- Main content -->

<section class="content">

 

  <div class="box">

    <!-- /.box-header -->
    <div class="box-body">

    <div class="row">
    
    <div class="col-md-9 ">

    <form method="GET" action="{{ route('cash.out.serach') }}">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"></h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
          <div class="form-group">
            <div class="col-sm-12">
              <input type="text" class="form-control" value="{{$searchingVals['expediteur']}}" name="expediteur" id="inputexpediteur" placeholder="Expediteur (Email | nom | Téléphone )">
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            
            <div class="col-sm-12">
              <input type="text" class="form-control" value="{{$searchingVals['beneficiaire']}}" name="beneficiaire" id="inputbeneficiaire" placeholder="Beneficiaire (Email | nom | Téléphone )">
            </div>
          </div>
        </div>
      </div>  <br>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">          
              <div class="col-md-12">
                  <div class="input-group">
                      <input type="date"  autocomplete="off" value="{{$searchingVals['from']}}" name="from"  required>
                  </div>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">          
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="date"  autocomplete="off" value="{{$searchingVals['to']}}" name="to"  required>
                    </div>
                </div>
              </div>
          </div>
        </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="submit" class="btn btn-warning" name="search" value="recherche">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
            Search
          </button>
            <button type="submit" class="btn btn-primary" name="search" value="excel">
            <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
            To Excel
          </button>
            </div>
      </div>
    </form>

    </div>

    <div class="col-md-3 ">
      <div class="info-box">
    <span class="info-box-icon bg-green"><i class="fa fa-money" aria-hidden="true"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">{{ Auth::user()->email}}</br> Solde disponible </span>

          <span class="info-box-number" style="font-size: x-large;"> {{ number_format(floor($soldedispo)) }} <small>MRU</small> </span>
        </div>
        <!-- /.info-box-content -->
      </div>
     
    </div>
  </div>

     
      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">@lang('lang.ordered') </th>
                  <th width="16%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.kyc-shipper')</th>
                  <th width="10%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.email')</th>
                  <th width="18%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">@lang('lang.beneficiary')</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending"> @lang('lang.euros')| @lang('lang.dollar')</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending"> @lang('lang.mru')</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">@lang('lang.costs.gaza')</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">@lang('lang.date') </th>

                  <th width="6%" class="sorting_1" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">@lang('lang.status')</th>

                </tr>
              </thead>
              <tbody>
                @foreach ($cashout as $indexKey =>$cache)


                @if($indexKey % 2 )
                <tr role="row" class="odd" style="background : #ddd">
                  @else
                <tr role="row" class="odd">
                  @endif


                  <td class="hidden-xs">{{ $cache->id_commande }}</td>
                  <td class="sorting_1">
                    @if($cache->kyc ==0)
                    <i class='fa fa-circle text-yellow'></i>

                    @elseif($cache->kyc ==1)
                    <i class='fa fa-circle text-success'></i>
                    @elseif($cache->kyc ==3)
                    <i class='fa fa-circle text-infos'></i>
                    @else

                    <i class='fa fa-circle text-danger'></i>
                    @endif
                    - {{$cache->nom_exp}} ({{$cache->phone_exp}} ) </td>

                  <td class="sorting_1"> {{$cache->mail_exp}}</td>
                  <td class="sorting_1"> {{$cache->nom_benef}} ( {{$cache->phone_benef}}-{{$cache->adress_benef}} ) </td>
                  <td class="hidden-xs">{{ $cache->payment_amount  }} {{ $cache->payment_currency  }}</td>
                  <td class="hidden-xs">{{ strrev(wordwrap(strrev($cache->somme_mru), 3, ' ', true))   }} MRU</td>
                  <td class="hidden-xs"> {{ $cache->frais_gaza  }}</td>
                  <td class="hidden-xs">{{ $cache->payment_date  }}</td>
                  <td class="sorting_1">
                    @if($cache->tracker_status =='retire')
                    <i class='fa fa-circle text-success'></i> @lang('lang.delivered')
                    @endif

                    @if($cache->tracker_status =='attente')
                    <a href=" {{ route('cash.out.edit', ['id' => $cache->id_commande]) }}" class="btn btn-danger">
                      En attente
                    </a>
                    @endif
                    @if($cache->tracker_status =='transfert')
                    <a href=" {{ route('cash.out.edit', ['id' => $cache->id_commande]) }}"class="{{$cache->gaza_confirm==0 ? 'btn btn-warning': 'btn btn-success' }}">
                      Transfert Gaza
                    </a>
                    @endif

                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                 <tr role="row" style="background: #000; color :#fff">
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">@lang('lang.ordered') </th>
                  <th width="16%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.kyc-shipper')</th>
                  <th width="10%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.email')</th>
                  <th width="18%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">@lang('lang.beneficiary')</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending"> @lang('lang.euros')| @lang('lang.dollar')</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending"> @lang('lang.mru')</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">@lang('lang.costs.gaza')</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">@lang('lang.date') </th>

                  <th width="6%" class="sorting_1" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">@lang('lang.status')</th>

                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-5">
            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($cashout)}} of {{count($cashout)}} entries</div>
          </div>
          <div class="col-sm-7">
            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
              {{ $cashout->appends(request()->input())->links() }}
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