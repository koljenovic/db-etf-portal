<?php
date_default_timezone_set('Europe/Sarajevo');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require 'class/Sesija.php';
require 'class/Medium.php';
require 'class/Podaci.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

\Slim\Slim::registerAutoloader();

chdir(__DIR__);
$loader = new Twig_Loader_Filesystem(realpath('../site/twig/template'));
$loader->addPath(realpath('../site/twig/template/base'), 'base');
$loader->addPath(realpath('../site/twig/template/part'), 'part');
$loader->addPath(realpath('../site/twig/template/page'), 'page');
$twig = new Twig_Environment($loader);

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/class"), $isDevMode);

$conn = array(
    'url' => 'postgres://portal:test@localhost/portal',
);

$app = new \Slim\Slim();
$em = EntityManager::create($conn, $config);

$urls = array(
    'rootUri' => $app->request()->getRootUri(),
);

$urls['ulogovan'] = is_ulogovan();

function generateSalt()
{
    return uniqid(mt_rand(), true);
}

function is_ulogovan()
{
    global $em;
    global $urls;
    if (array_key_exists('session', $_COOKIE)) {
        $c = explode(':', $_COOKIE['session']);
        $s = $em->find('Sesija', $c[1]);
        if ($s->getKljuc() == $c[0] && $s->getValidna()) {
            $urls['korisnik'] = $s;
            $urls['korisnik_string'] = var_export($s, true);
            return true;
        } else {
            setcookie('session', '', time() - 60 * 60 * 24 * 30, $urls['rootUri'] . '/');
        }
    }
    return false;
}

$app->get('/', function () use ($twig, $urls, $em) {
    echo $twig->render('@page/landing.html', $urls);
});

$app->get('/login/', function () use ($twig, $urls) {
    if (!$urls['ulogovan']) {
        echo $twig->render('@page/login.html', $urls);
    } else {
        header('Location: /');
        die();
    }
});

$app->post('/login/', function () use ($em, $urls) {
    if (!$urls['ulogovan']) {
        try {
            $ds = ldap_connect("localhost");
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
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
            header('Location: /');
            die();
//            echo json_encode($r);
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
    }
});

$app->get('/logout/', function () use ($em, $urls) {
    if ($urls['ulogovan']) {
        $c = explode(':', $_COOKIE['session']);
        $s = $em->find('Sesija', $c[1]);
        $s->setValidna(false);
        $s->setKrajDt(new DateTime("now"));
        $em->merge($s);
        $em->flush();
        setcookie('session', '', time() - 60 * 60 * 24 * 30, $urls['rootUri'] . '/');
    }
    header('Location: /');
    die();
});

$app->get('/media/:id/', function ($id) use ($twig, $em, $urls) {
    if ($urls['ulogovan']) {
        // @TODO spremati filesize i mimetype u Podaci, konvertovati u base64 odma prije snimanja
        echo $twig->render('@page/media.html', $urls);
        $slika = $em->find('Podaci', $id);
        $cont = base64_encode(stream_get_contents($slika->getSadrzaj()));
        echo '<img style="width:320px" src="data:image/jpg;base64,' . $cont . '" />';
    }
});

$app->post('/media/', function () use ($em, $urls) {
    if (is_uploaded_file($_FILES['datoteka']['tmp_name'])) {
        $p = new Podaci();
        $f = fopen($_FILES['datoteka']['tmp_name'], "r");
        $fc = fread($f, $_FILES['datoteka']['size']);
        $p->setSadrzaj($fc);
        fclose($f);
        $em->persist($p);
        $em->flush();
        echo 'OK';
    }
});

$app->run();
