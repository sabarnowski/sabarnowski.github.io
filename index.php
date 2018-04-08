<?php

require_once __DIR__.'/../vendor/autoload.php';

use SymfonyComponentHttpFoundationRequest;

$app = new SilexApplication();
$app['debug'] = (boolean) getenv('DEBUG');
$app->register(new SilexProviderSessionServiceProvider());
$app->register(new SilexProviderTwigServiceProvider(), [
    'twig.path' => __DIR__.'/../app/Resources/views',
]);


$app->get('/', function(SilexApplication $app) {
    return $app['twig']->render('index.html.twig');
});

$app->post('/open-door', function(Request $request, SilexApplication $app) {
    $formData = $request->request->all();

    if (!CarriotsDoorFormIsValidForm($formData)) {
        return $app['twig']->render('index.html.twig', ['error' => 'Wrong credentials']);
    }

    try {
        CarriotsDoorApisendData($formData);
    } catch (Exception $e) {
        return $app['twig']->render('index.html.twig', ['error' => $e->getMessage()]);
    }

    $app['session']->getFlashBag()->add('success', 'The request has been sent');

    return $app->redirect('/');
});

$app->run();

