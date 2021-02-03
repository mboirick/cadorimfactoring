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
          <a class="btn btn-primary" href="{{ route('cache-management.addclient') }}">Ajouter un Client</a>

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
    
      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="4%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Societe</th>
                  <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Nom Prenom</th>
                  <th width="8%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Adresse</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">Telephone</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Remarque</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">CashIn</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">CashOut</th>

                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Solde </th>
                  <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Action</th>
                </tr>
              </thead>
              <tbody>



                @foreach ($clients as $indexKey => $client)
                @if($indexKey % 2 )
                <tr role="row" class="odd" style="background : #ddd">
                  @else
                <tr role="row" class="odd">
                  @endif

                  <td class="sorting_1">{{$client->societe }} </td>
                  <td class="sorting_1"> {{$client->nom_prenom}}</td>
                  <td class="hidden-xs">{{ $client->adresse  }}</td>

                  <td class="hidden-xs">{{ $client->telephone  }}</td>
                  <td class="hidden-xs">{{ $client->remarque  }}</td>
                  <td class="hidden-xs">{{ $client->cash_in  }}</td>
                  <td class="hidden-xs">{{ $client->cash_out  }}</td>
                  <td class="hidden-xs">   {{ $client->solde  }}         </td>
                  <td class="hidden-xs">
                    <a href=" {{ route('cache-management.editClient', ['id' => $client->id]) }}" class="btn btn-danger">
                      Edite
                    </a>

                  </td>



                </tr>
                @endforeach
              </tbody>
              <tfoot>
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="4%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Societe</th>
                  <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Nom Prenom</th>
                  <th width="8%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Adresse</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">Telephone</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Remarque</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">CashIn</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">CashOut</th>

                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Solde </th>
                  <th width="4%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Action</th>
                </tr>
              </tfoot>
            </table>
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