@extends('cache-mgmt.base')
@section('action-content')
<!-- Main content -->
@if(Auth::user()->user_type=='admin' || Auth::user()->user_type=='client' || Auth::user()->user_type=='operateur' )
<section class="content">

  <div class="box">

    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4"></div>
      </div>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">{{isset($title) ? $title : 'Envoie de message'}}</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            {{$message}}  
        </div>
</section>
<!-- /.content -->
</div>
@endif
@endsection