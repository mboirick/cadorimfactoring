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
    <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="background-color: #fff;padding: 5% 0;">
            <tr>
                <td align="center" valign="top">
                <div align="left"><img src="https://dev.cadorim.com/mg/logos/cryp_log.png" alt="" width="180" /></div>
                </td>
            </tr>
            <tr>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px">
                    <tbody>
                        <tr>
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;background-color: white">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="width: 100%;text-align: center;padding-top: 3%">
                                                    <tr>
                                                        <td>

                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                        <tr style="background-color: #f7f8fb;">
                                            <td>
                                                <h3>Facture</h3>
                                                <p>Numéro : {{$idBill}}</p>
                                                <p>Nouakchout Le {{$dateToday}}.</p><br />
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;background-color: white">
                                                    <tr>
                                                        <td style="text-align: center;width: 100%;">
                                                            <p style="text-align: justify;font-size: 18px;color: #000;line-height: 1.5;;width: 90%;margin: auto">
                                                             @if($typeOperation == 'deposit')
                                                                Mr {{Auth::user()->firstname}} {{Auth::user()->lastname}} a déposé {{$amount}} MRU dans le compte d'agence {{$agencyName}} en date {{$dateToday}}
                                                                @elseif($typeOperation == 'withdrawal')
                                                                    L'agence {{$agencyName}} a déposé {{$amount}} dans le compte Mr {{Auth::user()->firstname}} {{Auth::user()->lastname}} en date de {{$dateToday}}
                                                                @endif
                                                            </p><br/>
                                                
                                                            <br/>
                                                            <p style="text-align: left;font-size: 18px;color: #000;line-height: 1.5;;width: 90%;margin: auto">Si vous avez une question, n’hésitez pas à nous contacter.</p>
                                                            <br/><br/>
                                                            <p style="text-align: left;font-size: 18px;color: #000;line-height: 1.5;;width: 90%;margin: auto">Bien a vous</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;background-color: transparent">
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: center;"><span style="font-size: 10px;color: #999999;">Copyright © 2020 cadorim.com. All Rights Reserved.</span></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </td>
        </tr>
    </table>
</div>
</body>
</html>