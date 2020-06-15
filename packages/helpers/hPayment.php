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
 * Helpers for integration payment method
 */

/**
 * Payment method is enabled?
 *
 * @return boolean
 */
if (!function_exists('payment_method_enabled')) {
    function payment_method_enabled() {
        return osc_plugin_is_enabled('payment_pro/index.php');
    }
}

/**
 * Pay per post?
 *
 * Detect if the payment is ready for charge.
 *
 * @return boolean
 */
if (!function_exists('pay_per_post')) {
    function pay_per_post() {
        return osc_get_preference('pay_per_post', 'payment_pro');
    }
}
