<?php

/**
 * The auto-loading function, which will be called every time a file "is missing"
 * NOTE: don't get confused, this is not "__autoload", the now deprecated function.
 * The PHP Framework Interoperability Group (@see https://github.com/php-fig/fig-standards) recommends using a
 * standardized auto-loader https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md, so we do:
 *
 * @param $class string The to-be-loaded class's name
 */
function autoload($class) {

    // if file does not exist in PATH_LIBS folder (set it in config/config.php)
    if (file_exists(PATH_LIBS . $class . '.php')) {
        require PATH_LIBS . $class . '.php';
    } else {
        exit ('The file ' . $class . '.php is missing in the libs folder.');
    }
}

// spl_autoload_register defines the function that is called every time a file is missing. as we created this
// function above, every time a file is needed, autoload(THENEEDEDCLASS) is called
spl_autoload_register("autoload");
