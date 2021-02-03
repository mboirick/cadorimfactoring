@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('lang.text.form')</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('mail-send') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" id="idUser" name="idUser" value="{{$user[0]->id }}">

                        <div>
                            <label>@lang('lang.text.email')</label>
                            <input id="email" type="text" class="form-control" name="email" value="{{ $user[0]->email }}" readonly>
                            <label>@lang('lang.text.sujet')</label>
                            <input id="subject" type="text" class="form-control" name="subject" value="Parrainnage - CadoRIM">
                        </div>
                        <div>
                            <label>@lang('lang.text.body')</label>
                             <textarea id="body"  class="form-control" rows="20" cols="70" name="body"></textarea>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    @lang('lang.text.send')
                                </button>
                                <a href="/parrainage-management" class="btn btn-danger">
                                     @lang('lang.text.cancel')
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