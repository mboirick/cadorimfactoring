@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Ajouter un nouveau client</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('cache-management.creatclient') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('societe') ? ' has-error' : '' }}">
                            <label for="societe" class="col-md-4 control-label">Societe</label>

                            <div class="col-md-6">
                                <input id="expediteur" type="hidden" class="form-control" name="expediteur" value="{{ Auth::user()->email }}" required autofocus>
                               
                                <input id="societe" type="text" class="form-control" name="societe" value="{{ old('societe') }}" required autofocus>

                                @if ($errors->has('societe'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('societe') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        
                        
                        <div class="form-group{{ $errors->has('nom_prenom') ? ' has-error' : '' }}">
                            <label for="nom_prenom" class="col-md-4 control-label">Nom Prenom</label>

                            <div class="col-md-6">
                                <input id="nom_prenom" type="text" class="form-control" name="nom_prenom" value="{{ old('nom_prenom') }}" required>

                                @if ($errors->has('nom_prenom'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nom_prenom') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('adresse') ? ' has-error' : '' }}">
                            <label for="adresse" class="col-md-4 control-label">Adresse</label>

                            <div class="col-md-6">
                                <input id="adresse" type="text" class="form-control" name="adresse" value="{{ old('adresse') }}" required>

                                @if ($errors->has('adresse'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('adresse') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('telephone') ? ' has-error' : '' }}">
                            <label for="telephone" class="col-md-4 control-label">Telephone</label>

                            <div class="col-md-6">
                                <input id="telephone" type="text" class="form-control" name="telephone" value="{{ old('telephone') }}" required>

                                @if ($errors->has('telephone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('telephone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('phone_benef') ? ' has-error' : '' }}">
                            <label for="remarque" class="col-md-4 control-label">Remarque</label>

                            <div class="col-md-6">
                                <input id="remarque" type="text" class="form-control" name="remarque" value="{{ old('remarque') }}" required>

                                @if ($errors->has('remarque'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('remarque') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Ajouter
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
