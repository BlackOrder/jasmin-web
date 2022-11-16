<?php

use JasminWeb\Jasmin\Command\MtInterceptor\MtInterceptor as JasminMtInterceptor;
use JasminWeb\Jasmin\Connection\Session as JasminSession;
use JasminWeb\Jasmin\Connection\SocketConnection as JasminConnector;


$J_connection = JasminConnector::init('127.0.0.1', 8990, 500000);
$J_session = JasminSession::init('jcliadmin', 'jclipwd', $J_connection);
$manager = new JasminMtInterceptor($J_session);

// Show all MtInterceptors
$manager->all();

// Create a new MtInterceptor
$errors = '';
$manager->add([
  'type' => 'StaticMTInterceptor',
  'filters' => ['some_filter_id'],
  'order' => 19,
  'script' => 'python3(/etc/jasmin/script.py)',
], $errors);

// Remove a MtInterceptor
$manager->remove('19');
