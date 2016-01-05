<?php
date_default_timezone_set('Europe/Sarajevo');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'class/Sesija.php';
require_once 'class/Medium.php';
require_once 'class/MediaTip.php';
require_once 'class/Kategorija.php';

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
    'rootUri' => $app->request->getRootUri(),
);

function generateSalt()
{
    return uniqid(mt_rand(), true);
}

function is_ulogovan($sesija_kljuc = null)
{
    global $em, $urls;
    $r = array(
        'status' => false,
    );
    if (!is_null($sesija_kljuc)) {
        $sesija = $sesija_kljuc;
    } elseif (array_key_exists('session', $_COOKIE)) {
        $sesija = $_COOKIE['session'];
    } else {
        return $r;
    }

    $c = explode(':', $sesija);
    $s = $em->find('Sesija', $c[1]);
    if ($s->getKljuc() == $c[0] && $s->getValidna()) {
        $r['status'] = true;
        $r['korisnik'] = $s;
        $r['korisnik_string'] = var_export($s, true);
        return $r;
    } else {
        setcookie('session', '', time() - 60 * 60 * 24 * 30, $urls['rootUri'] . '/');
    }
}

$app->hook('slim.before.router', function() use ($app) {
    // @TODO ovdje sam stao, sad treba napraviti da provjeri da li u podacima ima polje o sesiji i ako ima da loguje sa tim podacima
    $env = $app->environment();
    $env['ulogovan'] = is_ulogovan($app->request->params('session'));
});

$login = function ($role = 'member') {
    global $app;
    $env = $app->environment();
    if (!$env['ulogovan']['status']) {
        $app->redirect('/login/');
    }
};

// @TODO obrisati u prod
$app->get('/info/', function () {
    echo phpinfo();
});

$app->get('/', function () use ($app, $twig, $urls, $em) {
//    echo $twig->render('@page/landing.html', $urls);
    $env = $app->environment();
    $files = $em->getRepository('Medium')->findAll();
    $urls['listing'] = $files;
    if(array_key_exists('korisnik', $env['ulogovan'])) {
        $urls['ulogovan'] = $env['ulogovan']['korisnik'];
    }
    echo $twig->render('@page/media_listing.html', $urls);
});


$app->get('/login/', function () use ($app, $twig, $urls) {
    $env = $app->environment();
    if (!$env['ulogovan']['status']) {
        echo $twig->render('@page/login.html', array_merge($urls, $env['ulogovan']));
    } else {
        header('Location: /');
        die();
    }
});

$app->post('/logtest/', function () use ($app, $em, $urls) {
    $env = $app->environment();
    if ($env['ulogovan']['status']) {
        echo "{'status': 'ulogovan'}";
    } else {
        echo "{'status': 'nije ulogovan'}";
    }
});

$app->post('/login/', function () use ($app, $em, $urls) {
    $env = $app->environment();
    if (!$env['ulogovan']['status']) {
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

            $sesija_kljuc = $r['kljuc'] . ':' . $s->getId();
            setcookie('session', $sesija_kljuc, time() + 60 * 60 * 24 * 30, $urls['rootUri'] . '/');
//            header('Location: /');
//            die();
            echo json_encode(array('session' => $sesija_kljuc));
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

$app->get('/logout/', $login, function () use ($em, $urls) {
    $c = explode(':', $_COOKIE['session']);
    $s = $em->find('Sesija', $c[1]);
    $s->setValidna(false);
    $s->setKrajDt(new DateTime("now"));
    $em->merge($s);
    $em->flush();
    setcookie('session', '', time() - 60 * 60 * 24 * 30, $urls['rootUri'] . '/');
    header('Location: /');
    die();
});

// @TODO POST logout

$app->get('/media/', function () use ($twig, $em, $urls) {
    if ($urls['ulogovan']) {
        echo $twig->render('@page/media.html', $urls);
    } else {
        // @todo opet centralizovati ako nije ulogovan cemi ima cemu nema pristup zdra'o
        header('Location: /login/');
        die();
    }
});

$app->get('/media/list/', function () use ($twig, $em, $urls) {
    if ($urls['ulogovan']) {
        $files = $em->getRepository('Medium')->findAll();
        $urls['listing'] = $files;
        echo $twig->render('@page/media_listing.html', $urls);
    } else {
        header('Location: /login/');
        die();
    }
});

$app->get('/admin/editor/', function () use ($twig, $em, $urls) {
    if ($urls['ulogovan']) {
        echo $twig->render('@page/editor.html', $urls);
    } else {
        header('Location: /login/');
        die();
    }
});

$app->get('/admin/uploader/', function () use ($twig, $em, $urls) {
    if ($urls['ulogovan']) {
        echo $twig->render('@page/uploader.html', $urls);
    } else {
        header('Location: /login/');
        die();
    }
});

$app->get('/media/:id/', function ($id) use ($twig, $em, $urls, $app) {
    if ($urls['ulogovan']) {
        $m = $em->find('Medium', $id);
        $dest_file = '../data/' . $m->getFilename();
        $app->response->headers->set('Content-Type', $m->getTip()->getNaziv());
        // @TODO mozda neki pametniji kriterij sta prikazati sta downloadati ili da caller odluci
        if(explode('/', $m->getTip()->getNaziv())[0] != 'image') {
            $app->response->headers->set('Content-Disposition', 'attachment; filename="' . substr($m->getFilename(), 14) . '"');
        }
        $app->response->headers->set('Expires', '0');
        $app->response->headers->set('Cache-Control', 'must-revalidate');
        $app->response->headers->set('Pragma', 'public');
        $app->response->headers->set('Content-Length', '' . filesize($dest_file));
        // @TODO ovo treba postaviti da bude centralizovani data_dest ustvari source
        echo file_get_contents($dest_file);
    }
});

$app->post('/media/', function () use ($em, $urls, $app) {
    // @todo neka pametna provjera da li je ulogovan centralizovati da se ne mora stalno zivkati
    if (is_uploaded_file($_FILES['datoteka']['tmp_name'])) {
        $dest_dir = '../data/';
        $dest_name = uniqid() . '_' . basename($_FILES['datoteka']['name']);
        $dest_file = $dest_dir . $dest_name;
        $ext = '.' . strtolower(pathinfo($dest_file, PATHINFO_EXTENSION));
        $tip = $em->getRepository('MediaTip')->findOneBy(array('ekstenzija' => $ext));
        if($tip && $tip->getDozvoljen()) {
            // @TODO moze biti tekst ili binary na osnovu toga ko submita koristiti
            // razlicite metode snimanja ili mozda koristiti dvije metode hmm
            if(move_uploaded_file($_FILES['datoteka']['tmp_name'], $dest_file)) {
                $m = new Medium();
                $m->setKorisnik($urls['korisnik']);
                $m->setFilename($dest_name);
                $m->setTip($tip);
                $k = $em->getRepository('Kategorija')->findOneBy(array('naziv' => 'root'));
                $m->setKategorija($k);

                $em->persist($m);
                $em->flush();
            } else {
                $app->response->setStatus(400);
                echo json_encode(array('error' => 'Puk\'o kvar.'));
            }
        } else {
            $app->response->setStatus(415);
            echo json_encode(array('error' => "$ext tip datoteke nije podržan ili dozvoljen"));
        }
    }
});

// **** PUBLIC API ZA OBJEKTE

// ** SESIJA **
$app->get('/sesija/:id/', function ($id) use ($twig, $em, $urls, $app) {});
$app->post('/sesija/:id/', function ($id) use ($twig, $em, $urls, $app) {});
$app->put('/sesija/:id/', function ($id) use ($twig, $em, $urls, $app) {});
$app->delete('/sesija/:id/', function ($id) use ($twig, $em, $urls, $app) {});

// ** MEDIATIP **
$app->get('/mediatip/:id/', function ($id) use ($twig, $em, $urls, $app) {});
$app->post('/mediatip/:id/', function ($id) use ($twig, $em, $urls, $app) {});
$app->put('/mediatip/:id/', function ($id) use ($twig, $em, $urls, $app) {});
$app->delete('/mediatip/:id/', function ($id) use ($twig, $em, $urls, $app) {});

// ** KATEGORIJA **
$app->get('/kategorija/:id/', function ($id) use ($twig, $em, $urls, $app) {

});

//$app->post('/kategorija/', function ($id) use ($twig, $em, $urls, $app) {
//    $k = new Kategorija();
//    // naziv, parent, media
//    $parsedBody = $request->getParsedBody();
//    json_decode($input, true).
//    $k->setNaziv()
//    $m->setKorisnik($urls['korisnik']);
//    $m->setFilename($dest_name);
//    $m->setTip($tip);
//    $k = $em->getRepository('Kategorija')->findOneBy(array('naziv' => 'root'));
//    $m->setKategorija($k);
//
//    $em->persist($m);
//    $em->flush();
//});

$app->put('/kategorija/:id/', function ($id) use ($twig, $em, $urls, $app) {});
$app->delete('/kategorija/:id/', function ($id) use ($twig, $em, $urls, $app) {});

// ** SESIJA **
$app->get('/sesija/', $login, function () use ($twig, $em, $urls, $app) {
    $env = $app->environment();
    echo json_encode($env['ulogovan']['korisnik']->getSerial());
});

$app->post('/sesija/:id/', function ($id) use ($twig, $em, $urls, $app) {});
$app->put('/sesija/:id/', function ($id) use ($twig, $em, $urls, $app) {});
$app->delete('/sesija/:id/', function ($id) use ($twig, $em, $urls, $app) {});

$app->run();
