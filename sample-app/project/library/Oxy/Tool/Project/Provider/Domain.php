<?php
require_once 'Oxy/Application/Domain/Autoloader.php';
require_once 'Oxy/Domain/AggregateRoot/Abstract.php';
require_once 'Oxy/Domain/Entity/Abstract.php';
require_once 'Oxy/Loader/Autoloader.php';

/**
* Oxy Domain provider
*
* @category Oxy
* @package Oxy_Tool
* @subpackage Framework
* @author Tomas Bartkus <to.bartkus@gmail.com>
**/
class Oxy_Tool_Project_Provider_Domain extends Zend_Tool_Framework_Provider_Abstract
{
    const COMMAND_INTERFACE = 'Oxy_Command_CommandInterface';
    
    const DOMAIN_EVENT_INTERFACE = 'Oxy_Domain_Event_Interface';
    
    const BASE_COMMAND_CLASS = 'Oxy_Command_CommandAbstract';

    const BASE_COMMAND_HANDLER_CLASS = 'Oxy_Command_Handler_HandlerAbstract';

    const REPOSITORY_NAME = 'Repository';

    const BASE_AGGREGATE_ROOT_CLASS = 'Oxy_Domain_AggregateRoot_Abstract';
    
    const OXY_COLLECTION = 'Oxy_Collection';

    const BASE_ENTITY_CLASS = 'Oxy_Domain_Entity_Abstract';

    private $_boundedContext;

    private $_moduleName;

    private $_aggregateRoot;
    
    private $_aggregateRootSegments;

    private $_behaviour;

    private $_pathToDomain;
    
    private $_behaviourParams;
    
    private $_behaviourMethod;
    
    private $_commandHandlerParams;
    private $_commandParams;

    /**
     * Initialize
     *
     * @param string $boundedContext
     * @param string $domainModule
     * @param string $aggregateRoot
     * @param string $behaviour
     */
    private function _init($boundedContext, $domainModule = null, $aggregateRoot = null, $behaviour = null)
    {
        $this->_boundedContext = $boundedContext;
        // Create resources autoloader
        $domainLoader = new Oxy_Application_Domain_Autoloader(array(
                'namespace' => $this->_boundedContext,
                'basePath'  => $this->_registry->getConfig()->domain->base_path . 'apps' . DIRECTORY_SEPARATOR .
                               $this->_boundedContext
            )
        );
        
        Oxy_Loader_Autoloader::getInstance();

        // Create base path to BC domain
        $this->_pathToDomain = $this->_registry->getConfig()->domain->base_path . 'apps' . DIRECTORY_SEPARATOR .
                               $this->_boundedContext . DIRECTORY_SEPARATOR . 'domain' . DIRECTORY_SEPARATOR;


        if(!is_null($domainModule)){
            // If module name is passed than export only that module
            // iterate only though that module dir
            $this->_moduleName = $domainModule;
            $this->_pathToDomain .= $domainModule . DIRECTORY_SEPARATOR;

            if(!is_null($aggregateRoot)){
                // If AR is passed than export only that AR
                // iterate only though that AR methods
                $this->_aggregateRoot = $aggregateRoot;
                $this->_pathToDomain .= $this->_aggregateRoot . '.php';

                $this->_exportAggregateRoot($this->_pathToDomain);
                if(!is_null($behaviour)){
                    // If behaviour is passed than export only that behaviour
                    $this->_exportAggregateRoot($this->_pathToDomain, $behaviour);
                }
            }
        }
    }

	/**
	 * Parse domain and export
	 * behaviours to commands and command handlers
	 *
	 * @param string $boundedContext
	 * @param string $domainModule
	 * @param string $aggregateRoot
	 * @param string $behaviour
	 */
	public function exportBehaviours($boundedContext, $domainModule = null, $aggregateRoot = null, $behaviour = null)
	{
	    $this->_init($boundedContext, $domainModule, $aggregateRoot, $behaviour);

    	try {
            $domainModulesDir = new DirectoryIterator($this->_pathToDomain);
        } catch (Exception $e) {
            $this->_registry->getResponse()->appendContent("Directory $pathToDomain is not readable");
            return false;
        }

        foreach ($domainModulesDir as $moduleDirectory){
            if ($moduleDirectory->isDir() && !$moduleDirectory->isDot()){
                $moduleName = $moduleDirectory->getFilename();
                // Don't use SCCS directories as modules
                if (preg_match('/^[^a-z]/i', $moduleName) || ('CVS' == $moduleName) || ('.svn' == $moduleName)){
                    continue;
                }
                $this->_moduleName = $moduleDirectory;
                $this->_registry->getResponse()->appendContent(
                    "********Parsing {$moduleDirectory} module in {$boundedContext} BC ************"
                );
                $moduleDirPath = $moduleDirectory->getPath() . DIRECTORY_SEPARATOR . $moduleName;
                $modulesDir = new DirectoryIterator($moduleDirPath);

                $this->_exportModules($modulesDir);
            }
        }
	}

	/**
	 * Export behaviours from module
	 *
	 * @param DirectoryIterator $modulesDirectory
	 */
	private function _exportModules(DirectoryIterator $modulesDirectory)
	{
	    foreach ($modulesDirectory as $aggregateRootFile){
            if ($aggregateRootFile->isFile()){
                $this->_registry->getResponse()->appendContent(
                    " - Parsing {$aggregateRootFile->getFilename()} aggregate root"
                );

                $this->_exportAggregateRoot($aggregateRootFile->getPathname());
            }
        }
	}

	/**
	 * Export aggregate root
	 *
	 * @param string $domainAggregateRootPath
	 * @param string $methodName
	 */
	private function _exportAggregateRoot($domainAggregateRootPath, $methodName = null)
    {
        require_once $domainAggregateRootPath;

        $domainAggregateRoot = new Zend_Reflection_File($domainAggregateRootPath);
        try {
            $fileDocBlock = $domainAggregateRoot->getDocblock();
            if ($fileDocBlock->hasTag('export')) {
                $this->_registry->getResponse()->appendContent(
                    " - Exporting classes from {$domainAggregateRootPath}"
                );
                $classes = $domainAggregateRoot->getClasses();
                foreach ($classes as $class) {
                    $this->_registry->getResponse()->appendContent(
                        " - Parsing {$class->getName()} class "
                    );
                    $this->_parseAggregateRoot($class);
                    $methods = $class->getMethods();
                    foreach ($methods as $method) {
                        $reflectedDocBlockOfBehavior = $method->getDocblock();
                    	if($reflectedDocBlockOfBehavior->hasTag('command') && !$reflectedDocBlockOfBehavior->hasTag('ignore')){
                        	if(!is_null($methodName) && ($method->getName() !== (string)$methodName)){
                                continue;
                            }
                            try {
                                $this->_commands = array();
                                $this->_commandHandlers = array();
                            	$this->_parseBehaviour($method);
                            	$this->_parseBehaviourEvents($method);
                            	$this->_parseBehaviourParamsClasses($method);
                            	
                            	
                                $this->_generateCommands();
                                $this->_generateCommandHandlers();
                                //$this->_generateCommandEvents();
                                $this->_saveToFiles();
                            } catch (Zend_Reflection_Exception $ex) {
                                $this->_registry->getResponse()->appendContent(
                                    "Method {$method->getName()} has no doc block! "
                                );
                            }
                    	}
                    }
                }
            }
        } catch (Zend_Reflection_Exception $ex) {
            $this->_registry->getResponse()->appendContent(
                "File {$domainAggregateRootPath} has no doc block!"
            );
        }
    }
    
    /**
     * Save data to files
     */
    private function _saveToFiles()
    {
        // Save commands
        foreach($this->_commands as $behaviuorName => $behaviourData){
            $commandGenerator = new Zend_CodeGenerator_Php_Class($behaviourData);
            $pathToCommand = 'C:\Development\Workspace\Turnyrai\project\apps/' . 
                             ucfirst($this->_boundedContext) . DIRECTORY_SEPARATOR .
                             'interface' . DIRECTORY_SEPARATOR .
                             ucfirst($this->_interfaces[$behaviuorName]) . DIRECTORY_SEPARATOR .
                             'models' . DIRECTORY_SEPARATOR . 'Command' . DIRECTORY_SEPARATOR .
                             'Do' . ucfirst($behaviuorName) . '.php';
            file_put_contents(
                $pathToCommand,
                "<?php \r\n" . $commandGenerator->generate()
            );
        }
        
        foreach($this->_commandHandlers as $behaviuorName => $behaviourHandlerData){
            //print_r($behaviourHandlerData);
            $commandHandlerGenerator = new Zend_CodeGenerator_Php_Class($behaviourHandlerData);
            $pathToCommand = 'C:\Development\Workspace\Turnyrai\project\apps/' . 
                             ucfirst($this->_boundedContext) . DIRECTORY_SEPARATOR .
                             'interface' . DIRECTORY_SEPARATOR .
                             ucfirst($this->_interfaces[$behaviuorName]) . DIRECTORY_SEPARATOR .
                             'models' . DIRECTORY_SEPARATOR . 'Command/Handler' . DIRECTORY_SEPARATOR .
                             'Do' . ucfirst($behaviuorName) . '.php';
            file_put_contents(
                $pathToCommand,
                "<?php \r\n" . $commandHandlerGenerator->generate()
            );
        }
        
        /*foreach($this->_behaviourEvents as $behaviuorName => $behaviourEventData){
            //print_r($behaviourHandlerData);
            $eventGenerator = new Zend_CodeGenerator_Php_Class($behaviourEventData);
            $pathToCommand = 'C:\Development\Workspace\Turnyrai\project\apps/' . 
                             ucfirst($this->_boundedContext) . DIRECTORY_SEPARATOR .
                             'domain' . DIRECTORY_SEPARATOR .
                             ucfirst($this->_moduleName) . DIRECTORY_SEPARATOR .
                             ucfirst($this->_aggregateRoot) . DIRECTORY_SEPARATOR . 
                             'Event' . DIRECTORY_SEPARATOR . 
                             'Do' . ucfirst($behaviuorName) . '.php';
            file_put_contents(
                $pathToCommand,
                "<?php \r\n" . $eventGenerator->generate()
            );
        }*/
    }
    
    /**
     * Parse aggreagte root
     * 
     * @param $class
     */
    private function _parseAggregateRoot($class)
    {
        $this->_aggregateRoot = $class->getName();
    	$this->_aggregateRootSegments = explode('_', $class->getName());
    	$this->_aggregateRootName = array_pop($this->_aggregateRootSegments);
    	$this->_aggregateRootNameForVariable = strtolower(substr($this->_aggregateRootName, 0, 1)) . substr($this->_aggregateRootName, 1);
    }
    
    /**
     * Parse behaviour
     * 
     * @param $behaviour
     */
    private function _parseBehaviour($behaviour)
    { 
        $reflectedDocBlockOfBehavior = $behaviour->getDocblock();
    	$interfaceExportCommandAndHandlerTo = $reflectedDocBlockOfBehavior->getTag('interface');
        if(!$interfaceExportCommandAndHandlerTo){
        	$this->_registry->getResponse()->appendContent(
        		"{$behaviour->getName()} does not have defined interface tag!"
            );
            return false;
        }
        
        $methodToExport = ucfirst($behaviour->getName());
    	
    	$this->_behaviours[] = $methodToExport; 
    	
    	$this->_interfaces[$behaviour->getName()] = ucfirst($interfaceExportCommandAndHandlerTo->getDescription());
    	
    	$this->_commands[$behaviour->getName()] = array(
    		'name' => "{$this->_boundedContext}_{$this->_interfaces[$behaviour->getName()]}_Model_Command_Do{$methodToExport}",
    		'extendedClass' => self::BASE_COMMAND_CLASS
        );		
    	 	
        $handlerMainInvokerAttribute = "{$this->_aggregateRootNameForVariable}";
            
        $this->_commandHandlers[$behaviour->getName()] = array(
        	'name' => "{$this->_boundedContext}_{$this->_interfaces[$behaviour->getName()]}_Model_Command_Handler_Do{$methodToExport}",
            'extendedClass' => self::BASE_COMMAND_HANDLER_CLASS,
            'properties' => array(
                array(
                    'name' => '_' . $handlerMainInvokerAttribute . self::REPOSITORY_NAME,
                    'visibility' => 'private',
                    'type' => $this->_aggregateRoot . '_' . self::REPOSITORY_NAME,
                    'main' => true,
                    'docblock' => new Zend_CodeGenerator_Php_Docblock(array(
                    	'shortDescription' => 'AR repository',
                        'tags'             => array(
                            new Zend_CodeGenerator_Php_Docblock_Tag(array(
                                	'name' => 'var',
                                    'description'  =>  $this->_aggregateRoot . '_' . self::REPOSITORY_NAME
                                )),
                            ),
                        )
                    )
                )
            )
        );
    }
    
    /**
     * Parse events
     * 
     * @param $behaviour
     */
    private function _parseBehaviourEvents($behaviour)
    {
    	$methodToExport = ucfirst($behaviour->getName());
    	$matches = array();
        preg_match_all('/\$this\->handleEvent\((?s:(.*))\)(?=;)/U', $behaviour->getBody(), $matches);
        foreach($matches[1] as $eventData){
            $event = array();
            preg_match_all('/new\s(.*)\(/', $eventData, $event);
            $class = array_shift($event[1]);
            $classSegments = explode('_', $class);
            $this->_behaviourEvents[$methodToExport][$class]['name'] = $class;
            $this->_behaviourEvents[$methodToExport][$class]['options'] = array(
                'name' => trim($class),
                'implementedInterfaces' => array('Oxy_Domain_Event_Interface')
            );
          
            $params = array();
            preg_match_all('/\/\/(.*)/', $eventData, $params);
            foreach($params[1] as $paramData){
                $paramData = trim($paramData);
                $paramData = explode(' ', $paramData);
                
                $this->_behaviourEvents[$methodToExport][$class]['properties'][] = array(
                    'datatype' => $paramData[0],
                    'name' => $paramData[1]
                );
            }
        }    	
    }
    
    /**
     * Parse behaviour
     * 
     * @param $behaviour
     */
    private function _parseBehaviourParamsClasses($behaviour)
    {
    	$reflectedDocBlockOfBehavior = $behaviour->getDocblock();
    	$behaviorParams = $reflectedDocBlockOfBehavior->getTags('param');
        foreach($behaviorParams as $behaviorParam){

            $requiredParamClass = new Zend_Reflection_Class($behaviorParam->getType());


            if((!$requiredParamClass->isSubclassOf(self::BASE_AGGREGATE_ROOT_CLASS)) &&
               (!$requiredParamClass->isSubclassOf(self::BASE_ENTITY_CLASS))){
                $requiredProperties = (array)$requiredParamClass->getProperties();

                foreach ($requiredProperties as $property){
                    $propertyDocBlock = $property->getDocComment();
                    if(!$propertyDocBlock->hasTag('skip')){
                    	
                    	$this->_commandParams[$behaviour->getName()]['properties'][] = $property; 
                    	$this->_commandParams[$behaviour->getName()]['params'][$requiredParamClass->getName()]['paramVariableName'] = $behaviorParam->getVariableName();
                    	$this->_commandParams[$behaviour->getName()]['params'][$requiredParamClass->getName()]['properties'][] = $property;
                    }
                }
            }

            if(($requiredParamClass->isSubclassOf(self::BASE_AGGREGATE_ROOT_CLASS)) && 
               (!$requiredParamClass->isSubclassOf(self::BASE_ENTITY_CLASS))){
            	$this->_commandHandlerParams[$behaviour->getName()]['properties'][] = array(
            	    'paramType' => $behaviorParam->getType(),
            	    'paramVariableName' => $behaviorParam->getVariableName()
            	);
            	
            	$this->_commandParams[$behaviour->getName()]['properties'][] = array(
            	    'paramType' => $behaviorParam->getType(),
            	    'paramVariableName' => $behaviorParam->getVariableName() . 'Guid'
            	);
            }    

            if(!isset($this->_commandParams[$behaviour->getName()]['properties'])){
                $this->_commandParams[$behaviour->getName()]['properties'] = array();
            }
            
        }    	
        
        //print_r($this->_commandParams[$behaviour->getName()]['params']);
    }

    private function generateCommandEvents()
    {
        //$this->_behaviourEvents[$methodToExport][$class]['properties']
        $j = 0;
        foreach($this->_behaviourEvents as $behaviuorName => $class){
            // More then one behavior with samename
            $this->_behaviourEvents[$behaviuorName][$class][$j]['name'] = 
                $this->_behaviourEvents[$methodToExport][$class]['options']['name'];
                
            $this->_behaviourEvents[$behaviuorName]['implementedInterfaces'] = 
                $this->_behaviourEvents[$methodToExport][$class]['options']['implementedInterfaces'];    
            foreach($class['properties'] as $property){
                $i = 1;
    		    $this->_behaviourEvents[$behaviuorName]['methods'][0]['name'] = '__constructor';
    		    $this->_behaviourEvents[$behaviuorName]['methods'][0]['visibility'] = 'public';
    		    $this->_behaviourEvents[$behaviuorName]['methods'][0]['body'] = '';
    		    $constructorParamsDocs = array();
    		    
    		    $this->_behaviourEvents[$behaviuorName]['properties'][$i]['name'] = $property['name'];
                $this->_behaviourEvents[$behaviuorName]['properties'][$i]['visibility'] = 'private';
                $this->_behaviourEvents[$behaviuorName]['properties'][$i]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                	'shortDescription' => $propertyDocBlock->getShortDescription(),
                    'tags'             => array(
                        new Zend_CodeGenerator_Php_Docblock_Tag(array(
                            	'name' => 'var',
                                'description'  => $property['datatype']
                            )),
                        ),
                    )
                );

                $noPrefixGetterName = str_replace('_', '', $property['name']);
                $getterName = ucfirst($noPrefixGetterName);

                $this->_behaviourEvents[$behaviuorName]['methods'][$i]['name'] = 'get' . $getterName;
                $this->_behaviourEvents[$behaviuorName]['methods'][$i]['body'] = 'return $this->' . $property['name'] . "; \n";
                $this->_behaviourEvents[$behaviuorName]['methods'][$i]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                    'shortDescription' => 'Get ' . $property['name'],
                    'tags'             => array(
                        new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                            'datatype'  => $property['datatype']
                        )),
                    ),
                ));
                
                $this->_behaviourEvents[$behaviuorName]['methods'][0]['parameters'][] = array(
                    'name' => $noPrefixGetterName
                );
                $this->_behaviourEvents[$behaviuorName]['methods'][0]['body'] .= '$this->' . $property['name'] . ' = $' . "{$noPrefixGetterName}; \n";
                $constructorParamsDocs[] = new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                    'paramName' => $noPrefixGetterName,
                    'datatype'  => $property['datatype']
                ));
            }
        }
    }

	/**
	 * Export single behaviour from AR
	 *
	 * @param string $class
	 * @param string $behaviourName
	 */
	private function _generateCommands()
	{
		foreach($this->_commands as $behaviuorName => &$behaviourData){
		    $i = 1;
		    $behaviourData['methods'][0]['name'] = '__constructor';
		    $behaviourData['methods'][0]['visibility'] = 'public';
		    $behaviourData['methods'][0]['body'] = 'parent::__construct($commandName, $guid);' . "\n";
		    $behaviourData['methods'][0]['parameters'] = array(
		        array('name' => 'commandName'),
		        array('name' => 'guid')
            );
		    $constructorParamsDocs = array();
		    foreach ($this->_commandParams[$behaviuorName]['properties'] as $property){
		        if($property instanceof Zend_Reflection_Property){
    		        $propertyDocBlock = $property->getDocComment();
        			$behaviourData['properties'][$i]['name'] = $property->getName();
                    $behaviourData['properties'][$i]['visibility'] = 'private';
                    $behaviourData['properties'][$i]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                    	'shortDescription' => $propertyDocBlock->getShortDescription(),
                        'tags'             => array(
                            new Zend_CodeGenerator_Php_Docblock_Tag(array(
                                	'name' => 'var',
                                    'description'  => $propertyDocBlock->getTag('var')->getDescription()
                                )),
                            ),
                        )
                    );
    
                    $noPrefixGetterName = str_replace('_', '', $property->getName());
                    $getterName = ucfirst($noPrefixGetterName);
    
                    $behaviourData['methods'][$i]['name'] = 'get' . $getterName;
                    $behaviourData['methods'][$i]['body'] = 'return $this->' . $property->getName() . "; \n";
                    $behaviourData['methods'][$i]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                        'shortDescription' => 'Get ' . $propertyDocBlock->getShortDescription(),
                        'tags'             => array(
                            new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                                'datatype'  => $propertyDocBlock->getTag('var')->getDescription()
                            )),
                        ),
                    ));
                    
                    $behaviourData['methods'][0]['parameters'][] = array(
                        'name' => $noPrefixGetterName
                    );
                    $behaviourData['methods'][0]['body'] .= '$this->' . $property->getName() . ' = $' . "{$noPrefixGetterName}; \n";
                    $constructorParamsDocs[] = new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                        'paramName' => $noPrefixGetterName,
                        'datatype'  => $propertyDocBlock->getTag('var')->getDescription()
                    ));
		        } else {
		            $noDollarSign = str_replace('$', '', $property['paramVariableName']);
		            $prefixGetterName = '_' . $noDollarSign;
                    $getterName = ucfirst($noDollarSign);
                    
		            $behaviourData['properties'][$i]['name'] = $prefixGetterName;
                    $behaviourData['properties'][$i]['visibility'] = 'private';
                    $behaviourData['properties'][$i]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                    	'shortDescription' => 'Aggregate root',
                        'tags'             => array(
                            new Zend_CodeGenerator_Php_Docblock_Tag(array(
                                	'name' => 'var',
                                    'description'  => 'AR GUID'
                                )),
                            ),
                        )
                    );
    
    
    
                    $behaviourData['methods'][$i]['name'] = 'get' . $getterName;
                    $behaviourData['methods'][$i]['visibility'] = 'public';
                    $behaviourData['methods'][$i]['body'] = 'return $this->' . $prefixGetterName . "; \n";
                    $behaviourData['methods'][$i]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                        'shortDescription' => 'Get AR GUID',
                        'tags'             => array(
                            new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                                'datatype'  => 'string'
                            )),
                        ),
                    ));
                    
                    $behaviourData['methods'][0]['parameters'][] = array(
                        'name' => $noDollarSign
                    );
                    $behaviourData['methods'][0]['body'] .= '$this->' . $prefixGetterName . ' = ' . "{$property['paramVariableName']}; \n";
                    $constructorParamsDocs[] = new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                        'paramName' => $noDollarSign,
                        'datatype'  => 'string'
                    ));
		        }
		        
		        $i++;
		    }
		    
		    
		    $behaviourData['methods'][0]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                'shortDescription' => 'Init command',
                'tags' => $constructorParamsDocs
            ));
		}
	}
	
	/**
	 * Generate command handlers
	 */
	private function _generateCommandHandlers()
	{
	    foreach($this->_commandHandlers as $behaviuorName => &$behaviourHandlerData){
	        $i = 1;
		    $behaviourHandlerData['methods'][0]['name'] = '__constructor';
		    $behaviourHandlerData['methods'][0]['visibility'] = 'public';
		    $behaviourHandlerData['methods'][0]['body'] = '';
		    
		    $behaviourHandlerData['methods'][1]['name'] = 'execute';
		    $behaviourHandlerData['methods'][1]['visibility'] = 'public';
		    $behaviourHandlerData['methods'][1]['parameters'][] = array(
                'name' => 'command',
	            'type' => self::COMMAND_INTERFACE
            );
            $behaviourHandlerData['methods'][1]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                'shortDescription' => 'Execute command',
            ));
            $behaviourHandlerData['methods'][1]['body'] = '';

		    $constructorParamsDocs = array();
		    //print $behaviuorName . "\n";
		    if(isset($this->_commandHandlerParams[$behaviuorName])){
    		    foreach($this->_commandHandlerParams[$behaviuorName]['properties'] as $property){
    		        //$noDollarSign =
    		        //print $property['paramVariableName'] ."\n"; 
    		        $prefixGetterName = '_' . $property['paramVariableName'];
    		        $prefixGetterName = str_replace(array('$'), '', $prefixGetterName);
    		        $getterName = ucfirst(str_replace(array('$'), '', $property['paramVariableName']));
    		        $behaviourHandlerData['properties'][$i]['name'] = $prefixGetterName . self::REPOSITORY_NAME;
                    $behaviourHandlerData['properties'][$i]['visibility'] = 'private';
                    $behaviourHandlerData['properties'][$i]['type'] = $property['paramType'] . '_' . self::REPOSITORY_NAME;
                    $behaviourHandlerData['properties'][$i]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                    	'shortDescription' => 'AR repository',
                        'tags'             => array(
                            new Zend_CodeGenerator_Php_Docblock_Tag(array(
                                	'name' => 'var',
                                    'description'  =>  $property['paramType'] . '_' . self::REPOSITORY_NAME
                                )),
                            ),
                        )
                    );
    		    }
		    }
		    
		    $mainArVariable = null;
		    $behaviorParams = array();
		    $saveToRepoStatement = '';
	        foreach($behaviourHandlerData['properties'] as $property){
	            
		        $getterName = ucfirst(str_replace(array('_', self::REPOSITORY_NAME), '', $property['name']));
		        $variableName = '$' . str_replace(array('_'), '', $property['name']);
		        $variableNameNoRepo = '$' . str_replace(array('_', self::REPOSITORY_NAME), '', $property['name']);
		        
	            if(isset($property['main'])){
	                $mainArVariable = $variableNameNoRepo;
	                $saveToRepoStatement = '$this->' . $property['name'] . '->add('.$mainArVariable.');' . "\n";;
	            } else {
	                $behaviorParams[] = $variableNameNoRepo;
	            }
		        
		        $behaviourHandlerData['methods'][0]['parameters'][] = array(
                    'name' => str_replace(array('_'), '', $property['name']),
                    'type' => $property['type']
                );
                $behaviourHandlerData['methods'][0]['body'] .= '$this->' . $property['name'] .' = ' . "{$variableName}" . ";\n";
                $constructorParamsDocs[] = new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                    'paramName' => str_replace(array('$'), '', $variableName),
                    'datatype'  => $property['type']
                ));
                
                $behaviourHandlerData['methods'][1]['body'] .= $variableNameNoRepo . ' = ' .
                                                              '$this->' . $property['name'] . '->getById($command->get'.$getterName.'Guid());' . "\n";
		        
		    }
		    
		    $behaviourHandlerData['methods'][0]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                'shortDescription' => 'Init command',
		        'tags' => $constructorParamsDocs
            ));
		    
            
		    //$this->_commandParams[$behaviour->getName()]['params'][$behaviorParam->getType()]['properties'][] = $property;
		    if(isset($this->_commandParams[$behaviuorName]['params'])){
		        $string = '';
    		    foreach($this->_commandParams[$behaviuorName]['params'] as $class => $properties){
    		        $variable = $this->_commandParams[$behaviuorName]['params'][$class]['paramVariableName'];
    		        $requiredParamClass = new Zend_Reflection_Class($class);
    		        //if(($requiredParamClass->isSubclassOf(self::OXY_COLLECTION))){
    		            
    		            
    		        //} else {
    		            $behaviorParams[] = $variable;
    		            $string .= $variable . ' = new ' . $class . '(' . "\n";
    		            $params = array();
    		            foreach($properties['properties'] as $property){
    		                print $property->getName() . "\n";
    		                $noPrefixGetterName = str_replace(array('_','$'), '', $property->getName());
                            $getterName = ucfirst($noPrefixGetterName);
                            $params[] = '    $command->get'.$getterName.'()';       
    		            }
    		            
    		            
    		            $string .= implode(','."\n", $params);
    		            $string .= "\n" . ');' . "\n\n";
    		       //}
    		        $behaviourHandlerData['methods'][1]['body'] .= $string;
    		    }
		    }
		    
		    $behaviourHandlerData['methods'][1]['body'] .= "\n";
		    $behaviourHandlerData['methods'][1]['body'] .= "{$mainArVariable}->$behaviuorName(" . implode(',', $behaviorParams) . "); \n";
		    $behaviourHandlerData['methods'][1]['body'] .= $saveToRepoStatement;
	    }	   
	    
	    //print_r($this->_commandHandlers);
	}

    /**
     * Export events
     *
     * @param unknown_type $events
     */
    private function _exportEvents($aggregateRootClass, $behavior)
    {
        $matches = array();
        preg_match_all('/\$this\->handleEvent\((?s:(.*))\)(?=;)/U', $behavior->getBody(), $matches);
        foreach($matches[1] as $eventData){
            $event = array();
            preg_match_all('/new\s(.*)\(/', $eventData, $event);
            $class = array_shift($event[1]);
            $classNameSegments = explode('_', $class);
            $pathToEvent = str_replace('_', '/', $class) . '.php';
            $lastSegmentEventName = array_pop($classNameSegments);

            $optionsForEvent = array(
                'name' => trim($class),
                'implementedInterfaces' => array('Oxy_Domain_Event_Interface')
            );

            $optionsForEvent['methods'][0]['body'] = '';
            $optionsForEvent['methods'][1]['name'] = 'getEventName';
            $optionsForEvent['methods'][1]['body'] = 'return \''.$lastSegmentEventName.'\';';
            $optionsForEvent['methods'][1]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                'shortDescription' => 'Get event name',
                'tags' => array(
                    new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                        'datatype'  => 'string'
                    ))
                )
            ));

            $params = array();
            preg_match_all('/\/\/(.*)/', $eventData, $params);
            $cleanParamData = array();
            $i = 2;
            $constructorParamsDocs = array();
            foreach($params[1] as $paramData){
                $paramData = trim($paramData);
                $paramData = explode(' ', $paramData);
                $cleanParamData[] = array(
                    'datatype' => $paramData[0],
                    'name' => $paramData[1]
                );

                // What if we have same property name ?
                $optionsForEvent['properties'][$i]['name'] = $paramData[1];
                $optionsForEvent['properties'][$i]['visibility'] = 'private';
                $optionsForEvent['properties'][$i]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                    'shortDescription' => $paramData[1],
                    'tags'             => array(
                        new Zend_CodeGenerator_Php_Docblock_Tag(array(
                            'name' => 'var',
                            'description'  => $paramData[0]
                        )),
                    ),
                ));

                $noPrefixGetterName = str_replace('_', '', $paramData[1]);
                $getterName = ucfirst($noPrefixGetterName);

                $optionsForEvent['methods'][$i]['name'] = 'get' . $getterName;
                $optionsForEvent['methods'][$i]['body'] = 'return $this->' . $paramData[1] . "; \n";
                $optionsForEvent['methods'][$i]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                    'shortDescription' => 'Get ' . $paramData[1],
                    'tags'             => array(
                        new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                            'datatype'  => $paramData[0]
                        )),
                    ),
                ));

                $i++;

                $optionsForEvent['methods'][0]['parameters'][] = array(
                    'name' => $noPrefixGetterName
                );
                $optionsForEvent['methods'][0]['body'] .= '$this->' . $paramData[1] . ' = $' . "{$noPrefixGetterName}; \n";
                $constructorParamsDocs[] = new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                    'paramName' => $noPrefixGetterName,
                    'datatype'  => $paramData[0]
                ));
            }

            $optionsForEvent['methods'][0]['name'] = '__constructor';
            $optionsForEvent['methods'][0]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                'shortDescription' => 'Init command',
                'tags' => $constructorParamsDocs
            ));

            $eventGenerator = new Zend_CodeGenerator_Php_Class($optionsForEvent);
            file_put_contents(
                'C:\Development\Workspace\Turnyrai\project\apps/' . $pathToEvent,
                "<?php \r\n" . $eventGenerator->generate()
            );
        }
    }

    /**
     * Generating command handler
     * 
     * @param $behaviourName
     * @param $optionsForCommandHandler
     */
	private function _generateCommandHandlerProperties($arProperty, $behaviourName, Array &$optionsForCommandHandler)
	{
	    foreach ($optionsForCommandHandler['properties'] as $property){
	        $optionsForCommandHandler['methods'][1]['parameters'][] = array(
                'name' => $property['name']
            );
            $noPrefixGetterName = str_replace('_', '', $property['name']);
            $getterName = ucfirst($noPrefixGetterName);

            $optionsForCommandHandler['methods'][1]['body'] .= '$this->' . $property['name'] . ' = $' . "{$noPrefixGetterName}; \n";
            $constructorParamsDocs[] = new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                'paramName' => $noPrefixGetterName
            ));

            $optionsForCommandHandler['methods'][0]['body'] .= '$'.$noPrefixGetterName.' = $this->' . $property['name'] . '->getById($command->getGuid());' . "\n";
	    }

        $optionsForCommandHandler['methods'][1]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
            'shortDescription' => 'Init command',
            'tags' => $constructorParamsDocs
        ));
	}

	/**
	 * Generate command properties
	 *
	 * @param Array $tags
	 * @param Array $optionsForCommand
	 * @param Array $optionsForCommandHandler
	 */
	private function _generateCommandProperties(Array $behaviorParams, Array &$optionsForCommand, Array &$optionsForCommandHandler)
	{
        $i = 1;
        $constructorParamsDocs = array();
        foreach($behaviorParams as $behaviorParam){

            $this->_registry->getResponse()->appendContent(
                "[{$behaviorParam->getName()}:{$behaviorParam->getType()}]"
            );

            //if(class_exists($tag->getType())){
                $requiredParamClass = new Zend_Reflection_Class($behaviorParam->getType());
                $requiredClassProperties[$behaviorParam->getType()] = $requiredParamClass->getProperties();
            //}

            if((!$requiredParamClass->isSubclassOf(self::BASE_AGGREGATE_ROOT_CLASS)) &&
               (!$requiredParamClass->isSubclassOf(self::BASE_ENTITY_CLASS))){
                $requiredProperties = isset($requiredClassProperties[$behaviorParam->getType()]) ? $requiredClassProperties[$behaviorParam->getType()] : array();

                if(!isset($optionsForCommand['methods'][0]['body'])){
                    $optionsForCommand['methods'][0]['body'] = '';
                }

                foreach ($requiredProperties as $property){
                    $propertyDocBlock = $property->getDocComment();
                    if(!$propertyDocBlock->hasTag('skip')){

                        // What if we have same property name ?
                        $optionsForCommand['properties'][$i]['name'] = $property->getName();
                        $optionsForCommand['properties'][$i]['visibility'] = 'private';
                        $optionsForCommand['properties'][$i]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                            'shortDescription' => $propertyDocBlock->getShortDescription(),
                            'tags'             => array(
                                new Zend_CodeGenerator_Php_Docblock_Tag(array(
                                    'name' => 'var',
                                    'description'  => $propertyDocBlock->getTag('var')->getDescription()
                                )),
                            ),
                        ));

                        $noPrefixGetterName = str_replace('_', '', $property->getName());
                        $getterName = ucfirst($noPrefixGetterName);

                        $optionsForCommand['methods'][$i]['name'] = 'get' . $getterName;
                        $optionsForCommand['methods'][$i]['body'] = 'return $this->' . $property->getName() . "; \n";
                        $optionsForCommand['methods'][$i]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                            'shortDescription' => 'Get ' . $propertyDocBlock->getShortDescription(),
                            'tags'             => array(
                                new Zend_CodeGenerator_Php_Docblock_Tag_Return(array(
                                    'datatype'  => $propertyDocBlock->getTag('var')->getDescription()
                                )),
                            ),
                        ));

                        $i++;

                        $optionsForCommand['methods'][0]['parameters'][] = array(
                            'name' => $noPrefixGetterName
                        );
                        $optionsForCommand['methods'][0]['body'] .= '$this->' . $property->getName() . ' = $' . "{$noPrefixGetterName}; \n";
                        $constructorParamsDocs[] = new Zend_CodeGenerator_Php_Docblock_Tag_Param(array(
                            'paramName' => $noPrefixGetterName,
                            'datatype'  => $propertyDocBlock->getTag('var')->getDescription()
                        ));
                    }
                }

                $optionsForCommand['methods'][0]['name'] = '__constructor';
                $optionsForCommand['methods'][0]['docblock'] = new Zend_CodeGenerator_Php_Docblock(array(
                    'shortDescription' => 'Init command',
                    'tags' => $constructorParamsDocs
                ));
            }

            if(($requiredParamClass->isSubclassOf(self::BASE_AGGREGATE_ROOT_CLASS))){
                $classWords = explode('_', $behaviorParam->getType());
                $arName = array_pop($classWords);
                $arName = strtolower(substr($arName, 0, 1)) . substr($arName, 1);
                array_push(
                    $optionsForCommandHandler['properties'],
                    array(
                        'name' => "_{$arName}" . self::REPOSITORY_NAME,
                        'visibility' => 'private'
                    )
                );
            }
        }
	}

	/**
	 * Show info about domain behaviours
	 *
	 * @param string $boundedContext
	 * @param string $domainModule
	 * @param string $aggregateRoot
	 */
	public function explainDomain($boundedContext, $domainModule = null, $aggregateRoot = null)
	{
	}

	/**
	 * Generate AR skeleton
	 *
	 * @param string $boundedContext
	 * @param string $domainModule
	 * @param string $arName
	 */
	public function createAggregateRoot($boundedContext, $domainModule, $arName)
	{
	}
}