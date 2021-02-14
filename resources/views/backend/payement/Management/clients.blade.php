@extends('backend.layouts.base')
@section('action-content')
<!-- Main content -->
<section class="content">

<div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-university" aria-hidden="true"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Nombre de Comptes</span>
              <span class="info-box-number" style="font-size: x-large;"> {{ number_format( $infos['nbr_compte']) }} <small> Comptes</small></span>
              <span class="info-box-text"> </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
             <span class="info-box-icon bg-blue"><i class="fa fa-eur" aria-hidden="true"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Solde Globale € </span>
              <span class="info-box-number" style="font-size: x-large;">{{ number_format( $infos['solde_eur']) }} <small> € </small></span>
              <span class="info-box-text"> </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><h3> MRU </h3></span>
            <div class="info-box-content">
              <span class="info-box-text">Solde Globale MRU</span>
              <span class="info-box-number" style="font-size: x-large; "> {{ number_format(  $infos['solde_mru']) }}    <small>MRU</small></span>
              <span class="info-box-text"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-money" aria-hidden="true"></i></span>
            <div class="info-box-content">
              <span class="info-box-text"> Solde Disponible </span>
              <span class="info-box-number" style="font-size: x-large;"> {{number_format( $infos['solde_dispo']) }} <small>MRU</small> </span>
              <span class="info-box-text"> </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
  <div class="box">
    <div class="box-header">
      
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
      </div>
      <form method="GET" action="{{route('payement.clients')}}">
        <div class="box box-default">
          <div class="box-header with-border">
            <div class="row">
              <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                          <div class="col-sm-12">
                            <input value="{{$params['societe']}}" type="text" class="form-control" name="societe" id="input-Societe" placeholder="Societe">
                          </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                          <div class="col-sm-12">
                            <input value="{{$params['email']}}" type="email" class="form-control" name="email" id="input-Email" placeholder="Email">
                          </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                          <div class="col-sm-12">
                            <input value="{{$params['telephone']}}" type="text" class="form-control" name="telephone" id="input-Telephone" placeholder="Telephone">
                          </div>
                      </div>
                    </div>
                </div>
              </div>
              <div class="col-md-2">
                <button type="submit" class="btn btn-warning" name="search" value="recherche">
                  <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                  Search
                </button>
                  @if(Auth::user()->user_type=='admin')
                  |
                  <button type="submit" class="btn btn-primary" name="search" value="excel">
                    <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                  To Excel
                  </button>
                  @endif
              </div>    
                <div class="col-md-1">
                  <a class="btn btn-primary" href="{{ route('payement.add.customer') }}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Compte</a>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
          </div>
      </form>
      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">

            @if($clients->total())

            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="4%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Societe</th>
                  <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Nom Prenom</th>
                  <th width="8%" class="" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">email</th>
                  <th width="8%" class=" hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">Telephone</th>
                  <th width="6%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Remarque</th>

                  <th width="8%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Solde €</th>

                  <th width="8%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Solde MRU </th>
                  <th width="12%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Operations</th>
                  <th width="4%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Details</th>
                  <th width="4%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Edite</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($clients as $indexKey => $client)
                @if($indexKey % 2 )
                <tr role="row" class="odd" style="background : #ddd">
                  @else
                <tr role="row" class="odd">
                  @endif
                  <td class="sorting_1">{{$client->firstname }} </td>
                  <td class="sorting_1"> {{$client->name}} {{$client->lastname}}</td>
                  <td class="hidden-xs">{{ $client->email  }}</td>

                  <td class="hidden-xs">{{ $client->phone  }}</td>
                  <td class="hidden-xs">{{ $client->name  }}</td>

                  <td class="hidden-xs"> @if($client->solde_euros!=0) <b> {{ number_format($client->solde_euros ) }} </b> € @endif</td>
                  <td class="hidden-xs"> @if($client->solde_mru!=0)  <b> {{number_format($client->solde_mru)  }} </b> MRU  @endif</td>

                  <td class="hidden-xs"> <a href=" {{ route('payement.customer.credit', ['id' => $client->id_client]) }}" class="btn btn-success">
                      Crediter
                    </a> | <a href=" {{ route('payement.customer.debite', ['id' => $client->id_client]) }}" class="btn btn-warning">
                      Debiter
                    </a></td>
                  <td class="hidden-xs">
                    <a href=" {{ route('payement.customer.story', ['id' => $client->id_client]) }}" class="btn btn-info">
                      Story
                    </a>
                  </td>
                  <td class="hidden-xs"><a href=" {{ route('payement.customer.edit', ['id' => $client->id_client]) }}" class="btn btn-danger">
                      Edit
                    </a></td>

                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <thead>
              </tfoot>
            </table>
            @else
            <h3 align='center' style="color: #dd4b39;"> Désolé, aucun résultat n'a été trouvé</h3>
            @endif
          </div>
        </div>
        <div class="row">
          <div class="col-sm-5">
            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($clients)}} of {{count($clients)}} entries</div>
          </div>
          <div class="col-sm-7">
            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
              {{ $clients->links() }}
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