<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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
     * ModsDataTable class
     */
	class ModsDataTable extends DataTable
	{
		public function __construct()
        {
        	osc_add_filter('datatable_mods_status_class', array(&$this, 'row_class'));
            osc_add_filter('datatable_mods_status_text', array(&$this, '_status'));
        }

        /**
         * Build the table of all mods in the php file: views/admin/mods.php
         *
         * @access public
         * @param array $params
         * @return array
         */
        public function table($params = null)
        {
        	$this->addTableHeader();
            
            $mods = packages_get_mods();
            if (in_array('index.xml', $mods)) {
                unset($mods['index.xml']);
            }

            $total = count($mods);

            $start = $total;

            $this->start = intval($start);
            $this->limit = intval($total);

            $this->processData($mods);

            $this->total = $total;
            $this->total_filtered = $this->total;

            return @$this->getData();
        }

        private function addTableHeader()
        {
            $this->addColumn('status-border', '');
            $this->addColumn('status', __("Status", 'packages'));
            $this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');

            $this->addColumn('title', __("Title", 'packages'));
            $this->addColumn('file-name', __("Item ID", 'packages'));
            $this->addColumn('author', __("Author", 'packages'));

            $dummy = &$this;
            osc_run_hook("admin_mods_table", $dummy);
        }

        private function processData($mods)
        {
            if(!empty($mods)) {

                $i = 0;
                foreach($mods as $aRow) {
                    $i++;
                    $row = array();
                    $options = array();

                    $mod = current(explode(".", $aRow));
                    $modParts = pathinfo(packages_vqmod_xml_path().$aRow);
                    $status = ($modParts['extension'] == 'xml') ? 1 : 0;

                    $options[] = '<a href="javascript:opensource('.$i.', \''.$aRow.'\')">' . __("View source", 'packages') . '</a>';
                    if ($status == 1) {
                        $options[] = '<a href="javascript:disable_mod_dialog(\''.$mod.'\')">' . __("Disable", 'packages') . '</a>';
                    } else {
                        $options[] = '<a href="javascript:enable_mod_dialog(\''.$mod.'\')">' . __("Enable", 'packages') . '</a>';
                    }
                    $options[] = '<a href="javascript:delete_file(\''.$mod.'\')">' . __("Delete", 'packages') . '</a>';

                    $actions = '';
                    if (count($options) > 0) {
                        $options = osc_apply_filter('actions_manage_mods', $options, $aRow);
                        // create list of actions
                        $auxOptions = '<ul>'.PHP_EOL;
                        foreach( $options as $actual ) {
                            $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
                        }
                        $auxOptions  .= $moreOptions;
                        $auxOptions  .= '</ul>'.PHP_EOL;

                        $actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL;
                    }

                    $xml = simplexml_load_file(packages_vqmod_xml_path().$aRow);
                    $modVersion = (isset($xml->version)) ? (string) $xml->version : '';
                    $modTitle = (isset($xml->id)) ? (string) $xml->id : '';
                    $modTitle = ($modTitle != '') ? $modTitle." ($modVersion)" : $mod;
                    $modAuthor = (isset($xml->author)) ? (string) $xml->author : '';
                    
                    
                    $row['status-border']   = '';

                    $row['status']          = $status;
                    $row['bulkactions']     = '<input type="checkbox" name="id[]" value="'.$mod.'" />';
                    $row['title']           = $modTitle . $actions;
                    $row['file-name']       = $mod;
                    $row['author']          = $modAuthor;

                    $row = osc_apply_filter('mods_processing_row', $row, $aRow);

                    $this->addRow($row);
                    $this->rawRows[] = $aRow;
                }

            }
        }

        public function _status($status)
        {
            return (!$status) ? __("Disabled", 'packages') : __("Enabled", 'packages');
        }

        /**
         * Get the status of the row. There are three status:
         *     - inactive
         *     - active
         */
        private function get_row_status_class($status)
        {
            return (!$status) ? 'status-inactive' : 'status-active';
        }

        public function row_class($status)
        {
            return $this->get_row_status_class($status);
        }   

	}