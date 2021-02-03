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
              <form class="form-horizontal" role="form" method="POST" action="{{ route('kyc-management.updaterevenu') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" id="idUser" name="idUser" value="{{$user[0]->id }}">
                <input id="email" type="hidden" class="form-control" name="email" value="{{ $user[0]->email }}" required>

                <div style="margin-top: 50px;" align="center">

                  <p><h3> Vous pouvez charger votre justificatif de revenus ci-dessous, </h3></p>

                </div>
 <br>

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

          var n = $('input[type=file]').length;

          if (n < 5) {
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