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
 * Packages Model.
 * 
 * Data model of Packages plugin which inherits functions to manage data
 * of the DAO mother class, developed by Osclass for SQL querys.
 * 
 * @package DAO.Packages
 */

class Packages extends DAO
{

	private static $instance;

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
	
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * SQL Plugin's table
	 *
	 * Return only the table names
	 * 
	 * @package DAO.PackagesModel
	 * @access public 
	 * @return string Full table name
	 */
	public function getTable_packages_assigned()
	{
		return DB_TABLE_PREFIX.'t_packages_assigned';
	}

	public function getTable_packages_items()
	{
		return DB_TABLE_PREFIX.'t_packages_items';
	}

	public function getTable_packages()
	{
		return DB_TABLE_PREFIX.'t_packages';
	}

	public function import($file)
	{
		$sql  = file_get_contents($file);

		if(!$this->dao->importSQL($sql)) {
			throw new Exception("Error importSQL::Packages<br>".$file);
		}
	}

	/**
	 * Install all data of plugin to db.
	 *
	 * This function thogether with prevously function "import($file)" import the content from sql file
	 * to the db, so it save the data t_preference table of Osclass which is using the plugin:
	 * - Information about plugin version.
	 * - Configuration of habilitation of all Package plugin.
	 * - Configuration of Default package in the user register.
	 *
	 * @package DAO.PackagesModel
	 * @access public 
	 */
	public function install()
	{
		$this->import(PACKAGES_PATH . 'struct.sql');
		osc_set_preference('version', '1.1.3', 'packages', 'STRING');
		osc_set_preference('default_package', 0, 'packages', 'INTEGER');
		osc_set_preference('default_company_package', 0, 'packages', 'INTEGER');
		osc_set_preference('packages_profile_info', 1, 'packages', 'BOOLEAN');
		osc_set_preference('choose_package_url', '{OSC_CONTACT_URL}', 'packages', 'STRING');
		osc_set_preference('choose_package_show', '1', 'packages', 'STRING');

		osc_run_hook('packages_install');
	}

	/**
	 * Removal about of data related with Packages plugin from db.
	 *
	 * Is based from DAO class to make DROP TABLE queries for each one of tables,
	 * therefore delete completly the tables said previously.
	 * So delete the added information in t_preference.
	 *
	 * @package DAO.PackagesModel
	 * @access public 
	 */
	public function uninstall()
	{
		$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_packages_items()));
		$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_packages_assigned()));
		$this->dao->query(sprintf('DROP TABLE %s', $this->getTable_packages()));

		Preference::newInstance()->delete(array('s_section' => 'packages'));
		osc_run_hook('packages_uninstall');
	}

	/**
	 * Get the current date or the sum depending of her parameters.
	 *
	 * - Example of use 1: echo todaydate(); Show the current date.
	 * - Example of use 2: echo todaydate(3, 'days'); Show the current date but sum 3 days.
	 * - Example of use 3: echo todaydate(3, 'years'); Show the current date but sum 3 years.
	 *
	 * @param string $time The default value is H:i:s but can change during the estatemnt of function.
	 * @param int $num It defined null at the start as it can be empty, on the contrary must have a integer numeric value.
	 * @param string $ymd It defined null at the start as ir can be empty, on the contrary so it specific 'days', 'years' or 'month'.
	 * @return string $date Return the current in the H:i:s format.
	 * @return string $dateplus Return the current date added.
	 */
	public function todaydate($time = 'H:i:s', $num = null, $ymd = null)
	{
		$date = date('Y-m-d '.$time);

		if ($num && $ymd) {
			$dateplus = strtotime('+'.$num.' '.$ymd, strtotime($date));
			$dateplus = date('Y-m-d H:i:s', $dateplus);
			return $dateplus;
		} else {
			return $date;
		}
	}

	/**
	 * Create/Update package
	 */
	public function setPackage($data)
	{
		// Create
		if (!$data['pk_i_id']) {
			unset($data['pk_i_id']);
			return $this->dao->insert($this->getTable_packages(), $data);

		// Update
		} else {
			return $this->dao->update($this->getTable_packages(), $data, array('pk_i_id' => $data['pk_i_id']));
		}
	}

	public function getPackageById($id)
	{
		$this->dao->select('*');
		$this->dao->from($this->getTable_packages());
		$this->dao->where('pk_i_id', $id);
		$result = $this->dao->get();
		if ($result) {
			return $result->row();
		}
		return false;
	}

	public function getPackagePrice($id)
	{
		$package = $this->getPackageById($id);
		if ($package) {
			return $package['i_price'];
		}
		return 0;
	}

	public function getPackagesByUserType($type)
	{
		$this->dao->select('*');
		$this->dao->from($this->getTable_packages());
		$this->dao->where('b_company', $type);
		$this->dao->where('b_active', 1);
		//$this->dao->where("i_price > 0");
		$this->dao->orderBy('i_price', 'ASC');
		$result = $this->dao->get();
		if ($result) {
			return $result->result();
		}
		return array();
	}

	public function isEnabled($packageId)
	{
		$this->dao->select('b_active') ;
		$this->dao->from($this->getTable_packages());
		$this->dao->where('pk_i_id', $packageId);
		$result = $this->dao->get();
		if ($result) {
			$row = $result->row();
			if ($row) {
				if (isset($row['b_active']) && $row['b_active'] == 1) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Search packages
	 *
	 * This function is for search with parameters in the PackagesDataTable.php
	 *
	 * @access public
	 * @param array $params Is a array variable witch containt all parameters for the search and pagination
	 * @return array
	 */
	public function packages($params)
	{
		$start			= (isset($params['start']) && $params['start'] != '' ) ? $params['start']: 0;
		$limit			= (isset($params['limit']) && $params['limit'] != '' ) ? $params['limit']: 10;

		$sort			= (isset($params['sort']) && $params['sort'] != '') ? $params['sort'] : '';
		$sort			= strtolower($sort);

		switch ($sort) {
			case 'date':
				$sort = 'dt_date';
				break;

			case 'update':
				$sort = 'dt_update';
				break;

			case 'package_items':
				$sort = 'i_free_items';
				break;

			case 'price':
				$sort = 'i_price';
				break;

			default:
				$sort = 'dt_date';
				break;
		}

		$direction = (isset($params['direction']) && $params['direction'] == 'ASC') ? $params['direction'] : 'DESC';
		$direction = strtoupper($direction);

		$name = (isset($params['s_name']) && $params['s_name'] != '') ? $params['s_name'] : '';

		$userType = (isset($params['b_company']) && $params['b_company']!='') ? $params['b_company'] : '';
		$payFrequency = (isset($params['s_pay_frequency']) && $params['s_pay_frequency']!='') ? $params['s_pay_frequency'] : '';

		$packageItems = (isset($params['i_free_items']) && $params['i_free_items'] != '') ? $params['i_free_items'] : '';
		$packageItemsControl = (isset($params['package_items_control']) && $params['package_items_control']!='') ? $params['package_items_control'] : '';

		$price = (isset($params['i_price']) && $params['i_price'] != '') ? $params['i_price'] : '';
		$priceControl = (isset($params['price_control']) && $params['price_control']!='') ? $params['price_control'] : '';

		$date = (isset($params['dt_date']) && $params['dt_date'] != '') ? $params['dt_date'] : '';
		$dateControl = (isset($params['date_control']) && $params['date_control']!='') ? $params['date_control'] : '';

		$update = (isset($params['dt_update']) && $params['dt_update']!='') ? $params['dt_update'] : '';
		$updateControl = (isset($params['update_control']) && $params['update_control']!='') ? $params['update_control'] : '';

		$status = (isset($params['b_active']) && $params['b_active'] != '') ? $params['b_active'] : '';

		$this->dao->select('*');
		$this->dao->from($this->getTable_packages());
		$this->dao->orderBy($sort, $direction);

		if ($name != '') {
			$this->dao->like('s_name', $name);
		}

		if ($userType != '') {
			if ($userType == 0) {
				$this->dao->where('b_company', 0);
			} else {
				$this->dao->where('b_company', 1);
			}
		}

		if ($payFrequency != '') {
			$this->dao->where('s_pay_frequency', $payFrequency);
		}

		if ($packageItems != '') {
			switch ($packageItemsControl) {
				case 'equal':
					$this->dao->where('i_free_items', $packageItems);
					break;

				case 'greater':
					$this->dao->where("i_free_items > '$packageItems'");
					break;

				case 'greater_equal':
					$this->dao->where("i_free_items >= '$packageItems'");
					break;

				case 'less':
					$this->dao->where("i_free_items < '$packageItems'");
					break;

				case 'less_equal':
					$this->dao->where("i_free_items <= '$packageItems'");
					break;

				case 'not_equal':
					$this->dao->where("i_free_items != '$packageItems'");
					break;

				default:
					$this->dao->where('i_free_items', $packageItems);
					break;
			}
		}

		if ($price != '') {
			switch ($priceControl) {
				case 'equal':
					$this->dao->where('i_price', $price);
					break;

				case 'greater':
					$this->dao->where("i_price > '$price'");
					break;

				case 'greater_equal':
					$this->dao->where("i_price >= '$price'");
					break;

				case 'less':
					$this->dao->where("i_price < '$price'");
					break;

				case 'less_equal':
					$this->dao->where("i_price <= '$price'");
					break;

				case 'not_equal':
					$this->dao->where("i_price != '$price'");
					break;

				default:
					$this->dao->where('i_price', $price);
					break;
			}
		}

		if ($date != '') {
			switch ($dateControl) {
				case 'equal':
					$this->dao->where('dt_date', $date);
					break;

				case 'greater':
					$this->dao->where("dt_date > '$date'");
					break;

				case 'greater_equal':
					$this->dao->where("dt_date >= '$date'");
					break;

				case 'less':
					$this->dao->where("dt_date < '$date'");
					break;

				case 'less_equal':
					$this->dao->where("dt_date <= '$date'");
					break;

				case 'not_equal':
					$this->dao->where("dt_date != '$date'");
					break;

				default:
					$this->dao->where('dt_date', $date);
					break;
			}
		}

		if ($update != '') {
			switch ($updateControl) {
				case 'equal':
					$this->dao->where('dt_update', $update);
					break;

				case 'greater':
					$this->dao->where("dt_update > '$update'");
					break;

				case 'greater_equal':
					$this->dao->where("dt_update >= '$update'");
					break;

				case 'less':
					$this->dao->where("dt_update < '$update'");
					break;

				case 'less_equal':
					$this->dao->where("dt_update <= '$update'");
					break;

				case 'not_equal':
					$this->dao->where("dt_update != '$update'");
					break;

				default:
					$this->dao->where('dt_update', $update);
					break;
			}
		}

		if ($status != '') {
			if ($status == 0) {
				$this->dao->where('b_active', 0);
			} else {
				$this->dao->where('b_active', 1);
			}
		}

		$this->dao->limit($limit, $start);
		$result = $this->dao->get();
		if ($result) {
			return $result->result();
		}
		return array();
	}

	/**
	 * Count total packages
	 *
	 * @access public
	 * @return integer
	 */
	public function packagesTotal()
	{
		$this->dao->select('COUNT(*) as total') ;
		$this->dao->from($this->getTable_packages());
		$result = $this->dao->get();
		if ($result) {
			$row = $result->row();
			if(isset($row['total'])) {
				return $row['total'];
			}
		}
		return 0;
	}

	/**
	 * Delete package
	 *
	 * @access public
	 * @param integer $id
	 * @return bool Return true if has been deleted, on contrary will return false.
	 */
	public function deletePackage($id)
	{
		return $this->dao->delete($this->getTable_packages(), array('pk_i_id' => $id));
	}

	/**
	 * Assign package
	 *
	 * @access public
	 * @param integer $userId
	 * @return bool Return one result, on contrary will return false if not found nothing.
	 */
	public function getAssigned($userId)
	{
		$this->dao->select('*');
		$this->dao->from($this->getTable_packages_assigned());
		$this->dao->where('fk_i_user_id', $userId);
		$result = $this->dao->get();
		if($result) {
			return $result->row();
		}
		return false;
	}

	public function getActiveItemsByUserId($userId)
	{
		$this->dao->select('pk_i_id, fk_i_user_id, dt_pub_date, b_active, dt_expiration');
		$this->dao->from(DB_TABLE_PREFIX.'t_item');
		$this->dao->where("fk_i_user_id = $userId");
		$this->dao->where('b_active', 1);
		$this->dao->where('dt_expiration > \'' . date('Y-m-d H:i:s') . '\'');
		$this->dao->orderBy('dt_pub_date', 'ASC');
		$result = $this->dao->get();
		if($result) {
			return $result->result();
		}
		return array();
	}

	public function countActiveItemsByUserId($userId)
	{
		$this->dao->select('COUNT(*) as total');
		$this->dao->from(DB_TABLE_PREFIX.'t_item');
		$this->dao->where("fk_i_user_id = $userId");
		$this->dao->where('b_active', 1);
		$this->dao->where('dt_expiration > \'' . date('Y-m-d H:i:s') . '\'');
		$this->dao->orderBy('dt_pub_date', 'ASC');
		$result = $this->dao->get();
		if ($result) {
			$row = $result->row();
			if (isset($row['total'])) {
				return $row['total'];
			}
		}
		return 0;
	}

	public function updateAssignment($assignmentId, $package_id, $date = NULL, $invoiceId = NULL)
	{
		if($date==NULL) { $date = date("Y-m-d H:i:s"); };

		$package = $this->getPackageById($package_id);

		$date_from = $this->todaydate();
		switch ($package['s_pay_frequency']) {
			case 'month':
				$date_to  = $this->todaydate('00:00:00', 1, 'month');
				break;
			case 'quarterly':
				$date_to  = $this->todaydate('00:00:00', 3, 'month');
				break;
			case 'year':
				$date_to  = $this->todaydate('00:00:00', 1, 'year');
				break;
		}

		return $this->dao->update($this->getTable_packages_assigned(), array(
				'dt_date'=> $date,
				'dt_from_date'=> $date_from,
				'dt_to_date'=> $date_to,
				'fk_i_invoice_id'=> $invoiceId),
			array('pk_i_id' => $assignmentId));
	}

	/**
	 * Assign package
	 *
	 * @access public
	 * @param integer $packageId
	 * @param integer $userId
	 * @param date $date
	 * @param integer $invoiceId
	 * @return bool Return true if has been added, on contrary will return false.
	 */
	public function assignPackage($packageId, $userId, $date = NULL, $invoiceId = NULL)
	{
		if ($date == NULL) $date = date("Y-m-d H:i:s");

		$package = $this->getPackageById($packageId);

		// It will only assign active packages
		if ($package['b_active']) {

			$dateFrom = $this->todaydate('00:00:00');
			switch ($package['s_pay_frequency']) {
				case 'month':
					$dateSince  = $this->todaydate('00:00:00', 1, 'month');
					break;

				case 'quarterly':
					$dateSince  = $this->todaydate('00:00:00', 3, 'month');
					break;

				case 'year':
					$dateSince  = $this->todaydate('00:00:00', 1, 'year');
					break;
			}

			$addPck = array(
				'fk_i_user_id'      => $userId,
				'fk_i_package_id'   => $packageId,
				'dt_date'           => $date,
				'dt_from_date'      => $dateFrom,
				'dt_to_date'        => $dateSince,
				'fk_i_invoice_id'   => $invoiceId
			);

			return $this->dao->insert($this->getTable_packages_assigned(), $addPck);

		}

		return false;
	}

	/**
	 * Remove assignment package
	 *
	 * @access public
	 * @param integer $userId
	 * @return bool Return true if has been deleted, on contrary will return false.
	 */
	public function removePackageAssigned($userId)
	{
		return $this->dao->delete($this->getTable_packages_assigned(), array('fk_i_user_id' => $userId));
	}

	public function addItemRelation($item_id, $assignmentId)
	{
		$addRe = array('fk_i_item_id' => $item_id, 'fk_i_assignment_id' => $assignmentId, 'dt_date' => date('Y-m-d H:i:s'));
		return $this->dao->insert($this->getTable_packages_items(), $addRe);
	}

	/**
	 * Delete a relationship that have a package and Item published
	 *
	 * @access public
	 * @param integer $item_id
	 * @return bool Return true if has been deleted, on contrary will return false.
	 */
	public function delItemRelationByItemId($item_id)
	{
		return $this->dao->delete($this->getTable_packages_items(), array('fk_i_item_id' => $item_id));
	}

	/*
	 * Delete all about of a user (Delete assigned package)
	 */
	public function deleteAll($userId) {
		$this->removePackageAssigned($userId);
	}

	public function getTotalItemsByAssignment($assignmentId)
	{
		$this->dao->select('COUNT(*) as total') ;
		$this->dao->from($this->getTable_packages_items());
		$this->dao->where('fk_i_assignment_id', $assignmentId);
		$result = $this->dao->get();
		if ($result) {
			$row = $result->row();
			if (isset($row['total'])) {
				return (int) $row['total'];
			}
		}
		return 0;
	}
}
