@extends('cache-mgmt.base')
@section('action-content')
<!-- Main content -->
<section class="content">




  <div class="box">
    <div class="box-header">
      <div class="row">
        
      <div class="col-sm-12">@if(Session::has('message'))
                    <div class="alert alert-success text-center" style="margin-bottom: 10 px; padding: 6px" role="alert">
                        {{Session::get('message')}}
                    </div>
                    @endif
                </div>

        <div class="col-md-4">

          <!-- <a class="btn btn-success" href="{{ route('depotview')}}">
          <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
          Dépôt 
        </a> -->

          <a class="btn btn-danger" href="{{ route('retraitview')}}">
            <span class="glyphicon glyphicon-export" aria-hidden="true"></span>
            Retrait 
          </a>
          |
          <a class="btn btn-success" href="{{ route('cache-management.addcash') }}">
            <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
            Dépot</a>
           
        </div>
        <div class="col-sm-4">


        </div>

        <div class="col-sm-4">
       
        <a class="btn btn-primary" href="{{ route('cache-management.rapportquotidien',['date' => date('Y-m-d')] ) }}">Rapports quotidien</a>

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
      <form method="get" action="{{ route('cache-management.search') }}">
        {{ csrf_field() }}
        @component('cache-mgmt.search', ['title' => 'Search'])
        @component('cache-mgmt.two-cols-date-search-row', ['items' => ['From', 'To'],
        'oldVals' => [isset($searchingVals) ? $searchingVals['from'] : '', isset($searchingVals) ? $searchingVals['to'] : ''], 'clients' => $clients])
        @endcomponent

        @endcomponent


      </form>

      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
          @if($caches->total())
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="1%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Type</th>
                  <th width="2%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Operateur</th>
                  <th width="10%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">De la part | Pour</th>
                  <th width="15%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">Remarque</th>
                  <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">EUR</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending"> MRU</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Solde Avant </th>

                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Solde Apres</th>
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
                  <td class="hidden-xs" style="color: green;"> + {{  number_format(floor($cache->montant ))  }} MRU</td>
                  @else
                  <td class="hidden-xs" style="color: red;"> - {{ number_format($cache->montant) }} MRU</td>
                  @endif
                  <td class="hidden-xs">{{ number_format(round($cache->solde_avant, 1))   }} MRU</td>
                  <td class="hidden-xs">

                    {{ number_format(round($cache->solde_apres, 1)) }} MRU

                  </td>
                  <td class="hidden-xs">
                    @if($cache->id == $idmax )
                    <a href=" {{ route('cache-management.editSolde', ['id' => $cache->id]) }}" class="btn btn-danger">
                      Edite
                    </a>
                    @else
                    {{ $cache->created_at }}
                    @endif

                  </td>



                </tr>
                @endforeach

              </tbody>
              
            </table>

            
            @else
            <h3 align='center' style="color: #dd4b39;"> Désolé, aucun résultat n'a été trouvé</h3>


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