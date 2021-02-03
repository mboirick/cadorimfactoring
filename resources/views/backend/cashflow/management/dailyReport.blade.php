@extends('backend.layouts.base')))
@section('action-content')
<!-- Main content -->
<section class="content">
  <div class="row">
      <form method="get" action="{{ route('cash.flow.daily.report') }}">
          {{ csrf_field() }}
          <div class="box box-default">
              <div class="box-header with-border">
                  <h3 class="box-title">{{isset($title) ? $title : 'Recherche'}}</h3>
                  <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                  </div>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                  <div class="row">
                      <div class="col-md-2">
                          <div class="form-group">
                              <div class="col-md-12">
                                  <div class="input-group date">
                                      <div class="input-group-addon">
                                          <i class="fa fa-calendar"></i>
                                      </div>
                                      <input type="date" autocomplete="off"  value="{{$date}}" name="dateSearch" class="form-control pull-right" id="dateSearch" required>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                  <button type="submit"  class="btn btn-warning" name="search" value="submitSearch">
                      <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                      @lang('lang.search')
                  </button>
              </div>
          </div>
      </form>
    <div class="col-md-6">
      <div class="box">
        <div class="box-header with-border" style="background-color: #dd4b39; color: white">
          <h3 class="box-title">@lang('cashFlow.cashes.out')</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table table-bordered">
            <tr>
              <th>@lang('lang.operator')</th>
              <th>@lang('lang.numbre')</th>
              <th>@lang('lang.local')</th>
              <th>@lang('lang.transfer')</th>
              <th>@lang('lang.gaza')</th>
              <th>@lang('lang.total')</th>
            </tr>
            @foreach($cachesout as $key => $cache)
                <tr>
                    <td>{{ $cache -> expediteur}}</td>
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
            @if( $cashOutTotal )
              <tr style="font-weight: bold;">
                  <td>Total</td>
                  <td>{{ $cashOutTotal -> nbr}}</td>
                  <td>
                    {{number_format($cashOutTotal -> somme_local)}} <span style="  font-size:xx-small;">MRU</span>
                  </td>
                  <td>
                    {{number_format($cashOutTotal -> somme)}} <span style="  font-size:xx-small;">MRU</span>
                  </td>
                  <td> {{number_format($cashOutTotal -> somme_gaza)}} <span style="  font-size:xx-small;">MRU</span></td>
                  <td> {{number_format($cashOutTotal -> somme + $cashOutTotal -> somme_gaza)}} <span style="  font-size:xx-small;">MRU</span></td>
              </tr>
            @endif
          </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
        </div>
      </div>
      <!-- /.box -->
      <div class="box">
        <div class="box-header" style="background-color: #f0ad4e; color:white">
          <h3 class="box-title">@lang('lang.balance.cadorim')</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
          <table class="table table-bordered">
            <tr>
              <th>@lang('lang.account')</th>
              <th>@lang('lang.start.day.balance')</th>
              <th>@lang('lang.last.balance')</th>
            </tr>

            @php $total=0 @endphp
            @if($balanceCadorim)
              <tr>
                <td>
                    @if($balanceCadorim->email)
                        {{ $balanceCadorim->email}}
                    @else
                        @lang('lang.cadorim')
                    @endif
                </td>
                <td>
                  {{ number_format(floor($balanceCadorim->start)) }} <span style="  font-size:xx-small;">@lang('lang.mru')</span>
                </td>
                <td>
                  {{ number_format(floor($balanceCadorim->solde)) }} <span style="  font-size:xx-small;">@lang('lang.mru')</span>
                </td>
              </tr>
              @php $total=$total + intval ($balanceCadorim->solde) @endphp
            @endif

              @for( $y=0; $y < count($solde_cadorim)/4 ;  $y ++)
                  <tr>
                      <td>{{ $solde_cadorim['compte'.$y]}}</td>

                      <td>
                          {{ number_format(floor($solde_cadorim['soldeDebut'.$y])) }} <span style="  font-size:xx-small;">MRU</span>
                      </td>
                      <td>
                          {{ number_format(floor($solde_cadorim['soldeActuel'.$y])) }} <span style="  font-size:xx-small;">MRU</span>
                      </td>
                  </tr>
                  @php $total=$total + intval ($solde_cadorim['soldeActuel'.$y]) @endphp
              @endfor
            <tr style="font-weight: bold;">
              <td colspan="2" >@lang('lang.total.remaining.balance')</td>
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
          <h3 class="box-title">@lang('cashFlow.cashes.int')</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table table-bordered">
            <tr>
              <th>@lang('lang.operator')</th>
              <th>@lang('lang.customer')</th>
              <th>@lang('lang.amount.deposited')</th>
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
              <td>@lang('lang.total')</td>
              <td>
              </td>
              <td>
                {{number_format($total)}} <span style="  font-size:xx-small;">@lang('lang.mru')</span>
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
          <h3 class="box-title">@lang('lang.business.customers.sales')</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
          <table class="table table-bordered">
            <tr>
              <th>@lang('lang.customer')</th>
              <th>@lang('lang.start') @lang('lang.euros')</th>
              <th>@lang('lang.end') @lang('lang.euros')</th>
              <th>@lang('lang.start') @lang('lang.mru')</th>
              <th>@lang('lang.end') @lang('lang.mru')</th>
            </tr>
            @php $totalDEur=0 ;  $totalFEur=0 ; $totalDMru=0;  $totalFMru=0 @endphp
            @foreach($balancesClients as $balanceClient)
              <tr>
                <td>{{ $balanceClient->firstname}}</td>
                <td>
                  {{ number_format(floor($balanceClient->solde_avant_euros)) }} <span style="  font-size:xx-small;">@lang('lang.euros')</span>
                </td>
                <td>
                  {{ number_format(floor($balanceClient->solde_euros)) }} <span style="  font-size:xx-small;">@lang('lang.euros')</span>
                </td>

                <td>
                  {{ number_format(floor($balanceClient->solde_avant_mru)) }} <span style="  font-size:xx-small;">@lang('lang.mru')</span>
                </td>
                <td>
                  {{ number_format(floor($balanceClient->solde_mru)) }} <span style="  font-size:xx-small;">@lang('lang.mru')</span>
                </td>
              </tr>
              @php
                $totalDEur = $totalDEur + intval ($balanceClient->solde_avant_euros) ;
                $totalFEur = $totalFEur + intval ($balanceClient->solde_euros)  ;
                $totalDMru = $totalDMru + intval ($balanceClient->solde_avant_mru) ;
                $totalFMru = $totalFMru  + intval ($balanceClient->solde_mru)
              @endphp
            @endforeach
            <tr style="font-weight: bold;">
              <td>@lang('lang.total')</td>

              <td>  {{number_format($totalDEur)}} <span style="  font-size:xx-small;">@lang('lang.euros')</span>
              </td>
              <td>
                {{number_format($totalFEur)}} <span style="  font-size:xx-small;">@lang('lang.euros')</span>
              </td>
              <td>  {{number_format($totalDMru)}} <span style="  font-size:xx-small;">@lang('lang.mru')</span>
              </td>
              <td>
                {{number_format($totalFMru)}} <span style="  font-size:xx-small;">@lang('lang.mru')</span>
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
</section>
<!-- /.content -->
</div>
@endsection