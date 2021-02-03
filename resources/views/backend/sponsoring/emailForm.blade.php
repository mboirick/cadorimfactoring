@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('lang.text.form')</div>
                <div class="panel-body">
                    @if($send == 0)
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('sponsoring.email.form', ['id'=>$user[0]->id]) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" id="idUser" name="idUser" value="{{$user[0]->id }}">
                            <input type="hidden" id="firstName" name="firstName" value="{{$user[0]->prenom }}">
                            <input type="hidden" id="email" name="email" value="{{$user[0]->email }}">
                            <div>
                                <label>Voulez-vous envoyer un message de parrainage à {{$user[0]->prenom }} {{$user[0]->username }}?</label>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-success" name="send" value="send">
                                        @lang('lang.send')
                                    </button>
                                    <a href="{{ route('sponsoring.home') }}" class="btn btn-danger">
                                         @lang('lang.cancel')
                                    </a>
                                </div>
                            </div>
                        </form>
                    @elseif($response == false)
                        <div>
                            <label>@lang('lang.send.error.text')</label>
                        </div>
                        <a href="{{ route('sponsoring.email.form', ['id' => $user[0]->id]) }}" class="btn btn-success" >
                            @lang('lang.try.again')
                        </a>
                        <a href="{{ route('sponsoring.home') }}" class="btn btn-danger">
                            @lang('lang.cancel')
                        </a>
                    @else
                        <div>
                            <label>@lang('lang.message.send')</label>
                        </div>
                        <a href="{{ route('sponsoring.home') }}" class="btn btn-success">
                            @lang('lang.return')
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection