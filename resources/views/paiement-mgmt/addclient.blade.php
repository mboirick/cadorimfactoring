@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Ajouter un nouveau client</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('paiement-management.creatclient') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('societe') ? ' has-error' : '' }}">
                            <label for="societe" class="col-md-4 control-label">Entreprise</label>

                            <div class="col-md-6">


                                <input id="societe" type="text" class="form-control" name="societe" value="" required autofocus>

                                @if ($errors->has('societe'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('societe') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group{{ $errors->has('nom') ? ' has-error' : '' }}">
                            <label for="nom" class="col-md-4 control-label">Nom Prenom</label>

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
                            <label for="telephone" class="col-md-4 control-label">Telephone</label>

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
                            <label for="soldes" class="col-md-3 control-label">Solde €</label>

                            <div class="col-md-3">
                                <input id="solde_eur" type="number" class="form-control" name="solde_eur" value="" required placeholder="Solde €">

                                @if ($errors->has('solde_eur'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('solde_eur') }}</strong>
                                </span>
                                @endif
                            </div>
                            <label for="soldes" class="col-md-2 control-label">Solde MRU</label>
                            <div class="col-md-3">
                                <input id="solde_mru" type="text" class="form-control" name="solde_mru" value="" required placeholder="Solde MRU">

                                @if ($errors->has('solde_mru'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('solde_mru') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Ajouter
                                </button>

                                <a href="/paiement-management/clients" class="btn btn-danger"> 
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
