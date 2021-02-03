@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Effectuer un cash out</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('cache-management.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('nom_benef') ? ' has-error' : '' }}">
                            <label for="nom_benef" class="col-md-4 control-label">Bénéficiaire</label>

                            <div class="col-md-6">
                                <input id="expediteur" type="hidden" class="form-control" name="expediteur" value="{{ Auth::user()->email }}" required autofocus>
                                <input id="operation" type="hidden" class="form-control" name="operation" value="retrait" required autofocus>
                                
                                <input id="nom_benef" type="text" class="form-control" name="nom_benef" value="{{ old('nom_benef') }}" required autofocus>

                                @if ($errors->has('nom_benef'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nom_benef') }}</strong>
                                    </span>
                                @endif
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
                                <button type="submit" class="btn btn-success">
                                    Retrait
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
