@extends('backend.layouts.base')
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
        </div>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-sm-2"><a href="{{ route('agencies.home')}}" class=" btn btn-success">@lang('lang.return')</a></div>
        <div class="col-sm-6"></div>
        <div class="col-sm-4"> </div>
      </div>
      <br>
      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.days')</th>
                  <th width="3%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('agency.name')</th>
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.number.operations')</th>
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.sum') @lang('lang.euros')</th>
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">@lang('lang.sum') @lang('lang.mru')</th>
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">@lang('lang.costs.gaza')</th>
                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">@lang('lang.details')</th>
              </thead>
              <tbody>
              @foreach ($operations as $indexKey =>$operation)
                <tr role="row" class="odd" style="background : #fff">
                  <td class="sorting_1">
                      {{$operation->jours }}
                  </td>
                  <td class="sorting_1">{{$operation->email_agence }} </td>
                  <td class="sorting_1">
                    {{$operation->nbr_operation }}
                  </td>
                  <td class="hidden-xs">
                    {{ floor($operation->total_euro)   }} @lang('lang.euros')
                  </td>
                  <td class="hidden-xs">
                    {{ floor ($operation->total) }} @lang('lang.mru')
                  </td>
                  <td class="hidden-xs">
                    {{ floor ($operation->total_gaza) }} @lang('lang.mru')
                  </td>
                  <td class="hidden-xs">
                    <a href=" {{ route('agencies.transaction.operation.detail', ['idClient' => $operation->id_agence , 'day' => $operation->jours ]) }}" class="btn btn-info">
                      Details
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-5">
            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($operations)}} of {{count($operations)}} entries</div>
          </div>
          <div class="col-sm-7">
            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
              {{ $operations->appends(request()->input())->links() }}
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