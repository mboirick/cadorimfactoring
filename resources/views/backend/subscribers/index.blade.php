@extends('backend.layouts.base')
@section('action-content')
<!-- Main content -->
<section class="content">

  <div class="box">
  @if(auth()->user()->hasRole('admin'))
    <div class="box-header">
      <div class="row">
        <div class="col-sm-2">
          <i class='fa fa-circle text-yellow'></i>
          <h3 class="box-title" style="color: #f0ad4e;"> @lang('lang.waiting'): {{ $stat->nbr_attente }}</h3>
        </div>
        <div class="col-sm-2" style="color: #f0ad4e;">
          <i class='fa fa-circle text-yellow'></i>
          <h3 class="box-title"> {{ count($actif) }} </h3> (Users actifs sans Kyc)
        </div>
        <div class="col-sm-2">
          <i class='fa fa-circle text-green'></i>
          <h3 class="box-title" style="color: #5cb85c;"> @lang('lang.approved'): {{ $stat->nbr_approuved }}</h3>
        </div>
        <div class="col-sm-2">
          <i class='fa fa-circle text-black'></i>
          <h3 class="box-title" style="color: black;">@lang('lang.to.check') :{{ $stat->nbr_check }} </h3>
        </div>
        <div class="col-sm-2">
          <i class='fa fa-circle text-red'></i>
          <h3 class="box-title" style="color: #d9534f;"> @lang('lang.rejected'): {{$stat->nbr_rejeter }}</h3>
        </div>
        <div class="col-sm-2">
          <a class="btn btn-warning" href="{{route('subscribers.details') }}">@lang('lang.show.details')</a>
        </div>
      </div>
    </div>
    @endif
    <!-- /.box-header -->
    <div class="box-body">
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
      <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
      </div>
      <form method="GET" action="{{ route('subscribers.search') }}">
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
                
                  <div class="col-md-3">
                    <div class="form-group">  
                      <label class="col-sm-3 control-label">@lang('lang.status.kyc')</label>
                      <div class="col-sm-9">
                        <select name="statut_kyc" id="statut_kyc">
                          <option value="">@lang('lang.all')</option>
                              @if($params['kyc'] == '0')
                                <option value="0" class="btn btn-warning" selected>@lang('lang.waiting')</option>
                              @else
                                <option value="0" class="btn btn-warning">@lang('lang.waiting')</option>
                              @endif
                              @if($params['kyc'] == 1)
                                <option value="1" class="btn btn-success" selected>@lang('lang.approved')</option>
                              @else
                                <option value="1" class="btn btn-success">@lang('lang.approved')</option>
                              @endif
                              @if($params['kyc'] == 2)
                                <option value="2" class="btn btn-danger" selected>@lang('lang.rejected')</option>
                              @else
                                <option value="2" class="btn btn-danger">@lang('lang.rejected')</option>
                              @endif
                              @if($params['kyc'] == 3)
                                <option value="3" class="btn" style="background: #000; color: #fff" selected>@lang('lang.to.check')</option>
                              @else
                                <option value="3" class="btn" style="background: #000; color: #fff">@lang('lang.to.check')</option>
                              @endif
                        </select>
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
              <button type="submit" class="btn btn-primary" name="search" value="excel">
                <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                  @lang('lang.export.to.excel')
                </button>
            </div>
      </form>
      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.date')</th>
                  <th width="1%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">KYC</th>
                  <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.name')</th>
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">@lang('lang.first.name')</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">@lang('lang.email')</th>

                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">@lang('lang.phone')</th>
                  <th width="12%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">@lang('lang.address')</th>
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
                  <td class="hidden-xs"><a href=" {{ route('subscribers.edit', ['id' => $abonne->id]) }}" class="btn btn-danger">
                      Edite
                    </a></td>

                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.date')</th>
                  <th width="1%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">KYC</th>
                  <th width="5%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.name')</th>
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">@lang('lang.first.name')</th>
                  <th width="8%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">@lang('lang.email')</th>

                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">@lang('lang.phone')</th>
                  <th width="12%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">@lang('lang.address')</th>
                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">@lang('lang.action')</th>
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
@endsection