<?php
/**
 * Initiliazer
 *
 * Set up required routines
 */
class Initializer extends Zend_Controller_Plugin_Abstract
{
	/**
     * @var Zend_Controller_Front
     */
    protected $obj_front;

    /**
     * @var string Path to application root
     */
    protected $str_root;

    /**
     * Constructor
     *
     * Initialize environment, root path, and configuration.
     *
     * @param  string $env
     * @param  string|null $root
     * @return void
     */
    public function __construct($str_root = null)
    {
        if (null === $str_root) {
            $str_root = realpath(dirname(__FILE__) . '../application/');
        }
        $this->str_root = $str_root;
    }

	/**
     * Route startup
     *
     * @return void
     */
	public function routeStartup(Zend_Controller_Request_Abstract $request)
	{

	}

}