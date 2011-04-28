<?php
// Include Class
error_reporting(E_ALL);
define('SETUP_PROJECT', 'setup-project');
define('SYNC_PROJECT', 'sync-project');
define('ADD_BC', 'add-bc');
define('SYNC_BC', 'sync-bc');
define('ADD_DOMAIN_MODULE', 'add-domain-module');
define('SYNC_DOMAIN_MODULE', 'sync-domain-module');


fwrite(STDOUT, "What you would like todo ? [setup-project] [sync-project] [add-bc] [sync-bc] [add-domain-module] [sync-domain-module]\n");
$action = trim(fgets(STDIN));

// include task
require_once "tasks/{$action}.php";