<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>CadoRim</title>
</head>

<body>
    <div align="center">
        <table width="74%" border="0" style="height: 420px; width: 80%; border:#000000 solid 0px">
            <tbody>
                <tr>
                    <td bgcolor="#000000" height="50">
                    <div align="left"><img src="https://cadorim.com/mg/logos/logo.png" alt="" width="200" /></div></td>
                </tr>
                <tr align="center">
                    <td style="text-align: center; margin:auto" align="center">
                        <table width="100%" border="0">
                            <tr>
                                <td>
                                    <p align="left">  Bonjour Mme(Mrs) {{$firstName}},  </p>
                                    <p align="left">
                                        L'équipe cadorim a essayé de joindre par téléphone, Mme(Mrs) {{$tracker->nom_benef}} au numéro de téléphone :<strong> {{$tracker->phone_benef}}</strong>. sans succès<br>
                                        Nous vous invitons à lui demander de nous contacter au  <strong>  Tel: +222 48 13 30 66  |  Tel2: +222 45 29 40 07.</strong> afin de procéder au retrait.<br>
                                    </p>
                                </td>
                            </tr>
                        </table>
                        <div>
                            <div align="left" ><strong> Details de l'envoi: </strong></div>
                        </div>
                        <table cellpadding="5" cellspacing="3" width="100%" border="0"  >
                            <tbody>
                                <tr>
                                  <td width="46%" style="border:#999999 solid 1px"><div align="left"><strong>Expéditeur :</strong><br>
                                  </div></td>
                                  
                                  <td width="52%" style="border:#999999 solid 1px"><div align="left"><strong>Bénéficiaire :</strong></div></td>
                                </tr>
                                <tr>
                                  <td ><div align="left">{{$tracker->nom_exp}}<br>
                                  </div></td>
                                  
                                  <td ><div align="left">{{$tracker->nom_benef}}<br>
                                  </div></td>
                                </tr>
                                <tr>
                                  <td ><div align="left">Tel : {{$tracker->phone_exp}}<br>
                                  </div></td>
                                  
                                  <td ><div align="left">Tel : {{$tracker->phone_benef}}<br>
                                  </div></td>
                                </tr>
                                <tr>
                                  <td > <div align="left">Email : {{$tracker->mail_exp}}</div></td>
                                  
                                  <td ><div align="left">Adresse :  {{$tracker->adress_benef}}<br>
                                  </div></td>
                                </tr>
                            </tbody>
                        </table>
                        <br />
                        <div>
                          <div align="left" ><strong>Details de la commande:</strong></div>
                        </div>
                        
                        <table width="100%" border="0" cellspacing="3" cellpadding="3">
                            <tr>
                                <td style="border:#999999 solid 1px"><div align="left"><strong>Produit</strong></div></td>
                                <td style="border:#999999 solid 1px"><div align="left"><strong>Quantite</strong></div></td>
                                <td style="border:#999999 solid 1px"><div align="left"><strong>Prix</strong></div></td>
                            </tr>
                      
                            @foreach ($orders as $order) 
                                <tr>
                                  <td><div align="left">{{$order->nom_produit}}</div></td>
                                  <td><div align="left">{{$order->quantite}}</div></td>
                                  <td><div align="left">{{$order->prix_produit}}</div></td>
                                </tr>
                            @endforeach
                      
                            <tr>
                                <td style="border-top:#999999 solid 1px">&nbsp;</td>
                                <td style="border-top:#999999 solid 1px">&nbsp;</td>
                                <td style="border-top:#999999 solid 1px"><div align="left">Frais d'envoi : 0 {{$tracker->payment_currency}}</div></td>
                            </tr>
                            <tr>
                                <td >&nbsp;</td>
                                <td >&nbsp;</td>
                                <td >
                                    <div align="left">
                                        <strong>Total : {{$tracker->payment_amount}}  {{$tracker->payment_currency}}</strong>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <br />
            
                        <p>L'equipe CADORIM  </p>
          
                          N’hésitez pas a visiter notre page Facebook et LinkeDin et partager votre expérience avec vos amis & proches.
                        </p>
                        <br>
            
                        <table width="100%" height="40" border="0" style="background-color:#212529">
                            <tr>
                                <td  align="center">
                                    <a href="https://www.facebook.com/cradmin1/ "></a>
                                    <a href="https://twitter.com/Cadorim2">
                                        <img src="https://cadorim.com/img/logos/rs_twit.PNG">
                                    </a>
                                    <a href="https://www.facebook.com/cradmin1/ ">
                                        <img src="https://cadorim.com/img/logos/rs_fb.PNG">
                                    </a>
                                    <a href="https://www.linkedin.com/company/35526670/admin/">
                                        <img src="https://cadorim.com/img/logos/rs_in.PNG">
                                    </a>
                                </td>
                            </tr>
                        </table>
            
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>