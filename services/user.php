<?php

require_once(__DIR__.'/mysql.php');

class Visiteur {
  private $IP;
  public $email;
  public $nom;
  public $prenom;
  private $telephone;
  protected $codeParticipation;
  private $abonNewsletter;
  private $offrePartenaire;

  function Visiteur($email, $nom, $prenom, $telephone, $abonNewsletter, $offrePartenaire) {
    $this->email = $email;
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->telephone = $telephone;
    $this->abonNewsletter = $abonNewsletter;
    $this->offrePartenaire = $offrePartenaire;
    $this->codeParticipation = $_SESSION['coupon'];
  }

  public function enregistrer() {
    $abonNewsletter = '0'; // Faux
    if ($this->abonNewsletter === true) {
      $abonNewsletter = '1'; // Vrai
    }
    $offrePartenaire = '0'; // Faux
    if ($this->offrePartenaire === true) {
      $offrePartenaire = '1'; // Vrai
    }
    $query = "INSERT INTO users (email, nom, prenom, telephone, newsletter, offre_partenaires, code_participation)".
        " VALUES ('$this->email', '$this->nom', '$this->prenom', '$this->telephone', '$abonNewsletter', '$offrePartenaire', '$this->codeParticipation');";
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
function enregistrerUtilisateur($name, $firstname, $email, $telephone, $abonNewsletter, $offrePartenaire) {
  $abon = false;
  if ($abonNewsletter !== NULL)
    $abon = true;
  $offre = false;
  if ($offrePartenaire !== NULL)
    $offre = true;
  $user = new Visiteur(strtolower($email), $name, $firstname, $telephone, $abon, $offre);
  if ($user->verifierSiNouveau(strtolower($email))) {
    if ($user->enregistrer()) {
      return $user;
    }
  }
  return NULL;
}

?>