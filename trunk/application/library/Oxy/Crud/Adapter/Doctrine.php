<?php
/**
 * Oxy_Crud_Adapter_Doctrine
 *
 * @category   Oxy
 * @package    Oxy_Crud
 * @subpackage Adapter
 * @author     Tomas Bartkus <to.bartkus@gmail.com>
 */

/**
 * Doctrine model transformation class
 *
 * @category   Oxy
 * @package    Oxy_Crud
 * @subpackage Adapter_Doctrine
 * @author     Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_Crud_Adapter_Doctrine extends Oxy_Crud_Adapter_Abstract
{
    /**
     * Either generated models
     * has base class
     *
     * @var Boolean
     */
    protected $_blHasBaseClass = true;

    /**
     * Path to ORM tables
     *
     * @var String
     */
    protected $_strPathToOrmTables;

    /**
     * Path to models
     *
     * @var String
     */
    protected $_strPathToModels;

    /**
     * Path to controllers
     *
     * @var String
     */
    protected $_strPathToControllers;

    /**
     * Path to views
     *
     * @var String
     */
    protected $_strPathToViews;

    /**
     * Path to forms
     *
     * @var String
     */
    protected $_strPathToForms;

    /**
     * Managers
     *
     * @var Array
     */
    protected $_arrManagers;

    /**
     * Controllers
     *
     * @var Array
     */
    protected $_arrControllers;

    /**
     * Views
     *
     * @var Array
     */
    protected $_arrViews;

    /**
     * Forms
     *
     * @var Array
     */
    protected $_arrForms;


    public function __construct()
    {

    }

	/**
	 * Generate CRUD
     */
    public function crud()
    {
        $this->analyze();
        $this->generateForms();
        $this->generateModels();
        $this->generateViews();
        $this->generateControllers();
    }

    public function analyze()
    {

    }

    public function generateForms()
    {

    }
    public function generateModels()
    {

    }
    public function generateViews()
    {

    }
    public function generateControllers()
    {

    }

}