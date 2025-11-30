<?php

require_once __DIR__ . '/util/config.php';
require_once __DIR__ . '/util/app.php';

Config::set('basePath', __DIR__);
// Set the session id if you want to automatically download puzzle inputs for assignments.
// Can easily be taken from your browser cookies when logged in to adventofcode.com
Config::set('aocSessionId', '53616c7465645f5f6b606087d92f3964b4811b1dc07d302b9ac3c1a2946003ec2e8258b0b33169567436764be513fa2553049960422f9cfde7012461d2d145b8');
App::run($_SERVER['argv']);
