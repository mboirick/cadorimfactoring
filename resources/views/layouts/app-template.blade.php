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
  <!-- <link href="{{ asset("/bower_components/AdminLTE/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" /> -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link href="{{ asset("/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.css")}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset("/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.css")}}" rel="stylesheet" type="text/css" />

  <!-- Theme style -->
  <link href="{{ asset("/bower_components/AdminLTE/dist/css/AdminLTE.min.css")}}" rel="stylesheet" type="text/css" />

  <link href="{{ asset("/bower_components/AdminLTE/dist/css/skins/_all-skins.min.css")}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('css/app-template.css') }}" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/iCheck/flat/blue.css")}}">
  <!-- Morris chart -->
  <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/morris/morris.css")}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.css")}}">

  <link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css")}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">

  <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
  <script type="text/javascript" src="{{ URL::asset('js/parrainage.js') }}"></script>

</head>


</head>
<!--
    BODY TAG OPTIONS:
    =================
    Apply one or more of the following classes to get the
    desired effect
    |---------------------------------------------------------|
    | SKINS         | skin-blue                               |
    |               | skin-black                              |
    |               | skin-purple                             |
    |               | skin-yellow                             |
    |               | skin-red                                |
    |               | skin-green                              |
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
    -->

<body class="hold-transition skin-blue sidebar-mini">


  <div class="wrapper">
    <!-- Main Header -->
    @include('layouts.header')
    <!-- Sidebar -->

    @include('layouts.sidebar')

    @yield('content')
    <!-- /.content-wrapper -->
    <!-- Footer -->
    @include('layouts.footer')
    <!-- ./wrapper -->
    <!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery 2.1.3 -->


    <!-- Bootstrap 3.3.2 JS -->
    <!-- <script src="{{ asset ("/bower_components/AdminLTE/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script> -->
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/datatables/jquery.dataTables.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/fastclick/fastclick.js") }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/daterangepicker/daterangepicker.js") }}" type="text/javascript"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset ("/bower_components/AdminLTE/dist/js/app.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/dist/js/demo.js") }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>


    <!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->


    <script type="text/javascript">
      $(document).ready(function() {

        //eviter les multiplication de submit
        $(".multi-submits").submit(function(event) {
          alert("Handler for .submit() called.");
          event.preventDefault();
        });
        // FIN



        $("#retrait_button").click(function() {

        });

        $("#transfert_button").click(function() {
          $(this).hide();

        });



        //  suppression du code Promo
        $(".deleteRecord").click(function() {
          var id = $(this).attr('rel');
          var deleteFunction = $(this).attr('rel1');


          swal({
            title: 'Êtes-vous sûr?',
            text: "Vous ne pourrez pas revenir en arrière!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d9534f',
            cancelButtonColor: '#5cb85c',
            confirmButtonText: 'Oui, Supprimer',
            cancelButtonText: 'No, Annuler!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
            reverseButtons: true
          }, function() {
            window.location.href = "/" + deleteFunction + "/" + id;
          });
        });

        //  la fin

        //  Le temps de disparition du message

        setTimeout(function() {
          $("div.alert").fadeOut('slow');
        }, 3000);

        //  FIN



        $("#transfert_vers").change(function() {

          var local = $('#transfert_vers').val();
          if (local == '5f63cc77d3b00') {

            $("#transfert_gaza").show();
          } else {
            $("#transfert_gaza").hide();
          }


        });

        $(".valider").click(function() {

          var nbr_question = $(".id_question").length;

          $('#nombre_question').val(nbr_question);
          for (var i = 0; i <= nbr_question; i++) {

            var nbr_reponse = $(".R" + i).length;
            $('#nombre_R' + i).val(nbr_reponse);

          }
        });

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

        $(".addquestion").click(function() {

          var html = $(".question").html();
          $(".question").after(html);

        });

        $(".reponse").click(function() {

          $(this).alert()

          var n = $('input[type=file]').length;

          if (n < 10) {
            var html = $(".clone").html();
            $(this).parents(".increment").after(html);
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

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/morris/morris.min.js") }} "></script>
    <!-- Sparkline -->
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/sparkline/jquery.sparkline.min.js") }} "></script>
    <!-- jvectormap -->
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js") }} "></script>
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js") }} "></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/knob/jquery.knob.js") }}"></script>

    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset ("/bower_components/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset ("/bower_components/AdminLTE/dist/js/pages/dashboard.js") }}"></script>

    <script src="{{ asset('js/site.js') }}"></script>


</body>

</html>