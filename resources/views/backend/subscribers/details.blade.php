@extends('backend.layouts.base')

@section('action-content')
<!-- Main content -->
@if(Auth::user()->user_type=='admin' || Auth::user()->user_type=='client' || Auth::user()->user_type=='operateur' )
<section class="content">

  <div class="box">
    <div class="box-header">
      <div class="row">
        <div class="col-sm-12">
          @if(Session::has('message'))
            <div class="alert alert-success text-center" style="margin-bottom: 10 px; padding: 6px" role="alert">
                {{Session::get('message')}}
            </div>
          @endif
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
      <form method="GET" action="{{ route('subscribers.details') }}">
        {{ csrf_field() }}
            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">@lang('lang.search')</h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                  </button>
                </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="inputnom" class="col-sm-3 control-label">@lang('lang.name')</label>
                      <div class="col-sm-9">
                        <input value="{{$params['username']}}" type="text" class="form-control" name="nom" id="inputnom" placeholder="Nom">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="inputemail" class="col-sm-3 control-label">@lang('lang.email')</label>
                      <div class="col-sm-9">
                        <input value="{{$params['email']}}" type="text" class="form-control" name="email" id="inputemail" placeholder="Email">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="inputtelephone" class="col-sm-3 control-label">@lang('lang.phone')</label>
                      <div class="col-sm-9">
                        <input value="{{$params['phone']}}" type="text" class="form-control" name="telephone" id="inputtelephone" placeholder="Telephone">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" class="btn btn-warning" name="search" value="recherche">
                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                  @lang('lang.search')
              </button>
            </div>
      </form>
      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                 
                  <th width="1%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">KYC</th>
            
                 
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">E_mail</th>

                  <th width="10%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Nombre transactions</th>
                  <th width="12%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Somme transferée</th>
                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Last Date </th>
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
                  <td class="sorting_1">
                    @if(1)
                    <i class='fa fa-circle text-yellow'></i>

                    @elseif($abonne->kyc ==1)
                    <i class='fa fa-circle text-success'></i>

                    @elseif($abonne->kyc ==3)
                    <i class='fa fa-circle text-infos'></i>
                    @else
                    <i class='fa fa-circle text-danger'></i>
                    @endif</td>
                
                  <td class="hidden-xs">{{ $abonne->email  }}</td>

                  <td class="hidden-xs">{{ $abonne->nbr  }}</td>
                  <td class="hidden-xs">{{ $abonne->somme  }}</td>
                  <td class="hidden-xs"> {{$abonne->lastDate}}</td>
                  <td class="hidden-xs"><a href=" {{ route('subscribers.send.reminder', ['id' => $abonne->id]) }}" class="btn btn-primary">
                      Envoyer une demande
                    </a></td>


                </tr>
                @endforeach
              </tbody>
              <tfoot>
                
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