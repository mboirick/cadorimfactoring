@extends('cache-mgmt.base')

@section('action-content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('lang.task.form')</div>

                @if($confirmation =='ok' )
                    <label for="start">@lang('lang.task.add')</label><br/>
                    <a href="/parrainage-management/formulaire-planifier-envoie-courriel" class="btn btn-primary">
                      <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
                        @lang('lang.return')
                    </a>                                                    
                @else
                    
                <label for="start" id="erreurlabel"></label>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="GET" action="{{ route('parrainage-management.formulaire-planifier-envoie-courriel') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div>
                            <input type="checkbox" name="all" id="checkall" value="1" onclick="docheckall()">
                            <label for="all">@lang('lang.all')</label><br>
                        </div>
                        <div>
                            <label >@lang('lang.date.start')</label>
                            <input type="date" id="startdate" name="startdate" onchange="uncheck()">
                            <label>@lang('lang.date.end')</label>
                            <input type="date" id="enddate" name="enddate" onchange="uncheck()">
                        </div>
                        <div>
                            <label for="start">@lang('lang.numbre.sponsorship')</label>
                            <input type="text" id="numbre" name="numbre" onchange="uncheck()">
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success" name="add" value="add" onClick="return empty()">
                                    @lang('lang.add')
                                </button>
                                <a href="/parrainage-management" class="btn btn-danger">
                                    @lang('lang.cancel')
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>

@endsection