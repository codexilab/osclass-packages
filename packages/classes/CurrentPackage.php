<?php
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
 * Current Package
 */
class CurrentPackage
{
	public $currentPackage;

	function __construct()
	{
		$this->currentPackage = array();
	}

	public function packageAssigned()
	{
		$freeItems = 0;
		$packageAssigned	= Packages::newInstance()->getAssigned(osc_logged_user_id());
		$packageId 			= (isset($packageAssigned['fk_i_package_id'])) ? $packageAssigned['fk_i_package_id'] : 0;
		$assignmentId 		= (isset($packageAssigned['pk_i_id'])) ? $packageAssigned['pk_i_id'] : 0;

		if ($packageAssigned) {

			$packageName 	= get_package_name($packageId);
			$fromDate 		= $packageAssigned['dt_from_date'];
			$untilDate 		= $packageAssigned['dt_to_date'];

			$packageItems 	= get_package_free_items($packageId);
			$publishedItems	= Packages::newInstance()->getTotalItemsByAssignment($assignmentId);
			$freeItems 		= $packageItems-$publishedItems; if ($freeItems < 0) $freeItems = 0;

			$this->currentPackage['id'] 				= (int) $packageId;
			$this->currentPackage['assignment_id'] 		= (int) $assignmentId;
			$this->currentPackage['name'] 				= (string) $packageName;
			$this->currentPackage['free_items'] 		= (int) $freeItems; 		// Descending count, example: 3/5 (from five to three)
			$this->currentPackage['published_items'] 	= (int) $publishedItems; 	// Ascending count, example: 2/5 (from two to five)
			$this->currentPackage['package_items'] 		= (int) $packageItems;
			$this->currentPackage['status'] 			= "assigned";
			$this->currentPackage['from_date'] 			= $fromDate;
			$this->currentPackage['until_date'] 		= $untilDate;

			// true: is defeated | false: is not defeated
			$this->currentPackage['defeated'] 			= check_date_interval($fromDate, $untilDate);
		}

		if ($packageAssigned && $freeItems == 0) {

			$this->currentPackage['in_use'] = false;

		} elseif ($packageAssigned && $freeItems > 0 && $freeItems <= $packageItems) {

			$this->currentPackage['in_use'] = true;
		}
	}

	public function getInfo()
	{
		return $this->currentPackage;
	}
}
