@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row justify-content-center">
        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
     
        <div class="row">
            <div class="col-lg-8 margin-tb">
                <div class="pull-left">
                    <h2>@lang('lang.role.management')</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('roles.create') }}"> @lang('lang.create.new.role')</a>
                </div>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif


        <table class="table table-bordered">
            <tr role="row" style="background: #000; color :#fff">
                <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">@lang('lang.name') </th>
                <th width="6%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">@lang('lang.action') </th>
            </tr>
            @foreach ($roles as $key => $role)
            <tr>
            @if($key % 2 )
            <tr role="row" class="odd" style="background : #ddd">
                @else
            <tr role="row" class="odd">
            @endif
                <td>{{ $role->name }}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('roles.show',$role->id) }}">@lang('lang.show')</a>
                    <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}">@lang('lang.edit')</a>
        
                    {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Supprimer', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                   
                </td>
            </tr>
            @endforeach
        </table>

        </div>
    </div>
</div>
{!! $roles->render() !!}

@endsection