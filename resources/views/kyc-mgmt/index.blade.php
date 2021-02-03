<!DOCTYPE html>
<!--
  This is a starter template page. Use this page to start your new project from
  scratch. This page gets rid of all links and provides the needed markup only.
  -->
<html>

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CADORIM Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link href="{{ asset("/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link href="{{ asset("/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css")}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset("/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css")}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset("/bower_components/AdminLTE/plugins/datepicker/datepicker3.css")}}" rel="stylesheet" type="text/css" />
  <!-- Theme style -->
  <link href="{{ asset("/bower_components/AdminLTE/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
      page. However, you can choose any other skin. Make sure you
      apply the skin class to the body tag so the changes take effect.
      -->
  <link href="{{ asset("/bower_components/AdminLTE/dist/css/skins/_all-skins.min.css")}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('css/app-template.css') }}" rel="stylesheet">

</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="wrapper" style="background-color: #fff;">

    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <div class="panel panel-default" style="margin-top: 20px;">
            <div class="panel-heading" style="background-color: #000;">
              <div style="color: #fff; font-size: x-large;"> <img src="{{ asset("/bower_components/AdminLTE/dist/img/logo-mini.png") }}" alt="logo Image" width="60px">CADORIM KYC </div>
            </div>
            <div class="panel-body">
              <form class="form-horizontal" role="form" method="POST" action="{{ route('kyc-management.update') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" id="idUser" name="idUser" value="{{$user[0]->id }}">
                <input id="email" type="hidden" class="form-control" name="email" value="{{ $user[0]->email }}" required>

                <div class="form-group{{ $errors->has('genre') ? ' has-error' : '' }}">

                  <label for="genre" class="col-md-4 control-label">Genre</label>

                  <div class="col-md-3">
                                <input type="radio" id="genre" name="genre" value="homme" {{$user[0]->genre == 'homme' ? 'checked' : ''}} required >Homme </div>

                            <div class="col-md-3">
                                <input type="radio" id="genre" name="genre" value="femme" {{$user[0]->genre == 'femme' ? 'checked' : ''}}  required>Femme </div>

                </div>

                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                  <label for="username" class="col-md-4 control-label">Nom</label>

                  <div class="col-md-6">
                    <input id="username" type="text" class="form-control" name="username" value="{{$user[0]->username }}" required autofocus>

                    @if ($errors->has('username'))
                    <span class="help-block">
                      <strong>{{ $errors->first('username') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>
                <div class="form-group{{ $errors->has('prenom') ? ' has-error' : '' }}">
                  <label for="prenom" class="col-md-4 control-label">Prénom</label>

                  <div class="col-md-6">
                    <input id="prenom" type="text" class="form-control" name="prenom" value="{{$user[0]->prenom}} " required>
                    @if ($errors->has('prenom'))
                    <span class="help-block">
                      <strong>{{ $errors->first('prenom') }}</strong>
                    </span>
                    @endif

                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Téléphone</label>
                  <div class="col-md-6">


                    <input type="text" value="{{ $user[0]->phone }}" name="phone" class="form-control pull-right" id="phone" required>
                    @if ($errors->has('phone'))
                    <span class="help-block">
                      <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>



                

                <div class="form-group{{ $errors->has('adresse') ? ' has-error' : '' }}">
                  <label for="adresse" class="col-md-4 control-label">Adresse</label>

                  <div class="col-md-6">
                    <input id="adresse" type="text" class="form-control" name="adresse" value="{{ $user[0]->adress }}" required >
                    @if ($errors->has('adresse'))
                    <span class="help-block">
                      <strong>{{ $errors->first('adresse') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Ville</label>
                  <div class="col-md-6">
                    <input id="Ville" type="text" class="form-control" name="Ville" value="{{ $user[0]->ville }}" required>
                    @if ($errors->has('Ville'))
                    <span class="help-block">
                      <strong>{{ $errors->first('Ville') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-4 control-label">Code Postal</label>
                  <div class="col-md-6">
                    <input id="code_postal" type="text" class="form-control" name="code_postal" value="{{ $user[0]->code_postal }}" required>
                    @if ($errors->has('code_postal'))
                    <span class="help-block">
                      <strong>{{ $errors->first('code_postal') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-4 control-label">Pays de résidence</label>
                  <div class="col-md-6">
                    <input id="pays_residence" type="text" class="form-control" name="pays_residence" value="{{ $user[0]->pays_residence }}" required>
                    @if ($errors->has('pays_residence'))
                    <span class="help-block">
                      <strong>{{ $errors->first('pays_residence') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>



                <div class="form-group">
                            <label class="col-md-4 control-label">Date de naissance (YYYY-MM-DD)</label>
                            <div class="col-md-6">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" value="{{ $user[0]->date_naissance }}" name="date_naissance" class="form-control pull-right" id="birthDate" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Type document d'identité</label>
                            <div class="col-md-6">
                                <select name="type_doc" id="type_doc">
                                    <option value="Passport">Passport</option>
                                    <option value="piece">Piéce d'identité</option>
                                    <option value="permis">Permis de conduire</option>
                                    <option value="autre">Autre</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Numero document d'identité</label>
                            <div class="col-md-6">
                                <input id="numero_doc" type="text" class="form-control" name="numero_doc" value="{{ $user[0]->numero_doc }}" required>
                                @if ($errors->has('numero_doc'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('numero_doc') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-4 control-label">Date d'émission (YYYY-MM-DD)</label>
                            <div class="col-md-6">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" value="{{ $user[0]->date_emission }}" name="date_emission" class="form-control pull-right" id="from" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Date d'expiration' (YYYY-MM-DD)</label>
                            <div class="col-md-6">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" value="{{ $user[0]->date_expiration }}" name="date_expiration" class="form-control pull-right" id="to" required>
                                </div>
                            </div>
                        </div>
                <div class="form-group">
                  <label for="avatar" class="col-md-4 control-label">Documents</label>
                  <div class="col-md-6">
                    <div class="input-group control-group increment">
                      <input type="file" name="document[]" class="form-control">
                      <div class="input-group-btn">
                        <button class="btn btn-warning" type="button"><i class="glyphicon glyphicon-plus"></i>Ajouter</button>
                      </div>
                    </div>
                    <div class="clone hide">
                      <div class="control-group input-group" style="margin-top:10px">
                        <input type="file" name="document[]" class="form-control">
                        <div class="input-group-btn">
                          <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Supprimer</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>



                <div class="form-group">
                  <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-success">
                      Envoyer
                    </button>
                    <a href="https://cadorim.com/" class="btn btn-danger">
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
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js") }}"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="{{ asset ("/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/fastclick/fastclick.js") }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js") }}" type="text/javascript"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset ("/bower_components/AdminLTE/dist/js/app.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/dist/js/demo.js") }}" type="text/javascript"></script>

    <!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->
      <script type="text/javascript">
      $(document).ready(function() {

     
        $(".btn-warning").click(function() {

          var n = $( 'input[type=file]' ).length;
          
          if(n < 5 ) {
            var html = $(".clone").html();
          $(".increment").after(html);
          }
        
        });

        $("body").on("click", ".btn-danger", function() {
          $(this).parents(".control-group").remove();
        });

      });
    </script>
    
    <script>
      $(document).ready(function() {
        //Date picker
        $('#birthDate').datepicker({
          autoclose: true,
          format: 'yyyy/mm/dd'
        });
        $('#hiredDate').datepicker({
          autoclose: true,
          format: 'yyyy/mm/dd'
        });
        $('#from').datepicker({
          autoclose: true,
          format: 'yyyy/mm/dd'
        });
        $('#to').datepicker({
          autoclose: true,
          format: 'yyyy/mm/dd'
        });
      });
    </script>
    <script src="{{ asset('js/site.js') }}"></script>
</body>

</html>