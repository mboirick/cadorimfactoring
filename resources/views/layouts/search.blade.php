<div class="box box-default">
  <div class="box-header with-border">
    <h3 class="box-title">{{isset($title) ? $title : 'Recherche'}}</h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    {{ $slot }}
  </div>
  <!-- /.box-body -->
  <div class="box-footer">
    <button type="submit"  class="btn btn-warning" name="search" value="recherche">
      <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
      Search
    </button>
  @if(Auth::user()->user_type =='admin' || Auth::user()->user_type =='cash' )
    <button type="submit" class="btn btn-primary" name="search" value="excel">
      <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
      To Excel
    </button>
    @endif
  </div>
</div>