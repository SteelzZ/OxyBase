<?php
require_once 'Oxy/Compiler/Text/Filter/Manager.php';

abstract class Oxy_Compiler_Abstract
{
    /**
     * Directory which contents will be compiled into one file
     *
     * @var String
     */
    private $str_compile_dir;

    /**
     * Compile directory path offset
     *
     * @var String
     */
    private $str_compile_dir_offset;

    /**
     * Base compile dir, which is used as a first part of Namespace name
     *
     * @var string
     */
    private $str_base_compile_dir;

    /**
     * Name of a compiled file
     * @var String
     */
    private $str_compiled_filename;

    /**
     * Variable which holds data in memory until it's time to write data to file
     * @var String
     */
    private $str_code_chunk;

    /**
     * Max size of a text in memory
     * @var Integer
     */
    private $int_code_chunk_limit;

    /**
     * Filter manager object that will take care of text processing
     * @var Text_Filter_Manager
     */
    private $obj_filters_manager;

    /**
     * List of whitelisted modules.
     *
     * @var array
     */
    private $arr_module_whitelist = array();

    /**
     * List of blacklisted modules
     *
     * @var array
     */
    private $arr_module_blacklist = array();

    /**
     * Check if whitelist mode started - at least one module is in whitelist
     *
     * @return boolean
     */
    protected function isWhitelistMode()
    {
        return (count($this->arr_module_whitelist) > 0);
    }

    /**
     * Check if blacklist mode started - at least one module is in blacklist
     *
     * @return boolean
     */
    protected function isBlacklistMode()
    {
        return (count($this->arr_module_blacklist) > 0);
    }

    /**
     * Add module to whitelist.
     *
     * @param string|array $mix_module_name Module name or an array of module names
     * @return none
     */
    public function addWhitelistItem($mix_module_name)
    {
        if (is_string($mix_module_name))
        {
            $mix_module_name = array($mix_module_name);
        }
        elseif (!is_array($mix_module_name))
        {
            throw new Exception('Add whitelist module: string or array expected');
        }

        $arr_module_names = array();
        foreach ($mix_module_name as $str_module_name)
        {
            $arr_module_names[$str_module_name] = $str_module_name;
        }

        $this->arr_module_whitelist = array_merge($this->arr_module_whitelist, $arr_module_names);
    }

    /**
     * Add module to blacklist.
     *
     * @param string|array $mix_module_name Module name or an array of module names
     * @return none
     */
    public function addBlacklistItem($mix_module_name)
    {
        if (is_string($mix_module_name))
        {
            $mix_module_name = array($mix_module_name);
        }
        elseif (!is_array($mix_module_name))
        {
            throw new Exception('Add whitelist module: string or array expected');
        }

        $arr_module_names = array();
        foreach ($mix_module_name as $str_module_name)
        {
            $arr_module_names[$str_module_name] = $str_module_name;
        }

        $this->arr_module_blacklist = array_merge($this->arr_module_whitelist, $arr_module_names);
    }

    /**
     * Get whitelisted modules
     *
     * @return array
     */
    public function getWhitelist()
    {
        return $this->arr_module_whitelist;
    }

    /**
     * Get blacklisted modules
     *
     * @return array
     */
    public function getBlacklist()
    {
        return $this->arr_module_blacklist;
    }



    /**
     *
     * @param String $str_root_directory Directory which contents will be compiled
     */
    public function __construct($str_root_directory)
    {
        $int_current_memory_limit = (int)ini_get('memory_limit'); // Memory limit in Mb

        $this->setMaxChunkSize(1024 * 1024 * $int_current_memory_limit * 0.5);
        $this->obj_filters_manager = new Oxy_Compiler_Text_Filter_Manager();

        $arr_path_parts = explode('/', $str_root_directory);

        $this->str_base_compile_dir = array_pop($arr_path_parts);

        $this->str_compile_dir_offset = ($arr_path_parts) ? implode('/', $arr_path_parts) : '';

        $this->createAutoCompiledFilename($this->str_base_compile_dir);

        $this->setCompileDirectory($str_root_directory);
    }

    /**
     * Creates automatic compiled file name based on specified root directory name
     * @param $str_root_directory Directory, which contents will be compiled, name
     */
    private function createAutoCompiledFilename($str_root_directory)
    {
        $str_filename = str_replace(array('/', ' ', '-', '_', '.'), '', $str_root_directory);
        $str_filename = strtolower($str_filename);
        $str_filename = $str_filename . '.compiled.php';
        $this->setCompiledFilename($str_filename);
    }

    /**
     * Get text that currently exists in memory
     * @return String
     */
    private function getCodeChunk()
    {
        return $this->str_code_chunk;
    }

    /**
     * Resets text in memory with a text provided
     * @param String $str_code_chunk String Text to set to memory
     */
    private function setCodeChunk($str_code_chunk)
    {
        $this->str_code_chunk = $str_code_chunk;
    }

    /**
     * Get the size of text that is currently in the memory
     * @return Integer Text size
     */
    private function getCodeChunkSize()
    {
        return strlen($this->str_code_chunk);
    }

    /**
     * Add text to memory
     * @param String $str_code_chunk Text to add to memory
     */
    private function addCodeChunk($str_code_chunk)
    {
        $this->str_code_chunk .= $str_code_chunk;
    }



    /**
     * Do text processing with a filters specified in registerFilters method or by specifying needed filter as parameter.
     * @param String $str_contents Text to be processed
     * @param Array $arr_filters Array of filters that will be applied to text
     * @return String Processed contents
     */
    protected function processContents($str_contents, $arr_filters = null)
    {
        // If filters were not specified inline, then process text with filters set as a main filters
        if ($arr_filters === null)
        {
            return $this->obj_filters_manager->process($str_contents);
        }
        // ... if the filters were specified inline, process text using them
        elseif (Text_Filter_Manager::filtersListValid($arr_filters))
        {
            $obj_filters_manager = new Text_Filter_Manager();
            $obj_filters_manager->addFilters($arr_filters);
            return $obj_filters_manager->process($str_contents);
        }
        // ... otherwise don't process text
        else
        {
            return $str_contents;
        }
    }

    /**
     * Writes text to file
     * @param String $str_contents Text to write
     * @param Boolean $bl_append Should the text be appended (true) or should it overwrite existing contents (false)
     * @param Boolean $bl_do_text_processing Should the text be processed
     */
    protected function writeToFile($str_contents, $bl_append = true, $bl_do_text_processing = false)
    {
        $int_flags = $bl_append ? FILE_APPEND : 0;
        $str_contents = $bl_do_text_processing ? $this->processCode($str_contents) : $str_contents;
        file_put_contents($this->getCompiledFilename(), $str_contents, $int_flags);
    }

    /**
     * Forces to write out text that is in the memory to the file
     */
    protected function flushContents()
    {
        $this->writeToFile($this->getCodeChunk());
    }

    /**
     * Add code to compiled file or memory, depending on whether it's possible to put it on memory or not
     * @param String $str_code Text to add
     * @param Boolean $bl_do_filtering Should text filtering be done on provided text
     */
    protected function addCode($str_code, $bl_do_filtering = true)
    {
        // Do contents processing if required
        if ($bl_do_filtering)
        {
            $str_code = $this->processContents($str_code);
        }

        // Write code that exists in memory to file, if we have reached memory limit
        if ($this->getCodeChunkSize() + strlen($str_code) > $this->int_code_chunk_limit)
        {
            $this->writeToFile($this->getCodeChunk());
            $this->setCodeChunk($str_code);
        }
        // ... otherwise just add text into memory
        else
        {
            $this->addCodeChunk($str_code);
        }
    }



    /**
     * Set maximum text chunk size
     * @param Integer $int_max_size Max text size in memory
     */
    public function setMaxChunkSize($int_max_size)
    {
        $this->int_code_chunk_limit = (int) $int_max_size;
    }

    /**
     * Set compiled file name
     * @param String $str_filename File name
     */
    public function setCompiledFilename($str_filename)
    {
        $this->str_compiled_filename = $str_filename;
    }

    /**
     * Set directory, which contents will be be compiled, name
     * @param String $str_root_directory Directory name
     */
    public function setCompileDirectory($str_root_directory)
    {
        $this->str_compile_dir = $str_root_directory;
        $this->createAutoCompiledFilename($str_root_directory);
    }

    /**
     * Get directory, which contents will be compiled, base name
     *
     * @return string
     */
    public function getCompileDirectoryBase()
    {
        return ($this->str_base_compile_dir == '') ? '' : $this->str_base_compile_dir;
    }

    /**
     * Get directory, which contents will be compiled, path offset
     *
     * @return string
     */
    public function getCompileDirectoryOffset()
    {
        return ($this->str_compile_dir_offset == '') ? '' : $this->str_compile_dir_offset . '/';
    }

    /**
     * Get directory, which contents will be compiled, name
     * @return String
     */
    public function getCompileDirectory()
    {
        return $this->str_compile_dir;
    }

    /**
     * Registers filters that will be invoked during code processing
     * @param Array $arr_filters List of filter objects that complies to Text_Filter_Interface
     */
    public function registerFilters($arr_filters)
    {
        $this->obj_filters_manager->addFilters($arr_filters);
    }

    /**
     * Get compiled file name
     * @return String
     */
    public function getCompiledFilename()
    {
        return $this->str_compiled_filename;
    }

    /**
     * Main method that will compile all file into one
     */
    abstract public function compile($bl_compress_contents = false);

    /**
     * Check if given class is whitelisted
     *
     * @param string $str_class_name Class name to check
     * @return boolean Is whitelisted
     */
    abstract protected function isWhitelisted($str_item_name);

    /**
     * Check if given class is blacklisted
     *
     * @param string $str_class_name Class name to check
     * @return boolean Is blacklisted
     */
    abstract protected function isBlacklisted($str_item_name);
}