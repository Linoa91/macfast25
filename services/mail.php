<?php

function envoyerCouponCadeaux($coupon, $user) {
	$to = "$user->prenom $user->nom <$user->email>";
	$subject = "Vous avez gagné !";
	$message = 'Félicitation, vous avez gagné un lot Macfast. Pour le récupérer, ci-joint le coupon à présenter dans un de nos restaurants.';

	// $res = mail($to, $subject, $message);
	return $res;
}