@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-sm-10">
            @if(Session::has('message'))
            <div class="alert alert-success text-center" style="margin-bottom: 10 px; padding: 6px" role="alert">
                {{Session::get('message')}}
            </div>
            @endif
        </div>
    </div>
    @if($user)
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">@lang('lang.edit')</div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('user.update') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input id="idUser" type="hidden" name="idUser" value="{{ $user->id }}">
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">@lang('lang.email') </label>

                                <div class="col-md-6">
                                    <input id="email" readonly type="email" class="form-control" name="email" value="{{ $user->email }}">

                                    @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                                <label for="firstname" class="col-md-4 control-label">@lang('lang.lastName')</label>

                                <div class="col-md-6">
                                    <input id="firstname" type="text" class="form-control" name="firstname" value="{{ $user->firstname }}" required>

                                    @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                                <label for="lastname" class="col-md-4 control-label">@lang('lang.firstName')</label>

                                <div class="col-md-6">
                                    <input id="lastname" type="text" class="form-control" name="lastname" value="{{ $user->lastname }}" required>

                                    @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="type" class="col-md-4 control-label">@lang('lang.user.type')</label>

                                <div class="col-md-6">
                                    <select name="user_type" id="user_type">
                                        <option value="">Sélectionner</option>
                                        <option value="api" {{$user->user_type == 'api'? 'selected':'' }}>@lang('lang.api')</option>
                                        <option value="api" {{$user->user_type == 'operateur'? 'selected':'' }}>@lang('lang.operateur')</option>
                                        <option value="api" {{$user->user_type == 'business'? 'selected':'' }}>@lang('lang.business')</option>
                                        <option value="api" {{$user->user_type == 'cash'? 'selected':'' }}>@lang('lang.cash')</option>
                                        <option value="api" {{$user->user_type == 'client'? 'selected':'' }}>@lang('lang.client')</option>
                                        <option value="api" {{$user->user_type == 'marketing'? 'selected':'' }}>@lang('lang.marketing')</option>
                                        <option value="api" {{$user->user_type == 'agence'? 'selected':'' }}>Agence</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Réinitialisation le mot de passe</label>

                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="pwdRadios" id="yesRadios" value="1">
                                        <label class="form-check-label" for="pwdRadios">
                                            Oui
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="pwdRadios" id="noRadios" value="0" checked>
                                        <label class="form-check-label" for="pwdRadios">
                                            Non
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="firstname" class="col-md-4 control-label">@lang('lang.roles')</label>

                                <div class="col-md-6">
                                    {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control','multiple')) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        @lang('lang.save')
                                    </button>
                                    <a href="{{route('users')}}" class="btn btn-danger">
                                        @lang('lang.cancel')
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
    @endif
</div>
@endsection