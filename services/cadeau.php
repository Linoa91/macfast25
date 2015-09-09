<?php
/**
 * Ce fichier contient la class Cadeau et toutes les fonctionnalités liés aux cadeaux
 */

/**
 * Cadeau est la classe qui représente les cadeaux à gagnés.
 * Elle correspond à la table CADEAUX dans la base de données.
 */
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
    if ($res == null) { return null; }
    return new Cadeau(utf8_encode($res['nom']), $res['coupon'], $res['type_cadeau']);
  }
}

/**
 * ON RECUPERE LE CODE CADEAU ASSOCIE AU CADEAU
 * @param $code string le code d'activation de l'utilisateur
 */
function recupererCadeau($code) {
  return Cadeau::recuperer($code);
}