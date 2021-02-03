@extends('cache-mgmt.base')
@section('action-content')
<!-- Main content -->
<section class="content">




  <div class="box">
    <div class="box-header">
      <div class="row">

        <div class="col-sm-4">
          

        </div>

        <div class="col-sm-4">

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
      <form method="get" action="{{ route('paiement-management.search') }}">
      {{ csrf_field() }}
      @component('paiement-mgmt.search', ['title' => 'Search'])
      @component('paiement-mgmt.search-row', ['items' => ['Emetteur', 'Beneficiaire', 'Operation'],
      'oldVals' => [isset($searchingVals) ? $searchingVals['emetteur'] : '', isset($searchingVals) ? $searchingVals['beneficiaire'] : '' , isset($searchingVals) ? $searchingVals['operation'] : '']])
      @endcomponent
      @endcomponent


      </form>
      
      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row" style="background: #000; color :#fff">
                <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Date</th>
                <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Emetteur</th>
                <th width="10%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Bénéficiaire</th>
                <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Montant €</th>
                <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">Taux</th>
                <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Montant MRU</th>
                <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Operation</th>
                <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Statut</th>
                <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Action</th>

            </thead>
            <tbody>


              @foreach ($paiements as $indexKey =>$paiement)


              <tr role="row" class="odd" style="background : #fff">
                <td class="sorting_1">{{$paiement->created_at }} </td>
                <td class="sorting_1"> {{$paiement->firstname }}    </td>
                <td class="hidden-xs">{{ $paiement->entreprise  }}</td>
                <td class="hidden-xs">{{ $paiement->montant_euros  }}</td>

                <td class="hidden-xs">{{ $paiement->taux_echange  }}</td>
                <td class="hidden-xs">{{ $paiement->montant_mru  }}</td>
                <td class="sorting_1"> {{$paiement->type_demande}}</td>
                <td class="hidden-xs" style="color: #e08e0b;">
                  En cours....</td>
                <td class="hidden-xs"><a href="{{ route('paiement-management.detail',[ 'id'=> $paiement->id_paiement ])}} " class="btn btn-info">
                    Detail
                  </a></td>


              </tr>
              @endforeach
            </tbody>
            <tfoot>

              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($paiements)}} of {{count($paiements)}} entries</div>
        </div>
        <div class="col-sm-7">
          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            {{ $paiements->appends(request()->input())->links() }}
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