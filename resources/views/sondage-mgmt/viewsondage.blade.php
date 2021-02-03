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
        <style>
            .big {
                width: 1.5em;
                height: 1.5em;
            }

            .checked {
                color: orange;
            }
        </style>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default" style="margin-top: 20px;">
                        <div class="panel-heading" style="background-color: #000;">
                            <div style="color: #fff; font-size: x-large;"> <img src="{{ asset("/bower_components/AdminLTE/dist/img/logo-mini.png") }}" alt="logo Image" width="60px">CADORIM SONDAGE </div>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="{{ route('sondage-management.update') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <input type="hidden" id="nombre_question" name="nombre_question" value="">
                                <div class="form-group{{ $errors->has('nomsonadage') ? ' has-error' : '' }}">
                                    <h4 style="text-align: center;" class="col-md-12 control-label">Cher client, votre avis nous intéresse!</h4>

                                    <div class="col-md-6">
                                        <input id="nomsonadage" type="hidden" class="form-control" name="nomsonadage" value="sondage" autofocus>
                                        <input  type="hidden" class="form-control" name="email" value="{{$email}}" >
                                        <input  type="hidden" class="form-control" name="id" value="{{ $id}}" >
                                    </div>
                                </div>

                                <!-- Q1 -->
                                @php $i=1 @endphp
                                @foreach($questions as $question)
                                <div class="row">
                                    <input type="hidden" name="q{{$i}}" value="{{ $question }}">
                                    <b style="text-align: left;" class="col-md-12 control-label id_question">{{$i}}: {{ $question }}</b>
                                </div>
                                <div class="row">

                                    <label class="col-md-2 control-label"></label>
                                    <div class="col-md-8">

                                        @foreach($sondage as $info)

                                        @for($y=1; $y <= 10 ; $y++) @if( $info ->id_reponse =='R'.$i.'-'.$y )


                                            @if($info ->type_question=='radio')
                                            @if($info ->reponse=='autre5')
                                            <input name="{{'R'.$i}}" type="text" value='' placeholder="autre" style="width: 100%;" required></br>
                                            @elseif($info ->reponse)
                                            <input type="radio" class="big" name="{{'R'.$i}}" value="{{$info ->reponse}}" required> {{$info ->reponse}}
                                            @if($info ->precision)
                                            <input name="{{'R'.$i.'-text'}}" type="text" value='' placeholder="{{ $info ->precision}}" style="width: 60%;">


                                            @endif

                                            </br>
                                            @endif
                                            @elseif($info ->type_question=='text')
                                            <input type="text" name="{{'R'.$i}}" value="" style="width: 100%;"> </br>
                                            @elseif($info ->type_question=='note')
                                            <input type="hidden" name="{{'R'.$i}}" id='etoiles'>
                                            <span class="fa fa-star" id="id0" style="font-size:36px"></span>
                                            <span class="fa fa-star" id="id1" style="font-size:36px"></span>
                                            <span class="fa fa-star" id="id2" style="font-size:36px"></span>
                                            <span class="fa fa-star" id="id3" style="font-size:36px"></span>
                                            <span class="fa fa-star" id="id4" style="font-size:36px"></span>


                                            </br>
                                            @elseif($info ->type_question=='textarea')

                                            <textarea name="{{'R'.$i}}" id="" cols="40" rows="5"></textarea></br>
                                            @endif

                                            @endif
                                            @endfor
                                            @endforeach
                                    </div>

                                </div>
                                </br>
                                @php $i++ @endphp
                                @endforeach

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-success valider">
                                            Envoyer
                                        </button>

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

                $('.fa-star').hover(function() {

                    var test = $(this).index()-1;

                    for (var i = 0; i <= test; i++) {

                        $('#id' + i).css("color", "orange");
                    }

                    for (var y = 4; y > test; y--) {

                        $('#id' + y).css("color", "black");
                    }


                    //console.log(test);
                    //$(this).css("color", "orange");
                    $('#etoiles').val(test);

                });

                $(".valider").click(function() {

                    var nbr_question = $(".id_question").length;

                    $('#nombre_question').val(nbr_question);
                    for (var i = 0; i <= nbr_question; i++) {

                        var nbr_reponse = $(".R" + i).length;
                        $('#nombre_R' + i).val(nbr_reponse);

                    }






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
        <script src="{{ asset('js/site.js') }}"></script>
</body>

</html>