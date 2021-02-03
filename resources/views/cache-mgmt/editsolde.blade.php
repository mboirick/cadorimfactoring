@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Cash Edite</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('cache-management.updatesolde') }}">
                        {{ csrf_field() }}
                        <input id="expediteur" type="hidden" class="form-control" name="expediteur" value="{{ Auth::user()->email }}" required autofocus>
                        <input id="operation" type="hidden" class="form-control" name="operation" value="{{ $soldedit->operation }}" required autofocus>
                        <input id="id_operation" type="hidden" class="form-control" name="id_operation" value="{{ $soldedit->id }}" required autofocus>

                        <!-- <div class="form-group{{ $errors->has('montant') ? ' has-error' : '' }}">
                            <label for="montant" class="col-md-4 control-label">Montant {{ $soldedit->operation }} (EUR)</label>

                            <div class="col-md-6">
                                <input id="montant_euro" type="number" step=any class="form-control" name="montant_euro" value="{{ $soldedit->montant_euro }}" required>

                                @if ($errors->has('montant_euro'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('montant_euro') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div> -->

                        <div class="form-group{{ $errors->has('montant') ? ' has-error' : '' }}">
                            <label for="montant" class="col-md-4 control-label">Montant {{ $soldedit->operation }} (MRU)</label>

                            <div class="col-md-6">
                                <input id="montant" type="number" step=any class="form-control" name="montant" value="{{ $soldedit->montant }}" required>

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
                                <input id="phone_benef" type="text" class="form-control" name="phone_benef" value="{{ $soldedit->phone_benef }}" required>

                                @if ($errors->has('phone_benef'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone_benef') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                 <button type="submit" class="btn btn-warning" name="action" value="modifier">
                                    Modifier
                                </button>
                               <!--  <button type="submit" class="btn btn-danger" name="action" value="Supprimer">
                                    Supprimer
                                </button> -->

                                <a href="/cache-management" class="btn btn-primary">
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