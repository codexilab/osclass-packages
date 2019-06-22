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
 * Controller Integration mods system with VQmod
 */
class CAdminPackagesMods extends AdminSecBaseModel
{

    //Business Layer...
    public function doModel()
    {

        switch (Params::getParam('plugin_action')) {
            case 'install_vqmod':
                osc_add_flash_info_message(VQModManager::newInstance()->install(), 'admin');
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('packages-admin-mods'));
                break;

            case 'uninstall_vqmod':
                osc_add_flash_info_message(VQModManager::newInstance()->uninstall(), 'admin');
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('packages-admin-mods'));
                break;

            case 'add_mod':
                $path = packages_vqmod_xml_path();

                if(!is_writeable($path)) {
                    @chmod($path, 0777);
                }

                $zip = Params::getFiles("mod");
                if(isset($zip['size']) && $zip['size']!=0) {
                    // 1) Check if the repeated files (file.xml and file.xml.etc are the same files)
                    $zipFiles = array(); $pathFiles = array(); $allFiles = array(); $dups = array();
                    $za = new ZipArchive();
                    
                    // 2) Open temporal zip file
                    $za->open($zip['tmp_name']);

                    // 3) Go through all the files and collect all the names in an array (files)
                    if ($za->numFiles > 0) {
                        for ($i = 0; $i < $za->numFiles; $i++) { 
                            $stat = $za->statIndex($i);
                            $zipFiles[] = current(explode(".", basename($stat['name'])));
                            // Adiotinally, rename (.disabled) by default all mods before put in the xml folder
                            $za->renameName(basename($stat['name']), current(explode(".", basename($stat['name']))).'.xml.disabled');
                        }
                    }

                    $za->close(); // Close zip! we not need more

                    // 4) Prepare xml mods to merge and detect repeated, as well as from the zip and xml path
                    $mods = packages_get_mods();
                    if ($mods) {
                        foreach ($mods as $mod) {
                            $pathFiles[] = current(explode(".", $mod));
                        }
                    }
                    $allFiles = array_merge($zipFiles, $pathFiles);
                    
                    // 5) Go through the list of names in the array and compare duplicates, if they exist, collect them in another array (dups)
                    if ($allFiles) {
                        foreach (array_count_values($allFiles) as $val => $c) {
                            if($c > 1) $dups[] = $val;
                        }
                    }

                    // 6) Account from array the number of duplicates
                    if (count($dups) <= 0) {
                        (int) $status = osc_unzip_file($zip['tmp_name'], $path);
                    } else {
                        $status = 4;
                    }

                    @unlink($zip['tmp_name']);
                } else {
                    $status = 3;
                }
                switch ($status) {
                    case(0):    $msg = __("The xml mods integration folder is not writable", 'packages');
                                osc_add_flash_error_message($msg, 'admin');
                    break;
                    case(1):    $msg = __("The mod file integration has been uploaded correctly", 'packages');
                                osc_add_flash_ok_message($msg, 'admin');
                    break;
                    case(2):    $msg = __("The zip file is not valid", 'packages');
                                osc_add_flash_error_message($msg, 'admin');
                    break;
                    case(3):    $msg = __("No file was uploaded", 'packages');
                                osc_add_flash_error_message($msg, 'admin');
                    break;
                    case(4):    $msg = __("There are files repeated", 'packages');
                                osc_add_flash_error_message($msg, 'admin');
                    break;
                    case(-1):
                    default:    $msg = __("There was a problem adding the mod integration", 'packages');
                                osc_add_flash_error_message($msg, 'admin');
                    break;
                }
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('packages-admin-mods'));
                break;

            case 'enable':
                $path = packages_vqmod_xml_path();

                $enabled = 0;
                $mods = Params::getParam('id');

                if (!is_array($mods)) {
                    osc_add_flash_error_message(__("Select a mod.", 'packages'), 'admin');
                } else {

                    foreach ($mods as $mod) {
                        $mod = $path.$mod;
                        if (file_exists($mod.'.xml.disabled') && !is_dir($mod.'.xml.disabled') && $mod.'.xml' != $path.'index.xml' && !file_exists($mod.'.xml')) {
                            if (rename($mod.'.xml.disabled', $mod.'.xml')) $enabled++;
                        }
                    }

                    if ($enabled > 0) {
                        osc_add_flash_ok_message(__("Mod(s) files(s) have been enabled.", 'packages'), 'admin');
                    } else {
                        osc_add_flash_error_message(__("No mod file have been enabled.", 'packages'), 'admin');
                    }
                }
                ob_get_clean();
                $this->redirectTo($_SERVER['HTTP_REFERER']);
                break;

            case 'disable':
                $path = packages_vqmod_xml_path();

                $enabled = 0;
                $mods = Params::getParam('id');

                if (!is_array($mods)) {
                    osc_add_flash_error_message(__("Select a mod.", 'packages'), 'admin');
                } else {

                    foreach ($mods as $mod) {
                        $mod = $path.$mod;
                        if (file_exists($mod.'.xml') && !is_dir($mod.'.xml') && $mod.'.xml' != $path.'index.xml' && !file_exists($mod.'.xml.disabled')) {
                            if (rename($mod.'.xml', $mod.'.xml.disabled')) $enabled++;
                        }
                    }

                    if ($enabled > 0) {
                        osc_add_flash_ok_message(__("Mod(s) files(s) have been disabled.", 'packages'), 'admin');
                    } else {
                        osc_add_flash_error_message(__("No mod file have been disabled.", 'packages'), 'admin');
                    }
                }
                ob_get_clean();
                $this->redirectTo($_SERVER['HTTP_REFERER']);
                break;

            case 'delete':
                $path = packages_vqmod_xml_path();

                $deleted = 0;
                $mods = Params::getParam('id');

                if (!is_array($mods)) {
                    osc_add_flash_error_message(__("Select a mod.", 'packages'), 'admin');
                } else {
                    // Enabled and disabled versions are the same file instance
                    foreach ($mods as $mod) {
                        // Delete enabled version
                        $file = $path.$mod.'.xml';
                        if (file_exists($file) && !is_dir($file) && $file != $path.'index.xml') {
                            if (!is_writeable($file)) @chmod($file, 0777);
                            if (unlink($file)) $deleted++;
                        }
                        
                        // Delete disabled version
                        $file = $path.$mod.'.xml.disabled';
                        if (file_exists($file) && !is_dir($file)) {
                            if (!is_writeable($file)) @chmod($file, 0777);
                            if (unlink($file)) $deleted++;
                        }
                    }

                    if ($deleted > 0) {
                        osc_add_flash_ok_message(__("Mod(s) files(s) have been deleted.", 'packages'), 'admin');
                    } else {
                        osc_add_flash_error_message(__("No mod file have been deleted.", 'packages'), 'admin');
                    }
                }
                ob_get_clean();
                $this->redirectTo($_SERVER['HTTP_REFERER']);
                break;

            case 'purge_cache':
                $write_notif = array();

                if (Params::getParam('purge_vqmod_cache')) {
                    $vqcache_dir = packages_vqmod_vqcache_path();
                    $deleted = 0;
                    if (file_exists($vqcache_dir) && is_dir($vqcache_dir)) {
                        $files = glob($vqcache_dir.'*');
                        foreach($files as $file){ // iterate files
                            if(is_file($file))
                                if (@unlink($file)) $deleted++; // delete file
                        }
                        
                        $countFiles = count($files);
                        if ($countFiles == $deleted) {
                            $write_notif[] = __("- The entire cache was deleted", 'packages');
                        } else {
                            $write_notif[] = __('- Only '.$deleted.' of '.$countFiles.' files could be deleted', 'packages');    
                        }
                    } else {
                        $write_notif[] = __("The cache is empty", 'packages');
                    }
                }

                if (Params::getParam('purge_checked_cache')) {
                    $file = packages_vqmod_path().'checked.cache';
                    $deleted = 0;
                    if (file_exists($file)) {
                        if (@unlink($file)) $deletd++;
                    }

                    if ($deleted) {
                        $write_notif[] = __("- checked.cache was successfully deleted", 'packages');
                    } else {

                        $write_notif[] = __("- checked.cache no exists", 'packages');
                    }
                }

                if (Params::getParam('purge_mods_cache')) {
                    $file = packages_vqmod_path().'mods.cache';
                    $deleted = 0;
                    if (file_exists($file)) {
                        if (@unlink($file)) $deletd++;
                    }

                    if ($deleted) {
                        $write_notif[] = __("- mods.cache was successfully deleted", 'packages');
                    } else {
                        $write_notif[] = __("- mods.cache no exists", 'packages');
                    }
                }

                if (empty($write_notif)) {
                    osc_add_flash_info_message(__("Select an option", 'packages'), 'admin');
                } else {
                    osc_add_flash_info_message(implode('<br />', $write_notif), 'admin');
                }
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('packages-admin-mods'));
                break;
            
            default:
                $numLogs = count(packages_get_mods_logs());
                $this->_exportVariableToView('numLogs', $numLogs);

                // DataTable
                require_once PACKAGES_PATH . "classes/datatables/ModsDataTable.php";

                $modsDataTable = new ModsDataTable();
                $modsDataTable->table();
                $aData = @$modsDataTable->getData();
                $this->_exportVariableToView('aData', $aData);

                $bulk_options = array(
                    array('value' => '', 'data-dialog-content' => '', 'label' => __("Bulk actions", 'packages')),
                    array('value' => 'enable', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected mod file?', 'packages'), strtolower(__("Enable", 'packages'))), 'label' => __("Enable", 'packages')),
                    array('value' => 'disable', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected mod file?', 'packages'), strtolower(__("Disable", 'packages'))), 'label' => __("Disable", 'packages')),
                    array('value' => 'delete', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected mod file?', 'packages'), strtolower(__("Delete", 'packages'))), 'label' => __("Delete", 'packages'))
                );

                $bulk_options = osc_apply_filter("mods_bulk_filter", $bulk_options);
                $this->_exportVariableToView('bulk_options', $bulk_options);

                $status = VQModManager::newInstance()->status();
                $this->_exportVariableToView('status', $status);
                break;
        }
    }
    
}