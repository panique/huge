<?php

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    // cancel everything when PHP is too old
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} elseif (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // loading the Official PHP Password Hashing Compatibility Library (see more in the README file)
    require 'application/libs/external/PasswordCompatibilityLibrary.php';
}