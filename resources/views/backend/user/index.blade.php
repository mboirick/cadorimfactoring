@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row justify-content-center">
        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <form method="GET" action="{{ route('users') }}">
          <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input  type="text" value="{{$lastName}}" class="form-control" name="lastName" id="lastName" placeholder="@lang('lang.lastName')">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input  type="text" value="{{$firstName}}" class="form-control" name="firstName" id="firstName" placeholder="@lang('lang.firstName')">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input  type="text" value="{{$email}}" class="form-control" name="email" id="email" placeholder="@lang('lang.email')">
                            </div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col-md-2">
                        <button type="submit" class="btn btn-warning" name="search" value="recherche">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            @lang('lang.search')
                        </button>
                    </div>
                    <div class="col-sm-4">
                <a class="btn btn-success" href="{{ route('user.add') }}">@lang('lang.add.user')</a>
              </div>
            </div>
          </form>
        <div class="row">
            <div class="col-sm-10">
              @if(Session::has('message'))
                <div class="alert alert-success text-center" style="margin-bottom: 10 px; padding: 6px" role="alert">
                    {{Session::get('message')}}
                </div>
              @endif
            </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">@lang('lang.lastName') </th>
                  <th width="16%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.FirstName')</th>
                  <th width="18%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">@lang('lang.email')</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">@lang('lang.action') </th>
                </tr>
              </thead>
              <tbody>
              @foreach ($users as $indexKey =>$user)
                @if($indexKey % 2 )
                <tr role="row" class="odd" style="background : #ddd">
                  @else
                <tr role="row" class="odd">
                  @endif
                  <td>{{ $user->firstname }}</td>
                  <td> {{$user->lastname}}</td>
                  <td> {{$user->email}}</td>
                   <td>
                    @if($user->deleted_at)
                      <!-- Button trigger modal -->
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#enableModal-{{$user->id}}">
                        @lang('lang.enable')
                      </button>

                      <!-- Modal -->
                      <div class="modal fade" id="enableModal-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">@lang('lang.enable')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            @lang('lang.text.enable') {{ $user->firstname }}  {{$user->lastname}}?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('lang.no')</button>
                            <a href="{{ route('user.restore', ['id' => $user->id]) }}" class="btn btn-primary">@lang('lang.yes')</a>
                          </div>
                          </div>
                        </div>
                      </div>
                      @else
                        <!-- Button trigger modal -->
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#desableModal-{{$user->id}}">
                        @lang('lang.desable')
                      </button>

                      <!-- Modal -->
                      <div class="modal fade" id="desableModal-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">@lang('lang.desable')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            @lang('lang.text.desable') {{ $user->firstname }}  {{$user->lastname}}?
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('lang.no')</button>
                            <a href="{{ route('user.delete', ['id' => $user->id]) }}" class="btn btn-primary">@lang('lang.yes')</a>
                          </div>
                          </div>
                        </div>
                      </div>
                      @endif |
                      <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-primary">@lang('lang.edit')</a>
                   </td>  
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">@lang('lang.lastName') </th>
                  <th width="16%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">@lang('lang.FirstName')</th>
                  <th width="18%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">@lang('lang.email')</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">@lang('lang.key.cleint')</th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">@lang('lang.token') </th>
                  <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">@lang('lang.action') </th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
                <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($users)}} of {{count($users)}} entries</div>
            </div>
            <div class="col-sm-7">
                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
@endsection
