<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../services/user.php';
require_once __DIR__.'/../services/cadeau.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// $link = mysql_connect('localhost', 'root', '') or die('Impossible de sélectionner la base de données');
// mysql_select_db('macfast25') or die('Impossible de sélectionner la base de données');
// $query = 'SELECT * FROM cadeaux';
// $result = mysql_query($query) or die('Échec de la requête : ' . mysql_error());
// mysql_close($link);
// var_dump($result);
$app = new Silex\Application();
$app['debug'] = true;
$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/templates',
));

$app['user.save'] = enregistrerUtilisateur;
// $app['cadeau.get'] = Cadeau::recupererCadeau;
$app['cadeau.get'] = recupererCadeau;

/**
 * Controller de la page d'accueil
 * @category Controlleur
 * @todo mettre en place le compte a rebour ({@link http://flipclockjs.com/api})
 * @return le code html de la page d'accueil
 */
$app->get('/', function () use ($app) {
  return $app['twig']->render('index.twig.html', array());
});

/**
 * @category Controlleur
 * Controller de la page de jeu
 */
$app->get('/jouer', function () use ($app) {
  return $app['twig']->render('jouer_form.twig.html', array());
});
$app->post('/jouer', function (Request $request) use ($app) {
  if ($request->get('user')) {
    $name = $request->get('name');
    $firstname = $request->get('firstname');
    $email = $request->get('email');
    $telephone = $request->get('telephone');
    if ($app['user.save']($name, $firstname, $email, $telephone)) {
      return $app['twig']->render('jouer_code.twig.html', array());
    }
    return $app['twig']->render('jouer_form.twig.html', array('error' => 'Ce mail est déjà utilisé'));
  } else if (!empty($request->get('code'))) {
    $cadeau = $app['cadeau.get']($request->get('code'));
    return $app['twig']->render('jouer_recompense.twig.html', array('cadeau' => $cadeau));
  }
  return $app['twig']->render('jouer_form.twig.html', array());
});

$app->run();
?>
