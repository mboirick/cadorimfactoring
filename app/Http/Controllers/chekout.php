<?php

require 'header.php';   ?>

<?php

if (isset($_SESSION['auth'])) {
  $nom_commande = $_SESSION['auth']->username;
  $phone_commande = $_SESSION['auth']->phone;
  $mail_commande = $_SESSION['auth']->email;
} else {

  $nom_commande = '';
  $phone_commande = '';
  $mail_commande = '';
}

echo "<br><br><br><br><br><br>";
$id_commande = time();

//$currency=$_POST['currency'];
$currency = '€';
if ($currency == '€')
  $currency_code = 'EUR';
else
  $currency_code = 'USD';

/* Les variables suivantes doivent être personnalisées selon vos besoins */
// compte acheter paypal test FR mboirick-buyer@yahoo.fr pass&p@ss
// compte business paypal mboirik@gmail.com pass&p@ss   https://www.sandbox.paypal.com/
// compte acheter paypal test USD absaty@gmail.com  pass&p@ss
$email_paypal = 'mboirik@cadorim.com';/*email associé au compte paypal du vendeur*/

//$email_paypal= 'bmneine@gmail.com';
$url_retour = URLROOT . 'remerciement';/*page de remerciement à créer*/
$url_cancel = URLROOT . 'annuler'; /*page d'annulation d'achat*/
$url_confirmation = URLROOT . 'listener.php';/*page de confirmation d'achat*/
/* fin déclaration des variables */
//echo "transfert" .$euros."VERS".$MRU;
//echo $id_commande;
//if (isset($_POST['transfert'])) 
if (1) {

  $euros = $_POST['euros'];
  $euros = round($euros, 1, PHP_ROUND_HALF_UP);
  $MRU = $_POST['MRU'];
  $MRU = round($MRU, 2, PHP_ROUND_HALF_UP);

  $item_numero = 'Transfert ' . $euros . ' €'; /*Numéro du produit en vente*/
  $item_prix   =  $euros;    /*prix du produit*/
  $item_nom    = 'Transfert: ' . $MRU . ' MRU'; /*Nom du produit*/

?>

  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div>
          <!-- Project Details Go Here -->
          <?= validationcommande; ?>

          <div class="row">
            <div class="col-lg-12">


              <form id="connexionForm" name="commande" novalidate="novalidate" action="altpay.php" method="post">

                <div class="row">
                  <div class="col-md-6">

                    <div class="form-group "> <b><?= expediteur; ?> </b>
                    </div>

                    <div class="form-group">
                      <input class="form-control" name="nom_exp" id="nom_exp" type="text" placeholder="Nom Prénom *" required="required" data-validation-required-message="<?= nomexp; ?>" value="<?php echo $nom_commande; ?>" onchange="custom_data()">
                      <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group">
                      <input class="form-control" name="phone_exp" id="phone_exp" type="number" placeholder="Téléphone *: " required="required" data-validation-required-message="<?= telexp; ?>" value="<?php echo $phone_commande; ?>" onchange="custom_data()">
                      <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group">
                      <input class="form-control" name="adress_exp" id="adress_exp" type="email" placeholder="Email *:  " required="required" data-validation-required-message="<?= adressexp; ?>" value="<?php echo  $mail_commande; ?>" onchange="custom_data()">
                      <p class="help-block text-danger"></p>
                    </div>
                  </div>
                  <div class="col-md-6">

                    <div class="form-group "> <b><?= beneficiaire; ?></b>
                    </div>
                    <div class="form-group">
                      <input class="form-control" name="nom_benef" id="nom_benef" type="text" placeholder="Nom Prénom *" required="required" data-validation-required-message="<?= nombenef; ?>">
                      <div id="resultat">

                        <ul>

                        </ul>

                      </div>


                      <p class="help-block text-danger"></p>
                    </div>

                    <div class="form-group">
                      <input class="form-control" name="phone_benef" id="phone_benef" type="tel" placeholder="Téléphone *: +22201020304" required="required" data-validation-required-message="<?= telbenef; ?>" onchange="custom_data()">
                      <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group">
                      <input class="form-control" name="adress_benef" id="adress_benef" type="text" placeholder="Adresse *: Exemple NKTT  " required="required" data-validation-required-message="<?= adressbenef; ?>" onchange="custom_data()">
                      <p class="help-block text-danger"></p>
                    </div>
                  </div>
                  <div class="clearfix" style="margin-top:20px"></div>
                  <div class="col-lg-12 text-center">


                    <div class="cart-row">
                      <?= detailscommande; ?>
                    </div>
                    <div class="cart-items">

                      <div class="cart-row">
                        <div class="cart-item cart-column">

                          <span class="cart-item-title">Transfert</span>
                        </div>
                        <span class="cart-price cart-column"><?php echo  $euros . ' ' . $currency; ?></span>
                        <input type="hidden" name="euros" value="<?php echo  $euros; ?>" />
                        <div class="cart-quantity cart-column">
                          <span class="cart-item-title"><?php echo  $MRU; ?> MRU</span>
                          <input type="hidden" name="MRU" value="<?php echo  $MRU; ?>" />
                          <input type="hidden" name="action" value="transfert" />
                        </div>
                      </div>

                    </div>
                    <div class="cart-total">
                      <strong class="cart-total-title">Total</strong>

                      <span class="cart-total-price"><?php echo $euros . ' ' . $currency; ?></span>
                    </div>

                    <br />

                    <div class="col-lg-12" align="left">
                      <div class="row">
                        <div class="col-md-6">

                          <input id="paypal" type="radio" class="input-radio" name="payment_method" value="paypal" checked="checked" onclick="pp ()">
                          <b class="form-group">

                            <?= cartebancaire; ?>
                          </b>
                        </div>

                        <div class="col-md-6">
                          <input id="virement" type="radio" class="input-radio" name="payment_method" value="bacs" onclick="pm ()">

                          <b class="form-group">

                            <?= virement; ?>

                          </b>
                        </div>
                      </div>
                      <br>
                      <div class="col-lg-12" align="left">
                        <div id="pp" class="row" style="display:block">
                          <p>
                            <div class="row" align="center">
                              <div class="col-2" style="border: 1px solid #ced4da; margin: 9px">
                                <img src="https://payments.atlpay.com/payments/img/visa.svg" height="30" alt="">
                              </div>
                              <div class="col-2" style="border: 1px solid #ced4da; margin: 9px">
                                <img src="https://payments.atlpay.com/payments/img/mastercard.svg" height="30" alt="">
                              </div>
                              <div class="col-2" style="border: 1px solid #ced4da; margin: 9px">
                                <img src="https://payments.atlpay.com/payments/img/maestro.svg" height="30" alt="">
                              </div>

                              <div class="col-2" style="border: 1px solid #ced4da; margin: 9px">
                                <img src="https://payments.atlpay.com/payments/img/sofort.svg" height="30" alt="">
                              </div>

                              <div class="col-2" style="border: 1px solid #ced4da; margin: 9px">
                                <img src="https://static.trustly.com/logotype/footer/trustly_95.png" height="30" alt="">
                              </div>

                            </div>
                          </p>
                        </div>
                        <div id="pm" class="row" style="display:none">
                          <?= virementdesc; ?>
                        </div>
                      </div>
                      <br />
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <input type="checkbox" name="terms" id="terms" required="required" data-validation-required-message="<?= cg; ?>">
                            <?= acceptconditions; ?>

                            <p class="help-block text-danger"></p>
                          </div>

                        </div>
                      </div>
                      <!--    *************************************************************-->
                      <?php echo '    
  
<input type="hidden" name="cmd" value="_cart">


<input type="hidden" id="custom" name="custom" value=" ">
<input type="hidden" name="item_name_1" value="' . $item_nom . '">
<input type="hidden" name="amount_1" value="' . $item_prix . '">
<input type="hidden" name="taille_panier" value="' . $taille_panier . '"> 


 
	
  <input type="hidden" name="currency_code" value="' . $currency_code . '"/>
 
  <input type="hidden" name="no_shipping" value="0"/>
  <input type="hidden" name="lc" value="FR"/>
  <input type="hidden" name="notify_url" value="' . $url_confirmation . '"/>
  <input type="hidden" name="cancel_return" value="' . $url_cancel . '">
  <input type="hidden" name="return" value="' . $url_retour . '">'

                      ?>

                      <!--************************************************************-->




                      <div id="success-inscrip"></div>

                      <div align="center">
                        <button id="sendInscriptionButton" class="btn btn-primary btn-xl text-uppercase" type="submit" name="paypal"><?= valider; ?></button>

                        <button id="virementButton" class="btn btn-primary btn-xl text-uppercase" type="submit" name="viremement" style="display:none"><?= valider; ?></button>
                      </div>
                    </div>
                  </div>
              </form>
            </div>
          </div>


        </div>

      </div>
    </div>
  </div>

<?

  echo "<br><br><br>";
} else {



  if (isset($_POST['taille']))
    $taille_panier = $_POST['taille'];
  else
    $taille_panier = 0;

  //debug($_SESSION);

?>

  <div class="container">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div>
          <!-- Project Details Go Here -->
          <h5 class="text-uppercase" align="center">Validation de la commande</h5>

          <p class="item-intro text-muted" align="center">Remplissez le formulaire ci-dessous pour passer la commande.</p>

          <div class="row">
            <div class="col-lg-12">
              <form id="connexionForm" name="commande" novalidate="novalidate" action="altpay.php" method="post">
                <div class="row">
                  <div class="col-md-6">

                    <div class="form-group  "> <b>Expéditeur </b>
                    </div>

                    <div class="form-group">
                      <input class="form-control" name="nom_exp" id="nom_exp" type="text" placeholder="Nom Prénom *" required="required" data-validation-required-message="Entrer le nom." value="<?php echo $nom_commande; ?>" onchange="custom_data()">
                      <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group">
                      <input class="form-control" name="phone_exp" id="phone_exp" type="number" placeholder="Téléphone *: " required="required" data-validation-required-message="Entrer le numéro de téléphone." value="<?php echo $phone_commande; ?>" onchange="custom_data()">
                      <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group">
                      <input class="form-control" name="adress_exp" id="adress_exp" type="email" placeholder="Email *:  " required="required" data-validation-required-message="Entrer votre email." value="<?php echo  $mail_commande; ?>" onchange="custom_data()">
                      <p class="help-block text-danger"></p>
                    </div>
                  </div>
                  <div class="col-md-6">

                    <div class="form-group  "> <b>Bénéficiaire</b>
                    </div>
                    <div class="form-group">
                      <input class="form-control" name="nom_benef" id="nom_benef" type="text" placeholder="Nom Prénom *" required="required" data-validation-required-message="Entrer le nom." onchange="custom_data()">
                      <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group">
                      <input class="form-control" name="phone_benef" id="phone_benef" type="tel" placeholder="Téléphone *: +22201020304" required="required" data-validation-required-message="Entrer le numéro de téléphone." onchange="custom_data()">
                      <p class="help-block text-danger"></p>
                    </div>
                    <div class="form-group">
                      <input class="form-control" name="adress_benef" id="adress_benef" type="text" placeholder="Adresse *: Exemple NKTT " required="required" data-validation-required-message="Entrer l'adresse." onchange="custom_data()">
                      <p class="help-block text-danger"></p>
                    </div>
                  </div>
                  <div class="clearfix" style="margin-top:50px"></div>

                  <br /><br />
                  <div class="col-lg-12 text-center">


                    <div class="cart-row">
                      <span class="cart-item  cart-column"> <strong>Produits</strong> </span>
                      <span class="cart-price  cart-column"> <strong>Prix</strong> </span>
                      <span class="cart-quantity cart-column"> <strong>Quantite</strong> </span>
                    </div>
                    <div class="cart-items">

                      <?php
                      $total = 0;
                      for ($i = 0; $i < $taille_panier; $i++) {

                        $titre = 'titre' . $i;
                        $prix = 'prix' . $i;
                        $qtite = 'qtite' . $i;
                        $item = $i + 1;
                      ?>
                        <div class="cart-row">
                          <div class="cart-item cart-column">

                            <span class="cart-item-title"><?php echo  $_POST[$titre]; ?></span>
                            <input type="hidden" name="<?php echo "titre" . $i; ?>" value="<?php echo  $_POST[$titre]; ?>" />
                            <input type="hidden" name="<?php echo "item_name_" . $item; ?>" value="<?php echo  $_POST[$titre]; ?>">



                          </div>
                          <span class="cart-price cart-column"><?php echo  $_POST[$prix]; ?></span>
                          <input type="hidden" name="<?php echo "prix" . $i; ?>" value="<?php echo  $_POST[$prix]; ?>" />
                          <input type="hidden" name="<?php echo "amount_" . $item; ?>" value="<?php echo  $_POST[$prix]; ?>">

                          <div class="cart-quantity cart-column">
                            <span class="cart-item-title"><?php echo  $_POST[$qtite]; ?></span>
                            <input type="hidden" name="<?php echo "qtite" . $i; ?>" value="<?php echo  $_POST[$qtite]; ?>" />
                            <input type="hidden" name="<?php echo "quantity_" . $item; ?>" value="<?php echo  $_POST[$qtite]; ?>">
                          </div>
                        </div>

                      <?php

                        $total = $total + ($_POST[$prix] * $_POST[$qtite]);
                      } ?>
                      <input type="hidden" name="taille" value="<?php echo  $taille_panier; ?>" />
                      <input type="hidden" name="total" value="<?php echo  $total; ?>" />
                    </div>
                    <div class="cart-total">
                      <strong class="cart-total-title">Total</strong>
                      <span class="cart-total-price"><?php echo $total; ?> €</span>
                    </div>


                    <div class="col-lg-12" align="left">

                      <div class="row form-group text-uppercase">
                        <B>PAIEMENT</B>
                      </div>
                      <div class="row" align="center">
                        <div class="col-2" style="border: 1px solid #ced4da; margin: 9px">
                          <img src="https://payments.atlpay.com/payments/img/visa.svg" height="30" alt="">
                        </div>
                        <div class="col-2" style="border: 1px solid #ced4da; margin: 9px">
                          <img src="https://payments.atlpay.com/payments/img/mastercard.svg" height="30" alt="">
                        </div>
                        <div class="col-2" style="border: 1px solid #ced4da; margin: 9px">
                          <img src="https://payments.atlpay.com/payments/img/maestro.svg" height="30" alt="">
                        </div>

                        <div class="col-2" style="border: 1px solid #ced4da; margin: 9px">
                          <img src="https://payments.atlpay.com/payments/img/sofort.svg" height="30" alt="">
                        </div>

                        <div class="col-2" style="border: 1px solid #ced4da; margin: 9px">
                          <img src="https://static.trustly.com/logotype/footer/trustly_95.png" height="30" alt="">
                        </div>

                      </div>
                    </div>

                    <div class="col-lg-12" align="left">
                      <div id="pp" class="row" style="display:block">
                        <p></p>
                      </div>
                      <div id="pm" class="row" style="display:none">
                        <p></p>
                      </div>
                    </div>
                    <br />
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <input type="checkbox" name="terms" id="terms" required="required" data-validation-required-message="Accepter les conditions générales.">
                          J’ai lu et j’accepte les <a class="portfolio-link" data-toggle="modal" href="#condition"> conditions générales</a>
                          <p class="help-block text-danger"></p>
                        </div>

                      </div>
                    </div>
                    <!--    *************************************************************-->
                    <?php


                    echo '  <input type="hidden" name="cmd" value="_cart">
  <input type="hidden" name="business" value="' . $email_paypal . '"/>
  <input type="hidden" name="upload" value="1">
  <input type="hidden" id="custom" name="custom" value=" ">
  <input type="hidden" name="currency_code" value="' . $currency_code . '"/>
  <input type="hidden" name="no_note" value="1"/>
  <input type="hidden" name="no_shipping" value="0"/>
  <input type="hidden" name="lc" value="FR"/>
  <input type="hidden" name="notify_url" value="' . $url_confirmation . '"/>
  <input type="hidden" name="cancel_return" value="' . $url_cancel . '">
  <input type="hidden" name="return" value="' . $url_retour . '">'

                    ?>

                    <!--************************************************************-->




                    <div id="success-inscrip"></div>
                    <div align="center">
                      <button id="sendInscriptionButton" class="btn btn-primary btn-xl text-uppercase" type="submit" name="paypal">VALIDER</button>


                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>


        </div>

      </div>
    </div>
  </div>

<?

  echo "<br><br><br>";
}
?>
</div>


<?php require 'footer.php'; ?>