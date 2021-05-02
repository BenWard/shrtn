<?php

require('./shrtn.php');

define('shrtn_config', './config/shrtn.json');

$shrtn = new \uk\benward\Shrtn(shrtn_config);
$shrtn->handleRequest();
