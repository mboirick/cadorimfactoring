@extends('cache-mgmt.base')
@section('action-content')
    <!-- Main content -->
    <section class="content">

      <div class="box" >
  <div class="box-header" >
    <div class="row"  >
        <div class="col-sm-8">
          <h3 class="box-title">Gestion des virement</h3>
        </div>
        <div class="col-sm-4">
        <button class="btn btn-primary">         Exporter vers Excel                </button>
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
      
    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row" style="background: #000; color :#fff">
              <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">N commande</th>
              <th width="20%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Expediteur</th>
            <th width="20%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Bénéficiaire</th>
            <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Montant en €|$</th>

            <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Date </th>
         
            <th width="15%" class="sorting_1" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Action</th>
               
                
              </tr>
            </thead>
            <tbody>
            @foreach ($cashout as $indexKey =>$cache)
                 

                 @if($indexKey % 2 )
                 <tr role="row" class="odd" style="background : #ddd">
                       @else
               <tr role="row" class="odd">
                 @endif
                  
                 <td class="sorting_1"> {{$cache->id_commande}}  </td>
                        
                  <td class="sorting_1"> {{$cache->nom_exp}} ( {{$cache->phone_exp}} )  </td>
                  <td class="sorting_1"> {{$cache->nom_benef}} ( {{$cache->phone_benef}} ) </td>
                  <td class="hidden-xs">{{ $cache->payment_amount  }} {{ $cache->payment_currency  }}</td>

                  <td class="hidden-xs">{{ $cache->date_commande  }}</td>
                  <td>
                    <form class="row" method="POST" action="{{ route('virement-management.destroy', ['id' => $cache->id_commande]) }}" onsubmit = "return confirm('Etes Vous sure de vouloir supprimer ce virement ?')">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         <div style="display:none"> {{$action=$cache->id_commande.'@Virement'}}</div>
                        <a href="{{ route('virement-management.show', ['id' => $action]) }}" class="btn btn-success col-sm-3 col-xs-5 btn-margin">
                        Valider
                        </a>
                        <button type="submit" class="btn btn-danger col-sm-3 col-xs-5 btn-margin">
                        Supprimer
                        </button>
                    </form>
                  </td>
                 
                  
              </tr>
            @endforeach
            </tbody>
            <tfoot>
              <tr>
              <tr role="row">
              <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending"></th>
              <th width="20%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending"></th>
            <th width="20%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending"></th>
            <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending"></th>
           
            <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending"> </th>
         
            <th width="15%" class="sorting sorting_1" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending"></th>
           
            
          </tr>
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
            {{ $cashout->links() }}
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