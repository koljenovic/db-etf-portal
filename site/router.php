<?php

require 'vendor/autoload.php';
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

function generateSalt() {
    return uniqid(mt_rand(), true);
}

$app->get('/', function () use ($twig, $urls) {
    
});

$app->get('/admin/', function () use ($twig, $urls) {
    echo $twig->render('@page/login.html', $urls);
});

$app->post('/login/', function () {
    try {
        $ds = ldap_connect("192.168.1.134");
        ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option( $ds, LDAP_OPT_REFERRALS, 0 );
        $user_data = explode('@', $_POST['email']);
        $user = $user_data[0];
        $pass = $_POST['pass'];
        $r = ldap_bind($ds, "cn=$user,ou=users,dc=db,dc=etf,dc=lab,dc=ba", $pass);
        $sr = ldap_search($ds, "dc=db,dc=etf,dc=lab,dc=ba", "cn=$user");
        $info = ldap_get_entries($ds, $sr);
        $r = [
            'username' => $info[0]['cn'][0],
            'name' => $info[0]['givenname'][0],
            'surname' => $info[0]['sn'][0],
            'session_key' => generateSalt()
        ];
        echo json_encode($r);
    } catch (Exception $e) {
        if ($e->getCode() != 2) {
            $r = [
                "error" => $e->getCode()
            ];
            echo json_encode($r);
        } else {
            echo json_encode([]);
        }
    }
});

$app->post('/logout/', function () {
    // @TODO: invalidiraj sesiju
});

$app->run();
