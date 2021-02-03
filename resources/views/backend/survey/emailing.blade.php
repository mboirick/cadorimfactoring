@extends('cache-mgmt.base')

@section('action-content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Emailing</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('sondage-envoiemail') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="nomsonadage" value="{{ $id_sondage}}">


                        <div>
                            <div class="box-header">
                                <i class="fa fa-envelope"></i>

                                <h3 class="box-title">Email</h3>

                            </div>
                            <div class="box-body">

                                <div class="form-group">
                                    <input type="email" class="form-control" name="emailto" placeholder="Email to:" value="">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject" value="Enquête de satisfaction Cadorim">
                                </div>
                                <div>
                                    <textarea id="summernote" name="text" placeholder="Message" style="width: 100%; height: 500px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">



                                    <div align="center">
      <table width="74%" border="0" style=" width: 80%; ">
      <tbody>
      <tr>
      <td>
      <div align="left"><img src="https://cadorim.com/mg/logos/cryp_log.png" alt="" width="150"></div></td>
      </tr>
      <tr align="center">
      <td style="text-align: center; margin:auto" align="center">
      <table width="100%" border="0">
        <tbody><tr>
          <td>
          <br>
          <p align="left">Chers clients, chères clientes,</p><p align="left">Je vous  remercie d’avoir choisi notre service pour votre transfère d’argent vers la Mauritanie.

          </p><p align="left">Notre objectif est de faciliter le transfert d’argent et l’achat des cadeaux de l’étranger vers la Mauritanie.</p>
          <p align="left">Nos agents se mobilisent pour vous servir et vous accompagner dans ses démarches. Comme vous, nous sommes des expatriés mauritaniens et nous 
              sommes les premiers clients de nos services. En effet, les flux d’argent et d’objets vers notre patrie relèvent du quotidien.</p>
              <p align="left">Pour mieux connaitre vos besoins, vos attentes et améliorer l’accès et l’utilisation de nos services, nous vous laissons nous faire votre retour
                   d’expérience en prennant 2 minutes pour répondre à un questionnaire.Vos réponses sont très utiles pour notre entreprise Cadorim et le développement de ses services.</p>
                   <p align="left">Cliquer sur le lien pour accéder au questionnaire:&nbsp;<br></p><div style="display: inline-block;background-color: #FFC107; padding: 7px 30px; border-radius: 20px;color: #000">
                   <a href="https://admin.cadorim.com/sondage-management/*email*/*iduser*" style="color: #000; text-decoration: none">
          <b>Je réponds au questionnaire</b> </a></div><a href="https://admin.cadorim.com/sondage-management/*email*/*iduser*" style="color: #000; text-decoration: none">
          </a>
          <p></p></td>
        </tr>
      </tbody></table>

        <p align="left"><span style="text-align: center; background-color: transparent;">Notre équipe est à votre disposition pour toutes autres questions.</span><br></p><p align="left">Bien à vous.</p><p align="left"><b>Mohamed Elmoctar Neine</b> </p><p align="left"><b>Fondateur de CADORIM</b> </p>

        
        </td>
      </tr>
      </tbody>
      <tbody><tr><td>
     </td></tr>
      </tbody></table>
      </div>
      




                                    
      




                                    </textarea>
                                    <script>
                                        $(document).ready(function() {
                                            $('#summernote').summernote();
                                        });
                                    </script>
                                </div>

                            </div>

                        </div>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Envoyer
                                </button>
                                <a href="/sondage-management" class="btn btn-danger">
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