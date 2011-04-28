<?php
// Include Class
error_reporting(E_ALL);
/*
fwrite(STDOUT, "Bounded Context name: \n");
$boundedContextName = trim(fgets(STDIN));

fwrite(STDOUT, "Path to dir where new BC should be created: \n");
$pathWhereToCreate = trim(fgets(STDIN));
*/

$profileDocument = new DOMDocument();
$profileDocument->load('profile.xml');

$xpath = new DOMXpath($profileDocument);
$elements = $xpath->query("//folder");
foreach($elements as $node){
    print $node->getAttribute('name') . '\n';
}
exit;

// @todo: read from somewhere
$profileFolderStructure = array(
    $boundedContextName => array(
        'build' => array(
            'di' => array(
                'input' => false
            )
        ),
        'config' => array(
        ),
        'daemon' => array(
        ),
        'interface' => array(
            'default' => array(
                'config' => false,
                'models' => false
            )
        ),
        'library' => array(
            'Command' => array(
               'Handler' => false 
            ),
            'EventHandler' => false,
            'Query' => false,
            'Remote' => array(
                'Invoker' => false
            ),
            'Service' => array(
                'Read' => false,
                'Write' => false,
            )
        ),
        'web-services' => array(
            $boundedContextName => array(
                'General' => array(
                    'V1r0' => array(
                        'Struct' => false
                    )
                )
            )
        )
    )
);

// Execute
output('Starting BC setup');
output('-----------------');
parse($profileFolderStructure, $pathWhereToCreate);
output('-----------------');



// Functions
function parse($folderStructure, $path, $override = false, $lvl = 1){
    foreach($folderStructure as $folder => $deeperLevel){
        $pathToFolder = $path . DIRECTORY_SEPARATOR . $folder;
        
        if(!file_exists($pathToFolder)){
            mkdir($pathToFolder);
        } 
        output(str_repeat(' ', $lvl * 2) . $folder);
        if(is_array($deeperLevel)){
            $nextLvl = $lvl + 1;
            parse($deeperLevel, $pathToFolder, $override, $nextLvl);
        }
    }
}

function output($msg){
    print "Info: {$msg} \n";
}