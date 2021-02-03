@extends('cache-mgmt.base')
@section('action-content')
<!-- Main content -->
<section class="content">



    <div class="box">
        <div class="box-header">

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-4"></div>
                <div class="col-sm-4"></div>
            </div>
            <form method="POST" action="{{route('searchsondage')}}">
                {{ csrf_field() }}
                @component('sondage-mgmt.searchsondage', ['title' => 'Search'])
                @component('sondage-mgmt.sondage-search-row', ['items' => ['Nom Sondage', 'date', 'xxx'],
                'oldVals' => [isset($searchingVals) ? $searchingVals['Societe'] : '', isset($searchingVals) ? $searchingVals['Email'] : '' , isset($searchingVals) ? $searchingVals['Telephone'] : '']])
                @endcomponent
                @endcomponent
            </form>
            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-12">

                        @if($sondages->total())

                        <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                                <tr role="row" style="background: #000; color :#fff">
                                    <th width="4%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Date</th>
                                    <th width="8%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Titre sondage</th>
                                    <th width="8%" class="" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">nombre envoie</th>
                                    <th width="8%" class=" hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthdate: activate to sort column ascending">nombre de reponse</th>

                                    <th width="8%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">operation</th>

                                    <th width="6%" class="   hidden-xs" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="HiredDate: activate to sort column ascending">Statistiques </th>

                                </tr>
                            </thead>
                            <tbody>




                                @foreach ($sondages as $indexKey => $sondage)
                                @if($indexKey % 2 )
                                <tr role="row" class="odd" style="background : #ddd">
                                    @else
                                <tr role="row" class="odd">
                                    @endif


                                    <td class="sorting_1"> </td>
                                    <td class="sorting_1"> {{ $sondage -> id_sondage}}</td>
                                    <td class="hidden-xs">{{$envoie}}</td>

                                    <td class="hidden-xs">{{ $reponse}}</td>

                                    <!-- <td class="hidden-xs"> <a href=" {{route('sondage-management.voir',['id_sondages' => $sondage -> id_sondage ])}}" class="btn btn-success">
                                            voir -->

                                    <td class="hidden-xs"> <a href=" {{route('sondage-reponses',['id_sondages' => $sondage -> id_sondage ])}}" class="btn btn-success">
                                            Reponses
                                        </a> | <a href=" {{ route('sondage-emailing', ['id_sondages' => $sondage -> id_sondage ])}}" class="btn btn-warning">
                                            Emailing
                                        </a></td>
                                    <td class="hidden-xs">


                                        <a href=" " class="btn btn-primary">
                                            Details
                                        </a>

                                    </td>

                                </tr>
                                @endforeach

                            </tbody>

                            <tfoot>
                                <thead>

                            </tfoot>
                        </table>
                        @else
                        <h3 align='center' style="color: #dd4b39;"> Aucun sondage n'a été trouvé</h3>


                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($sondages)}} of {{count($sondages)}} entries</div>
                    </div>
                    <div class="col-sm-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            {{ $sondages->links() }}
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