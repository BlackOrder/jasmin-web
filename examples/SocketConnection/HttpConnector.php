<?php

use JasminWeb\Jasmin\Command\HttpConnector\Connector as JasminHttpConnector;
use JasminWeb\Jasmin\Connection\Session as JasminSession;
use JasminWeb\Jasmin\Connection\SocketConnection as JasminConnector;


$J_connection = JasminConnector::init('127.0.0.1', 8990, 500000);
$J_session = JasminSession::init('jcliadmin', 'jclipwd', $J_connection);
$manager = new JasminHttpConnector($J_session);

// Show all HttpConnectors
$manager->all();

// Create a new HttpConnector
$errors = '';
$manager->add([
  'cid' => 'new_httpConnector_id',
  'url' => 'https://api.jasmin.com/sms',
  'method' => 'GET',
], $errors);

// Show a HttpConnector
$manager->show('some_httpConnector_id');

// Remove a HttpConnector
$manager->remove('some_httpConnector_id');
