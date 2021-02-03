@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('agency.information')</div>
                <h3 class="panel-body">
                    @if ($isOk == true)
                        <p>@lang('agency.create.agency')</p>
                        <div class="form-group">
                            <label class="col-md-3 control-label">@lang('agency.name')</label> : {{$params['agence']}}
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">@lang('lang.name')</label> : {{$params['nom']}}
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">@lang('lang.first.name')</label> : {{$params['prenom']}}
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">@lang('lang.phone')</label> : {{$params['telephone']}}
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">@lang('lang.email')</label> : {{$params['email']}}
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">@lang('lang.city')</label> : {{$params['ville']}}
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">@lang('lang.district')</label> : {{$params['quartier']}}
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href=" {{ route('agency.edit', ['id' => $params['idClient']]) }}" class="btn btn-danger">
                                    @lang('lang.edit')
                                </a>
                                <a href="{{ route('agencies.home') }}" class="btn btn-success">
                                    @lang('lang.return')
                                </a><br/><br/>
                                <a href="{{ route('agency.add') }}" class="btn btn-success">
                                    @lang('agency.add.another.agency')
                                </a>
                            </div>
                        </div>
                    @else
                        <p>@lang('agency.create.error')</p>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('agency.add') }}" class="btn btn-success">
                                    @lang('lang.add')
                                </a>
                                <a href="{{ route('agencies.home') }}" class="btn btn-success">
                                    @lang('lang.return')
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
