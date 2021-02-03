@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('agency.update') </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('agency.edit', ['id' => $clientedit->id_client]) }}" enctype="multipart/form-data" >
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label for="agence" class="col-md-4 control-label">@lang('agency.name')</label>
                            <div class="col-md-6">
                                <input id="agence" type="text" class="form-control" name="agence" value="{{ $clientedit->firstname }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nom" class="col-md-4 control-label">@lang('lang.name') @lang('lang.first.name')</label>
                            <div class="col-md-3">
                                <input id="nom" type="text" class="form-control" name="nom" value="{{ $clientedit->name }}" required placeholder="@lang('lang.name')">
                            </div>
                            <div class="col-md-3">
                                <input id="prenom" type="text" class="form-control" name="prenom" value="{{ $clientedit->lastname }}" required placeholder="@lang('lang.first.name')">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telephone" class="col-md-4 control-label">@lang('lang.phone')</label>
                            <div class="col-md-6">
                                <input id="telephone" type="tel" class="form-control" name="telephone" value="{{ $clientedit->phone }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ville" class="col-md-4 control-label"> @lang('lang.city') & @lang('lang.district')</label>
                            <div class="col-md-3"> {{$clientedit->vill}}
                                <select name="ville" id="ville" class="form-control">
                                    @foreach( $villes as $ville)
                                        @if($ville->name == $clientedit->ville)
                                            <option value="{{$ville->name}}" selected>{{$ville->name}}</option>
                                        @else
                                            <option value="{{$ville->name}}">{{$ville->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input id="quartier" type="text" class="form-control" name="quartier" value="{{ $clientedit->quartier }}" required placeholder="">
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">@lang('lang.password')</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">@lang('lang.confirm.password')</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    @lang('lang.edit')
                                </button>
                                <a href="{{ route('agencies.home') }}" class="btn btn-danger">
                                    @lang('lang.cancel')
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection