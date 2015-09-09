<?php 
/**
 * Ce fichier contient les différentes méthodes utiliser pour intéragir avec la base de données.
 */

/**
 * Cette méthode permet d'executer des requete de type 'SELECT'
 * @param string $query La requete à exécuter
 * @return array un tableau associatif contenant le resultat de la requete
 */
function query($query) {
  $mysqli = new mysqli('localhost', 'root', '', 'macfast25');
  if ($mysqli->connect_errno)
    die('Impossible de sélectionner la base de données : ' . $mysqli->connect_error);
  $result = $mysqli->query($query);

  // if ($result !== TRUE) { return null; }
  $res = $result->fetch_assoc();
  $result->close();
  $mysqli->close();
  return $res;
}

/**
 * Cette méthode permet d'executer les requetes SQL de type 'DELETE', 'INSERT', 'UPDATE' ...
 * @param string $query La requete e executer
 * @return boolean TRUE si la requete s'est bien executée, sinon FALSE
 */
function execute($query) {
  $res = true;
  $mysqli = new mysqli('localhost', 'root', '', 'macfast25');
  if ($mysqli->connect_errno)
    die('Impossible de sélectionner la base de données : ' . $mysqli->connect_error);
  if (!$mysqli->query($query)) {
    $res = false;
  }
  $mysqli->close();
  return $res;
}

?>