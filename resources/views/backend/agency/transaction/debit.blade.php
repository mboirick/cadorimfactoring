@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('lang.debiter') </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('agencies.transaction.pull') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-md-6">
                                <b>@lang('lang.account.debited'):{{ $debiteur->firstname}}</b>
                            </div>
                            <div class="col-md-3">
                                <b></b>
                            </div>
                            <div class="col-md-3">
                                <b>>@lang('lang.balance.mru') : {{ $debiteur->solde_mru}} @lang('lang.mru')</b>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('benef') ? ' has-error' : '' }}">
                            <label for="benef" class="col-md-4 control-label">@lang('lang.beneficiary')</label>
                            <div class="col-md-6">
                                <input id="expediteur" type="hidden" class="form-control" name="expediteur" value="{{ Auth::user()->email }}" required autofocus>
                                <input id="debiteur" type="hidden" class="form-control" name="debiteur" value="{{ $debiteur->id_client}}" required autofocus>
                                <select class="form-control js-country" name="benef" required>
                                    <option value="">@lang('lang.selected')</option>
                                    @foreach ($clients as $client)
                                    <option value="{{$client->id_client}}">{{$client->firstname}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('nom_benef'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('nom_benef') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('montant') ? ' has-error' : '' }}">
                            <label for="montant" class="col-md-4 control-label">@lang('lang.amount.withdra.mru')Montant à retirer (MRU)</label>
                            <div class="col-md-6">
                                <input id="montant" type="number" step=any class="form-control" name="montant" value="{{ old('montant') }}" required>
                                @if ($errors->has('montant'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('montant') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('motif') ? ' has-error' : '' }}">
                            <label for="motif" class="col-md-4 control-label">@lang('lang.pattern')</label>
                            <div class="col-md-6">
                                <input id="motif" type="text" class="form-control" name="motif" value="{{ old('motif') }}" required>
                                @if ($errors->has('motif'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('motif') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    @lang('lang.validate')
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