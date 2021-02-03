@extends('cache-mgmt.base')
@section('action-content')
    <!-- Main content -->

    @if(Auth::user()->user_type=='admin' || Auth::user()->user_type=='operateur'  )
    <section class="content">

      <div class="box" >

  <!-- /.box-header -->
  <div class="box-body">
     
  

    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
            <tr role="row" style="background: #000; color :#fff">
              <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Commande </th>
              <th width="16%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Expediteur</th>
              
            <th width="18%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Bénéficiaire</th>
            <th width="10%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Adresse</th>
            <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending"> €|$</th>
            <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending"> MRU</th>
            <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">Frais Ghaza</th>
            <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Date </th>
         
            <th width="6%" class="sorting_1" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Statut</th>
               
                
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
                  
                
                    - {{$cache->nom_exp}} ({{$cache->phone_exp}} ) </td>

                 
                  <td class="sorting_1"> {{$cache->nom_benef}} ( {{$cache->phone_benef}} ) </td>
                  <td class="sorting_1"> {{$cache->adress_benef}}</td>
                  <td class="hidden-xs">{{ $cache->payment_amount  }} {{ $cache->payment_currency  }}</td>
                  <td class="hidden-xs">{{ strrev(wordwrap(strrev($cache->somme_mru), 3, ' ', true))   }} MRU</td>
                  <td class="hidden-xs"> {{ $cache->frais_gaza  }}</td> 
                  <td class="hidden-xs">{{ $cache->payment_date  }}</td>
                  <td class="sorting_1">
                  
                
                  
                  @if($cache->tracker_status =='retire')
                    <i class='fa fa-circle text-success'></i> Livrée
                  @endif
                     
                  @if($cache->tracker_status =='attente')  
                    <a href=" {{ route('cashout-management.editcashout', ['id' => $cache->id_commande]) }}" class="btn btn-danger">
                    En attente
                        </a>
                 @endif
                 @if($cache->tracker_status =='transfert')  
                    <a href=" {{ route('cashout-management.editcashout', ['id' => $cache->id_commande]) }}" class="btn btn-warning">
                    Transfert Gaza
                        </a>
                 @endif
                  
                  </td>
                 
                 
                  
              </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr role="row" style="background: #000; color :#fff">
              <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Commande </th>
              <th width="16%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Expediteur</th>
              
            <th width="18%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Bénéficiaire</th>
            <th width="10%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Adresse</th>
            <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending"> €|$</th>
            <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending"> MRU</th>
            <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">Frais Ghaza</th>
            <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Date </th>
         
            <th width="6%" class="sorting_1" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Statut</th>
               
                
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
  @endif
@endsection