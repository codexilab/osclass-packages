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
 * VQMod Helpers
 * @author CodexiLab
 */

/**
 * Get installation path vQmod.
 */
function packages_vqmod_path() {
	return PACKAGES_PATH . 'vqmod/';
}

/**
 * Get vQmod's xml file path.
 */
function packages_vqmod_xml_path() {
	return PACKAGES_PATH . 'vqmod/xml/';
}

/**
 * Get the vQmod logs path.
 */
function packages_vqmod_logs_path() {
	return PACKAGES_PATH . 'vqmod/logs/';
}

/**
 * Get the vQmod cache path
 */
function packages_vqmod_vqcache_path() {
	return PACKAGES_PATH . 'vqmod/vqcache/';
}

function packages_vqmod_checked_cache() {
	return PACKAGES_PATH . 'vqmod/checked.cache';
}

function packages_vqmod_mods_cache() {
	return PACKAGES_PATH . 'vqmod/mods.cache';
}

/**
 * Get a array of mods xml files.
 */
function packages_get_mods($path = null) {
	if ($path == null) $path = packages_vqmod_xml_path();

	$mods = array();
	if(file_exists($path) && is_dir($path) && $gestor = opendir($path)) {
	    while (($file = readdir($gestor)) !== false) { 
	        if ((!is_file($file)) && ($file != '.') && ($file != '..')) {
	            $mods[$file] = $file;
	        }
	    }; closedir($gestor);
	}
	return $mods;
}

/**
 * Get a array of logs files.
 */
function packages_get_mods_logs($path = null) {
	if ($path == null) $path = packages_vqmod_logs_path();

	$logs = array();
	if(file_exists($path) && is_dir($path) && $gestor = opendir($path)) {
	    while (($file = readdir($gestor)) !== false) { 
	        if ((!is_file($file)) && ($file != '.') && ($file != '..') && (preg_match('/^.*\.(log)$/i', $file))) {
	            $logs[$file] = $file;
	        }
	    }; closedir($gestor);
	}
	return $logs;
}