<?php
// Include Class
error_reporting(E_ALL);

// Allowed arguments & their defaults
$runmode = array(
    'online' => false,
    'help' => false
);
 
// Scan command line attributes for allowed arguments
foreach ($argv as $k => $arg) {
    if (substr($arg, 0, 2) == '--' && isset($runmode[substr($arg, 2)])) {
        $runmode[substr($arg, 2)] = true;
    }
}

