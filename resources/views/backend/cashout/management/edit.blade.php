@extends('backend.layouts.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="box-header">
      <div class="row">
        <div class="col-sm-12">
          @if(Session::has('message'))
            <div class="alert alert-success text-center" style="margin-bottom: 10 px; padding: 6px" role="alert">
                {{Session::get('message')}}
            </div>
          @endif
        </div>
      </div>
    </div>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> <b>{{ $editecash[0]->username}} {{ $editecash[0]->prenom}} --({{ $editecash[0]->email}}) </b></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('cash.out.detail', ['id' => $editecash[0]->id_commande]) }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_user" value="{{$editecash[0]->id}}">
                        <input type="hidden" name="email" value="{{$editecash[0]->email}}">
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}" style="display: {{$editecash[0]->kyc==0 ? 'block': 'none' }}">
                            <div class="col-md-10">
                                <b style="color: red"> @lang('lang.warning') : </b> {{ $editecash[0]->nom_exp}} @lang('lang.send.documents'):
                            </div>
                            <div class="col-md-2">
                                <a  href="{{ route('cash.out.request', ['status'=>'proof', 'id' => $editecash[0]->id_commande]) }}" class="btn btn-primary">
                                    @lang('lang.request')
                                </a>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}" style="display: {{$editecash[0]->kyc==1 ? 'block': 'none' }}">
                            <div class="col-md-4">
                                @lang('lang.documents.provided') ({{ $editecash[0]->type_doc}}) :
                            </div>
                            <div class="col-md-8">
                                @foreach($documents as $key => $document)
                                    @lang('lang.document'){{$key}}
                                    <a href="{{route('cash.out.view.documet', ['id' => $document->id])}}" style="font-size:26px">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}" style="display: {{$editecash[0]->kyc==3 ? 'block': 'none' }}">
                            <div class="col-md-10">
                                <b style="color: red"> @lang('lang.warning') : </b> {{ $editecash[0]->nom_exp}} @lang('lang.document.check')
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success" name="operation" value="kyc_verifier">
                                    @lang('lang.check')
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <div class="col-md-8">
                                <b> Adresse : </b> {{ $editecash[0]->adress}} - {{ $editecash[0]->code_postal}} -{{ $editecash[0]->ville}} -{{ $editecash[0]->pays_residence}}
                            </div>
                            <div class="col-md-4">
                                <b> @lang('lang.phone') : </b> {{ $editecash[0]->phone}}
                            </div>
                        </div>
                        <hr>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <div class="col-md-8">
                                <b> @lang('lang.registered.beneficiary') : </b> {{$nombre_benef}} @lang('lang.people')
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <div class="col-md-3">
                                <b> @lang('lang.total') : </b> {{ $total}} @lang('lang.eur')
                                <input type="hidden" name="somme_eur" id="somme_eur" value=" {{ $editecash[0]->payment_amount }}">
                            </div>
                            <div class="col-md-6">
                                <b> @lang('lang.sum.last.months') : </b> {{ $troismois}} @lang('lang.eur')
                                <input type="hidden" name="somme_mru" id="somme_mru" value=" {{ $editecash[0]->somme_mru }}">
                            </div>
                            <div class="col-md-3">
                                <b>@lang('lang.transactions') : </b> {{$transaction}}
                            </div>
                        </div>
                        <hr>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <div class="col-md-4" style="text-align:center;">
                                <button type="submit" class="btn btn-info" name="operation" value="details">
                                    @lang('lang.details')
                                </button>
                            </div>
                            @if($displayProofIncome)
                            <div class="col-md-4">
                                <a href="{{ route('cash.out.request', ['status'=>'income', 'id' => $editecash[0]->id_commande]) }}" class="btn btn-primary">
                                    @lang('lang.request.proof.income')
                                </a>
                                </div>
                            @endif
                            <div class="col-md-4" style="text-align:center;">
                                <a href="{{ route('cash.out.home') }}" class="btn btn-danger">
                                    @lang('lang.return')
                                </a>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('lang.validation.order') : {{ $editecash[0]->id_commande}}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('cash.out.operation', ['id' => $editecash[0]->id_commande]) }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_user" value="{{$editecash[0]->id}}">
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}" style="display: {{$editecash[0]->kyc==3 ? 'block': 'none' }}">
                            <div class="col-md-10">
                                <b style="color: red"> @lang('lang.warning') : </b> {{ $editecash[0]->nom_exp}} @lang('lang.document.check')
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success" name="operation" value="kyc_verifier">
                                    @lang('lang.check')
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <div class="col-md-4">
                                <b> @lang('lang.sender') : </b> {{ $editecash[0]->nom_exp}}
                                <input type="hidden" name="email_exp" id="email_exp" value=" {{ $editecash[0]->mail_exp }}">
                            </div>
                            <div class="col-md-8">
                                <b> @lang('lang.beneficiary') : </b> {{ $editecash[0]->nom_benef}} ( {{ $editecash[0]->phone_benef}} -{{ $editecash[0]->adress_benef}} )
                            </div>
                        </div>
                        <hr>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <div class="col-md-4">
                                <b> Somme en (€-$) : </b> {{ $editecash[0]->payment_amount}} {{ $editecash[0]->payment_currency}}
                                <input type="hidden" name="somme_eur" id="somme_eur" value=" {{ $editecash[0]->payment_amount }}">
                            </div>
                            <div class="col-md-4">
                                <b> @lang('lang.sum.mru') : </b> {{ strrev(wordwrap(strrev($editecash[0]->somme_mru), 3, ' ', true))  }} @lang('lang.mru')
                                <input type="hidden" name="somme_mru" id="somme_mru" value=" {{ $editecash[0]->somme_mru }}">
                            </div>
                            <div class="col-md-4">
                                <b> @lang('lang.payment') : </b> {{ $editecash[0]->payment_type}} - {{ $editecash[0]->payment_status}}
                            </div>
                        </div>       
                        <hr>
                        <div class="col-row">
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <div class="col-md-2">
                                    <b> @lang('lang.pick.up.point')</b>
                                </div>
                                <div class="col-md-3">
                                    <select name="transfert_vers" id="transfert_vers" required>
                                        <option value="cadorim" {{$editecash[0]->point_retrait=='cadorim' ? 'selected': '' }}> @lang('lang.agency.cadorim')</option>
                                        <option value="5f63cc77d3b00" {{$editecash[0]->point_retrait =='5f63cc77d3b00' ? 'selected': '' }} > @lang('lang.gaza')</option>
                                        <option value="5f63cc55a65e5" {{$editecash[0]->point_retrait =='5f63cc55a65e5' ? 'selected': '' }}>@lang('lang.selibaby')</option>
                                        <option value="5f63cc98aafdd" {{$editecash[0]->point_retrait =='5f63cc98aafdd'? 'selected': '' }} >@lang('lang.tachout')</option>
                                        <option value="5f63ecf0810ef"{{$editecash[0]->point_retrait =='5f63ecf0810ef' ? 'selected': '' }} >@lang('lang.ould.yenja')</option>
                                        <option value="5fd7d9fc9075a"{{$editecash[0]->point_retrait =='5fd7d9fc9075a' ? 'selected': '' }} >Mauritanie</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                   @lang('lang.frais'): <input type="text" name="frais_gaza" id="frais_gaza" size="2" value=" {{ $editecash[0]->frais_gaza  }}" ><span style="  font-size:small;">@lang('lang.mru')</span>
                                </div>
                                <div class="col-md-3" style="display: {{$editecash[0]->point_retrait ? 'block': 'none' }}" id="transfert_gaza">
                                   @lang('agency.name'): <input type="text" name="agence_gaza" id="agence_gaza" size="9" value="{{ $editecash[0]->agence_gaza  }} " >
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                @if($editecash[0]->tracker_status!='retire')
                                    <button type="submit" class="btn btn-success" name="operation" value="retire" id='retrait_button'>
                                        @lang('lang.withdrawal')
                                    </button>-
                                    @if($editecash[0]->tracker_status!='transfert')
                                    <button type="submit" class="btn btn-warning" name="operation" value="transfert" id='transfert_button'>
                                        @lang('lang.transfer')
                                    </button>
                                    -
                                    @endif
                                    <a href="{{ route('cash.out.request', ['status'=>'reminder', 'id' => $editecash[0]->id_commande]) }}" class="btn btn-primary">
                                    @lang('lang.email')
                                    </a>
                                    -
                                @endif
                                <a href="{{ route('cash.out.home') }}" class="btn btn-danger">@lang('lang.return')</a>
                            </div>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection