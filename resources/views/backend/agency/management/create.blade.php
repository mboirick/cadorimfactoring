@extends('backend.layouts.base'))

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('agency.add.new')</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('agency.add') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="agence" class="col-md-4 control-label">@lang('agency.name')</label>
                            <div class="col-md-6">
                                <input id="agence" type="text" class="form-control" name="agence" value="{{ old('agence') }}" required autofocus>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nom" class="col-md-4 control-label">@lang('lang.name') @lang('lang.first.name')</label>
                            <div class="col-md-3">
                                <input id="nom" type="text" class="form-control" name="nom" value="{{ old('nom') }}" required placeholder="nom">
                            </div>
                            <div class="col-md-3">
                                <input id="prenom" type="text" class="form-control" name="prenom" value="{{ old('prenom') }}" required placeholder="Prenom">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="telephone" class="col-md-4 control-label">@lang('lang.phone')</label>
                            <div class="col-md-6">
                                <input id="telephone" type="tel" class="form-control" name="telephone" value="{{ old('telephone') }}" required>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">@lang('lang.email')</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ville" class="col-md-4 control-label">@lang('lang.city') & @lang('lang.district')</label>
                            <div class="col-md-3">
                                <select name="ville" id="ville" class="form-control">
                                    <option value=""></option>
                                    @foreach( $villes as $ville)
                                        @if($ville->name == old('ville'))
                                            <option value="{{$ville->name}}" selected>{{$ville->name}}</option>
                                        @else
                                            <option value="{{$ville->name}}">{{$ville->name}}</option>
                                        @endif
                                        <option value="{{$ville->name}}">{{$ville->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input id="quartier" type="text" class="form-control" name="quartier" value="{{ old('quartier') }}" required placeholder="@lang('lang.district')">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    @lang('lang.add')
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
