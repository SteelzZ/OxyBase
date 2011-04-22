<?php
require_once 'Oxy/Compiler/Abstract.php';

class Oxy_Compiler_Aggregate extends Oxy_Compiler_Abstract
{
    private $arr_scanable_directories = array();

    public function addDirectory($mix_directory)
    {
        if (is_string($mix_directory))
        {
            $mix_directory = array($mix_directory);
        }
        elseif (!is_array($mix_directory))
        {
            throw new Exception('Expected string or array');
        }

        $this->arr_scanable_directories = array_merge($this->arr_scanable_directories, $mix_directory);
    }

    private function getFilesList()
    {
        $arr_files_list = array();
        foreach ($this->arr_scanable_directories as $str_directory)
        {
            $str_directory = $this->getCompileDirectory() . '/' . $str_directory;
            $arr_files_list = array_merge($arr_files_list, $this->rscandir($str_directory));
        }

        return $arr_files_list;
    }

    /**
     * Recursive scan directories method.
     *
     * @param string $str_dir Top directory to scan
     * @param boolean $recursive Should the scane be recursive
     * @return array All files that are in given directory
     */
    private function rscandir($str_dir, $bl_recursive = false)
    {
        if (is_dir($str_dir))
        {
            for ($arr_list = array(), $handle = opendir($str_dir); (false !== ($str_file = readdir($handle)));)
            {
                if (
                    ($str_file != '.' && $str_file != '..')
                    && (file_exists($str_path = $str_dir . '/' . $str_file))
                )
                {
                    if (is_dir($str_path) && ($bl_recursive))
                    {
                        $arr_list = array_merge($arr_list, $this->rscandir($str_path, true));
                    }
                    else
                    {
                        if (strtolower(substr($str_file, -3)) == 'php')
                        {
                            $str_current_file = $str_dir . '/' . $str_file;

                            $entry = new File_Info(
                                $str_file,
                                $str_dir
                            );

                            $arr_list[$str_current_file] = $entry;
                        }
                    }
                }
            }
            closedir($handle);
            return $arr_list;
        }
        else
            return false;
    }

    /**
     * Add files contents to compiled file.
     *
     * @param string $str_filename File, which will be added, name
     */
    private function addFileToCompiled($str_filename)
    {
        if (is_file($str_filename))
        {
            $this->addCode(file_get_contents($str_filename));
        }
        else
        {
            throw new Exception('Failed to add to compiled file: ' . $str_filename);
        }
    }

    /**
     * Add file source to compiled file.
     *
     * @param File_Info $obj_file_info File info object, which should be added
     * @param Boolean $bl_no_dependency_check Should the file be added without checking dependencies
     * @return unknown_type
     */
    private function addFile($obj_file_info)
    {
        if ($obj_file_info === false || $obj_file_info->isIncluded()) return;

        $this->addFileToCompiled($obj_file_info->getFullpath());

        $obj_file_info->setIncluded();
    }

    /**
     * Start compilation process.
     *
     * @param boolean $bl_compress_contents Should whitespace and comments be removed
     */
    public function compile($bl_compress_contents = false)
    {
        $this->arr_files_list = $this->getFilesList();

        if (empty($this->arr_files_list))
        {
            throw new Exception('Can not compile from empty list!');
        }

        $this->writeToFile('<?', false);

        foreach ($this->arr_files_list as $obj_file_info)
        {
            $this->addFile($obj_file_info);
        }

        $this->flushContents();

        if ($bl_compress_contents)
        {
            file_put_contents($this->getCompiledFilename(), php_strip_whitespace($this->getCompiledFilename()));
        }
    }

    /**
     * Get file info from the existing files list
     *
     * @param string $str_classname
     * @return File_Info object for given class name
     */
    public function getFileInfo($str_filename)
    {
        $str_filename = $this->getCompileDirectoryOffset() . str_replace('_', '/', $str_filename) . '.php';

        if (!isset($this->arr_files_list[$str_filename]))
        {
            //throw new Exception('Unknown filename requested: ' . $str_filename);
            return false;
        }
        return $this->arr_files_list[$str_filename];
    }


    /**
     * This compiler does not implement any white/black listing
     *
     * @param string $str_class_name Class name to check
     * @return boolean Is whitelisted
     */
    protected function isWhitelisted($str_item_name)
    {
        return true;
    }

    /**
     * This compiler does not implement any white/black listing
     *
     * @param string $str_class_name Class name to check
     * @return boolean Is blacklisted
     */
    protected function isBlacklisted($str_class_name)
    {
        return false;
    }
}