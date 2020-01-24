<?php
/*
 * Copyright 2019 CodexiLab
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * vQmod Manager Class
 */
class VQModManager
{
	
	private static $instance;

    public static $path;

    /**
     * Singleton Pattern
     * 
     * @package DAO.Packages
     * @access public 
     */
	public static function newInstance()
	{
		if (!self::$instance instanceof self) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	function __construct() {
        // Get directory two above installation directory (/var/www/html/osclass375/oc-includes/osclass/classes/)
        self::$path = realpath(dirname(__FILE__) . '/../../../../') . '/oc-includes/osclass/classes/';
    }

    public function status()
    {
        $fileContent = file_get_contents(self::$path . 'Plugins.php');

        preg_match('~static function loadActive\(\)
        {
            //VirtualQMOD 
            \$vqmod = osc_plugins_path\(\).\'packages/classes/VQMod.php\'; 
            if \(isset\(\$vqmod\) && file_exists\(\$vqmod\)\) require_once \$vqmod; if \(class_exists\(\"VQMod"\)\) VQMod::bootup\(\);~', $fileContent, $VQMod_started);

        preg_match('~if \(isset\(\$vqmod\) && file_exists\(\$vqmod\) && class_exists\(\"VQMod"\)\) : include_once\(VQMod::modCheck\(([^\';]+)\)\); else : include_once ([^\';]+); endif;~', $fileContent, $VQMod_installed);

        if ($VQMod_started && $VQMod_installed) return true;
        return false;
    }

    public function checkUpgradePluginSystem()
    {
        $fileContent = file_get_contents(self::$path . 'Plugins.php');

        // For Osclass 3.8.x, 3.7.x, 3.6.x versions
        if (osc_version() < 390) {
            if (preg_match('~osc_run_hook\(\"before_plugin_deactivate", \$path\);~', $fileContent)) {
                return true;
            }
            return false;

        // For Osclass 3.9.x
        } else {
            if (preg_match('~osc_run_hook\(\ \'before_plugin_deactivate\', \$path \);~', $fileContent)) {
                return true;
            }
            return false;
        }
        
    }

    public function upgradePluginSystem()
    {
        // Counters
        $write_errors = array();
        $changes = 0;
        $writes = 0;
        $i = 0;

        if(!is_writeable(self::$path . 'Plugins.php')) @chmod(self::$path . 'Plugins.php', 0777);

        if(!is_writeable(self::$path . 'Plugins.php')) {
            $write_errors[] = 'Plugins.php not writeable in ' . self::$path;
        }

        if(!empty($write_errors)) {
            return(implode('<br />', $write_errors));
        }

        // Create new UGRSR class
        $u = new UGRSR(self::$path);

        // Set file searching to off
        $u->file_search = false;

        // Add catalog index files to files to include
        $u->addFile('Plugins.php');

        // Upgrade plugin system of osclass
        $pattern_array['pattern'] = "~osc_run_hook\(\ 'before_plugin_deactivate' \);~";
        $pattern_array['replace'] = 'osc_run_hook( \'before_plugin_deactivate\', $path );';
        // For Osclass 3.8.x, 3.7.x, 3.6.x versions
        if (osc_version() < 390) {
            $pattern_array['pattern'] = '~osc_run_hook\(\"before_plugin_deactivate"\);~';
            $pattern_array['replace'] = 'osc_run_hook("before_plugin_deactivate", $path);';
        }
        
        $u->addPattern($pattern_array['pattern'], $pattern_array['replace']);

        // Get number of changes during run
        $result = $u->run();
        $writes += $result['writes'];
        $changes += $result['changes'];
        $i++;

        // output result to user
        if(!$changes) return('YOUR PLUGIN SYSTEM ALREADY UPGRADED!');
        if($writes != $i) return('ONE OR MORE FILES COULD NOT BE WRITTEN IN '. self::$path);
        return('YOUR PLUGIN SYSTEM HAS BEEN UPGRADED!');
    }

    public function purgeCache($checked_cache = null, $mods_cache = null, $vqmod_cache = null)
    {
        if ($checked_cache == null) $checked_cache = true;
        if ($mods_cache == null) $mods_cache = true;
        if ($vqmod_cache == null) $vqmod_cache = true;
        
        if ($checked_cache == true) {
            $file = packages_vqmod_path().'checked.cache';
            if (file_exists($file))
                @unlink($file);
        }

        if ($mods_cache == true) {
            $file = packages_vqmod_path().'mods.cache';
            if (file_exists($file))
                @unlink($file);
        }

        if ($vqmod_cache == true) {
            $vqcache_dir = packages_vqmod_vqcache_path();
            if (file_exists($vqcache_dir) && is_dir($vqcache_dir)) {
                $files = glob($vqcache_dir.'*');
                foreach ($files as $file) { // iterate files
                    if (is_file($file))
                        @unlink($file); // delete file
                }
            }
        }
    }

	public function install()
    {
        // Preparing the environment
        $VQModPath = PACKAGES_PATH . 'vqmod/';
        if(!is_writeable($VQModPath)) {
            chmod($VQModPath, 0777);
        }
        if(!is_writeable($VQModPath)) {
            $write_errors[] = $VQModPath.' could not change to writable';
        }

        // Counters
        $write_errors = array();
        $changes = 0;
        $writes = 0;
        $i = 0;

        // Verify path is correct
        if(empty(self::$path)) return('ERROR - COULD NOT DETERMINE CENTRAL PATH CORRECTLY - ' . dirname(__FILE__));

        if ($this->status()) {
            return 'VQMOD ALREADY INSTALLED!';
        }

        $original_p = substr(sprintf('%o', fileperms(self::$path . 'Plugins.php')), -4);

        if(!is_writeable(self::$path . 'Plugins.php')) @chmod(self::$path . 'Plugins.php', 0777);

        if(!is_writeable(self::$path . 'Plugins.php')) {
            $write_errors[] = 'Plugins.php not writeable in ' . self::$path;
        }

        if(!empty($write_errors)) {
            return(implode('<br />', $write_errors));
        }

        // Create new UGRSR class
        $u = new UGRSR(self::$path);

        // Set file searching to off
        $u->file_search = false;

        // Add catalog index files to files to include
        $u->addFile('Plugins.php');

        // Pattern to add vqmod include
        $pattern_array = array();

        $pattern_array['pattern'] = "~static function loadActive\(\)
        {~";

        $pattern_array['replace'] = 'static function loadActive()
        {
            //VirtualQMOD 
            $vqmod = osc_plugins_path().\'packages/classes/VQMod.php\'; 
            if (isset($vqmod) && file_exists($vqmod)) require_once $vqmod; if (class_exists("VQMod")) VQMod::bootup();';

        $u->addPattern($pattern_array['pattern'], $pattern_array['replace']);

        $result = $u->run();
        $writes += $result['writes'];
        $changes += $result['changes'];
        $i++;

        $u->clearPatterns();
        $u->resetFileList();

        $u->addFile('Plugins.php');

        // Pattern to run required files through vqmod
        $pattern_array['pattern'] = '/include_once ([^\';]+);/';
        $pattern_array['replace'] = 'if (isset($vqmod) && file_exists($vqmod) && class_exists("VQMod")) : include_once(VQMod::modCheck($1)); else : include_once $1; endif;';
        $u->addPattern($pattern_array['pattern'], $pattern_array['replace']);

        // Get number of changes during run
        $result = $u->run();
        $writes += $result['writes'];
        $changes += $result['changes'];
        $i++;

        if (!$this->checkUpgradePluginSystem()) {

            $u->clearPatterns();
            $u->resetFileList();

            $u->addFile('Plugins.php');

            // Upgrade plugin system of osclass
            $pattern_array['pattern'] = "~osc_run_hook\(\ 'before_plugin_deactivate' \);~";
            $pattern_array['replace'] = 'osc_run_hook( \'before_plugin_deactivate\', $path );';
            // For Osclass 3.8.x, 3.7.x, 3.6.x versions
            if (osc_version() < 390) {
                $pattern_array['pattern'] = '~osc_run_hook\(\"before_plugin_deactivate"\);~';
                $pattern_array['replace'] = 'osc_run_hook("before_plugin_deactivate", $path);';
            }
            $u->addPattern($pattern_array['pattern'], $pattern_array['replace']);

            // Get number of changes during run
            $result = $u->run();
            $writes += $result['writes'];
            $changes += $result['changes'];
            $i++;

        }

        @chmod(self::$path . 'Plugins.php', $original_p);

        // output result to user
        if(!$changes) return('VQMOD ALREADY INSTALLED!');
        if($writes != $i) return('ONE OR MORE FILES COULD NOT BE WRITTEN IN '. self::$path);
        return('VQMOD HAS BEEN INSTALLED ON YOUR SYSTEM!');
    }

    public function uninstall()
    {
        // Counters
        $changes = 0;
        $writes = 0;
        $i = 0;

        // Verify path is correct
        if(empty(self::$path)) return('ERROR - COULD NOT DETERMINE CENTRAL PATH CORRECTLY - ' . dirname(__FILE__));

        // Get original permissions
        $original_p = substr(sprintf('%o', fileperms(self::$path . 'Plugins.php')), -4);

        if(!is_writeable(self::$path . 'Plugins.php')) @chmod(self::$path . 'Plugins.php', 0777);

        $write_errors = array();
        if(!is_writeable(self::$path . 'Plugins.php')) {
            $write_errors[] = 'Plugins.php not writeable in ' . self::$path;
        }

        if(!empty($write_errors)) {
            return(implode('<br />', $write_errors));
        }

        // Create new UGRSR class
        $u = new UGRSR(self::$path);

        // Set file searching to off
        $u->file_search = false;

        // Add catalog index files to files to include
        $u->addFile('Plugins.php');

        // Pattern to add vqmod include
        $pattern_array = array();
        $pattern_array['pattern'] = '~static function loadActive\(\)
        {
            //VirtualQMOD 
            \$vqmod = osc_plugins_path\(\).\'packages/classes/VQMod.php\'; 
            if \(isset\(\$vqmod\) && file_exists\(\$vqmod\)\) require_once \$vqmod; if \(class_exists\(\"VQMod"\)\) VQMod::bootup\(\);~';

        $pattern_array['replace'] = 'static function loadActive()
        {';

        $u->addPattern($pattern_array['pattern'], $pattern_array['replace']);

        $result = $u->run();
        $writes += $result['writes'];
        $changes += $result['changes'];
        $i++;

        $u->clearPatterns();
        $u->resetFileList();

        $u->addFile('Plugins.php');

        // Pattern to run required files through vqmod
        $pattern_array['pattern'] = '~if \(isset\(\$vqmod\) && file_exists\(\$vqmod\) && class_exists\(\"VQMod"\)\) : include_once\(VQMod::modCheck\(([^\';]+)\)\); else : include_once ([^\';]+); endif;~';
        $pattern_array['replace'] = 'include_once $1;';
        
        $u->addPattern($pattern_array['pattern'], $pattern_array['replace']);

        // Get number of changes during run
        $result = $u->run();
        $writes += $result['writes'];
        $changes += $result['changes'];
        $i++;

        if (!$this->checkUpgradePluginSystem()) {

            $u->clearPatterns();
            $u->resetFileList();

            $u->addFile('Plugins.php');

            // Upgrade plugin system of osclass
            $pattern_array['pattern'] = "~osc_run_hook\(\ 'before_plugin_deactivate' \);~";
            $pattern_array['replace'] = 'osc_run_hook( \'before_plugin_deactivate\', $path );';
            // For Osclass 3.8.x, 3.7.x, 3.6.x versions
            if (osc_version() < 390) {
                $pattern_array['pattern'] = '~osc_run_hook\(\"before_plugin_deactivate"\);~';
                $pattern_array['replace'] = 'osc_run_hook("before_plugin_deactivate", $path);';
            }
            $u->addPattern($pattern_array['pattern'], $pattern_array['replace']);

            // Get number of changes during run
            $result = $u->run();
            $writes += $result['writes'];
            $changes += $result['changes'];
            $i++;

        }

        @chmod(self::$path . 'Plugins.php', $original_p);

        // output result to user
        if(!$changes) return('VQMOD ALREADY UNINSTALLED!');
        if($writes != $i) return('ONE OR MORE FILES COULD NOT BE WRITTEN IN '. self::$path);
        return('VQMOD HAS BEEN UNINSTALLED ON YOUR SYSTEM!');
    }
}
