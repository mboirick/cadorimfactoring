@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Mise à jour Agence </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('agency.edit', ['id' => $clientedit->id_client]) }}" enctype="multipart/form-data" >
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group{{ $errors->has('agence') ? ' has-error' : '' }}">
                            <label for="agence" class="col-md-4 control-label">Agence</label>

                            <div class="col-md-6">


                                <input id="agence" type="text" class="form-control" name="agence" value="{{ $clientedit->firstname }}" required autofocus>

                                @if ($errors->has('agence'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('agence') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>



                        <div class="form-group{{ $errors->has('nom') ? ' has-error' : '' }}">
                            <label for="nom" class="col-md-4 control-label">Nom Prenom</label>

                            <div class="col-md-3">
                                <input id="nom" type="text" class="form-control" name="nom" value="{{ $clientedit->name }}" required placeholder="nom">

                                @if ($errors->has('nom'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="col-md-3">
                                <input id="prenom" type="text" class="form-control" name="prenom" value="{{ $clientedit->lastname }}" required placeholder="Prenom">

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
                                <input id="telephone" type="tel" class="form-control" name="telephone" value="{{ $clientedit->phone }}" required>

                                @if ($errors->has('telephone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('telephone') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('ville') ? ' has-error' : '' }}">
                            <label for="ville" class="col-md-4 control-label"> Ville & Quartier</label>

                            <div class="col-md-3">
                                <select name="ville" id="ville" class="form-control">
                                    @foreach( $villes as $ville)
                                    <option value="{{$ville->name}}">{{$ville->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <input id="quartier" type="text" class="form-control" name="quartier" value="{{ $clientedit->quartier }}" required placeholder="">

                                @if ($errors->has('quartier'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('quartier') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Modifier
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