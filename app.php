<?php

require_once __DIR__ . '/util/config.php';
require_once __DIR__ . '/util/app.php';

Config::set('basePath', __DIR__);
// Set the session id if you want to automatically download puzzle inputs for assignments.
// Can easily be taken from your browser cookies when logged in to adventofcode.com
Config::set('aocSessionId', '');
App::run($argv);
