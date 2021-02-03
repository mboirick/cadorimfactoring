@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('agency.information')</div>
                <h3 class="panel-body">
                    @if (!empty($clientDebtor) && !empty($clientBenefit))
                        <p>
                            @lang('agency.climbing') {{$clientBenefit->montant_mru}} @lang('lang.mru') @lang('agency.add.mount.confirmation')  {{$clientBenefit->firstname}} @lang('agency.to.the.account') {{$clientDebtor->firstname}}
                        </p>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('agencies.transaction.credit', ['id' => $idClient]) }}" class="btn btn-success">
                                    @lang('lang.do.another')
                                </a>
                                <a href="{{ route('agencies.home') }}" class="btn btn-success">
                                    @lang('lang.return')
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="panel-body">
                            <p>@lang('agency.credit.error')</p>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <a href="{{ route('agencies.transaction.credit', ['id' => $idClient]) }}" class="btn btn-success">
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
