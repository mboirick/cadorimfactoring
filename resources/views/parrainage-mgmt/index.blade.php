@extends('cache-mgmt.base')
@section('action-content')
<!-- Main content -->
@if(Auth::user()->user_type=='admin' || Auth::user()->user_type=='client' || Auth::user()->user_type=='operateur' )
<section class="content">

  <div class="box">

    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
      </div>
      <form method="GET" action="{{ route('parrainage-management.search') }}">
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">{{isset($title) ? $title : 'Recherche'}}</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <div class="col-md-3">
              <div class="form-group">
                  <label for="inputnom" class="col-sm-3 control-label">@lang('lang.name')</label>
                  <div class="col-sm-9">
                    <input value="{{isset($params) ? $params['nom'] : ''}}" type="text" class="form-control" name="nom" id="nom" placeholder="nom">
                  </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                  <label for="inputemail" class="col-sm-3 control-label">@lang('lang.email')</label>
                  <div class="col-sm-9">
                    <input value="{{isset($params) ? $params['email'] : ''}}" type="text" class="form-control" name="email" id="email" placeholder="email">
                  </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                  <label for="inputnombre" class="col-sm-3 control-label">@lang('lang.numbre')</label>
                  <div class="col-sm-9">
                    <input value="{{isset($params) ? $params['nombre'] : ''}}" type="text" class="form-control" name="nombre" id="nombre" placeholder="nombre">
                  </div>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit"  class="btn btn-warning" name="search" value="recherche">
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
              @lang('lang.search')
            </button>  
            <a href="/parrainage-management/formulaire-planifier-envoie-courriel" class="btn btn-primary">
              <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                @lang('task.planifier.send.email')
            </a>
          </div>
        </div>
      </form>
      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.name')</th>
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">@lang('lang.first.name')</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">@lang('lang.email')</th>

                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">@lang('lang.numbre.sponsorship')</th>
                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">@lang('lang.action')</th>


                </tr>
              </thead>
              <tbody>


                @foreach ($abonnes as $indexKey =>$abonne)


                @if($indexKey % 2 )
                <tr role="row" class="odd" style="background : #ddd">
                  @else
                <tr role="row" class="odd">
                  @endif

                  <td class="sorting_1"> {{$abonne->username}}</td>
                  <td class="hidden-xs">{{ $abonne->prenom  }}</td>
                  <td class="hidden-xs">{{ $abonne->email  }}</td>
                  <td class="hidden-xs">{{ $abonne->number  }}</td>
                  <td class="hidden-xs">
                      <a href=" {{ route('parrainage-management.formulaire-courriel', ['id' => $abonne->id]) }}" class="btn btn-danger">Envoyer un message</a>
                    </td>


                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Nom</th>
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Prénom</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">E_mail</th>

                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Nombre de parrainage</th>
                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Action</th>
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