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
      <form method="get" action="{{ route('cache-management.search') }}">
        {{ csrf_field() }}
        @component('layouts.search', ['title' => 'Search'])
        @component('layouts.abonnes-search-row', ['items' => ['Nom', 'Email', 'Telephone'],
        'oldVals' => [isset($searchingVals) ? $searchingVals['Nom'] : '', isset($searchingVals) ? $searchingVals['Email'] : '' , isset($searchingVals) ? $searchingVals['Telephone'] : '']])
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
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Entreprise</th>
                  <th width="4%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Facture</th>
                  <th width="10%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">IBAN</th>
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Montant €</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">Taux change</th>

                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">MRU</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">date paiement</th>
                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Statut</th>
                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Motifs</th>

              </thead>
              <tbody>


                @foreach ($paiements as $indexKey =>$paiement)


                <tr role="row" class="odd" style="background : #fff">
                  <td class="sorting_1">{{$paiement->created_at }} </td>
                  
                  <td class="sorting_1">{{$paiement->firstname }} </td>              
                  
                  <td class="sorting_1">

                    @foreach($documents as $key => $document)
                    @if($document->id_paiement== $paiement->id_paiement )

                    <a href="{{route('visualiser', ['id' => $document->id])}}">
                      <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                    </a>
                    @endif
                    @endforeach

                  </td>

                  <td class="hidden-xs">{{ $paiement->iban  }}</td>
                  <td class="hidden-xs">{{ $paiement->montant_euros  }}</td>

                  <td class="hidden-xs">{{ $paiement->taux_echange  }}</td>
                  <td class="hidden-xs">{{ $paiement->montant_mru  }}</td>
                  <td class="sorting_1"> {{$paiement->date_limit}}</td>
                  @if($paiement->statut==1)
                  <td class="hidden-xs" style="color: #008d4c;">
                    Approuver</td>
                  @elseif($paiement->statut==2)
                  <td class="hidden-xs" style="color: #dd4b39;">
                    Réjéter</td>
                  @endif
                  <td class="hidden-xs">{{$paiement->reponses}}</td>


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