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

            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-12">

                        @if($reponses->total())

                        <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                                <tr role="row" style="background: #000; color :#fff">
                                    <th width="2%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Q</th>
                                    <th width="20%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Question</th>
                                    <th width="20%" class="" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Reponse</th>
                                                                       
                                </tr>
                            </thead>
                            <tbody>




                                @foreach ($reponses as $indexKey => $reponse)

                                @if($reponse -> id_question =='q1' )
                                <tr style="background : #ddd" >
                                    <td colspan="6"> <b>{{ $reponse -> username}}___{{ $reponse -> id_client}}__{{ $reponse -> phone}}__{{ $reponse -> pays_residence}}</b>   </td>
                                </tr>
                                    @else
                                
                                <tr role="row" class="odd">
                                    @endif


                                    <td class="sorting_1"> {{ $reponse -> id_question}}</td>
                                   
                                    <td class="sorting_1"> {{ $reponse -> text_question}}</td>

                                    @if($reponse -> id_question =='q5' &&  $reponse -> response!='-' )
                                    <td class="hidden-xs">{{ str_replace("-", "", $reponse -> response) +1  }} Etoiles</td>
                                    @else
                                    <td class="hidden-xs">{{ $reponse -> response}}</td>
                                    @endif
                                  
                                </tr>
                                @endforeach

                            </tbody>

                            <tfoot>
                                <thead>

                            </tfoot>
                        </table>
                        @else
                        <h3 align='center' style="color: #dd4b39;"> Aucune reponse n'a été trouvée</h3>


                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($reponses)}} of {{count($reponses)}} entries</div>
                    </div>
                    <div class="col-sm-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            {{ $reponses->links() }}
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