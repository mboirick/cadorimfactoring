@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Motifs du rejet</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('abonnes-management.mailrejet') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" id="idUser" name="idUser" value="">

                        
                                <div>
                                    <div class="box-header">
                                        <i class="fa fa-envelope"></i>

                                        <h3 class="box-title">Email</h3>
                                      
                                    </div>
                                    <div class="box-body">
                                        
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="emailto" placeholder="Email to:" value="{{$email}}">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="subject" placeholder="Subject" value="CADORIM : Documents KYC rejetès">
                                            </div>
                                            <div>
                                                <textarea class="textarea" name="text" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                            </div>
                                        
                                    </div>
                                    
                                </div>

            
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Envoyer
                                </button>
                                <a href="/abonnes-management" class="btn btn-danger">
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