@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"> <b>Demande de {{ $paiements->type_demande }} </b></div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('paiement-management.decision')}}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="paiement" value="{{ $paiements->id_paiement }}" required>
                        <input type="hidden" name="id_client" value="{{ $paiements->id_client }}" required>
                        <input type="hidden" name="type_demande" value="{{ $paiements->type_demande }}" required>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">

                            <div class="col-md-4">

                            </div>
                            <div class="col-md-8">

                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">

                            <div class="col-md-6">
                                <b> Emetteur : </b>{{ $paiements->firstname}}
                            </div>

                            <div class="col-md-6">
                                @if($paiements->type_demande !='credit') <b> Beneficaire : </b> {{ $paiements->entreprise}}- {{ $paiements->adresse}}

                                @endif
                            </div>

                        </div>
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">

                            <div class="col-md-6">
                                <b> Solde en €uros : </b> {{ $soldes->solde_euros}} €
                            </div>

                            <div class="col-md-6">
                            <b> Solde en MRU : </b> {{ $soldes->solde_mru}} MRU
                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">

                            <div class="col-md-4">
                                <b> Montant € : </b> {{ $paiements->montant_euros}} €
                                <input type="hidden" name="montant_euros" value="{{ $paiements->montant_euros }}" required>
                            </div>

                            <div class="col-md-4">
                                <b> Taux de change : </b> {{ $paiements->taux_echange}} MRU/€
                                <input type="hidden" name="taux_echange" value="{{ $paiements->taux_echange }}" required>
                            </div>

                            <div class="col-md-4">
                                <b> Montant MRU : </b> {{ $paiements->montant_mru}} MRU
                                <input type="hidden" name="montant_mru" value="{{ $paiements->montant_mru }}" required>
                            </div>

                        </div>

                        @if($paiements->type_demande =='transfert')
                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">


                            <div class="col-md-4">
                                <b> IBAN : </b>
                                {{ $paiements->iban}}
                            </div>


                            <div class="col-md-4">
                                <b> Factures : </b>
                                @foreach($documents as $key => $document)

                                <a href="" style="font-size:22px">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </a>

                                @endforeach
                            </div>

                            <div class="col-md-4">

                                <input type="hidden" name="comptedebit" id="comptedebit" value="{{ $paiements->id_client}}">

                            </div>

                        </div>
                        @endif

                        <div class="form-group{{ $errors->has('remarque') ? ' has-error' : '' }}">
                            <label for="remarque" class="col-md-3 control-label">Motifs</label>

                            <div class="col-md-8">
                                <input id="remarque" type="text" class="form-control" name="remarque" value="{{ old('remarque') }}" required>


                            </div>
                        </div>

                        <div class="form-group">
                            <label for="avatar" class="col-md-3 control-label">Ajouter des justificatifs</label>
                            <div class="col-md-8">
                                <div class="input-group control-group increment">
                                    <input type="file" name="document[]" class="form-control">

                                    <div class="input-group-btn">
                                        <button class="btn btn-warning" type="button"><i class="glyphicon glyphicon-plus"></i>Ajouter</button>
                                    </div>
                                </div>
                                <div class="clone hide">
                                    <div class="control-group input-group" style="margin-top:10px">
                                        <input type="file" name="document[]" class="form-control">
                                        <div class="input-group-btn">
                                            <button class="btn btn-danger" type="button"><i class="glyphicon glyphicon-remove"></i> Supprimer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">

                            <div class="col-md-3">

                            </div>

                            <div class="col-md-6" style="text-align:center;">

                                <button type="submit" class="btn btn-success" name="operation" value="approuver">
                                    Approuver

                                </button>
                                |
                                <button type="submit" class="btn btn-primary" name="operation" value="rejeter">
                                    Réjéter
                                </button>
                                |
                                <button type="submit" class="btn btn-danger" name="operation" value="annuler">
                                    Annuler
                                </button>
                            </div>



                            <div class="col-md-3" style="text-align:center;">

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