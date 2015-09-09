<?php
/**
 * @file
 * Ce fichier contient tout les controlleurs du site.
 * Ce fichier permet aussi d'initialiser le site.
 */

session_start(); //DONNEES GARDER DURANT LA SESSION UTILISATEUR 
// web/index.php
// REQUIRE PERMET DE FAIRE EN SORTE QUE LE PHP EST REQUIS
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
/**
 * On ajoute dans l'application la fonction d'enregistrement de l'utilisateur
 */
$app['user.save'] = $app->protect(function($name, $firstname, $email, $telephone, $abonNewsletter, $offrePartenaire) {
  return enregistrerUtilisateur($name, $firstname, $email, $telephone, $abonNewsletter, $offrePartenaire);
});
/**
 * Cette fonction permet d'appeler la fonction qui récupère les cadeaux et de la rendre disponible depuis la variable $app
 */
$app['cadeau.get'] = $app->protect(function($code) {
  return recupererCadeau($code);
});
/**
 * On ajoute dans l'application la fonction d'envoie de mail du coupon cadeaux
 */
$app['mail.send'] = $app->protect(function($coupon, $user) {
  return envoyerCouponCadeaux($coupon, $user);
});

/**
 * Détection automatique de la langue du navigateur
 *
 * Les codes langues du tableau $aLanguages doivent obligatoirement être sur 2 caractères
 *
 * Utilisation : $langue = autoSelectLanguage(array('fr','en','es','it','de','cn'), 'en')
 *
 * @param array $aLanguages Tableau 1D des langues du site disponibles (ex: array('fr','en','es','it','de','cn')).
 * @param string $sDefault Langue à choisir par défaut si aucune n'est trouvée
 * @return string La langue du navigateur ou bien la langue par défaut
 * @author Hugo Hamon
 * @version 0.1
 */
function autoSelectLanguage($aLanguages, $sDefault = 'fr') {
  if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $aBrowserLanguages = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
    foreach($aBrowserLanguages as $sBrowserLanguage) {
      $sLang = strtolower(substr($sBrowserLanguage,0,2));
      if(in_array($sLang, $aLanguages)) {
        return $sLang;
      }
    }
  }
  return $sDefault;
}
/**
 * Controller de la page d'accueil
 * @category Controlleur
 * @return le code html de la page d'accueil
 */
$app->get('/', function () use ($app) {
  return $app['twig']->render('index.twig.html', array());
});

/**
 * @category Controlleur
 * Controller de la page de jeu
 * Affiche la page de jeu avec le tableau pour le code ticket
 */
$app->get('/jouer', function () use ($app) {
  return $app['twig']->render('jouer_code.twig.html', array()); // Appel twig du template de la page de jeu
});
/**
 * @category Controlleur
 * Controller de la page de jeu / traitement du code cadeau et du formulaire d'inscription
 */
$app->post('/jouer', function (Request $request) use ($app, $_SESSION) { // on recupere le code ticket et on verifie si il est correcte
  if ($request->get('code_verif')) {
    // $_SESSION = Un tableau associatif des valeurs stockées dans les sessions, et accessible au script courant.
    $_SESSION['coupon'] = $request->get('code');
    // afficher la page de formulaire suite à l'envoi du code du ticket
    return $app['twig']->render('jouer_form.twig.html', array());
  } else if ($request->get('user')) {
    $name = $request->get('name');
    $firstname = $request->get('firstname');
    $email = $request->get('email');
    $telephone = $request->get('telephone');
    $offrePartenaire = $request->get('offre_partenaire');
    $abonNewsletter = $request->get('abon_newsletter');
    $cadeau = $app['cadeau.get']($_SESSION['coupon']);
    $user = $app['user.save']($name, $firstname, $email, $telephone, $abonNewsletter, $offrePartenaire);
    if ($user != null) {
      if ($cadeau != null) {
        // si les conditions sont ok alors le mail avec le code coupon est envoyé
        $app['mail.send']($cadeau->coupon, $user);
      }
      return $app['twig']->render('jouer_recompense.twig.html', array('cadeau' => $cadeau)); // si les conditions sont ok alors on affiche le cadeau gagner ou perdu
    }
    return $app['twig']->render('jouer_form.twig.html', array('error' => 'Ce mail est déjà utilisé'));
  }// vérification des données utilisateur et code d'erreur si déjà enregistré
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
        'description' => 'Paris est la capitale mondiale de la mode, de la culture, de l\'art, de la gastronomie... bref, une destination à ne manquer sous aucun prétexte.',
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
 * Controller de la carte / informations carte
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
 * Controller du reglement du jeu
 */
$app->get('/reglement_jeu', function () use ($app) {
  return $app['twig']->render('reglement_jeu.twig.html');
});

/**
 * @category Controller
 * Controller des conditions generales
 */
$app->get('/condition_general', function () use ($app) {
  return $app['twig']->render('condition_general.twig.html');
});
$app->run();
?>
