@extends('agence-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Update Agence</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('agence-management.update', ['id' => $agence->id]) }}" enctype="multipart/form-data">
                       
                    
                       <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group{{ $errors->has('nom_agence') ? ' has-error' : '' }}">
                            <label for="nom_agence" class="col-md-4 control-label">Nom agence </label>

                            <div class="col-md-6">
                                <input id="nom_agence" type="text" class="form-control" name="nom_agence" value="{{ $agence->nom_agence }}"  required autofocus>

                                @if ($errors->has('nom_agence'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nom_agence') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('nom_responsable') ? ' has-error' : '' }}">
                            <label for="nom_responsable" class="col-md-4 control-label">Nom résponsable</label>

                            <div class="col-md-6">
                                <input id="nom_responsable" type="text" class="form-control" name="nom_responsable" value="{{ $agence->nom_responsable }}"  required>

                                @if ($errors->has('nom_responsable'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nom_responsable') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Téléphone</label>
                            <div class="col-md-6">

                                    <input type="text" value="{{ $agence->phone }}" name="phone" class="form-control pull-right" id="phone" required>
                               
                            </div>
                        </div>
                  
                       
                        <div class="form-group{{ $errors->has('adresse') ? ' has-error' : '' }}">
                            <label for="adresse" class="col-md-4 control-label">Adresse</label>

                            <div class="col-md-6">
                                <input id="adresse" type="text" class="form-control" name="adresse" value="{{ $agence->adresse }}"required>

                                @if ($errors->has('adresse'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('adresse') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('heure_ouverture') ? ' has-error' : '' }}">
                            <label for="heure_ouverture" class="col-md-4 control-label">Heure ouverture</label>

                            <div class="col-md-6">
                                <input id="heure_ouverture" type="text" class="form-control" name="heure_ouverture" value="{{ $agence->heure_ouverture }}"required>

                                @if ($errors->has('heure_ouverture'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('heure_ouverture') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('heure_fermeture') ? ' has-error' : '' }}">
                            <label for="heure_fermeture" class="col-md-4 control-label">Heure fermeture</label>

                            <div class="col-md-6">
                                <input id="heure_fermeture" type="text" class="form-control" name="heure_fermeture" value="{{ $agence->heure_fermeture }}"required>

                                @if ($errors->has('heure_fermeture'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('heure_fermeture') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('jours_ouvrable') ? ' has-error' : '' }}">
                            <label for="jours_ouvrable" class="col-md-4 control-label">Jours ouvrable</label>

                            <div class="col-md-6">
                                <input id="jours_ouvrable" type="text" class="form-control" name="jours_ouvrable" value="{{ $agence->jours_ouvrable }}"required>

                                @if ($errors->has('jours_ouvrable'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('jours_ouvrable') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                         
                        <div class="form-group{{ $errors->has('latitude') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Latitude</label>
                            <div class="col-md-6">
                            <input id="latitude" type="text" class="form-control" name="latitude" value="{{ $agence->latitude }}" required>
                                 @if ($errors->has('latitude'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('latitude') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('longitude') ? ' has-error' : '' }}">
                            <label for="longitude" class="col-md-4 control-label">Longitude</label>

                            <div class="col-md-6">
                                <input id="longitude" type="text" class="form-control" name="longitude" value="{{ $agence->longitude }}" required>

                                @if ($errors->has('longitude'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('longitude') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="avatar" class="col-md-4 control-label" >Photo</label>
                            <div class="col-md-6">
                                <img src="../../{{$agence->picture }}" width="50px" height="50px"/>
                                <input type="file" id="picture" name="picture" />
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Mise à jour
                                </button>
                                <a href="/agence-management" class="btn btn-danger">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
