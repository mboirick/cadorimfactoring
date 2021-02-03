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
        <table width="74%" border="0" style=" width: 80%; ">
            <tbody>
                <tr>
                    <td >
                        <div align="left"><img src="https://dev.cadorim.com/mg/logos/cryp_log.png" alt="" width="150" /></div>
                    </td>
                </tr>
                <tr align="center">
                    <td style="text-align: center; margin:auto" align="center">
                        <table width="100%" border="0">
                            <tr>
                                <td>
                                    <p align="left"> Bonjour {{$firstName}},  </p>
                                    <p align="left">
                                          Merci d’avoir utilisé notre service pour le transfert numéro {{$tracker->id_commande}}, on vous prie de bien vouloir nous envoyer le plutôt 
                                          possible un document d’identité (Passeport, pièce d’identité ou un permis de conduire), à l’adresse : compliance@cadorim.com ou sur le lien Ci-dessous

                                         <a href="https://admin.cadorim.com/kyc-management/{{$idUser}}/{{$tracker->mail_exp}}" style="color: #fff; text-decoration: none"> 
                                            <div style="display: inline-block;background-color: #FFC107; padding: 7px 30px; border-radius: 20px;color: #f7f8fb">
                                                LIEN KYC</div>
                                        </a>       
                                    </p>

                                    <p align="left">
                                      Cette démarche s'inscrit dans le cadre de la réglementation européenne et internationale en matière de transfert d'argent,
                                      dont l'objectif est de lutter contre le blanchiment d\'argent et le financement du terrorisme.
                                     
                                    </p>
                                    <p align="left">
                                        Veuillez ne pas tenir compte de ce message si vous nous avez déja envoyé ce document.
                                    </p>
                                </td>
                            </tr>
                          </table>

                        <p>L'équipe CADORIM. </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>