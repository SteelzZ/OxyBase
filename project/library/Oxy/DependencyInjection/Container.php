<?php

class Oxy_DependencyInjection_Container extends sfServiceContainer
{
  protected $shared = array();

  public function __construct()
  {
    parent::__construct($this->getDefaultParameters());
  }

  protected function getOxyApplicationService()
  {
    require_once $this->getParameter('project.path').'library/Oxy/Application.php';

    if (isset($this->shared['OxyApplication'])) return $this->shared['OxyApplication'];

    $instance = new Oxy_Application($this->getParameter('environment'), $this->getParameter('project.path').'config/config.xml');
    $instance->bootstrap();

    return $this->shared['OxyApplication'] = $instance;
  }

  protected function getDefaultParameters()
  {
    return array(
      'project.path' => 'C:/Development/Workspace/OxyBase/project/',
      'environment' => 'development',
    );
  }
}
