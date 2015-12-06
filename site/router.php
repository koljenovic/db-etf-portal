<?php
date_default_timezone_set('Europe/Sarajevo');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'class/Sesija.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

\Slim\Slim::registerAutoloader();

chdir(__DIR__);
$loader = new Twig_Loader_Filesystem(realpath('../site/twig/template'));
$loader->addPath(realpath('../site/twig/template/base'), 'base');
$loader->addPath(realpath('../site/twig/template/part'), 'part');
$loader->addPath(realpath('../site/twig/template/page'), 'page');
$twig = new Twig_Environment($loader);

$app = new \Slim\Slim();

$urls = array(
        'rootUri' => $app->request()->getRootUri(),
        );

require_once "vendor/autoload.php";

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/class"), $isDevMode);

$conn = array(
    'url' => 'postgres://portal:test@localhost/portal',
);

$em = EntityManager::create($conn, $config);

function generateSalt() {
    return uniqid(mt_rand(), true);
}

$app->get('/', function () use ($twig, $urls, $em) {
    if (array_key_exists('session', $_COOKIE)) {
        $c = explode(':', $_COOKIE['session']);
        $s = $em->find('Sesija', $c[1]);
        if($s->getValidna()) {
            echo '<h3>Ulogovan:</h3>';
            echo '<pre>';
            print_r($s);
            echo '</pre>';
        } else {
            setcookie('session', '', time() - 60 * 60 * 24 * 30, $urls['rootUri'] . '/');
        }
    }
});

$app->get('/login/', function () use ($twig, $urls) {
    // @TODO: premjetiti u POST/login/ objediniti i refaktorisati da provjeri validnost sesije
    if(!array_key_exists('session', $_COOKIE)) {
        echo $twig->render('@page/login.html', $urls);
    } else {
        header('Location: /');
        die();
    }
});

$app->post('/login/', function () use ($em, $urls) {
    // @TODO objediniti sa GET/login
    if(array_key_exists('session', $_COOKIE)) {
        return;
    }
    try {
        $ds = ldap_connect("localhost");
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option( $ds, LDAP_OPT_REFERRALS, 0 );
        $user_data = explode('@', $_POST['email']);
        $user = $user_data[0];
        $pass = $_POST['pass'];
        $r = ldap_bind($ds, "cn=$user,ou=users,dc=db,dc=etf,dc=lab,dc=ba", $pass);
        $sr = ldap_search($ds, "dc=db,dc=etf,dc=lab,dc=ba", "cn=$user");
        $info = ldap_get_entries($ds, $sr);
        $r = [
            'korisnik' => $info[0]['cn'][0],
            'ime' => $info[0]['givenname'][0],
            'prezime' => $info[0]['sn'][0],
            'kljuc' => generateSalt(),
            'lozinka' => $pass,
            'rola' => '',
        ];

        $s = new Sesija();
        $s->setKorisnik($r['korisnik']);
        $s->setIme($r['ime']);
        $s->setPrezime($r['prezime']);
        $s->setKljuc($r['kljuc']);
        $s->setLozinka($r['lozinka']);
        $s->setRola($r['rola']);

        $em->persist($s);
        $em->flush();
        setcookie('session', $r['kljuc'] . ':' . $s->getId(), time() + 60 * 60 * 24 * 30, $urls['rootUri'] . '/');

        echo json_encode($r);
    } catch (Exception $e) {
        if ($e->getCode() != 2) {
            $r = [
                "error" => $e->getCode()
            ];
//            echo json_encode($r);
            echo '<pre>';
            echo $e->getTraceAsString();
            echo '</pre>';
//            print_r($e->getTrace());
        } else {
            echo json_encode([]);
        }
    }
});



$app->get('/logout/', function () use ($em, $urls) {
    if(array_key_exists('session', $_COOKIE)) {
        $c = explode(':', $_COOKIE['session']);
        $s = $em->find('Sesija', $c[1]);
        if ($s->getKljuc() == $c[0] && $s->getValidna()) {
            $s->setValidna(false);
            $s->setKrajDt(new DateTime("now"));
            $em->merge($s);
            $em->flush();
            setcookie('session', '', time() - 60 * 60 * 24 * 30, $urls['rootUri'] . '/');
        } else {
            $r = [
                "error" => 'Sesija nije validna.',
            ];
            echo json_encode($r);
        }
    }
});

$app->run();
