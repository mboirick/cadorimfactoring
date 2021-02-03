@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Update abonne</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="GET" action="{{ route('subscribers.send.form', ['id' => $id]) }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" id="idUser" name="idUser" value="{{$id }}">
                        <input type="hidden" id="type" name="type" value="rejet">
                        <label for="genre" class="col-md-4 control-label">Raison du rejet</label>
                        <input type="textarea" id="reason" name="reason" required> 

                    
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    @lang('lang.send')
                                </button>
                                <a href="{{ route('subscribers.edit', ['id' => $id]) }}" class="btn btn-danger">
                                    @lang('lang.cancel')
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