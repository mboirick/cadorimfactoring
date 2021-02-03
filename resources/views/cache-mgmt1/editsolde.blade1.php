@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Validation de la commande : {{ $editecash[0]->id_commande}} </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('cache-management.update', ['id' => $editecash[0]->id_commande]) }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            

                            <div class="col-md-4">
                           <b> Expediteur : </b>  {{ $editecash[0]->nom_exp}}
                            </div>

                            

                            <div class="col-md-8">
                            <b> Beneficiaire : </b> {{ $editecash[0]->nom_benef}} ( {{ $editecash[0]->phone_benef}} -{{ $editecash[0]->adress_benef}} )
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            

                            <div class="col-md-4">
                           <b> Somme en (€-$) : </b>  {{ $editecash[0]->payment_amount}} {{ $editecash[0]->payment_currency}} 
                            </div>

                            

                            <div class="col-md-4">
                            <b> Somme en MRU : </b>  {{   strrev(wordwrap(strrev($editecash[0]->somme_mru), 3, ' ', true))  }} MRU
                            <input type="hidden" name="somme_mru" id="somme_mru" value=" {{ $editecash[0]->somme_mru }}">
                            </div>

                            <div class="col-md-4">
                            <b> Paiement : </b>  {{ $editecash[0]->payment_type}} - {{ $editecash[0]->payment_status}} 
                            </div>
                        </div>

                        <div class="col-row"><div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            

                            <div class="col-md-4">
                           <b> frais Ghaza : </b> 
                            </div>

                            <div class="col-md-4">
                            <input type="text" name="frais_gaza" id="frais_gaza" value=" {{ $Ghaza }}">  <b>  MRU </b>
                            </div>
                            <div class="col-md-4">
                            <b>  Agence </b> <input type="text" name="agence_gaza" id="agence_gaza" value=" "> 
                            </div>
                        </div>
</div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success"  name="operation" value="retrait" >
                                    Retrait
                                </button>-
                                <button type="submit" class="btn btn-warning"  name="operation" value="transfert" >
                                Transfert
                                </button>
                                -
                                <button type="submit" class="btn btn-primary"  name="operation" value="email" >
                                Email
                                </button>
                                -
                                <a href="/cache-management/cashout" class="btn btn-danger">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
