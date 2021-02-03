@extends('cache-mgmt.base')
@section('action-content')
    <!-- Main content -->
    <section class="content">

    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <a href="{{ route('cache-management.abonnes') }}"><span class="info-box-icon bg-blue"><i class="fa fa-users" aria-hidden="true"></i></span></a>
            

            <div class="info-box-content">
            <span class="info-box-text">Abonnes total | Ce mois ci</span>
              <span class="info-box-number" style="font-size: x-large;" >{{ $abonnes}} | {{ $abonnesmois}} <small> ce mois</small></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <a href="{{ route('cache-management.virement') }}"> <span class="info-box-icon bg-yellow"><i class="fa fa-share" aria-hidden="true"></i></span></a>
           
            <div class="info-box-content">
              <span class="info-box-text">Les virements en attente </span>
              <span class="info-box-number" style="font-size: x-large;">
              
              {{strrev(wordwrap(strrev($virementEUR), 3, ' ', true)) }} <small>€</small> |  {{strrev(wordwrap(strrev($virementUSD), 3, ' ', true)) }} <small>$</small>
            
             </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <a href="{{ route('cache-management.cashout') }}"><span class="info-box-icon bg-red"><i class="fa fa-long-arrow-up" aria-hidden="true"></i></span></a>
            

            <div class="info-box-content">
              <span class="info-box-text">Cash OUT EN ATTENTE</span>
              <span class="info-box-number" style="font-size: x-large;">{{ strrev(wordwrap(strrev(round($soldecashout, 0)), 3, ' ', true)) }} <small> MRU</small></span>
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
              
              <span class="info-box-number" style="font-size: x-large;"> {{  strrev(wordwrap(strrev(round($solde[0]-> solde, 0)), 3, ' ', true))  }}  <small>MRU</small> </span> 
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>


      <div class="box" style="border-top: #f39c12 solid 6px" >
  <div class="box-header" >
    <div class="row"  >
        <div class="col-sm-8">
          <h3 class="box-title">Cash Disponible</h3> 
        </div>
        <div class="col-sm-4">
        <form class="form-horizontal" role="form" method="POST" action="{{ route('excel_cash') }}">
                {{ csrf_field() }}
                <input type="hidden" value="{{$searchingVals['from']}}" name="from" />
                <input type="hidden" value="{{$searchingVals['to']}}" name="to" />
                <button type="submit" class="btn btn-primary">
                  Export to Excel
                </button>
            </form>

            <!-- <form class="form-horizontal" role="form" method="POST" action="{{ route('pdf') }}">
                {{ csrf_field() }}
                <input type="hidden" value="{{$searchingVals['from']}}" name="from" />
                <input type="hidden" value="{{$searchingVals['to']}}" name="to" />
                <button type="submit" class="btn btn-primary">
                  Export to PDF
                </button>
            </form> -->
        </div>

        <div class="col-sm-4">
          <a class="btn btn-success" href="{{ route('cache-management.create') }}">Add Cash</a> <a class="btn btn-danger" href="{{ route('cache-management.retrait') }}"> Retirer Cash</a>
        </div>

        
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
      <div class="row" >
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
      </div>
      <form method="POST" action="{{ route('cache-management.search') }}">
         {{ csrf_field() }}
         @component('layouts.search', ['title' => 'Search'])
         @component('layouts.two-cols-date-search-row', ['items' => ['From', 'To'], 
          'oldVals' => [isset($searchingVals) ? $searchingVals['from'] : '', isset($searchingVals) ? $searchingVals['to'] : '']])
          @endcomponent
         
        @endcomponent

        
      </form>
    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row" style="background: #000; color :#fff">
              <th width="4%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Type</th>
                <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Operateur</th>
                <th width="10%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">De la part | Pour</th>
                <th width="15%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">Remarque</th>
                <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Montant en MRU</th>
               
                <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Date </th>
             
                <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Solde</th>
                <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Action</th>
               
                
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
                      <td class="sorting_1" style="background: #00a65a; color: white">Cash IN</td>
                       @else
                       <td class="sorting_1" style="background: #dd4b39; color: white">cash OUT </td>
                           @endif
                  <td class="sorting_1">{{$cache->expediteur }} </td>
                  <td class="sorting_1"> {{$cache->nom_benef}}</td>
                  <td class="hidden-xs">{{ $cache->phone_benef  }}</td>
                 
                  @if($cache->operation== 'depot' )
                      <td class="hidden-xs"> +  {{ $cache->montant  }}</td>
                       @else
                       <td class="hidden-xs"> -  {{ $cache->montant  }}</td>
                           @endif
                  <td class="hidden-xs">{{ $cache->created_at  }}</td>
                  <td class="hidden-xs">

                  {{ $cache->solde  }}
                  
                  </td>
                  <td class="hidden-xs">

                  <a href=" {{ route('cache-management.editSolde', ['id' => $cache->id]) }}" class="btn btn-danger">
                    Edite
                        </a>
                  
                  </td>

                  
              </tr>
            @endforeach
            </tbody>
            <tfoot>
              <tr>
                <tr role="row" style="background: #000; color :#fff">
                <th width="4%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Type</th>
                <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Operateur</th>
                <th width="10%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">De la part | Pour</th>
                <th width="15%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">Remarque</th>
                <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Montant en MRU</th>
               
                <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Date </th>
             
                <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Solde</th>
                <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Action</th>
              </tr>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($caches)}} of {{count($caches)}} entries</div>
        </div>
        <div class="col-sm-7">
          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            {{ $caches->links() }}
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