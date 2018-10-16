<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#checking-symfony-application-configuration-and-setup
// for more information
//umask(0000);

/**
 * BlackFire extension
 */
// If the header is set
if (isset($_SERVER['HTTP_BLACKFIRETRIGGER'])) {
    $config = new \Blackfire\ClientConfiguration();
    $config->setClientId('852814fc-548f-4216-bc37-03d5aab5662f');
    $config->setClientToken('c54644f37928f624f157280c2e1ec5353a11044c81022b4bfdcee1dc9284cc00');

    // let's create a client
    $blackfire = new \Blackfire\Client($config);
    // then start the probe
    $probe = $blackfire->createProbe();

    // When runtime shuts down, let's finish the profiling session
    register_shutdown_function(function () use ($blackfire, $probe) {
        // See the PHP SDK documentation for using the $profile object
        $profile = $blackfire->endProbe($probe);
    });
}

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '192.168.0.47', '::1']) || php_sapi_name() === 'cli-server')
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
Debug::enable();

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
