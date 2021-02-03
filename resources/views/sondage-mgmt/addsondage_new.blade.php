@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Creation de sondage</div>
        <div class="panel-body">
          <form class="form-horizontal" role="form" method="POST" action="{{ route('sondage-management.creation') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" id="idUser" name="idUser" value="">


            <div class="form-group{{ $errors->has('nomsonadage') ? ' has-error' : '' }}">
              <label for="nomsonadage" class="col-md-4 control-label">Nom du sondage</label>

              <div class="col-md-6">
                <input id="nomsonadage" type="text" class="form-control" name="nomsonadage" value="" required autofocus>

              </div>
            </div>


            <div class="question">
              <div class="form-group">
                <label for="avatar" class="col-md-4 control-label">Question 1: </label>
                <div class="col-md-6">
                  <div class="input-group control-group increment">
                    <input type="text" name="document[]" class="form-control">
                    <div class="input-group-btn">
                      <button class="btn btn-warning reponse" type="button"><i class="glyphicon glyphicon-plus"></i>Reponse</button>
                    </div>
                  </div>
                  <div class="clone hide">
                    <div class="control-group input-group" style="margin-top:10px">
                      <input type="text" name="document[]" id="file" class="form-control">
                      <div class="input-group-btn">
                        <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Supprimer</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <button class="btn btn-warning addquestion" type="button"><i class="glyphicon glyphicon-plus"></i>Ajouter une question</button>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-success">
                  Valider
                </button>
                <a href="/abonnes-management" class="btn btn-danger">
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