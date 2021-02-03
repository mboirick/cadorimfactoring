@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('lang.add.documents')</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="post" action="{{route('cash.flow.add.files', ['idCash'=>$idCash])}}" enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="input-group hdtuto control-group lst increment" >
                            <input type="file" name="filenames[]" class="myfrm form-control">
                            <br/><br/>
                            <button class="btn btn-success btn-add-fil" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                        </div>
                        <br/><br/>
                        <button type="@lang('lang.add')" class="btn btn-success" >
                            @lang('lang.add')
                        </button>

                        <a href="{{ route('cash.flow.home') }}" class="btn btn-danger">
                            @lang('lang.cancel')
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
