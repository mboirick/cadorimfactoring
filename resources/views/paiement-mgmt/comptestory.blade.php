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
        <div class="col-sm-2"><a href="{{ url('paiement-management/clients')}}" class=" btn btn-success"> Retour</a></div>
        <div class="col-sm-6"></div>
        <div class="col-sm-4"> </div>
      </div>
 <br>

      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Date</th>
                  <th width="3%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Operation</th>
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Compte A</th>
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Montant €</th>
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Montant MRU</th>
                  
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">solde €</th>
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">solde MRU</th>

                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Motifs</th>
              </thead>
              <tbody>


                @foreach ($paiements as $indexKey =>$paiement)


                <tr role="row" class="odd" style="background : #fff">
                  <td class="sorting_1">{{$paiement->created_at }} </td>
                  <td class="sorting_1">{{$paiement->type_opperation }} </td>
                  <td class="sorting_1">
                    @foreach($comptes as $key => $compte)
                    @if($compte->id_client== $paiement->id_client )

                    {{ $compte->firstname}}
                    @endif
                    @endforeach

                  </td>
                  @if($paiement->type_opperation == 'Retrait' || $paiement->type_opperation == 'debit' )
                  <td class="hidden-xs" style="color: red"> - <b> {{ number_format( $paiement->montant_euros  )  }} </b> €</td>
                  <td class="hidden-xs" style="color: red"> - <b>{{ number_format( $paiement->montant_mru  )  }}</b> MRU</td>
                  @else
                  <td class="hidden-xs" style="color: green"> + <b>{{ number_format( $paiement->montant_euros  )  }} </b>€</td>
                  <td class="hidden-xs" style="color: green"> + <b>{{  number_format ($paiement->montant_mru  )  }}</b>  MRU</td>
                  @endif

                  
                  <td class="hidden-xs"> <b>{{ number_format($paiement->solde_euros  )  }} </b> €</td>
                  <td class="sorting_1"><b> {{number_format ($paiement->solde_mru)  }} </b>MRU</td>

                  <td class="hidden-xs"> {{$paiement->motif}}</td>

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