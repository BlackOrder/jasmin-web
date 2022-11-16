<?php

use JasminWeb\Jasmin\Command\MoRouter\MoRouter as JasminMoRouter;
use JasminWeb\Jasmin\Connection\Session as JasminSession;
use JasminWeb\Jasmin\Connection\SocketConnection as JasminConnector;


$J_connection = JasminConnector::init('127.0.0.1', 8990, 500000);
$J_session = JasminSession::init('jcliadmin', 'jclipwd', $J_connection);
$manager = new JasminMoRouter($J_session);

// Show all MoRouters
$manager->all();

// Create a new MoRouter
$errors = '';
$manager->add([
  'order' => 7,
  'type' => 'StaticMORoute',
  'connector' => 'smpps(some_user_id)',
  'filters' => ['some_filter_id'],
], $errors);

// Remove a MoRouter
$manager->remove('7');
