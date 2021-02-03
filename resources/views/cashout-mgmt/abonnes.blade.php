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
              <span class="info-box-number" style="font-size: x-large;" >{{ $nombre}} | {{ $abonnesmois}} <small> ce mois</small></span>
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


      <div class="box" style="border-top: #0073b7   solid 6px" >
  <div class="box-header" >
    <div class="row"  >
        <div class="col-sm-8">
          <h3 class="box-title"> Les abonnes</h3>
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
      <form method="POST" action="{{ route('cache-management.searchabonnes') }}">
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
                <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Date</th>
                <th width="1%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">KYC</th>
                <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Nom</th>
                <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Prénom</th>
                <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">E_mail</th>
                
                <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Telephone</th>
                <th width="12%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Adresse</th>
                <th  width="5%"class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Action</th>
                
                
              </tr>
            </thead>
            <tbody>
           

            @foreach ($abonnes as $indexKey =>$abonne)
                 

                 @if($indexKey % 2 )
                 <tr role="row" class="odd" style="background : #ddd">
                       @else
               <tr role="row" class="odd">
                 @endif
                  
               
                     
                  <td class="sorting_1">{{$abonne->confirmed_at }} </td>
                  <td class="sorting_1">@if($abonne->kyc ==0)
                    <i class='fa fa-circle text-yellow'></i>
                    
                    @elseif($abonne->kyc ==1)
                    <i class='fa fa-circle text-success'></i> 
                    @else
                   
                    <i class='fa fa-circle text-danger'></i> 
                    @endif</td>
                  <td class="sorting_1"> {{$abonne->username}}</td>
                  <td class="hidden-xs">{{ $abonne->prenom  }}</td>
                  <td class="hidden-xs">{{ $abonne->email  }}</td>
                  
                  <td class="hidden-xs">{{ $abonne->phone  }}</td>
                  <td class="hidden-xs">{{ $abonne->adress  }}</td>
                  <td class="hidden-xs"><a href=" {{ route('cache-management.editabonnes', ['id' => $abonne->id]) }}" class="btn btn-danger">
                    Edite
                        </a></td>
                 

              </tr>
            @endforeach
            </tbody>
            <tfoot>
              <tr role="row" style="background: #000; color :#fff">
              <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Date</th>
                <th width="1%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">KYC</th>
                <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Nom</th>
                <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Prénom</th>
                <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">E_mail</th>
                
                <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Telephone</th>
                <th width="12%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Adresse</th>
                <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Action</th>
                
                
              </tr>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($abonnes)}} of {{count($abonnes)}} entries</div>
        </div>
        <div class="col-sm-7">
          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            {{ $abonnes->links() }}
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