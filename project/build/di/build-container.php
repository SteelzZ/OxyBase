<?php
$libraryPath = dirname(__FILE__) . '/../../library/';
require($libraryPath . 'Symfony/sfServiceContainerAutoloader.php');
sfServiceContainerAutoloader::register();

$oxyLibraryPath = $libraryPath . '/Oxy/';
$oxyDiPath = $oxyLibraryPath . 'DependencyInjection/';

$sc = new sfServiceContainerBuilder();
$loader = new sfServiceContainerLoaderFileXml($sc);
$loader->load($argv[1] . '_container.xml');
 
$dumper = new sfServiceContainerDumperPhp($sc);
 
$code = $dumper->dump(array('class' => 'Oxy_DependencyInjection_Container'));
 
file_put_contents($oxyDiPath . 'Container.php', $code);