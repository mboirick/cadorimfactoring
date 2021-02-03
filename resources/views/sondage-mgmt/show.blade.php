@extends('cache-mgmt.base')
@section('action-content')
<!-- Main content -->
<section class="content">

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
                             
                                    <input type="hidden" id="nombre_question" name="nombre_question" value="">
                                    <div class="form-group{{ $errors->has('nomsonadage') ? ' has-error' : '' }}">
                                        <h3 style="text-align: center;" class="col-md-12 control-label">Cher client, votre avis nous intéresse</h3>

                                        <div class="col-md-6">
                                            <input id="nomsonadage" type="hidden" class="form-control" name="nomsonadage" value="sondage" autofocus>

                                        </div>
                                    </div>

                                    <!-- Q1 -->
                                    @php $i=1 @endphp
                                    @foreach($questions as $question)
                                    <div class="row">
                                        <input type="hidden" name="q{{$i}}" value="{{ $question }}">
                                        <b style="text-align: left; color:orange" class="col-md-12 control-label id_question">Q{{$i}}: {{ $question }}</b>
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

                                                <textarea name="{{'R'.$i}}" id="" cols="60%" rows="10"></textarea></br>
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
                                            <a href="{{ url('sondage-management/survey')}}" class="btn btn-success valider">
                                                Retour
                                            </a>

                                        </div>
                                    </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>


    </body>

    </html>
</section>
<!-- /.content -->
</div>
@endsection