<?php

/**
 * the auto-loading function, which will be called every time a file "is missing"
 * NOTE: don't get confused, this is not "__autoload", the now deprecated function
 * The PHP Framework Interoperability Group (@see https://github.com/php-fig/fig-standards) recommends using a
 * standardized auto-loader https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md, so we do:
 */
function autoload($class)
{
    // if file does not exist in LIBS_PATH folder [set it in config/config.php]
    $autoloadPaths = parse_ini_file("directories.ini", true);
    $classPath = "";
    foreach ($autoloadPaths as $group => $groupItem) {
        foreach ($groupItem as $path) {
            if (file_exists($path . $class . ".php")) {
                $classPath = $path . $class . ".php";
            } else {
                if ($classPath != "") {
                    exit ("The file " . $class . '.php has multiple implementations. Please ensure unique
                           class-names within your autoload area. (' . $path . " : " . $classPath . ');');
                }
            }
        }
    }
    if ($classPath != "") {
        require $classPath;
    } else {
        exit ('The file ' . $class . '.php is missing in your autoload area.');
    }

}

// spl_autoload_register defines the function that is called every time a file is missing. as we created this
// function above, every time a file is needed, autoload(THENEEDEDCLASS) is called
spl_autoload_register("autoload");
