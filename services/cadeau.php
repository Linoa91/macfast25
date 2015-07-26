<?php

class Cadeau {
  public $nom;
  private $coupon;

  function Cadeau($nom, $coupon) {
    $this->nom = $nom;
    $this->coupon = $coupon;
  }
  
  
  /**
   * Recupère le cadeaux correspondant au code
   * @return retourne le cadeau correspondant, si le code ne correspond à aucun cadeau NULL est retourné
   */
  public function recuperer($code) {
    $cadeaux = array(
      'aaaaaaaa' => new Cadeau('Voyages à New York', 'coup42')
    );
    return $cadeaux[$code];
  }
}

function recupererCadeau($code) {
  return Cadeau::recuperer($code);
}