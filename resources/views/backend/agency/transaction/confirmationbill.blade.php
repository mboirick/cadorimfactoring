@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if($error == false)
                        @lang('lang.confirmation')
                    @else
                        @lang('lang.error')
                    @endif
                </div>
                <h3 class="panel-body">
                    @if ($error == 0)
                        <p>
                            @if($typeOperation == 'deposit')
                                Mr {{Auth::user()->firstname}} {{Auth::user()->lastname}} a déposé {{$amount}} MRU dans le compte d'agence {{$agencyName}} en date {{$dateToday}}
                            @elseif($typeOperation == 'withdrawal')
                                L'agence {{$agencyName}} a déposé {{$amount}} dans le compte Mr {{Auth::user()->firstname}} {{Auth::user()->lastname}} en date de {{$dateToday}}
                            @endif
                        </p>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('transaction.bill.download', ['idBill' => $idBill]) }}" class="btn btn-success" target="_blank">
                                    @lang('lang.download.bill')
                                </a>
                                @if($typeOperation == 'deposit')
                                    <a href="{{ route('agencies.transaction.deposit') }}" class="btn btn-success">
                                        @lang('lang.return')
                                    </a>
                                @elseif($typeOperation == 'withdrawal')
                                    <a href="{{ route('agencies.transaction.withdrawal') }}" class="btn btn-success">
                                        @lang('lang.return')
                                    </a>
                                @endif

                            </div>
                        </div>
                    @else
                        <div class="panel-body">
                            <p>@lang('agency.credit.error')</p>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <a href="{{ route('agencies.transaction.deposit') }}" class="btn btn-success">
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
