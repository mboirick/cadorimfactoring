@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Debiter </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('paiement-management.retirermontant') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            
                            <div class="col-md-6">
                                <b>Compte debité :{{ $debiteur->firstname}}</b>
                            </div>
                            <div class="col-md-3">
                                <b>Solde € : {{ $debiteur->solde_euros}} Euros</b>
                            </div>

                            <div class="col-md-3">
                                <b>Solde MRU : {{ $debiteur->solde_mru}} MRU</b>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('benef') ? ' has-error' : '' }}">
                            <label for="benef" class="col-md-4 control-label">Bénéficiaire</label>

                            <div class="col-md-6">
                                <input id="expediteur" type="hidden" class="form-control" name="expediteur" value="{{ Auth::user()->email }}" required autofocus>
                                <input id="debiteur" type="hidden" class="form-control" name="debiteur" value="{{ $debiteur->id_client}}" required autofocus>

                                <select class="form-control js-country" name="benef" required>
                                    <option value="">selectionné</option>
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

                        <div class="form-group{{ $errors->has('montant_euro') ? ' has-error' : '' }}">
                            <label for="montant_euro" class="col-md-4 control-label">Montant à retirer (EUR)</label>

                            <div class="col-md-6">
                                <input id="montant_euro" type="number" step=any class="form-control" name="montant_euro" value="{{ old('montant_euro') }}" required>

                                @if ($errors->has('montant_euro'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('montant_euro') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('taux') ? ' has-error' : '' }}">
                            <label for="taux" class="col-md-4 control-label">Taux de change</label>

                            <div class="col-md-6">
                                <input id="taux" type="number" step=any class="form-control" name="taux" value="{{ old('taux') }}" required>

                               </div>
                        </div>

                        <div class="form-group{{ $errors->has('montant') ? ' has-error' : '' }}">
                            <label for="montant" class="col-md-4 control-label">Montant à retirer (MRU)</label>

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
                            <label for="motif" class="col-md-4 control-label">Motif</label>

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
                                    Valider
                                </button>

                                <a href="/cache-management" class="btn btn-danger">
                                    Annuler
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