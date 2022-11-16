<?php

use JasminWeb\Jasmin\Command\MtRouter\MtRouter as JasminMtRouter;
use JasminWeb\Jasmin\Connection\Session as JasminSession;
use JasminWeb\Jasmin\Connection\SocketConnection as JasminConnector;


$J_connection = JasminConnector::init('127.0.0.1', 8990, 500000);
$J_session = JasminSession::init('jcliadmin', 'jclipwd', $J_connection);
$manager = new JasminMtRouter($J_session);

// Show all MtRouters
$manager->all();

// Create a new MtRouter
$errors = '';
$manager->add([
  'order' => 3,
  'type' => 'StaticMTRoute',
  'rate' => '500',
  'connector' => 'smppc(some_smppConnector_id)',
  'filters' => ['some_filter_id'],
], $errors);

// Remove a MtRouter
$manager->remove('3');
