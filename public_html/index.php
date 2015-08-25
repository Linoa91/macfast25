<?php
session_start();
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../services/mail.php';
require_once __DIR__.'/../services/user.php';
require_once __DIR__.'/../services/cadeau.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/templates',
));

$app['user.save'] = $app->protect(function($name, $firstname, $email, $telephone, $abonNewsletter, $offrePartenaire) {
  return enregistrerUtilisateur($name, $firstname, $email, $telephone, $abonNewsletter, $offrePartenaire);
});
$app['cadeau.get'] = $app->protect(function($code) {
  return recupererCadeau($code);
});
$app['mail.send'] = $app->protect(function($coupon, $user) {
  return envoyerCouponCadeaux($coupon, $user);
});


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
 * Controller de la page de jeu / affichage des cadeaux
 */
$app->get('/jouer', function () use ($app) {
  return $app['twig']->render('jouer_code.twig.html', array()); // retourne un tableau pour le formulaire
});
$app->post('/jouer', function (Request $request) use ($app, $_SESSION) { // récupère les données utilisateurs
  if ($request->get('code_verif')) {
    $_SESSION['coupon'] = $request->get('code');
    return $app['twig']->render('jouer_form.twig.html', array());
  } else if ($request->get('user')) {
    $name = $request->get('name');
    $firstname = $request->get('firstname');
    $email = $request->get('email');
    $telephone = $request->get('telephone');
    $SaveUser = $app['user.save'];
    $offrePartenaire = $request->get('offre_partenaire');
    $abonNewsletter = $request->get('abon_newsletter');
    $user = $SaveUser($name, $firstname, $email, $telephone, $abonNewsletter, $offrePartenaire);
    if ($user != null) {
      $cadeau = $app['cadeau.get']($_SESSION['coupon']);
      if ($cadeau != null && $cadeau->type != "MUS") {
        $app['mail.send']($cadeau->coupon, $user);
      }
      return $app['twig']->render('jouer_recompense.twig.html', array('cadeau' => $cadeau));
    }// vérification des données utilisateur et code d'erreur si déjà enregistré
    return $app['twig']->render('jouer_form.twig.html', array('error' => 'Ce mail est déjà utilisé'));
  }
  return $app['twig']->render('jouer_code.twig.html', array());
});

/**
 * @category Controller
 * Controller de la gallerie de cadeaux / informations cadeaux
 */
$app->get('/cadeaux', function () use ($app) {
  return $app['twig']->render('cadeaux.twig.html', array(
    "cadeaux" => array(
      array(
        'nom' => '5 voyages à New York',
        'description' => 'Laissez-vous surprendre par la demesure de "Big Apple", la ville qui ne dort jamais ; une ville environnante qui fascnie par son énergie débordante.',
        'img' => 'new_york.jpg',
        'alt' => 'cadeau new york'
      ),
      array(
        'nom' => '15 weeks end à Paris',
        'description' => 'Paris est la capitale ;ondiale de la mode, de la culture, de l\'art, de la gastronomie... bref, une destination à ne manquer sous aucun prétexte.',
        'img' => 'paris.jpg',
        'alt' => 'cadeau paris'
      ),
      array(
        'nom' => '50 IPhone 6',
        'description' => 'Plus large, mais beaucoup plus fin< Plus puissant, mais remarquablement économe en énergie. Sous son design profilé s\'opère une fusion parfaite entre matériel et logiciel.',
        'img' => 'iphone.jpg',
        'alt' => 'cadeau iphone6'
      ),
      array(
        'nom' => '50 Samsung Galaxy S5',
        'description' => 'en tant que smartphone propulsé sur le terrain, resserré, du haut de gamme, le GS5, résistant à l\'eau et à la poussière, affiche des caractéristiques techniques haut de gamme.',
        'img' => 'galaxy_s5.jpg',
        'alt' => 'cadeau galaxy s5'
      ),
      array(
        'nom' => '1 Suzuki Swift 3 Sport',
        'description' => 'Le look sportif, c’est avant tout une question d’attitude. Et la Swift n’en manque pas. Homogène, elle offre des vraies sensations de sportive, tout en restant parfaitement civilisée au quotidien.',
        'img' => 'voiture.jpg',
        'alt' => 'cadeau swift sport'
      ),
      array(
        'nom' => '2 000 places de cinéma UGC',
        'description' => 'Profiter des meilleurs films du moment avec votre pass solo.',
        'img' => 'ugc.jpg',
        'alt' => 'cadeau place de cinéma'
      ),
      array(
        'nom' => '5 000 téléchagements musique',
        'description' => 'Télécharger votre musique préférée avec votre code promo, c’est gratuit.',
        'img' => 'musique.jpg',
        'alt' => 'cadeau téléchargement musique'
      ),
      array(
        'nom' => '30 000 goodies',
        'description' => 'Des milliers de cadeaux aux couleurs de MAC FAST qui vous accompagnerons parout.',
        'img' => 'goodies.jpg',
        'alt' => 'cadeau goodies'
      ),
    )
  ));
});

/**
 * @category Controller
 * Controller de la gallerie de cadeaux / informations cadeaux
 */
$app->get('/carte', function () use ($app) {
  return $app['twig']->render('carte.twig.html');
});


/**
 * @category Controller
 * Controller des mentionslegales
 */
$app->get('/mentionslegales', function () use ($app) {
  return $app['twig']->render('mentionslegales.twig.html');
});

/**
 * @category Controller
 * Controller des mentionslegales
 */
$app->get('/reglement_jeu', function () use ($app) {
  return $app['twig']->render('reglement_jeu.twig.html');
});

/**
 * @category Controller
 * Controller des mentionslegales
 */
$app->get('/condition_general', function () use ($app) {
  return $app['twig']->render('condition_general.twig.html');
});
$app->run();
?>
