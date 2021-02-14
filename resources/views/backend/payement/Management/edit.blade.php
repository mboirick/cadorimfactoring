@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Information Client </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('payement.customer.edit', ['id' => $clientedit[0]->id_client]) }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('societe') ? ' has-error' : '' }}">
                            <label for="societe" class="col-md-4 control-label">Entreprise</label>

                            <div class="col-md-6">


                                <input id="societe" type="text" class="form-control" name="societe" value="{{ $clientedit[0]->firstname }}" required autofocus>

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
                                <input id="nom" type="text" class="form-control" name="nom" value="{{ $clientedit[0]->name }}" required placeholder="nom">

                                @if ($errors->has('nom'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-md-3">
                                <input id="prenom" type="text" class="form-control" name="prenom" value="{{ $clientedit[0]->lastname }}" required placeholder="Prenom">

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
                                <input id="telephone" type="tel" class="form-control" name="telephone" value="{{ $clientedit[0]->phone }}" required>

                                @if ($errors->has('telephone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('telephone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone_benef') ? ' has-error' : '' }}">
                            <label for="soldes" class="col-md-3 control-label">Solde €</label>

                            <div class="col-md-3">
                                <input id="solde_eur" type="number" class="form-control" name="solde_eur" value="{{ $solde->solde_euros }}" required placeholder="Solde €">

                                @if ($errors->has('solde_eur'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('solde_eur') }}</strong>
                                </span>
                                @endif
                            </div>
                            <label for="soldes" class="col-md-2 control-label">Solde MRU</label>
                            <div class="col-md-3">
                                <input id="solde_mru" type="text" class="form-control" name="solde_mru" value="{{ $solde->solde_mru }}" required placeholder="Solde MRU">

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
                                    Modifier
                                </button>
                                <a href="{{route('payement.clients')}}" class="btn btn-danger">
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