@extends('cache-mgmt.base')
@section('action-content')
<!-- Main content -->

@if(Auth::user()->user_type=='admin' || Auth::user()->user_type=='gaza' )




<section class="content">

<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <a href="{{ route('abonnes-management') }}"><span class="info-box-icon bg-blue"><i class="fa fa-users" aria-hidden="true"></i></span></a>


      <div class="info-box-content">
        <span class="info-box-text">Abonnes total | Ce mois ci</span>
        <span class="info-box-number" style="font-size: x-large;"> <small> ce mois</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>


  <!-- fix for small devices only -->
  <div class="clearfix visible-sm-block"></div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <a href="{{ url('virement-management') }}"> <span class="info-box-icon bg-yellow"><i class="fa fa-share" aria-hidden="true"></i></span></a>

      <div class="info-box-content">
        <span class="info-box-text">Les virements en attente </span>
        <span class="info-box-number" style="font-size: x-large;">

          <small>€</small> 878787 <small>$</small>

        </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <a href="{{ route('cashout-management') }}"><span class="info-box-icon bg-red"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></span></a>


      <div class="info-box-content">
        <span class="info-box-text">Cash OUT EN ATTENTE</span>
        <span class="info-box-number" style="font-size: x-large;">888 <small> MRU</small></span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <a href="{{ url('cache-management') }}"><span class="info-box-icon bg-green"><i class="fa fa-money" aria-hidden="true"></i></span></a>

      <div class="info-box-content">
        <span class="info-box-text">Cash Disponible </span>

        <span class="info-box-number" style="font-size: x-large;"> 222 <small>MRU</small> </span>
      </div>
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
</div>


  <div class="box">

    <!-- /.box-header -->
    <div class="box-body">

      <div class="row">

        <div class="col-sm-12">@if(Session::has('message'))
          <div class="alert alert-success text-center" style="margin-bottom: 10 px; padding: 6px" role="alert">
            {{Session::get('message')}}
          </div>
          @endif
        </div>





      </div>


      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info" style="text-align: right;">
              <thead style="text-align: right;">
                <tr role="row" style="background: #000; color :#fff">


                  <th width="6%" class="sorting_1" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">التحويل</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">بتاريخ </th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">مجموع</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending"> رسوم غزة</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending"> مبلغ </th>
                  <th width="10%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">وكالة غزة</th>
                  <th width="10%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">هاتف</th>
                  <th width="16%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">المستفيد</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">رقم الطلب</th>

                </tr>
              </thead>
              <tbody>
                @foreach ($cashout as $indexKey =>$cache)


                @if($indexKey % 2 )
                <tr role="row" class="odd" style="background : #ddd">
                  @else
                <tr role="row" class="odd">
                  @endif


                  <td class="hidden-xs">
                    @if($cache->gaza_confirm =='0')
                    <a href=" {{ route('cashout-management.gazaconfirmation', ['id' => $cache->id_commande]) }}" class="btn btn-success">
                      تم التحويل
                    </a>
                    @else


                    <i class="fa fa-check-circle" style="font-size: 30px;"></i>

                    @endif</td>
                  <td class="sorting_1">{{ $cache->payment_date  }}</td>

                  <td class="sorting_1"> (<small>أوقيه جديده</small>) {{ $cache->somme_mru + $cache->frais_gaza}} </td>
                  <td class="sorting_1"> (<small>أوقيه جديده</small>) {{ $cache->frais_gaza  }} </td>
                  <td class="hidden-xs"> (<small>أوقيه جديده</small>) {{ $cache->somme_mru  }} </td>
                  <td class="hidden-xs">{{ $cache->agence_gaza  }}</td>
                  <td class="hidden-xs"> {{ $cache->phone_benef  }}</td>
                  <td class="hidden-xs">{{ $cache->nom_benef  }}</td>
                  <td class="sorting_1">
                    {{ $cache->id_commande  }}
                  </td>



                </tr>
                @endforeach
              </tbody>

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
@endif
@endsection