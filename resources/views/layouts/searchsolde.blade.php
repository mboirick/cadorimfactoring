<div class="box box-default">
  <div class="box-header with-border">
  
    <div class="row">
      <div class="col-md-6">
        {{ $slot }}
      </div>
      <div class="col-md-2">

        <button type="submit" class="btn btn-warning" name="search" value="recherche">
          <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
          Search
        </button>
        

        @if(Auth::user()->user_type=='admin')
        |
        <button type="submit" class="btn btn-primary" name="search" value="excel">
          <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
         To Excel
        </button>
        @endif

      </div>
      <div class="col-md-2">

        <!-- <a class="btn btn-success" href="{{ route('depotview')}}">
          <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
          Dépôt
        </a>
        |
        <a class="btn btn-danger" href="{{ route('retraitview')}}">
          <span class="glyphicon glyphicon-export" aria-hidden="true"></span>
          Retrait
        </a> -->

      </div>
      
      <div class="col-md-1">

        <a class="btn btn-primary" href="{{ route('paiement-management.addclient') }}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Compte</a>

      </div>
    </div>
  </div>
  <!-- /.box-header -->


</div>