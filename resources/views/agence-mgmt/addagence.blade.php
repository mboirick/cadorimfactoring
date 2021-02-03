@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Ajouter un nouveau compte agence</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('agency.add') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('agence') ? ' has-error' : '' }}">
                            <label for="agence" class="col-md-4 control-label">Agence</label>

                            <div class="col-md-6">


                                <input id="agence" type="text" class="form-control" name="agence" value="" required autofocus>

                                @if ($errors->has('agence'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('agence') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('nom') ? ' has-error' : '' }}">
                            <label for="nom" class="col-md-4 control-label">Nom Prénom</label>

                            <div class="col-md-3">
                                <input id="nom" type="text" class="form-control" name="nom" value="" required placeholder="nom">

                                @if ($errors->has('nom'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-md-3">
                                <input id="prenom" type="text" class="form-control" name="prenom" value="" required placeholder="Prenom">

                                @if ($errors->has('prenom'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('prenom') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('telephone') ? ' has-error' : '' }}">
                            <label for="telephone" class="col-md-4 control-label">Téléphone</label>

                            <div class="col-md-6">
                                <input id="telephone" type="tel" class="form-control" name="telephone" value="" required>

                                @if ($errors->has('telephone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('telephone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="" required>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone_benef') ? ' has-error' : '' }}">
                            <label for="ville" class="col-md-4 control-label">Ville & Quartier</label>
                            <div class="col-md-3">
                                <select name="ville" id="ville" class="form-control">
                                    <option value=""></option>
                                    @foreach( $villes as $ville)
                                    <option value="{{$ville->name}}">{{$ville->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <input id="quartier" type="text" class="form-control" name="quartier" value="" required placeholder="Quartier">

                                @if ($errors->has('quartier'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('quartier') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Ajouter
                                </button>

                                <a href="/agencies/home" class="btn btn-danger">
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
