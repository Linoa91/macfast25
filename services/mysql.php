<?php 

function query($query) {
  $mysqli = new mysqli('localhost', 'root', '', 'macfast25');
  if ($mysqli->connect_errno)
    die('Impossible de sélectionner la base de données : ' . $mysqli->connect_error);
  $result = $mysqli->query($query);
  if (!$result) return null;
  $res = $result->fetch_assoc();
  $result->close();
  $mysqli->close();
  return $res;
}

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