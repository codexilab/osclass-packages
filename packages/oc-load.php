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
 
// Model
require_once PACKAGES_PATH . 'model/Packages.php';

// Helpers
require_once PACKAGES_PATH . 'helpers/hPayment.php';
require_once PACKAGES_PATH . 'helpers/hUtils.php';
require_once PACKAGES_PATH . 'helpers/hPackages.php';

// Controllers
require_once PACKAGES_PATH . 'controller/admin/packages.php';
require_once PACKAGES_PATH . 'controller/admin/settings.php';
require_once PACKAGES_PATH . 'controller/admin/users.php';

// Ajax
require_once PACKAGES_PATH . 'ajax/ajax.php';

// Classes
require_once PACKAGES_PATH . 'classes/CurrentPackage.php';
require_once PACKAGES_PATH . 'classes/XMLValidator.php';