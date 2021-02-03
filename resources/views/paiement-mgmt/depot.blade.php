@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Effectuer un Depot</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('depotretrait') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('nom_benef') ? ' has-error' : '' }}">
                            <label for="nom_benef" class="col-md-4 control-label">Compte bénéficiaire</label>

                            <div class="col-md-6">
                                <input id="expediteur" type="hidden" class="form-control" name="expediteur" value="{{ Auth::user()->firstname }}" required autofocus>
                                <input id="operation" type="hidden" class="form-control" name="operation" value="depot" required autofocus>
                                
                                <select class="form-control js-country" name="id_client" required>
                                    <option value="">selectionné</option>
                                    @foreach ($clients as $client)
                                        <option value="{{$client->id_client}}">{{$client->firstname}}</option>
                                    @endforeach
                                    
                                </select>


                                @if ($errors->has('nom_benef'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nom_benef') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <input id="montant_euro" type="hidden" step=any class="form-control" name="montant_euro" value="0" required>
                        <!-- <div class="form-group{{ $errors->has('montant_euro') ? ' has-error' : '' }}">
                            <label for="montant_euro" class="col-md-4 control-label">Montant à retirer (EUR)</label>

                            <div class="col-md-6">
                                <input id="montant_euro" type="number" step=any class="form-control" name="montant_euro" value="{{ old('montant_euro') }}" required>

                                @if ($errors->has('montant_euro'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('montant_euro') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->
                       
                        <div class="form-group{{ $errors->has('montant_mru') ? ' has-error' : '' }}">
                            <label for="montant_mru" class="col-md-4 control-label">Montant à retirer (MRU)</label>

                            <div class="col-md-6">
                                <input id="montant_mru" type="number" step=any class="form-control" name="montant_mru" value="{{ old('montant_mru') }}" required>

                                @if ($errors->has('montant_mru'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('montant_mru') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                            <label for="message" class="col-md-4 control-label">Message</label>

                            <div class="col-md-6">
                                <input id="message" type="text" class="form-control" name="message" value="{{ old('message') }}" required>

                                @if ($errors->has('message'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Valider
                                </button>

                                <a href="/paiement-management/clients" class="btn btn-danger"> 
                                    Annuler
                                </a>
                            </div>
                        </div>

                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
