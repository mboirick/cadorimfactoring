@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('lang.add.cash')</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('cash.flow.transaction.deposit') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('nom_benef') ? ' has-error' : '' }}">
                            <label for="nom_benef" class="col-md-4 control-label">@lang('lang.deposit.in.account')</label>
                            <div class="col-md-6">
                                <input id="expediteur" type="hidden" class="form-control" name="expediteur" value="{{ Auth::user()->email }}" required autofocus>
                                <input id="operation" type="hidden" class="form-control" name="operation" value="depot" required autofocus>
                                <select class="form-control js-country" name="nom_benef" required>
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

                        <input id="montant_euro" type="hidden" class="form-control" name="montant_euro" value="0" required>
                        <div class="form-group{{ $errors->has('montant') ? ' has-error' : '' }}">
                            <label for="montant" class="col-md-4 control-label">@lang('lang.amount.mru')</label>

                            <div class="col-md-6">
                                <input id="montant" type="number" class="form-control" name="montant" value="{{ old('montant') }}" required>
                                @if ($errors->has('montant'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('montant') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('phone_benef') ? ' has-error' : '' }}">
                            <label for="phone_benef" class="col-md-4 control-label">Remarque</label>
                            <div class="col-md-6">
                                <input id="phone_benef" type="text" class="form-control" name="phone_benef" value="{{ old('phone_benef') }}" required>
                                @if ($errors->has('phone_benef'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone_benef') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success" id='retrait_button'>
                                    @lang('lang.add')
                                </button>

                                <a href="{{ route('cash.flow.home') }}" class="btn btn-danger">
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
