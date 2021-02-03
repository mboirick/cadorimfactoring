@extends('backend.layouts.base')
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
        <div class="col-sm-2"><a href="{{ route('agencies.home')}}" class=" btn btn-success"> Retour</a></div>
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
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Agence</th>
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Operateur</th>
             
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">solde avant</th>
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Montant MRU</th>
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">solde apres</th>

                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Motifs</th>
              </thead>
              <tbody>


                @foreach ($paiements as $indexKey =>$paiement)


                @if($indexKey % 2 )
                <tr role="row" class="odd" style="background : #ddd">
                  @else
                <tr role="row" class="odd">
                  @endif
                  <td class="sorting_1">{{$paiement->created_at }} </td>
                  <td class="sorting_1">{{$paiement->type_opperation }} </td>
                  <td class="sorting_1">
                  {{$paiement->firstname }} 

                  </td>
                 
                  <td class="hidden-xs">
                  @foreach($agences as $agence)
                  @php $verif=true @endphp
                  @if($agence->id_client == $paiement->id_client_debiteur)
                     {{  $agence->firstname }}  
                     @php $verif=false @endphp
                      @break  
                  @endif
                  @endforeach
                @if($verif)
                {{  $paiement->id_client_debiteur }}
                @endif
                    </td>
               

 
                  <td class="hidden-xs"> {{$paiement->solde_avant_mru}}</td>
                  @if($paiement->type_opperation !='retrait'&& $paiement->type_opperation !='debit' )
                  <td class="hidden-xs" style="color: green">  + {{ $paiement->montant_mru  }}</td> 
                  @else
                  <td class="hidden-xs" style="color: red">  - {{ $paiement->montant_mru  }}</td> 
                  @endif                 
                  <td class="sorting_1"> {{$paiement->solde_mru}}</td>

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