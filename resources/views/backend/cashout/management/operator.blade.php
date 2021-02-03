@extends('backend.layouts.base')
@section('action-content')
<!-- Main content -->

<section class="content">
  <div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <form method="get" action="{{ route('cash.out.operator') }}">
          {{ csrf_field() }}
          <span class="info-box-number" style="font-size: x-large; margin-left:20px"> {{ $gaza ?  $gaza->day : date("Y-m-d")}}</span>
          <div class="col-md-8">
            <div class="input-group">
                <input type="date" value="{{$date}}" name="date" id="date" placeholder="" required>
            </div>
            @can('admin-agence')
              <div class="input-group">
                <select name="idUser" id="idUser" required>
                  <option value="">@lang('lang.select.a.customer')</option>
                  @foreach ($clients as $client)
                   
                      @if($idUser == $client->id)
                      <option value="{{$client->id}}" selected>{{$client->firstname}} {{$client->lastname}}</option>
                      @else
                        <option value="{{$client->id}}">{{$client->firstname}} {{$client->lastname}}</option>
                      @endif
                   
                  @endforeach
                </select>
              </div>
            @endcan
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-warning" name="search" value="search"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span> إ بحث </button>
           <!--  <button type="submit" class="btn btn-warning" name="search" value="search"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span> Exce</button> -->
          </div>
        </form>
      </div>
      <!-- /.info-box -->
    </div>

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-money" aria-hidden="true"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Total + Gaza </span>
          <span class="info-box-number" style="font-size: x-large;">
            {{ $gaza ? number_format(floor($gaza->livre + $gaza->frais_gaza)) : 0}} <small>MRU</small>
          </span>
          <span class="info-box-text">مجموع التحوىلاة + غزه </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-yellow"><i class="fa fa-money" aria-hidden="true"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">Frais Gaza</span>
          <span class="info-box-number" style="font-size: x-large;">{{ $gaza ? number_format(floor($gaza->frais_gaza)) : 0}} <small> MRU</small></span>
          <span class="info-box-text">مجموع رسوم غزه</span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-money" aria-hidden="true"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">En attente</span>
          <span class="info-box-number" style="font-size: x-large;"> {{ $gaza ? number_format(floor($gaza->attente)) : 0}} <small>MRU</small> </span>
          <span class="info-box-text"> التحوىلاة </span>
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
                    @if($cache->gaza_confirm)
                    <i class="fa fa-check-circle" style="font-size: 30px;"></i>
  
                    @else

                    <a href=" {{ route('cash.out.confirme.operation', ['id' => $cache->id_commande, 'operator' => Auth::user()->user_type]) }}" class="btn btn-success">
                      تم التحويل 
                    </a>
                    
                    @endif</td>
                  <td class="sorting_1">{{ $cache->updated_at  }}</td>

                  <td class="sorting_1"> (<small>أوقيه جديده</small>) {{ number_format(floor($cache->somme_mru + $cache->frais_gaza))}} </td>
                  <td class="sorting_1"> (<small>أوقيه جديده</small>) {{ number_format(floor($cache->frais_gaza))  }} </td>
                  <td class="hidden-xs"> (<small>أوقيه جديده</small>) {{ number_format(floor($cache->somme_mru))  }} </td>
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
        @if($cashout)
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
        @endif
      </div>
    </div>
    <!-- /.box-body -->
  </div>
</section>
<!-- /.content -->
</div>
@endsection