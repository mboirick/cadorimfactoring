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

                <div class="col-sm-10">@if(Session::has('message'))
                    <div class="alert alert-success text-center" style="margin-bottom: 0px; padding: 6px" role="alert">
                        {{Session::get('message')}}
                    </div>
                    @endif
                </div>
                <div class="col-sm-2"> <a href="{{ url('coupon/create') }}" class=" btn btn-primary"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Cree un promo </a></div>
            </div>
            <br>

            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                                <tr role="row" style="background: #000; color :#fff">
                                    <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Code Promo</th>
                                    <th width="3%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Remise</th>
                                    <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Type </th>
                                    <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Date expiration</th>

                                    <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Actif</th>
                                    <th width="5%" class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column descending" aria-sort="ascending">Utilisé</th>
                                    <th width="6%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Picture: activate to sort column descending" aria-sort="ascending">Action</th>

                            </thead>
                            <tbody>


                                @foreach ($promos as $indexKey =>$promo)


                                @if($indexKey % 2 )
                                <tr role="row" class="odd" style="background : #ddd">
                                    @else
                                <tr role="row" class="odd">
                                    @endif

                                    <td class="sorting_1"> {{$promo->coupon_code }} </td>
                                    @if($promo->amount_type =='Percentage')
                                    <td class="sorting_1"> {{$promo->amount }} %</td>
                                    @else
                                    <td class="sorting_1"> {{$promo->amount }} €</td>
                                    @endif
                                    <td class="sorting_1"> {{$promo->amount_type }} </td>
                                    <td class="hidden-xs"> {{$promo->expiry_date}}</td>
                                    <td class="sorting_1"> {{$promo->status}}</td>
                                    <td class="sorting_1"> {{$promo->used}}</td>

                                    <td class="hidden-xs">

                                        <a href="{{route('coupon.edit',$promo->id)}}" class="btn btn-success btn-mini">Edit</a>
                                        <a href="javascript:" rel="{{$promo->id}}" rel1="delete-coupon" class="btn btn-danger btn-mini deleteRecord">Delete</a>
                                    </td>

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
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($promos)}} of {{count($promos)}} entries</div>
                    </div>
                    <div class="col-sm-7">
                        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                            {{ $promos->appends(request()->input())->links() }}
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