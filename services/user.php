<?php

require_once(__DIR__.'/mysql.php');

class Visiteur {
  private $IP;
  public $email;
  public $nom;
  public $prenom;
  private $telephone;
  protected $codeParticipation;

  function Visiteur($email, $nom, $prenom, $telephone) {
    $this->email = $email;
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->telephone = $telephone;
  }

  public function enregistrer() {
    $query = "INSERT INTO users (email, nom, prenom, telephone)".
        " VALUES ('$this->email', '$this->nom', '$this->prenom', '$this->telephone');";
    $res = execute($query);
    return $res;
  }

  /**
   * Vérifie si l'utilisateur n'est pas deja enregistrer
   * On utilise l'adresse mail qui doit être unique
   * @return vrai si l'utilisateur n'est pas encore enregistrer, sinon retourne faux
   */
  public function verifierSiNouveau($email) {
    $res = query("SELECT count(*) as nb FROM users WHERE email = '$email'");
    return ($res['nb'] === '0');
  }

}

/**
 * Vérifie que l'utilisateur n'existe pas et l'enregistre en base de données
 * @return vrai si tout c'est bien passé
 */
function enregistrerUtilisateur($name, $firstname, $email, $telephone) {
  if (Visiteur::verifierSiNouveau(strtolower($email))) {
    $user = new Visiteur(strtolower($email), $name, $firstname, $telephone);
    return $user->enregistrer();
  }
  return false;
}

?>