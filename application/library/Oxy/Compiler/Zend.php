<?php
require_once 'Oxy/Compiler/Abstract.php';

/**
 * Compiler that concatenates all files, that are organised in directories in a Zend Framework manner
 *
 */
class Oxy_Compiler_Zend extends Oxy_Compiler_Abstract
{

    /**
     * Check if given class is whitelisted
     *
     * @param string $str_class_name Class name to check
     * @return boolean Is whitelisted
     */
    protected function isWhitelisted($str_class_name)
    {
        // If it's not a whitelist mode, the class is allowed
        if (!$this->isWhitelistMode()) return true;

        foreach ($this->getWhitelist() as $str_whitelisted_module)
        {
            if (preg_match('/^' . $this->getCompileDirectoryBase() . '_' . $str_whitelisted_module . '/', $str_class_name))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if given class is blacklisted
     *
     * @param string $str_class_name Class name to check
     * @return boolean Is blacklisted
     */
    protected function isBlacklisted($str_class_name)
    {
        // If it's not a blacklist mode, the class is allowed
        if (!$this->isBlacklistMode()) return false;

        foreach ($this->getBlacklist() as $str_blacklisted_module)
        {
            if (preg_match('/^' . $this->getCompileDirectoryBase() . '_' . $str_blacklisted_module . '/', $str_class_name))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns files that will be compiled list
     *
     * @return array
     */
    protected function getFilesList()
    {
        return $this->rscandir($this->getCompileDirectory(), true);
    }

    /**
     * Recursive scan directories method.
     *
     * @param string $str_dir Top directory to scan
     * @param boolean $recursive Should the scane be recursive
     * @return array All files that are in given directory
     */
    protected function rscandir($str_dir, $bl_recursive = false)
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

                            $contents = file_get_contents($str_current_file);
                            $contents = Text_Filter_Manager::applyFilters($contents, array(
                                            new Text_Filter_Strip_Comments()
                                        ));
                            $arr_match = array();
                            preg_match_all('/(class|interface) (\w+)(\s*extends (\w+))?(\s*implements ([\w,\s]+))?/', $contents, $arr_match);

                            if (
                                !$this->isWhitelisted($arr_match[2][0])
                                ||
                                $this->isBlacklisted($arr_match[2][0])
                            )
                            {
                                continue;
                            }

                            // Class may extend only one class...
                            $arr_extends    = $arr_match[4];

                            // ...but implement multiple interfaces
                            if (isset($arr_match[6][0]))
                            {
                                $arr_implements = explode(',', $arr_match[6][0]);
                            }
                            else
                            {
                                $arr_implements = array();
                            }

                            $arr_dependencies = array();
                            $arr_implements = array_merge($arr_implements, $arr_extends);
                            foreach ($arr_implements as $str_classname)
                            {
                                $str_classname = trim($str_classname);
                                if ($str_classname != '')
                                {
                                    $arr_dependencies[$str_classname] = $str_classname;
                                }
                            }

                            $obj_entry = new File_Info(
                                $str_file,
                                $str_dir,
                                $arr_dependencies
                            );

                            $arr_list[$str_current_file] = $obj_entry;
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
     * @return none
     */
    private function addFile($obj_file_info, $bl_no_dependency_check = false)
    {
        if ($obj_file_info === false || $obj_file_info->isIncluded()) return;
        if (!$bl_no_dependency_check)
        {
            $this->addDependencies($obj_file_info);
        }

        $this->addFileToCompiled($obj_file_info->getFullpath());

        $obj_file_info->setIncluded();
    }

    /**
     *
     *
     * @param File_Info $obj_file_info
     * @return unknown_type
     */
    private function addDependencies($obj_file_info)
    {
        $arr_dependencies = $obj_file_info->getDependencies();

        if (empty($arr_dependencies))
        {
            return;
        }


        // Loop through dependencies for the file
        foreach ($arr_dependencies as $str_dependency)
        {
            // Get dependency object
            $obj_dependency_file_info = $this->getFileInfo($str_dependency);

            if ($obj_dependency_file_info instanceof File_Info)
            {
                // Check if dependent file is not yet included
                if (!$obj_dependency_file_info->isIncluded())
                {
                    $this->addFile($obj_dependency_file_info);
                }
            }
        }
    }


    /**
     * Get file info from the existing files list
     *
     * @param string $str_classname
     * @return File_Info object for given class name
     */
    private function getFileInfo($str_classname)
    {
        $str_classname = $this->getCompileDirectoryOffset() . str_replace('_', '/', $str_classname) . '.php';
        if (!isset($this->arr_files_list[$str_classname]))
        {
            //throw new Exception('Unknown filename requested: ' . $str_classname);
            return false;
        }
        return $this->arr_files_list[$str_classname];
    }



    /**
     * Start compilation process.
     *
     * @param boolean $bl_compress_contents Should whitespace and comments be removed
     */
    public function compile($bl_compress_contents = false)
    {
        // Get 'files to compile' list
        $this->arr_files_list = $this->getFilesList();

        if (empty($this->arr_files_list))
        {
            throw new Exception('Can not compile from empty list!');
        }

        $this->writeToFile('<?', false);

        // Loop through all files and write them
        foreach ($this->arr_files_list as $obj_file_info)
        {
            $this->addFile($obj_file_info);
        }

        // Make sure that all contents that sits in memory are writen to file
        $this->flushContents();

        // Compress contents if needed
        if ($bl_compress_contents)
        {
            file_put_contents($this->getCompiledFilename(), php_strip_whitespace($this->getCompiledFilename()));
        }
    }

}