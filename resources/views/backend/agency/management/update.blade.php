@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('agency.information')</div>
                <h3 class="panel-body">
                    @if ($error == false)
                        <p>
                            @lang('agency.the.agency') {{$agency}} @lang('lang.update.success.text')
                        </p>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('agencies.home') }}" class="btn btn-success">
                                    @lang('lang.return')
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="panel-body">
                            @lang('lang.update.error.text')
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <a href="{{ route('agency.edit', ['id' => $idClient]) }}" class="btn btn-success">
                                        @lang('lang.try.again')
                                    </a>
                                    <a href="{{ route('agencies.home') }}" class="btn btn-success">
                                        @lang('lang.return')
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
