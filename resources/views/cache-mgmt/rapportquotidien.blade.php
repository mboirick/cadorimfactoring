@extends('cache-mgmt.base')
@section('action-content')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-6">
      <div class="box">
        <div class="box-header with-border" style="background-color: #dd4b39; color: white">
          <h3 class="box-title">Cashes OUT (Rétrait)</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table table-bordered">
            <tr>

              <th>Operateur</th>
              <th>Nbr</th>
              <th>Local</th>
              <th>Transfert</th>
              <th>Gaza</th>
              <th>Total</th>
            </tr>

            @foreach($cachesout as $key => $cache)
            @if($key=='Total')
            <tr style="font-weight: bold;">
              @else
            <tr>
              @endif
              <td>{{ $key}}</td>
              <td>{{ $cache -> nbr}}</td>
              <td>
                {{number_format($cache -> somme_local)}} <span style="  font-size:xx-small;">MRU</span>
              </td>
              <td>
                {{number_format($cache -> somme)}} <span style="  font-size:xx-small;">MRU</span>
              </td>
              <td> {{number_format($cache -> somme_gaza)}} <span style="  font-size:xx-small;">MRU</span></td>
              <td> {{number_format($cache -> somme + $cache -> somme_gaza)}} <span style="  font-size:xx-small;">MRU</span></td>
            </tr>
            @endforeach


          </table>

        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div>
      <!-- /.box -->

      <div class="box">
        <div class="box-header" style="background-color: #f0ad4e; color:white">
          <h3 class="box-title">Soldes Cadorim</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
          <table class="table table-bordered">
            <tr>

              <th>Compte</th>

              <th>Solde debut journee</th>
              <th>solde avant </th>
              <th>Dernier solde</th>

            </tr>
            
            @php $total=0 @endphp
            @for( $y=0; $y < count($solde_cadorim)/4 ;  $y ++)
            <tr>
              <td>{{ $solde_cadorim['compte'.$y]}}</td>

              <td>
              {{ number_format(floor($solde_cadorim['soldeDebut'.$y])) }} <span style="  font-size:xx-small;">MRU</span>
              </td>
              <td>
              {{ number_format(floor($solde_cadorim['soldeFin'.$y])) }} <span style="  font-size:xx-small;">MRU</span>
              </td>

              <td>
              {{ number_format(floor($solde_cadorim['soldeActuel'.$y])) }} <span style="  font-size:xx-small;">MRU</span>
              </td>
            </tr>
            @php $total=$total + intval ($solde_cadorim['soldeActuel'.$y]) @endphp
            @endfor
           

            <tr style="font-weight: bold;">
              <td colspan="3" >Solde total restant:</td>

              <td>
                {{number_format($total)}} <span style="  font-size:xx-small;">MRU</span>
              </td>

            </tr>


          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-6">
      <div class="box">
        <div class="box-header with-border" style="background-color: #00a65a; color: white">
          <h3 class="box-title">Cashes IN (Dépot)</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table table-bordered">
            <tr>

              <th>Operateur</th>

              <th>Client</th>
              <th>Somme déposée</th>

            </tr>
            @php $total=0 @endphp
            @foreach($cashs_in as $key => $cache)
            <tr>
              <td>{{ $cache -> expediteur}}</td>

              <td>
                {{$cache -> nom_benef}}
              </td>
              <td>
                {{number_format($cache -> montant)}} <span style="  font-size:xx-small;">MRU</span>
              </td>

            </tr>
            @php $total=$total + $cache -> montant @endphp
            @endforeach

            <tr style="font-weight: bold;">
              <td>Total</td>

              <td>
              </td>
              <td>
                {{number_format($total)}} <span style="  font-size:xx-small;">MRU</span>
              </td>

            </tr>


          </table>

        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">

        </div>
      </div>
      <!-- /.box -->

      <div class="box">
        <div class="box-header" style="background-color: #337ab7; color:white">
          <h3 class="box-title">Soldes Clients Business</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
          <table class="table table-bordered">
            <tr>

              <th>Client</th>

              <th>Debut €</th>
              <th>Fin €</th>
              <th>Debut MRU</th>
              <th>Fin MRU</th>
            </tr>
            @php $totalDEur=0 ;  $totalFEur=0 ; $totalDMru=0;  $totalFMru=0 @endphp

            @for( $y=0; $y < count($solde_client)/5 ;  $y ++)
            <tr>
              <td>{{ $solde_client['email'.$y]}}</td>

              <td>
              {{ number_format(floor($solde_client['soldeDebutEur'.$y])) }} <span style="  font-size:xx-small;">€</span>
              </td>
              <td>
              {{ number_format(floor($solde_client['soldeFinEur'.$y])) }} <span style="  font-size:xx-small;">€</span>
              </td>

              <td>
              {{ number_format(floor($solde_client['soldeDebutMru'.$y])) }} <span style="  font-size:xx-small;">MRU</span>
              </td>
              <td>
              {{ number_format(floor($solde_client['soldeFinMru'.$y])) }} <span style="  font-size:xx-small;">MRU</span>
              </td>

            </tr>

            @php $totalDEur=$totalDEur + intval ($solde_client['soldeDebutEur'.$y]) ;  $totalFEur=$totalFEur + intval ($solde_client['soldeFinEur'.$y])  ;
             $totalDMru=$totalDMru + intval ($solde_client['soldeDebutMru'.$y]) ;  $totalFMru=$totalFMru  + intval ($solde_client['soldeFinMru'.$y])   @endphp
            @endfor

            <tr style="font-weight: bold;">
              <td>Total</td>

              <td>  {{number_format($totalDEur)}} <span style="  font-size:xx-small;">€</span>
              </td>
              <td>
                {{number_format($totalFEur)}} <span style="  font-size:xx-small;">€</span>
              </td>
              <td>  {{number_format($totalDMru)}} <span style="  font-size:xx-small;">MRU</span>
              </td>
              <td>
                {{number_format($totalFMru)}} <span style="  font-size:xx-small;">MRU</span>
              </td>

            </tr>


          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
  <!-- <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header" style="background-color: black; color:white">
          <h3 class="box-title">Les rapports par jours</h3>

          <div class="box-tools">
            <div class="input-group input-group-sm" style="width: 150px;">
              <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

              <div class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>
        </div>
     
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tr>
              <th>ID</th>
              <th>User</th>
              <th>Date</th>
              <th>Status</th>
              <th>Reason</th>
            </tr>
            <tr>
              <td>183</td>
              <td>John Doe</td>
              <td>11-7-2014</td>
              <td><span class="label label-success">Approved</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>219</td>
              <td>Alexander Pierce</td>
              <td>11-7-2014</td>
              <td><span class="label label-warning">Pending</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>657</td>
              <td>Bob Doe</td>
              <td>11-7-2014</td>
              <td><span class="label label-primary">Approved</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
            <tr>
              <td>175</td>
              <td>Mike Doe</td>
              <td>11-7-2014</td>
              <td><span class="label label-danger">Denied</span></td>
              <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
            </tr>
          </table>
        </div>
       
      </div>
     
    </div>
  </div> -->
</section>
<!-- /.content -->
</div>
@endsection