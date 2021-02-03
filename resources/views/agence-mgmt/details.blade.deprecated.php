@extends('cache-mgmt.base')
@section('action-content')
<!-- Main content -->
<section class="content">




  <div class="box">
    <div class="box-header">
      <div class="row">

        <div class="col-sm-4">

        <h3>Agent: {{$agent->email_agence}}</h3>
        </div>

        <div class="col-sm-4">

        </div>

        <div class="col-sm-4">

          <a class="btn btn-success" href="{{ url('agence-management')}}">Retour</a> 

          <a class="btn btn-primary" href="">Exporte excel</a>

        </div>

      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-sm-3">
          <h3>Date: {{$agent->jours}}</h3>
        </div>
        <div class="col-sm-3">
          <h3>Nombre operation: {{$agent->nbr_operation}}</h3>
        </div>
        <div class="col-sm-3">
          <h3>Somme MRU: {{floor($agent->total)}} MRU</h3>
        </div>
        <div class="col-sm-3">
          <h3>Frais Gaza: {{$agent->total_gaza}} MRU</h3>
        </div>
      </div>



      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">

                  <th width="2%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Operateur</th>
                  <th width="15%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">De la part | Pour</th>
                  <th width="10%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">ID commande</th>
                  <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">EUR</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending"> MRU</th>
                  <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">frais Gaza </th>

                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Agence</th>
                  <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Date</th>


                </tr>
              </thead>
              <tbody>



                @foreach ($caches as $indexKey => $cache)
                @if($indexKey % 2 )
                <tr role="row" class="odd" style="background : #ddd">
                  @else
                <tr role="row" class="odd">
                  @endif

                  <td class="sorting_1">{{$cache->expediteur }} </td>
                  <td class="sorting_1"> {{$cache->nom_benef}}</td>
                  <td class="hidden-xs">{{ $cache->phone_benef  }}</td>
                  <td class="hidden-xs"> {{ $cache->montant_euro  }} €</td>

                  @if($cache->operation== 'depot' )
                  <td class="hidden-xs"> + {{ $cache->montant  }} MRU</td>
                  @else
                  <td class="hidden-xs"> - {{ $cache->montant  }} MRU</td>
                  @endif
                  <td class="hidden-xs">{{ $cache->frais_gaza  }} MRU</td>
                  <td class="hidden-xs">

                    {{ $cache->agence_gaza  }}

                  </td>
                  <td class="hidden-xs">
                    @if($cache->id_client!= '99' )
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
              <tfoot>

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