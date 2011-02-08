<?php
$libraryPath = dirname(__FILE__) . '/../../library/';
require($libraryPath . 'Symfony/sfServiceContainerAutoloader.php');
sfServiceContainerAutoloader::register();

$sc = new sfServiceContainerBuilder();
$loader = new sfServiceContainerLoaderFileXml($sc);
$loader->load($argv[1] . '_container.xml');
$dumper = new sfServiceContainerDumperPhp($sc);
$code = $dumper->dump(array('class' => 'ApplicationContainer'));
file_put_contents('output/ApplicationContainer.php', $code);