@extends('cache-mgmt.base')
@section('action-content')
<!-- Main content -->
<section class="content">




  <div class="box">
    <div class="box-header">
      <div class="row">

        <div class="col-sm-4">


        </div>

        <div class="col-sm-4">

        </div>

        <div class="col-sm-4">



        </div>

      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div class="row">
        <div class="col-sm-2"><a href="{{ route('agencies.home')}}" class=" btn btn-success"> Retour</a></div>
        <div class="col-sm-6"></div>
        <div class="col-sm-4"> </div>
      </div>
      <br>

      <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
          <div class="col-sm-12">
            <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
              <thead>
                <tr role="row" style="background: #000; color :#fff">
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Jours</th>
                  <th width="3%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Agence</th>
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Nombre d'operation</th>
                  <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Somme €</th>
             
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Somme MRU</th>
                  <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Frais Gaza</th>

                  <th width="5%" class="sorting hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Détails</th>
              </thead>
              <tbody>


                @foreach ($operations as $indexKey =>$operation)

	
               <tr role="row" class="odd" style="background : #fff">
                  <td class="sorting_1">{{$operation->jours }}
                    
                </td>
                  <td class="sorting_1">{{$operation->email_agence }} </td>
                  <td class="sorting_1">
                  {{$operation->nbr_operation }} 

                  </td>
                  
                  <td class="hidden-xs"> {{ floor($operation->total_euro)   }} €</td>
          
                  <td class="hidden-xs"> {{ floor ($operation->total) }} MRU</td>

                  <td class="hidden-xs"> {{ floor ($operation->total_gaza) }} MRU</td>
              
                  <td class="hidden-xs"><a href=" {{ route('agence-management.details', ['id' => $operation->id_agence , 'jour' => $operation->jours ]) }}" class="btn btn-info">
                      Details
                    </a></td>

                </tr>
                @endforeach
              </tbody>
              <tfoot>

                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-5">
            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($operations)}} of {{count($operations)}} entries</div>
          </div>
          <div class="col-sm-7">
            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
              {{ $operations->appends(request()->input())->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.box-body -->
  </div>
</section>
<!-- /.content -->
</div>
@endsection