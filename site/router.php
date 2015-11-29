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

$app->get('/', function () use ($twig, $urls) {
    $ds = ldap_connect("192.168.1.134");
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option( $ds, LDAP_OPT_REFERRALS, 0 );
    $r = ldap_bind($ds, "cn=admin,dc=db,dc=etf,dc=lab,dc=ba", "");
    $sr = ldap_search($ds, "dc=db,dc=etf,dc=lab,dc=ba", "CN=mko*");
    $info = ldap_get_entries($ds, $sr);

    echo print_r($info);
    // echo $twig->render('@page/login.html', $urls);
});

$app->post('/login/', function () use ($app, $twig, $urls) {
    print_r($_POST);
});

$app->run();
