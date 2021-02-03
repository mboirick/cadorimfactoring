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
              <span class="info-box-number" style="font-size: x-large;">{{ strrev(wordwrap(strrev($soldecashout), 3, ' ', true)) }} <small> MRU</small></span>
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
              
              <span class="info-box-number" style="font-size: x-large;"> {{  strrev(wordwrap(strrev($solde[0]-> solde), 3, ' ', true))  }}  <small>MRU</small> </span> 
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>




      <div class="box">
  <div class="box-header">
    <div class="row">
        <div class="col-sm-8">
          <h3 class="box-title">Liste de taux</h3>
        </div>
        <div class="col-sm-4">
          <a class="btn btn-success" href="{{ route('division.create') }}">Ajouter un nouveau taux</a>
        </div>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
      <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6"></div>
      </div>
     
    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row" style="background: #000; color :#fff">
                <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="division: activate to sort column ascending">Nom</th>
                <th width="8%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="division: activate to sort column ascending">valeur</th>
                <th width="8%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="division: activate to sort column ascending">Somme Min</th>
                <th width="8%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="division: activate to sort column ascending">Somme Max</th>
                
                <th   tabindex="0" aria-controls="example2" rowspan="1" colspan="2" aria-label="Action: activate to sort column ascending">Action</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($divisions as $division)
                <tr role="row" class="odd">
                  <td class="sorting hidden-xs" >{{ $division->name }}</td>
                  <td>{{ $division->taux }}</td>
                  <td>{{ $division->somme_min }}</td>
                  <td>{{ $division->somme_max }}</td>
                  <td>
                    <form class="row" method="POST" action="{{ route('division.destroy', ['id' => $division->id]) }}" onsubmit = "return confirm('Are you sure?')">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <a href="{{ route('division.edit', ['id' => $division->id]) }}" class="btn btn-warning col-sm-3 col-xs-5 btn-margin">
                        Update
                        </a>
                        <button type="submit" class="btn btn-danger col-sm-3 col-xs-5 btn-margin">
                          Delete
                        </button>
                    </form>
                  </td>
              </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr role="row" style="background: #000; color :#fff">
            <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="division: activate to sort column ascending">Nom</th>
                <th width="8%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="division: activate to sort column ascending">valeur</th>
                <th width="8%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="division: activate to sort column ascending">Somme Min</th>
                <th width="8%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="division: activate to sort column ascending">Somme Max</th>
                
                <th   tabindex="0" aria-controls="example2" rowspan="1" colspan="2" aria-label="Action: activate to sort column ascending">Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="row">    
        <div class="col-sm-5">
          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Affiche 1 to {{count($divisions)}} of {{count($divisions)}} entrées</div>
        </div>
        <div class="col-sm-7">
          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            {{ $divisions->links() }}
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