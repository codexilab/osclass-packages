<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2019 - 2020 CodexiLab
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
	 * PackagesDataTable class
	 *
	 * @package Packages
	 * @subpackage classes
	 * @author CodexiLab
	 */
	class PackagesDataTable extends DataTable
	{
		public function __construct()
		{
			osc_add_filter('datatable_packages_status_class', array(&$this, 'row_class'));
			osc_add_filter('datatable_packages_status_text', array(&$this, '_status'));
		}

		/**
		 * Build the table of all packages with search and pagination in the php file: admin/packages.php
		 *
		 * @access public
		 * @param array $params
		 * @return array
		 */
		public function table($params)
        	{
			$this->addTableHeader();

			$start = ((int)$params['iPage']-1) * $params['iDisplayLength'];
			
			$this->start = intval($start);
			$this->limit = intval($params['iDisplayLength']);

			$packages = Packages::newInstance()->packages(array(
				'start'                 => $this->start,
				'limit'                 => $this->limit,

				'sort'                  => Params::getParam('sort'),
				'direction'             => Params::getParam('direction'),

				's_name'                => Params::getParam('s_name'),

				'b_company'             => Params::getParam('b_company'),
				's_pay_frequency'       => Params::getParam('s_pay_frequency'),

				'i_free_items'          => Params::getParam('package_items'),
				'package_items_control' => Params::getParam('packageItemsControl'),

				'i_price'               => Params::getParam('price'),
				'price_control'         => Params::getParam('priceControl'),

				'dt_date'               => Params::getParam('date'),
				'date_control'          => Params::getParam('dateControl'),

				'dt_update'             => Params::getParam('update'),
				'update_control'        => Params::getParam('updateControl'),

				'b_active'              => Params::getParam('b_active')

			));
			$this->processData($packages);

			$this->total = Packages::newInstance()->packagesTotal();
			$this->totalFiltered = $this->total;

			return $this->getData();
        	}

		private function addTableHeader()
		{
			$this->addColumn('status-border', '');
			$this->addColumn('status', __('Status'));
			$this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');

			$this->addColumn('name', __("Name", 'packages'));
			$this->addColumn('date', __("Date", 'packages'));
			$this->addColumn('user-type', __("User type", 'packages'));
			$this->addColumn('free-listings', __("Free listings", 'packages'));
			$this->addColumn('price', __("Price", 'packages'));
			$this->addColumn('pay-frecuency', __("Pay frequency", 'packages'));
			$this->addColumn('update-date', __("Update date", 'packages'));

			$dummy = &$this;
			osc_run_hook("admin_packages_table", $dummy);
		}

		private function processData($packages)
		{
		    if(!empty($packages)) {

			foreach($packages as $aRow) {
			    $row = array();

			    $options        = array();
			    $options_more   = array();
			    $moreOptions 	= '';
			    // first column

			    $options[]  = '<a href="'.osc_route_admin_url('packages-admin').'&package='.$aRow['pk_i_id'].'">' . __("Edit", 'packages') . '</a>';
			    $options[]  = '<a href="#" onclick="delete_dialog('.$aRow['pk_i_id'].');return false;">' . __('Delete', 'packages') . '</a>';

			    if( $aRow['b_active'] == 1 ) {
				$options[]  = '<a href="#" onclick="deactivate_dialog('.$aRow['pk_i_id'].');return false;">' . __("Deactivate", 'packages') . '</a>';
			    } else {
				$options[]  = '<a href="#" onclick="activate_dialog('.$aRow['pk_i_id'].');return false;">' . __("Activate", 'packages') . '</a>';
			    }
			    if( $aRow['pk_i_id'] == get_default_package_id() || $aRow['pk_i_id'] == get_default_company_package_id() ) {
				$options[] = '<a href="#" onclick="unset_default_dialog('.$aRow['pk_i_id'].');return false;">' . __("Unset default package", 'packages') . '</a>';
			    } else {
				$options[] = '<a href="#" onclick="set_default_dialog('.$aRow['pk_i_id'].');return false;">' . __("Set as default package", 'packages') . '</a>';
			    }
			    //$options_more[] = '<a href="#">' . __('Custom option') . '</a>';

			    // more actions
			    if (count($options_more) > 0) {
				$options_more = osc_apply_filter('more_actions_manage_packages', $options_more, $aRow);
				$moreOptions = '<li class="show-more">'.PHP_EOL.'<a href="#" class="show-more-trigger">'. __("Show more", 'packages') .'...</a>'. PHP_EOL .'<ul>'. PHP_EOL;
				foreach( $options_more as $actual ) {
				    $moreOptions .= '<li>'.$actual."</li>".PHP_EOL;
				}
				$moreOptions .= '</ul>'. PHP_EOL .'</li>'.PHP_EOL;
			    }

			    $actions = '';
			    if (count($options) > 0) {
				$options = osc_apply_filter('actions_manage_packages', $options, $aRow);
				// create list of actions
				$auxOptions = '<ul>'.PHP_EOL;
				foreach( $options as $actual ) {
				    $auxOptions .= '<li>'.$actual.'</li>'.PHP_EOL;
				}
				$auxOptions  .= $moreOptions;
				$auxOptions  .= '</ul>'.PHP_EOL;

				$actions = '<div class="actions">'.$auxOptions.'</div>'.PHP_EOL;
			    }

			    $row['status-border']   = '';
			    $row['status']          = $aRow['b_active'];
			    $row['bulkactions']     = '<input type="checkbox" name="id[]" value="'.$aRow['pk_i_id'].'" />';

			    $row['name']            = $aRow['s_name'] . $actions;
			    $row['date']            = osc_format_date($aRow['dt_date'], osc_date_format());
			    $row['user-type']       = ($aRow['b_company'] == 0) ? __("User", 'packages') : __("Company", 'packages');
			    $row['free-listings']   = $aRow['i_free_items'];
			    $row['price']           = $aRow['i_price'];
			    $row['update-date']     = ($aRow['dt_update'] == 0) ? '' : osc_format_date($aRow['dt_update'], osc_date_format());

			    switch ($aRow['s_pay_frequency']) {
				case 'month':
				    $aRow['s_pay_frequency'] = __("Monthly", 'packages');
				    break;

				case 'quarterly':
				    $aRow['s_pay_frequency'] = __("Quarterly", 'packages');
				    break;

				case 'year':
				    $aRow['s_pay_frequency'] = __("Yearly", 'packages');
				    break;
			    }
			    $row['pay-frecuency']   = $aRow['s_pay_frequency'];

			    $row = osc_apply_filter('packages_processing_row', $row, $aRow);

			    $this->addRow($row);
			    $this->rawRows[] = $aRow;
			}

		    }
		}

		public function _status($status)
		{
		    return (!$status) ? __("Inactive", 'packages') : __("Active", 'packages');
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
