<?php
require_once 'Oxy/Tool/Project/Provider/Cqrs.php';
require_once 'Oxy/Tool/Project/Provider/Domain.php';

class Oxy_Tool_Project_Provider_Manifest implements
    Zend_Tool_Framework_Manifest_ProviderManifestable
{

    /**
     * getProviders()
     *
     * @return array Array of Providers
     */
    public function getProviders()
    {
        return array(
            'Oxy_Tool_Project_Provider_Cqrs',
            'Oxy_Tool_Project_Provider_Domain',
        );
    }
}