<?php
// Include smarty class
require_once 'External/smarty/libs/Smarty.class.php';

class Oxy_View_Smarty extends Zend_View_Abstract
{

	/**
	 * Smarty object
	 * @var Smarty
	 */
	protected $_smarty;

	protected $_plugins;

	protected $_skin;

	/**
	 * Constructor
	 *
	 * Pass it a an array with the following configuration options:
	 *
	 * scriptPath: the directory where your templates reside
	 * compileDir: the directory where you want your compiled templates (must be
	 * writable by the webserver)
	 * configDir: the directory where your configuration files reside
	 *
	 * both scriptPath and compileDir are mandatory options, as Smarty needs
	 * them. You can't set a cacheDir, if you want caching use Zend_Cache
	 * instead, adding caching to the view explicitly would alter behaviour
	 * from Zend_View.
	 *
	 * @see Zend_View::__construct
	 * @param array $config ["scriptPath" => /path/to/templates,
	 *			     "compileDir" => /path/to/compileDir,
	 *			     "configDir"  => /path/to/configDir ]
	 * @throws Exception
	 */
	public function __construct($config = array())
	{
		$this->_smarty = new Smarty();
		// Compiler that allows usage of chaining methods inside templates
		$this->_smarty->compiler_file = "Oxy/View/Smarty/Compiler.php";
		$this->_smarty->compiler_class = "Oxy_View_Smarty_Compiler";
		//smarty object
		if (! array_key_exists('compileDir', $config))
		{
			throw new Exception('compileDir must be set in $config for ' . get_class($this));
		}
		else
		{
			$this->_smarty->compile_dir = $config['compileDir'];
		}
		//compile dir must be set
		$this->_smarty->debugging = true;
		if (array_key_exists('configDir', $config))
		{
			$this->_smarty->config_dir = $config['configDir'];
		}
		//configuration files directory
		parent::__construct($config);
		//call parent constructor
	//$this->_plugins = new Eica_View_Smarty_Plugin_Broker($this);
	//$this->registerPlugin(new Eica_View_Smarty_Plugin_Standart());
	}

	public function setCompilePath($dir)
	{
		$this->_smarty->compile_dir = $dir;
		return $this;
	}

	/**
	 * Return the template engine object
	 *
	 * @return Smarty
	 */
	public function getEngine()
	{
		return $this->_smarty;
	}

	/**
	 * register a new plugin
	 *
	 * @param Eica_View_Smarty_Plugin_Abstract
	 */
	public function registerPlugin(Oxy_View_Smarty_Plugin_Abstract $plugin, $stackIndex = null)
	{
		$this->_plugins->registerPlugin($plugin, $stackIndex);
		return $this;
	}

	/**
	 * Unregister a plugin
	 *
	 * @param string|Eica_View_Smarty_Plugin_Abstract $plugin Plugin object or class name
	 */
	public function unRegisterPlugin($plugin)
	{
		$this->_plugins->registerPlugin($plugin);
		return $this;
	}

	public function setSkin($str_skin_name = null)
	{
		$this->_skin = $str_skin_name . '/';
	}

	public function getSkin()
	{
		return $this->_skin;
	}

	public function render($name)
	{
		$this->strictVars(true);
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value)
		{
			if ('_' != substr($key, 0, 1))
			{
				$this->_smarty->assign($key, $value);
			}
		}
		//assign variables to the template engine
		$this->_smarty->assign_by_ref('this', $this);
		//why 'this'?
		//to emulate standard zend view functionality
		//doesn't mess up smarty in any way
		$path = $this->getScriptPaths();
		//smarty needs a template_dir, and can only use templates,
		//found in that directory, so we have to strip it from the filename
		if (is_array($path))
		{
			$path = array_reverse($path);
			foreach ($path as $str_path_dir)
			{
				array_unshift($this->_smarty->template_dir, $str_path_dir . $this->_skin);
			}
		}
		elseif (is_string($path))
		{
			array_unshift($this->_smarty->template_dir, $path . $this->_skin);
		}
		// In Smarty.php template_dir was converted to array
		//array_unshift($this->_smarty->template_dir, $path[0].$this->_skin);
		//$this->_smarty->template_dir = $path[0].$this->_skin;
		return $this->_smarty->fetch($name);
	}

	/**
	 * fetch a template, echos the result,
	 *
	 * @see Zend_View_Abstract::render()
	 * @param string $name the template
	 * @return void
	 */
	protected function _run()
	{
		$this->strictVars(true);
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value)
		{
			if ('_' != substr($key, 0, 1))
			{
				$this->_smarty->assign($key, $value);
			}
		}
		//assign variables to the template engine
		$this->_smarty->assign_by_ref('this', $this);
		//why 'this'?
		//to emulate standard zend view functionality
		//doesn't mess up smarty in any way
		$path = $this->getScriptPaths();
		$file = substr(func_get_arg(0), strlen($path[0]));
		//smarty needs a template_dir, and can only use templates,
		//found in that directory, so we have to strip it from the filename
		// $this->_smarty->template_dir = $path[0].$this->_skin;
		array_unshift($this->_smarty->template_dir, $path[0] . $this->_skin);
		//var_dump($this->_smarty->template_dir);exit;
		//set the template diretory as the first directory from the path
		echo $this->_smarty->fetch($file);
		//process the template (and filter the output)
	}
}