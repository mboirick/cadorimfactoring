@extends('backend.layouts.base')

@section('action-content')
<div class="container">
@if(Session::has('message'))
            <div class="alert alert-success text-center" role="alert">
                <strong>Succès </strong> {{Session::get('message')}}
            </div>
        @endif
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Ajouter un nouveau coupon</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{route('reduce.store')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="amount" class="col-md-4 control-label">Montant</label>

                            <div class="col-md-6">
                            <div class="controls{{$errors->has('amount')?' has-error':''}}">
                                <input type="number" min="0" name="amount" id="amount" class="form-control" value="{{old('amount')}}" title="" required="required" >
                                <span class="text-danger">{{$errors->first('amount')}}</span>
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="amount_type" class="col-md-4 control-label">Type</label>
                            <div class="col-md-6">
                            <div class="controls{{$errors->has('amount_type')?' has-error':''}}">
                                <select name="amount_type" id="amount_type" class="form-control" >
                                    <option value="Percentage">Pourcentage (%)</option>
                                    <option value="Montant">Monant (€)</option>
                                </select>
                                <span class="text-danger">{{$errors->first('amount_type')}}</span>
                            </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="expiry_date" class="col-md-4 control-label">Date d'expiration</label>

                            <div class="col-md-6">
                            <div class="controls{{$errors->has('expiry_date')?' has-error':''}}">
                                <div class="input-prepend">
                                    <div data-date="12-02-2012" class="input-append date datepicker">
                                        <input type="text" name="expiry_date" id="expiry_date" value="{{old('expiry_date')}}" data-date-format="yyyy-mm-dd" class="span11"  placeholder="yyyy-mm-dd">
                                        <span class="add-on"><i class="icon-th"></i></span>
                                    </div>
                                </div>
                                <span class="text-danger">{{$errors->first('expiry_date')}}</span>
                            </div>
                            </div>
                        </div>
                        <div class="form-group{{$errors->has('status')?' has-error':''}}">
                            <label class="col-md-4 control-label">Active :</label>

                            <div class="col-md-6">
                            <div class="controls">
                                <input type="checkbox" name="status" id="status" value="1">
                                <span class="text-danger">{{$errors->first('status')}}</span>
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-4 control-label"></label>
                            <div class="controls">
                                <button type="submit" class="btn btn-success">Valider</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection