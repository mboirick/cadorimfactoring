@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> <b>{{ $editecash[0]->username}} {{ $editecash[0]->prenom}} --({{ $editecash[0]->email}}) </b></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('cashout-management.infos', ['id' => $editecash[0]->id_commande]) }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_user" value="{{$editecash[0]->id}}">
                        <input type="hidden" name="email" value="{{$editecash[0]->email}}">


                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}" style="display: {{$editecash[0]->kyc==0 ? 'block': 'none' }}">


                            <div class="col-md-10">

                                <b style="color: red"> Attention : </b> {{ $editecash[0]->nom_exp}} n'a pas encore envoyé ces documents d'identité ou peut etre pas encore approuvé:

                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary" name="operation" value="kyc_demande">
                                    Demander
                                </button>
                            </div>


                        </div>
                        
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}" style="display: {{$editecash[0]->kyc==1 ? 'block': 'none' }}">

                            <div class="col-md-4">

                                Les documents frounis ({{ $editecash[0]->type_doc}}) :

                            </div>
                            <div class="col-md-8">

                                @foreach($documents as $key => $document)
                                Document{{$key}}
                                <a href="{{route('visualiser', ['id' => $document->id])}}" style="font-size:26px">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>

                                @endforeach

                            </div>


                        </div>
                      
                        
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}" style="display: {{$editecash[0]->kyc==3 ? 'block': 'none' }}">


                            <div class="col-md-10">

                                <b style="color: red"> Attention : </b> {{ $editecash[0]->nom_exp}} a déja envoyé ces documents d'identité, verifier pour approuver.

                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success" name="operation" value="kyc_verifier">
                                    Verifier
                                </button>
                            </div>


                        </div>
                        <hr>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">

                            <div class="col-md-8">
                                <b> Adresse : </b> {{ $editecash[0]->adress}} - {{ $editecash[0]->code_postal}} -{{ $editecash[0]->ville}} -{{ $editecash[0]->pays_residence}}
                            </div>

                            <div class="col-md-4">
                                <b> Phone : </b> {{ $editecash[0]->phone}}
                            </div>

                        </div>

                        <hr>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">

                            <div class="col-md-8">
                                <b> Nombre de bénéficiaire enregisté : </b> {{$nombre_benef}} Personnes
                            </div>

                            <div class="col-md-4">

                            </div>

                        </div>
                        <hr>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">


                            <div class="col-md-3">
                                <b> Total : </b> {{ $total}} EUR
                                <input type="hidden" name="somme_eur" id="somme_eur" value=" {{ $editecash[0]->payment_amount }}">
                            </div>


                            <div class="col-md-6">
                                <b> Somme (trois derniers mois) : </b> {{ $troismois}} EUR
                                <input type="hidden" name="somme_mru" id="somme_mru" value=" {{ $editecash[0]->somme_mru }}">
                            </div>

                            <div class="col-md-3">
                                <b>Transactions : </b> {{$transaction}}
                            </div>
                        </div>
                        <hr>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">


                            <div class="col-md-4" style="text-align:center;">
                                <button type="submit" class="btn btn-info" name="operation" value="details">
                                    Details
                                </button>
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary" name="operation" value="revenu">
                                    Demander un justificatif de revenus
                                </button>
                            </div>

                            <div class="col-md-4" style="text-align:center;">
                                <button type="submit" class="btn btn-danger" name="operation" value="">
                                    xxx
                                </button>
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
                <div class="panel-heading">Validation de la commande : {{ $editecash[0]->id_commande}}</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('cashout-management.update', ['id' => $editecash[0]->id_commande]) }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_user" value="{{$editecash[0]->id}}">

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}" style="display: {{$editecash[0]->kyc==3 ? 'block': 'none' }}">


                            <div class="col-md-10">

                                <b style="color: red"> Attention : </b> {{ $editecash[0]->nom_exp}} a déja envoyé ces documents d'identité, verifier pour approuver.

                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success" name="operation" value="kyc_verifier">
                                    Verifier
                                </button>
                            </div>


                        </div>
                        <br>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">


                            <div class="col-md-4">
                                <b> Expediteur : </b> {{ $editecash[0]->nom_exp}}
                                <input type="hidden" name="email_exp" id="email_exp" value=" {{ $editecash[0]->mail_exp }}">
                            </div>



                            <div class="col-md-8">
                                <b> Beneficiaire : </b> {{ $editecash[0]->nom_benef}} ( {{ $editecash[0]->phone_benef}} -{{ $editecash[0]->adress_benef}} )
                            </div>
                        </div>

                       
                        <hr>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">


                            <div class="col-md-4">
                                <b> Somme en (€-$) : </b> {{ $editecash[0]->payment_amount}} {{ $editecash[0]->payment_currency}}
                                <input type="hidden" name="somme_eur" id="somme_eur" value=" {{ $editecash[0]->payment_amount }}">
                            </div>

                            <div class="col-md-4">
                                <b> Somme en MRU : </b> {{ strrev(wordwrap(strrev($editecash[0]->somme_mru), 3, ' ', true))  }} MRU
                                <input type="hidden" name="somme_mru" id="somme_mru" value=" {{ $editecash[0]->somme_mru }}">
                            </div>

                            <div class="col-md-4">
                                <b> Paiement : </b> {{ $editecash[0]->payment_type}} - {{ $editecash[0]->payment_status}}
                            </div>
                        </div>

                       
                        <hr>

                        <div class="col-row">
                            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                <div class="col-md-2">
                                    <b> Point retrait</b>
                                </div>
                                <div class="col-md-3">
                                    <select name="transfert_vers" id="transfert_vers" required>
                                        <option value="cadorim" {{$editecash[0]->point_retrait=='cadorim' ? 'selected': '' }}> CADORIM Agence</option>
                                        <option value="5f63cc77d3b00" {{$editecash[0]->point_retrait =='5f63cc77d3b00' ? 'selected': '' }} > Gaza (غزه)</option>
                                        <option value="5f63cc55a65e5" {{$editecash[0]->point_retrait =='5f63cc55a65e5' ? 'selected': '' }}> Selibaby (سيليبابي)</option>
                                        <option value="5f63cc98aafdd" {{$editecash[0]->point_retrait =='5f63cc98aafdd'? 'selected': '' }} >Tachout (تاشوط)</option>
                                        <option value="5f63ecf0810ef"{{$editecash[0]->point_retrait =='5f63ecf0810ef' ? 'selected': '' }} >Ould Yenja (اولد ينج)</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                   Frais: <input type="text" name="frais_gaza" id="frais_gaza" size="2" value=" {{ $editecash[0]->frais_gaza  }}" ><span style="  font-size:small;">MRU</span>
                                </div>
                                
                                <div class="col-md-3" style="display: {{$editecash[0]->point_retrait ? 'block': 'none' }}" id="transfert_gaza">
                                   Agence: <input type="text" name="agence_gaza" id="agence_gaza" size="9" value="{{ $editecash[0]->agence_gaza  }} " >
                                </div>

                            </div>
                        </div>

                        <hr>



                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success" name="operation" value="retrait" id='retrait_button'>
                                    Retrait
                                </button>-
                                @if($editecash[0]->tracker_status!='transfert')
                                <button type="submit" class="btn btn-warning" name="operation" value="transfert" id='transfert_button'>
                                    Transfert
                                </button>
                                -
                                @endif
                                <button type="submit" class="btn btn-primary" name="operation" value="email">
                                    Email
                                </button>
                                -
                                <a href="/cashout-management" class="btn btn-danger">Annuler</a>
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