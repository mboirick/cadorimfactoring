@extends('backend.layouts.app')
@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <b>
            {{ Auth::user()->lastname}} {{ Auth::user()->firstname}}
        </b>
        <ol class="breadcrumb">
            <!-- li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li-->
        </ol>
    </section>

    @yield('action-content')
    <!-- /.content -->
  </div>
@endsection