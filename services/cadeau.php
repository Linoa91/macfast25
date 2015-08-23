<?php

class Cadeau {
  public $nom;
  private $coupon;
  private $type;

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
    $res = query("SELECT nom, coupon FROM cadeaux WHERE code_activation = '$code'");
    if ($res == null) return null;
    return new Cadeau($res['nom'], $res['coupon'], NULL);
  }
}

function recupererCadeau($code) {
  return Cadeau::recuperer($code);
}