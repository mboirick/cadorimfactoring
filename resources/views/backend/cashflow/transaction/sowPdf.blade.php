@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang(('lang.error'))
                </div>
                <h3 class="panel-body">
                        <div class="panel-body">
                            <p>@lang(('lang.invoice.error'))</p>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <a href="{{ route('cash.flow.home') }}" class="btn btn-success">
                                        @lang('lang.return')
                                    </a>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
