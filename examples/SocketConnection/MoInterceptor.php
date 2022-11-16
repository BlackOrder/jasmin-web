<?php

use JasminWeb\Jasmin\Command\MoInterceptor\MoInterceptor as JasminMoInterceptor;
use JasminWeb\Jasmin\Connection\Session as JasminSession;
use JasminWeb\Jasmin\Connection\SocketConnection as JasminConnector;


$J_connection = JasminConnector::init('127.0.0.1', 8990, 500000);
$J_session = JasminSession::init('jcliadmin', 'jclipwd', $J_connection);
$manager = new JasminMoInterceptor($J_session);

// Show all MoInterceptors
$manager->all();

// Create a new MoInterceptor
$errors = '';
$manager->add([
  'type' => 'StaticMOInterceptor',
  'filters' => ['some_filter_id'],
  'order' => 13,
  'script' => 'python3(/etc/jasmin/script.py)',
], $errors);

// Remove a MoInterceptor
$manager->remove('13');
