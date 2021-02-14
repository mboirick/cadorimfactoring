@extends('backend.layouts.base')

@section('action-content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Creation de sondage</div>
        <div class="panel-body">
          <form class="form-horizontal" role="form" method="POST" action="{{ route('survey.store') }}" enctype="multipart/form-data">
            {{ csrf_field() }}

            <input type="hidden" id="nombre_question" name="nombre_question" value="">
            <div class="form-group{{ $errors->has('nomsonadage') ? ' has-error' : '' }}">
              <label for="nomsonadage" class="col-md-4 control-label">Nom du sondage</label>

              <div class="col-md-6">
                <input id="nomsonadage" type="text" class="form-control" name="nomsonadage" value="" required reautofocus>

              </div>
            </div>
            <!-- Q1 -->
            <div class="row">
              <label class="col-md-2 control-label id_question">Q1</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q1" name="q1" value="Vous êtes ?">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R1</label>
              <div class="col-md-8">
                <input type="hidden" id="nombre_R1" name="nombre_R1" value="">
                <input type="text" style="width: 60%;" class="R1" name="R1-0" value="radio">type de reponse </br>
                <input type="text" style="width: 60%;" class="R1" name="R1-1" value="homme"> </br>
                <input type="text" style="width: 60%;" class="R1" name="R1-2" value="femme">
              </div>
            </div>
            </br>


            <!-- Q2 -->

            <div class="row">
              <label class="col-md-2 control-label id_question">Q2</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q2" name="q2" value="De quelle tranche d’âge faites-vous partie ?">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R2</label>
              <div class="col-md-8">
                <input type="hidden" id="nombre_R2" name="nombre_R2" value="">
                <input type="text" style="width: 60%;" class="R2" name="R2-0" value="radio">type de reponse </br>
                <input type="text" style="width: 60%;" class="R2" name="R2-1" value="Moins de 20 ans"></br>
                <input type="text" style="width: 60%;" class="R2" name="R2-2" value="20-31 ans"> </br>
                <input type="text" style="width: 60%;" class="R2" name="R2-3" value="31-50 ans"> </br>
                <input type="text" style="width: 60%;" class="R2" name="R2-4" value="51-70 ans"> </br>
                <input type="text" style="width: 60%;" class="R2" name="R2-5" value="71 ans et plus"> </br>
              </div>
            </div>
            </br>


            <!-- Q3 -->

            <div class="row">
              <label class="col-md-2 control-label id_question">Q3</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q3" name="q3" value="Comment avez-vous connu CADORIM?">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R3</label>
              <div class="col-md-8">
                <input type="hidden" id="nombre_R3" name="nombre_R3" value="">
                <input type="text" style="width: 60%;" class="R3" name="R3-0" value="radio">type de reponse </br>
                <input type="text" style="width: 60%;" class="R3" name="R3-1" value="Recommandation d’un(e) proche"> </br>
                <input type="text" style="width: 60%;" class="R3" name="R3-2" value="Facebook/Instagram/Snap"> </br>
                <input type="text" style="width: 60%;" class="R3" name="R3-3" value="WhatsApp"> </br>
                <input type="text" style="width: 60%;" class="R3" name="R3-4" value="Échange direct avec l’équipe de Cadorim"> </br>
                <input type="text" style="width: 60%;" class="R3" name="R3-5" value="Recherche sur internet"> </br>
                <input type="text" style="width: 60%;" class="R3" name="R3-6" value="autre"> </br>
              </div>
            </div>
            </br>

            <!-- Q4 -->

            <div class="row">
              <label class="col-md-2 control-label id_question">Q4</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q4" name="q4" value="Utilisez-vous encore nos services ?">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R4</label>
              <div class="col-md-8">
                <input type="hidden" id="nombre_R4" name="nombre_R4" value="">
                <input type="text" style="width: 60%;" class="R4" name="R4-0" value="radio">type de reponse </br>
                <input type="text" style="width: 60%;" class="R4" name="R4-1" value="Oui"> </br>
                <input type="text" style="width: 60%;" class="R4" name="R4-2" value="non"> </br>
                <input type="text" style="width: 100%;" class="R4" name="R4-3" value="précisez la raison :">
              </div>
            </div>
            </br>

            <!-- Q5 -->
            <div class="row">
              <label class="col-md-2 control-label id_question">Q5</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q5" name="q5" value="Quel est votre degré de satisfaction ?">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R5</label>
              <div class="col-md-8">
                <input type="hidden" id="nombre_R5" name="nombre_R5" value="">
                <input type="text" style="width: 60%;" class="R5" name="R5-0" value="note">type de reponse </br>
              
              </div>
            </div>
            </br>

            <!-- Q6 -->
            <div class="row">
              <label class="col-md-2 control-label id_question">Q6</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q6" name="q6" value="Quel est le principal avantage que vous avez reçu de Cadorim?">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R6</label>
              <div class="col-md-8">
                <input type="hidden" id="nombre_R6" name="nombre_R6" value="">
                <input type="text" style="width: 60%;" class="R6" name="R6-0" value="text">type de reponse </br>

              </div>
            </div>
            </br>
            <!-- Q7 -->

            <div class="row">
              <label class="col-md-2 control-label id_question">Q7</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q7" name="q7" value="Avez-vous recommandé Cadorim à quelqu’un ?">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R7</label>
              <div class="col-md-8">
                <input type="hidden" id="nombre_R7" name="nombre_R7" value="">
                <input type="text" style="width: 60%;" class="R7" name="R7-0" value="radio">type de reponse </br>
                <input type="text" style="width: 60%;" class="R7" name="R7-1" value="nom"></br>
                <input type="text" style="width: 60%;" class="R7" name="R7-2" value="oui"> </br>
                <input type="text" style="width: 100%;" class="R7" name="R7-3" value="Combien ? :">
              </div>
            </div>
            </br>


            <!-- Q8 -->
            <div class="row">
              <label class="col-md-2 control-label  id_question">Q8</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q8" name="q8" value="À quel point seriez-vous déçu si Cadorim n’existait plus demain?">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R8</label>
              <div class="col-md-8">
                <input type="hidden" id="nombre_R8" name="nombre_R8" value="">
                <input type="text" style="width: 60%;" class="R8" name="R8-0" value="radio">type de reponse </br>
                <input type="text" style="width: 60%;" class="R8" name="R8-1" value="Très déçu"> </br>
                <input type="text" style="width: 60%;" class="R8" name="R8-2" value="Un peu déçu"> </br>
                <input type="text" style="width: 60%;" class="R8" name="R8-3" value="Pas déçu. Votre produit n’est vraiment pas si utile."> </br>
                <input type="text" style="width: 60%;" class="R8" name="R8-4" value="Non applicable. Je ne suis plus un client"> </br>

              </div>
            </div>
            </br>

            <!-- Q9 -->

            <div class="row">
              <label class="col-md-2 control-label  id_question">Q9</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q9" name="q9" value="Selon vous quel est le principal defaut de Cadorim?">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R9</label>
              <div class="col-md-8">
                <input type="hidden" id="nombre_R9" name="nombre_R9" value="">
                <input type="text" style="width: 60%;" class="R9" name="R9-0" value="text">type de reponse </br>

              </div>
            </div>
            </br>


            <!-- Q10 -->

            <div class="row">
              <label class="col-md-2 control-label id_question">Q10</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q10" name="q10" value="Qu’utiliseriez-vous comme solution de rechange si Cadorim n’était plus disponible ?">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R10</label>
              <div class="col-md-8">
                <input type="hidden" id="nombre_R10" name="nombre_R10" value="">
                <input type="text" style="width: 60%;" class="R10" name="R10-0" value="radio">type de reponse </br>
                <input type="text" style="width: 60%;" class="R10" name="R10-1" value="Je n’utiliserais pas une autre alternative"> </br>
                <input type="text" style="width: 60%;" class="R10" name="R10-2" value="J’utiliserai"> </br>
                <input type="text" style="width: 100%;" class="R10" name="R10-3" value='precision'>

              </div>
            </div>
            </br>


            <!-- Q11 -->


            <div class="row">
              <label class="col-md-2 control-label id_question">Q11</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q11" name="q11" value="Selon vous, quels sont les points à améliorer ?">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R11</label>
              <div class="col-md-8">
                <input type="hidden" id="nombre_R11" name="nombre_R11" value="">
                <input type="text" style="width: 60%;" class="R11" name="R11-0" value="radio">type de reponse </br>

                <input type="text" style="width: 60%;" class="R11" name="R11-1" value="Présentation du site internet"> </br>
                <input type="text" style="width: 60%;" class="R11" name="R11-2" value="Le service client"> </br>
                <input type="text" style="width: 60%;" class="R11" name="R11-3" value="WhatsApp"> </br>
                <input type="text" style="width: 60%;" class="R11" name="R11-4" value="Échange direct avec l’équipe de Cadorim"> </br>
                <input type="text" style="width: 60%;" class="R11" name="R11-5" value="Recherche sur internet"> </br>
                <input type="text" style="width: 60%;" class="R11" name="R11-6" value="autre"> </br>
              </div>
            </div>
            </br>
            <!-- Q12 -->
            <div class="row">
              <label class="col-md-2 control-label id_question">Q12</label><input class="col-md-10 control-label" style="text-align: left;" type="text" id="q12" name="q12" value="Remarques supplémentaires :">
            </div>
            <div class="row">
              <label class="col-md-2 control-label">R12</label>
              <div class="col-md-8"></br>
                <input type="hidden" id="nombre_R12" name="nombre_R12" value="">
                <input type="text" style="width: 60%;" class="R12" name="R12-0" value="textarea">type de reponse </br>

                </br>

              </div>
            </div>
            </br>


            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-success valider" name='action' value="valider">
                  Valider
                </button>
                <a href="{{route('survey.home')}}" class="btn btn-danger">
                  Annuler
                </a>
                |
                <button type="submit" class="btn btn-success valider" name='action' value="view">
                  Voir
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection