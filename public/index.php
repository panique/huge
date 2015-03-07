<?php

/**
 * A super-simple user-authentication solution, embedded into a small framework.
 *
 * HUGE
 *
 * @link https://github.com/panique/huge
 * @license http://opensource.org/licenses/MIT MIT License
 */

// auto-loading the classes (currently only from application/libs) via Composer's PSR-4 auto-loader
// later it might be useful to use a namespace here, but for now let's keep it as simple as possible
require '../vendor/autoload.php';

// start our application
new Application();
