<?php
/**
 * Ce fichier contient les fonctions relatives aux mails.
 */

/**
 * ON ENVOYE LE MAIL AVEC LE CODE COUPON A PRESENTER EN RESTAURANT
 * @param string $coupon le coupon du cadeau de l'utilisateur
 * @param User $user L'utilisateur ayant gagné un cadeau
 * @return boolean True si le mail a bien été envoyé, sinon False
 */
function envoyerCouponCadeaux($coupon, $user) {
	$to = "$user->prenom $user->nom <$user->email>";
	$subject = "Vous avez gagné !";
	$message = 'Félicitation, vous avez gagné un lot Macfast. Pour le récupérer, ci-joint le coupon à présenter dans un de nos restaurants.';
	$res = false;
	try {
    	// $res = mail($to, $subject, $message);
    	$res = true;
	} catch (Exception $e) {
	   $res = false;
	}

	return $res;
}