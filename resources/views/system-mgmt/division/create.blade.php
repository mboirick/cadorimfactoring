@extends('system-mgmt.division.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Ajouter un nouveau taux</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('division.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nom</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('taux') ? ' has-error' : '' }}">
                            <label for="taux" class="col-md-4 control-label">valeur</label>

                            <div class="col-md-6">
                                <input id="taux" type="text" class="form-control" name="taux" value="{{ old('taux') }}" required autofocus>

                                @if ($errors->has('taux'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('taux') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('somme_min') ? ' has-error' : '' }}">
                            <label for="somme_min" class="col-md-4 control-label">Somme Min MRU</label>

                            <div class="col-md-6">
                                <input id="somme_min" type="text" class="form-control" name="somme_min" value="{{ old('somme_min') }}" required autofocus>

                                @if ($errors->has('somme_min'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('somme_min') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('somme_max') ? ' has-error' : '' }}">
                            <label for="somme_max" class="col-md-4 control-label">Somme Max MRU</label>

                            <div class="col-md-6">
                                <input id="somme_max" type="text" class="form-control" name="somme_max" value="{{ old('somme_max') }}" required autofocus>

                                @if ($errors->has('somme_max'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('somme_max') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Ajouter
                                </button>
                                <a href="/cache-management/cashin" class="btn btn-danger">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
