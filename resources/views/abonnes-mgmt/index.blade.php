@extends('cache-mgmt.base')
@section('action-content')
<!-- Main content -->
@if(Auth::user()->user_type=='admin' || Auth::user()->user_type=='client' || Auth::user()->user_type=='operateur' )
<section class="content">

  <div class="box">
  @if(Auth::user()->user_type=='admin' )
    <div class="box-header">
      <div class="row">
        <div class="col-sm-2">
          <i class='fa fa-circle text-yellow'></i>
          <h3 class="box-title" style="color: #f0ad4e;"> En attente: {{ $stat->nbr_attente }}</h3>
        </div>
        <div class="col-sm-2" style="color: #f0ad4e;">
          <i class='fa fa-circle text-yellow'></i>
          <h3 class="box-title"> {{ count($actif) }} </h3> (Users actifs sans Kyc)
        </div>
        <div class="col-sm-2">
          <i class='fa fa-circle text-green'></i>
          <h3 class="box-title" style="color: #5cb85c;"> Approuve: {{ $stat->nbr_approuved }}</h3>
        </div>
        <div class="col-sm-2">
          <i class='fa fa-circle text-black'></i>
          <h3 class="box-title" style="color: black;"> à verifier :{{ $stat->nbr_check }} </h3>
        </div>
        <div class="col-sm-2">
          <i class='fa fa-circle text-red'></i>
          <h3 class="box-title" style="color: #d9534f;"> Réjeté: {{$stat->nbr_rejeter }}</h3>
        </div>

        <div class="col-sm-2">
          <a class="btn btn-warning" href="{{route('abonnes-details') }}">Voir les details</a>
        </div>
      </div>
    </div>
    @endif
    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
      </div>
      <form method="GET" action="{{ route('abonnes-management.searchabonnes') }}">
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
                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Action</th>


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
                  <td class="sorting_1">
                    @if($abonne->kyc ==0)
                    <i class='fa fa-circle text-yellow'></i>

                    @elseif($abonne->kyc ==1)
                    <i class='fa fa-circle text-success'></i>

                    @elseif($abonne->kyc ==3)
                    <i class='fa fa-circle text-infos'></i>
                    @else

                    <i class='fa fa-circle text-danger'></i>
                    @endif</td>
                  <td class="sorting_1"> {{$abonne->username}}</td>
                  <td class="hidden-xs">{{ $abonne->prenom  }}</td>
                  <td class="hidden-xs">{{ $abonne->email  }}</td>

                  <td class="hidden-xs">{{ $abonne->phone  }}</td>
                  <td class="hidden-xs">{{ $abonne->adress  }}</td>
                  <td class="hidden-xs"><a href=" {{ route('abonnes-management.editabonnes', ['id' => $abonne->id]) }}" class="btn btn-danger">
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
              {{ $abonnes->appends(request()->input())->links() }}
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