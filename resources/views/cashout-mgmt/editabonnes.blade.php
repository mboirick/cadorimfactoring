@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Update abonne</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('cache-management.updateAbonnes') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" id="idUser" name="idUser" value="{{$user[0]->id }}"   >

                        <div class="form-group{{ $errors->has('genre') ? ' has-error' : '' }}">
                        
                            <label for="genre" class="col-md-4 control-label">Genre</label>

                            <div class="col-md-3">
                            <input type="radio" id="genre" name="genre" value="homme"  {{$user[0]->genre == 'homme' ? 'checked' : ''}} >Homme </div>
                            
                            <div class="col-md-3">
                            <input type="radio" id="genre" name="genre" value="femme"  {{$user[0]->genre == 'femme' ? 'checked' : ''}} >Femme </div>
                                
                           
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Nom</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ $user[0]->username }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('prenom') ? ' has-error' : '' }}">
                            <label for="prenom" class="col-md-4 control-label">Prénom</label>

                            <div class="col-md-6">
                                <input id="prenom" type="text" class="form-control" name="prenom" value="{{ $user[0]->prenom }}" required>
                                @if ($errors->has('prenom'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('prenom') }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Téléphone</label>
                            <div class="col-md-6">
                               
                                    
                                    <input type="text" value="{{ $user[0]->phone }}" name="phone" class="form-control pull-right" id="phone" required>
                                    @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        
                            
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="zip" class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ $user[0]->email }}" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                              
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('adresse') ? ' has-error' : '' }}">
                            <label for="adresse" class="col-md-4 control-label">Adresse</label>

                            <div class="col-md-6">
                                <input id="adresse" type="text" class="form-control" name="adresse" value="{{ $user[0]->adress }}" required>     
                                @if ($errors->has('adresse'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('adresse') }}</strong>
                                    </span>
                                @endif                          
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Ville</label>
                            <div class="col-md-6">
                            <input id="Ville" type="text" class="form-control" name="Ville" value="{{ $user[0]->ville }}" required>
                            @if ($errors->has('Ville'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('Ville') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Code Postal</label>
                            <div class="col-md-6">
                            <input id="code_postal" type="text" class="form-control" name="code_postal" value="{{ $user[0]->code_postal }}" required>
                            @if ($errors->has('code_postal'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('code_postal') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Pays de résidence</label>
                            <div class="col-md-6">
                            <input id="pays_residence" type="text" class="form-control" name="pays_residence" value="{{ $user[0]->pays_residence }}" required>
                            @if ($errors->has('pays_residence'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pays_residence') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Date de naissance (YYYY-MM-DD)</label>
                            <div class="col-md-6">
                            <input id="date_naissance" type="text" class="form-control" name="date_naissance" value="{{ $user[0]->date_naissance }}" required>
                            @if ($errors->has('date_naissance'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('date_naissance') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Type document d'identité</label>
                            <div class="col-md-6">
                                <select name="type_doc" id="type_doc">
                                <option value="Passport">Passport</option>
                                <option value="piece">Piéce d'identité</option>
                                <option value="permis">Permis de conduire</option>
                                <option value="autre">Autre</option>
                                
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Numero document d'identité</label>
                            <div class="col-md-6">
                            <input id="numero_doc" type="text" class="form-control" name="numero_doc" value="{{ $user[0]->numero_doc }}" required>
                            @if ($errors->has('numero_doc'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('numero_doc') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Date d'émission (YYYY-MM-DD)</label>
                            <div class="col-md-6">
                            <input id="date_emission" type="text" class="form-control" name="date_emission" value="{{ $user[0]->date_emission }}" required>
                            @if ($errors->has('date_emission'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('date_emission') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                        
                            <label class="col-md-4 control-label">Date d'expiration' (YYYY-MM-DD)</label>
                            <div class="col-md-6">
                            <input id="date_expiration" type="text" class="form-control" name="date_expiration" value="{{ $user[0]->date_expiration }}" required>
                            @if ($errors->has('date_expiration'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('date_expiration') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="avatar" class="col-md-4 control-label" >Document</label>
                            <div class="col-md-6">
                                <img src="../../{{ $user[0]->document }}" width="50px" height="50px"/>
                                <input type="file" id="document" name="document" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">KYC</label>
                            <div class="col-md-6">
                                <select name="kyc" id="kyc">
                                <option value="0"   {{$user[0]->kyc == '0' ? 'selected' : ''}} >  En attente</option>
                                <option value="1" {{$user[0]->kyc == '1' ? 'selected' : ''}} >Approuvé</option>
                                <option value="2" {{$user[0]->kyc == '2' ? 'selected' : ''}} >Rejeté</option>
                            
                                
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Mise à jour
                                </button>
                                <a href="/cache-management/abonnes" class="btn btn-danger"> 
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
