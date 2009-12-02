<?php
require_once 'Oxy/Compiler/Abstract.php';

class Oxy_Compiler_Compiler_Mvc extends Oxy_Compiler_Zend
{
    /**
     * Subdirectories list, which contents will be compiled
     *
     * @var array
     */
    private $arr_subdir_list = array(
        'controllers',
        'models'
    );

    /**
     * Returns files that will be compiled list
     *
     * @return array
     */
    protected function getFilesList()
    {
        $arr_files_list = array();

        $arr_top_dir_files_list = scandir($this->getCompileDirectory());

        foreach ($arr_top_dir_files_list as $str_file)
        {
            // Scan only non-hidden directories and directories only
            if (
                (substr($str_file, 0, 1) == '.')
                || !is_dir($this->getCompileDirectory() . '/' . $str_file)
                || !$this->isWhitelisted($str_file)
                || $this->isBlacklisted($str_file)
            )
            {
                continue;
            }

            foreach ($this->arr_subdir_list as $str_subdir)
            {
                $arr_files_list = array_merge($arr_files_list, $this->rscandir($this->getCompileDirectory() . '/' . $str_file . '/' . $str_subdir, true));
            }
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
                                            new Oxy_Compiler_Text_Filter_Strip_Comments()
                                        ));
                            $arr_match = array();
                            preg_match_all('/(class|interface) (\w+)(\s*extends (\w+))?(\s*implements ([\w,\s]+))?/', $contents, $arr_match);

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

                            $str_relative_fake_path = $this->getCompileDirectoryOffset() . str_replace('_', '/', $arr_match[2][0]) . '.php';
                            $arr_list[$str_relative_fake_path] = $obj_entry;
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
     * Check if given class is whitelisted
     *
     * @param string $str_class_name Class name to check
     * @return boolean Is whitelisted
     */
    protected function isWhitelisted($str_directory_name)
    {
        // If it's not a whitelist mode, the class is allowed
        if (!$this->isWhitelistMode()) return true;

        return in_array($str_directory_name, $this->getWhitelist());
    }

    /**
     * Check if given class is blacklisted
     *
     * @param string $str_class_name Class name to check
     * @return boolean Is blacklisted
     */
    protected function isBlacklisted($str_directory_name)
    {
        // If it's not a blacklist mode, the class is allowed
        if (!$this->isBlacklistMode()) return false;

        return in_array($str_directory_name, $this->getBlacklist());
    }

}