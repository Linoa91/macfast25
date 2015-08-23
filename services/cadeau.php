<?php

class Cadeau {
  public $nom;
  public $coupon;
  public $type;

  function Cadeau($nom, $coupon, $type) {
    $this->nom = $nom;
    $this->coupon = $coupon;
    $this->type = $type;
  }
  
  
  /**
   * Recupère le cadeaux correspondant au code
   * @return retourne le cadeau correspondant, si le code ne correspond à aucun cadeau NULL est retourné
   */
  public static function recuperer($code) {
    $res = query("SELECT cadeaux.nom, coupon, type_cadeau FROM cadeaux LEFT JOIN users ON users.code_participation = cadeaux.code_activation WHERE email IS NULL AND code_activation = '$code'");
    if ($res == null) return null;
    return new Cadeau($res['nom'], $res['coupon'], $res['type_cadeau']);
  }
}

function recupererCadeau($code) {
  return Cadeau::recuperer($code);
}