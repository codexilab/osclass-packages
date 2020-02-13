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
?>

<style type="text/css">
	.form-row span {
		font-weight: bold;
	}
	.float-left {
		float: left; width: 50%; overflow-wrap: break-word;
	}

	.float-right {
		float: right; width: 50%; overflow-wrap: break-word;
	}
</style>

<!-- Introduction -->
<h2 class="render-title"><strong><?php _e("Introduction", 'packages'); ?></strong></h2>
<div class="form-row">
	<p><?php _e("Sells promotional packages for its users, can be associated with an official Osclass payment plugin via vQmod for Osclass, so that you pay monthly, quarterly or annually for the same package after its expiration date or buy a higher one (upgrade) if the user wishes to have more Free publications available.", 'packages'); ?></p>
</div>

<div style="clear: both;"><br><br></div>

<!-- Rules -->
<h2 class="render-title"><strong><?php _e("Rules", 'packages'); ?></strong></h2>
<div class="form-row">
	<ul>
		<li>
			<p>* <strong><?php _e("NOTE:", 'packages'); ?></strong> <?php _e("Automatically once the plugin is installed, it does not allow users to reach the publication form, they must do so with an assigned package and with available publications.", 'packages'); ?></p>
		</li>
		<li>
			<p>* <?php _e("Set a default package (optional), when the user it join at your site, automatically it is assigned the default package for her first free listings.", 'packages'); ?></p>
		</li>
		<li>
			<p>* <strong><?php _e("NOTE:", 'packages'); ?></strong> <?php _e("When a user update her profile and change the type of account to a 'Company', it will lost package that has and vice versa.", 'packages'); ?></p>
		</li>
		<li>
			<p>* <?php _e("Packages of one type are only assigned to the same type of user account.", 'packages'); ?></p>
		</li>
		<li>
			<p>* <?php _e("When a user account is deleted, all traces of activity are also deleted with the Promotional Packages System plugin.", 'packages'); ?></p>
		</li>
		<li>
			<p>* <?php _e("When the assignment of a packet expires, it sends a message in the 'Dashboard' section every time it enters there, warning the expiration.", 'packages'); ?></p>
		</li>
		<li>
			<p>* <?php _e("When the assignment of a package expires, it does not allow the use of the assigned package and therefore it does not publish items either.", 'packages'); ?></p>
		</li>
		<li>
			<p>* <?php _e("When the assignment of a package expires, it does not allow maintaining an active publication.", 'packages'); ?> <strong><?php _e("NOTE:", 'packages'); ?></strong> <?php _e("Inactive publications can not be edited.", 'packages');?></p>
		</li>
	</ul>
</div>

<div style="clear: both;"><br><br></div>

<!-- Tasks -->
<h2 class="render-title"><strong><?php _e("Tasks", 'packages'); ?></strong></h2>

<!-- Tasks - Set packages for accounts Company-type -->
<h2 class="render-title"><?php _e("Set default package for accounts Company-type:", 'packages'); ?></h2>
<div class="form-row float-left">
	<p><span>1)</span> <?php _e("Click on Show filters.", 'packages'); ?></p>
	<p><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-3.jpg'; ?>"></p>
	<p><span>3)</span> <?php _e("Hover over the packages table, click on Set as default package link.", 'packages') ?></p>
	<p><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-6.jpg'; ?>"></p>
	<p><span>4)</span> <?php _e("Then on Set default package button to confirm.", 'packages'); ?></p>
	<p><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-5.jpg'; ?>"></p><?php _e("Now every time someone registers as a Company type account, they will automatically be assigned the default package for Company type accounts. This whole procedure also applies to User-type accounts.", 'packages'); ?>
</div>
<div class="form-row float-right">
	<p><span>2)</span> <?php _e("In User type select Company, then click on Apply filters.", 'packages'); ?></p>
	<p><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-4.jpg'; ?>"></p>
</div>

<div style="clear: both;"><br><br></div>

<!-- Tasks - Assign package manually to a User -->
<h2 class="render-title"><?php _e("Assign package manually to a User:", 'packages'); ?></h2>
<div class="form-row">
	<p><span>1)</span> <?php _e("Go to Users section.", 'packages'); ?></p>
	<p><span>2)</span> <?php _e("Hover over the users table, click on Assign package.", 'packages'); ?></p>
	<p><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-7.jpg'; ?>"></p>
	<p><span>3)</span> <?php _e("Select a package and click on Assign.", 'packages'); ?></p>
</div>
<div class="form-row float-left">
	<img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-8.jpg'; ?>">
</div>
<div class="form-row float-right">
	<?php _e("When a package is assigned or reassigned, the used item counter returns to zero: [Used 0 / 100 Total].", 'packages'); ?>
</div>

<div style="clear: both;"><br><br></div>

<!-- FAQ -->
<h2 class="render-title"><strong><?php _e("FAQ", 'packages'); ?></strong></h2>

<?php osc_run_hook('admin_packages_help_faq'); ?>

<!-- Testing -->
<h2 class="render-title"><strong><?php _e("Testing", 'packages'); ?></strong></h2>

<!-- Testing - Situation 1 -->
<div class="form-row float-left">
	<h2 class="render-title"><?php _e("Situation [1]:", 'packages'); ?> <input type="checkbox"></h2>
	<ul>
		<li>* <?php _e("[1.1] payment_method_enabled() throws false.", 'packages'); ?></li>
		<li>* <?php _e("[1.2] There are NOT assigned package.", 'packages'); ?></li>
		<li><p><?php _e("Response: Do not allow to reach the form of publish of item (redirect).", 'packages') ?></p></li>
	</ul>
</div>

<!-- Testing - Situation 1.2 -->
<div class="form-row float-right">
	<h2 class="render-title"><?php _e("Situation [1.2]:", 'packages'); ?> <input type="checkbox"></h2>
	<ul>
		<li>* <?php _e("[1.1] payment_method_enabled() throws false.", 'packages'); ?></li>
		<li>* <?php _e("[1.2] There are assigned package, but:", 'packages'); ?>
			<ul>
				<li><p><?php _e("Defeated: Is sold out OR expired.", 'packages'); ?></p></li>
			</ul>
		</li>
		<li><?php _e("Response: Do not allow to reach the form of publish of item (redirect).", 'packages'); ?></li>
	</ul>
</div>

<div style="clear: both;"><br><br></div>

<!-- Testing - Situation 2 -->
<div class="form-row float-left">
	<h2 class="render-title"><?php _e("Situation [2]:", 'packages'); ?> <input type="checkbox"></h2>
	<ul>
		<li>* <?php _e("payment_method_enabled() throws true.", 'packages'); ?></li>
		<li>* <?php _e("pay_per_post() throws false.", 'packages'); ?></li>
		<li>* <?php _e("[2.1] There are NOT assigned package.", 'packages'); ?></li>
		<li><p><?php _e("Response: Do not allow to reach the form of publish of item (redirect).", 'packages'); ?></p></li>
	</ul>
</div>

<!-- Testing - Situation 2.2 -->
<div class="form-row float-right">
	<h2 class="render-title"><?php _e("Situation [2.2]:", 'packages'); ?> <input type="checkbox"></h2>
	<ul>
		<li>* <?php _e("payment_method_enabled() throws true.", 'packages'); ?></li>
		<li>* <?php _e("pay_per_post() throws false.", 'packages'); ?></li>
		<li>* <?php _e("[2.2] There are assigned package, but:", 'packages'); ?>
			<ul>
				<li><p><?php _e("Defeated: Is sold out OR expired.", 'packages'); ?></p></li>
			</ul>
		</li>
		<li><?php _e("Response: Do not allow to reach the form of publish of item (redirect).", 'packages'); ?></li>
	</ul>
</div>

<div style="clear: both;"><br><br></div>

<!-- Testing - Situation 3 -->
<div class="form-row float-left">
	<h2 class="render-title"><?php _e("Situation [3]:", 'packages'); ?> <input type="checkbox"></h2>
	<ul>
		<li>* <?php _e("payment_method_enabled() throws true.", 'packages'); ?></li>
		<li>* <?php _e("pay_per_post() throws true.", 'packages'); ?></li>
		<li>* <?php _e("There are NOT assigned package.", 'packages'); ?></li>
		<li><p><?php _e("Response: Allow publish (not redirect), the payment plugin will charge.", 'packages') ?></p></li>
	</ul>
</div>

<!-- Testing - Situation 3.2 -->
<div class="form-row float-right">
	<h2 class="render-title"><?php _e("Situation [3.2]:", 'packages'); ?> <input type="checkbox"></h2>
	<ul>
		<li>* <?php _e("payment_method_enabled() throws true.", 'packages'); ?></li>
		<li>* <?php _e("pay_per_post() throws true.", 'packages'); ?></li>
		<li>* <?php _e("There are assigned package, but:", 'packages'); ?>
			<ul>
				<li><p><?php _e("Defeated: Is sold out OR expired.", 'packages'); ?></p></li>
			</ul>
		</li>
		<li><?php _e("Response: Allow publish (not redirect), the payment plugin will charge.", 'packages'); ?></li>
	</ul>
</div>

<div style="clear: both;"><br><br></div>

<!-- FAQ for developers -->
<h2 class="render-title"><strong><?php _e("FAQ for developers", 'packages'); ?></strong></h2>

<!-- FAQ - How to pass ID of package as argument in URL of Choose button? -->
<h2 class="render-title"><?php _e("How to pass ID of package as argument in URL of Choose button?", 'packages'); ?></h2>
<div class="form-row">
	<p><span>1)</span> <?php _e("Go to Settings - Promotional Packages System.", 'packages'); ?></p>
	<p><span>2)</span> <?php _e("In URL for 'Choose' button, add this: &pkgId={PACKAGE_ID}", 'packages'); ?></p>
	<p><?php _e("The complete URL should look like this: {OSC_CONTACT_URL}&pkgId={PACKAGE_ID}", 'packages'); ?></p>
</div>

<div style="clear: both;"><br><br></div>

<!-- FAQ - How to custom Package Profile Information? -->
<h2 class="render-title"><?php _e("How to custom Package Profile Information?", 'packages'); ?></h2>
<div class="form-row float-right">
	<center><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-9.jpg'; ?>"></center>
</div>
<div class="form-row float-left">
	<p><span>1)</span> <?php _e("Open the following files:", 'packages'); ?></p>
	<p>CSS:</p>
	<p><code><?php echo PACKAGES_FOLDER; ?>assets/css/user/modal-dialog.css</code></p>
	<p><code><?php echo PACKAGES_FOLDER; ?>assets/css/user/flex-pricing.css</code></p>
	<p><code><?php echo PACKAGES_FOLDER; ?>assets/css/user/packages-profile-info.css</code></p>
	<p>PHP:</p>
	<p><code><?php echo PACKAGES_FOLDER; ?>parts/user/packages_profile_info.php</code></p> <?php _e("This file include the Modal Windows of enables packages.", 'packages'); ?>
</div>

<div style="clear: both;"><br><br></div>

<?php osc_run_hook('admin_packages_help_faq_dev'); ?>