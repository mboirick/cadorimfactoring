<?php
require_once'php/db.php';
$req_taux = $pdo->query("Select * FROM taux_echange LIMIT 1");

$taux= $req_taux->fetch();

$id_commande=str_shuffle (time());

	if ($_SERVER['REQUEST_METHOD'] != 'POST') {
		header('Location: accueil');
		exit();
	}

	$ch = curl_init();
	//curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
	curl_setopt($ch, CURLOPT_URL, 'https://ipnpb.paypal.com/cgi-bin/webscr');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "cmd=_notify-validate&" . http_build_query($_POST));
	$response = curl_exec($ch);
	curl_close($ch);

	//file_put_contents("test.txt", $response);
if($response=="VERIFIED"){

$handle=fopen("test.txt","w");
foreach($_POST as $key => $value)

fwrite($handle,"$key=> $value \r\n");

fwrite($handle,"$response");

fclose ($handle);

if($_POST['mc_currency']=="EUR")
{$T_change= $taux->taux_euros;
  $symbole='€'; 
  }
else
{
$T_change= $taux->taux_dollar;
 $symbole='$';
}

$TotalMRU=$T_change*$_POST['mc_gross'];


if($_POST['payment_status']=='Completed')
$payment_status='Complet';

elseif($_POST['payment_status']=='Pending')
$payment_status='En attente';

elseif($_POST['payment_status']=='Failed')
$payment_status='Echoue';

elseif($_POST['payment_status']=='Voided')
$payment_status='Annule';

else
$payment_status=$_POST['payment_status'];


$infos_user=$_POST['custom']; 
$infos = explode("<=>", $infos_user); //INFORMATION SUR L'EXPEIDTEUR ET LE BENEFICAIRE


/* AJOUT les information de paiement*/
$req_paie= $pdo->prepare("INSERT INTO paiements SET id_commande=?, payment_id = ?, payment_type = ?,payment_status = ?, payment_amount = ?, payment_currency = ?, payment_date = ?, payer_email = ?,address_name=?");
$req_paie->execute([$id_commande,$_POST['txn_id'],$_POST['txn_type'],$payment_status,$_POST['mc_gross'],$_POST['mc_currency'],$_POST['payment_date'], $_POST['payer_email'], $_POST['address_name']]);

/*AJOUT INFORMATION DE COMMANDE COORDONNEE*/

$req_commande= $pdo->prepare("INSERT INTO coordonnes_commandes SET id_commande = ?, mail_exp = ?, nom_exp = ?, phone_exp = ?, adress_exp = ?, nom_benef = ?, phone_benef = ?, adress_benef = ?, montant = ?, date_commande= NOW(), paiement_satus=?");


$req_commande->execute([$id_commande,$infos[2],$infos[0],$infos[1],$_POST['address_city'],$infos[3],$infos[4],$infos[5], $_POST['mc_gross'].' '.$symbole,$payment_status]);	

/*AJOUT INFORMATION DE COMMANDE */
$panier=""; //panier vide

for ( $i=1;  $i <= $_POST['num_cart_items'] ; $i++){
		
$req= $pdo->prepare("INSERT INTO commandes SET id_commande = ?, mail_commande = ?, nom_produit = ?, prix_produit = ?, quantite = ?, date_commande= NOW()");
$req->execute([$id_commande,$infos[2],$_POST['item_name'.$i],$_POST['mc_gross_'.$i].' '.$symbole,$_POST['quantity'.$i]]);			
	
$panier .= ' <tr>
    <td><div align="left">'.$_POST['item_name'.$i].'</div></td>
    <td><div align="left">'.$_POST['quantity'.$i].'</div></td>
    <td><div align="left">'.$_POST['mc_gross_'.$i].' '.$symbole.'</div></td>
  </tr>';
  			
}





$to = $infos[2]; // Email de l'expediteur.
$email_subject = "CADORIM : Confirmation de commande";


$email_body = '

<div align="center">
<table width="74%" border="0" style="height: 420px; width: 80%; border:#000000 solid 0px">
<tbody>
<tr>
<td bgcolor="#000000" height="50">
<div align="left"><img src="'.URLROOT.'img/logos/logo.png" alt="" width="200" /></div></td>
</tr>
<tr align="center">
<td style="text-align: center; margin:auto" align="center">
<table width="100%" border="0">
  <tr>
    <td>
    
    <p align="left"> Bonjour '.$infos[0].'   </p>
    <p align="left">Votre envoi  a été bien effectué, Votre numéro de suivi  est : <strong>'.$id_commande.'</strong>.
      Veuillez communiquer ce numéro au beneficiaire pour  le retrait.<br>
    </p>
    <b align="left" style="color: red">L\'agence CADORIM est exceptionnellement fermée ce dimanche, les retraits peuvent être effectués le lundi 2 septembre à partir de 10h, merci pour votre compréhension<br>
      </b>
    
    </td>
  </tr>
</table>
<div>
      <div align="left" ><strong> Details de l\'envoi: </strong></div>
    </div>
    <table cellpadding="5" cellspacing="3" width="100%" border="0"  >
      <tbody>
        <tr>
          <td width="46%" style="border:#999999 solid 1px"><div align="left"><strong>Expéditeur :</strong><br>
          </div></td>
          
          <td width="52%" style="border:#999999 solid 1px"><div align="left"><strong>Bénéficiaire :</strong></div></td>
        </tr>
        <tr>
          <td ><div align="left">'.$infos[0].'<br>
          </div></td>
          
          <td ><div align="left">'.$infos[3].'<br>
          </div></td>
        </tr>
        <tr>
          <td ><div align="left">Tel : '.$infos[1].'<br>
          </div></td>
          
          <td ><div align="left">Tel : '.$infos[4].'<br>
          </div></td>
        </tr>
        <tr>
          <td style="border-bottom:#999999 solid 1px"> <div align="left">Email : '.$infos[2].'</div></td>
          
          <td style="border-bottom:#999999 solid 1px"><div align="left">Adresse : '.$infos[5].'<br>
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
  '.$panier.'
  
    <tr>
    <td style="border-top:#999999 solid 1px">&nbsp;</td>
    <td style="border-top:#999999 solid 1px">&nbsp;</td>
    <td style="border-top:#999999 solid 1px"><div align="left">Frais d\'envoi : 0 ' .$symbole.'</div></td>
  </tr>
  
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
    <td ><div align="left"><strong>Total : '.$_POST['mc_gross'].' ' .$symbole.'</strong></div></td>
  </tr>
</table>

  <div>
    <div align="left"><strong> Paiement status : '.$payment_status.' </strong></div>
  </div>
  
  <br />
    
  <p>L\'equipe CADORIM vous remerci pour votre commande, esperant vous revoir prochainement pour un nouvel envoi. </p>
  
  N’hésitez pas a visiter notre page Facebook et LinkeDin et partager votre expérience avec vos amis & proches.
  </td>
</tr>

<tr><td>
<table width="100%" height="40" border="0" style="background-color:#212529">
  <tr>
    <td  align="center"><a href="https://www.facebook.com/cradmin1/ ">
                </a><a href="https://twitter.com/Cadorim2"><img src="https://cadorim.com/img/logos/rs_twit.PNG"></a><a href="https://www.facebook.com/cradmin1/ "><img src="https://cadorim.com/img/logos/rs_fb.PNG">
                </a><a href="https://www.linkedin.com/company/35526670/admin/"><img src="https://cadorim.com/img/logos/rs_in.PNG"></a></td>
  </tr>
</table>
</td></tr>
</tbody>
</table>
</div>




';


$headers = "From: \"CADORIM\"<noreply@cadorim.com>\n";
$headers .= "Bcc:". $_POST['business']."" . "\r\n";   
$headers .= "Reply-To: noreply@cadorim.com\n";
$headers .= 'Bcc:  '.MAILNOTIF.'' . "\r\n";
$headers .= "Content-Type: text/html; charset=\"utf8\"";
mail($to,$email_subject,$email_body,$headers);


//CREATION DE COMPTE FI NOT EXIST




$req = $pdo->prepare("Select id FROM users WHERE email= ?");
$req->execute([$to]);

$user= $req->fetch();

if($user){
 exit();
}
else
{

$req= $pdo->prepare("INSERT INTO users SET username = ?,prenom = ?, phone = ?, email = ?, password = ?");

$password = password_hash(time(),PASSWORD_BCRYPT);


$req->execute([$infos[0]," ",$infos[1],$to,$password]);


}



}












?>
