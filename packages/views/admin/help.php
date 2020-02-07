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
<h2 class="render-title"><strong>Introduction</strong></h2>
<div class="form-row">
	<p>Sells promotional packages for its users, can be associated with an official Osclass payment plugin via vQmod for Osclass, so that you pay monthly, quarterly or annually for the same package after its expiration date or buy a higher one (upgrade) if the user wishes to have more Free publications available.</p>
</div>

<div style="clear: both;"><br><br></div>

<!-- Rules -->
<h2 class="render-title"><strong>Rules</strong></h2>
<div class="form-row">
	<ul>
		<li>
			<p>* <strong>NOTE:</strong> Automatically once the plugin is installed, it does not allow users to reach the publication form, they must do so with an assigned package and with available publications.</p>
		</li>
		<li>
			<p>* Set a default package (optional), when the user it join at your site, automatically it is assigned the default package for her first free listings.</p>
		</li>
		<li>
			<p>* <strong>NOTE:</strong> When a user update her profile and change the type of account to a 'Company', it will lost package that has and vice versa.</p>
		</li>
		<li>
			<p>* Packages of one type are only assigned to the same type of user account.</p>
		</li>
		<li>
			<p>* When a user account is deleted, all traces of activity are also deleted with the Promotional Packages System plugin.</p>
		</li>
		<li>
			<p>* When the assignment of a packet expires, it sends a message in the 'Dashboard' section every time it enters there, warning the expiration.</p>
		</li>
		<li>
			<p>* When the allocation of a package expires, it does not allow the use of the assigned package and therefore it does not publish either.</p>
		</li>
		<li>
			<p>* When the allocation of a package expires, it does not allow maintaining an active publication. <strong>NOTE:</strong> Inactive publications can not be edited.</p>
		</li>
	</ul>
</div>

<div style="clear: both;"><br><br></div>

<!-- Tasks -->
<h2 class="render-title"><strong>Tasks</strong></h2>

<!-- Tasks - Set packages for accounts Company-type -->
<h2 class="render-title">Set default package for accounts Company-type:</h2>
<div class="form-row float-left">
	<p><span>1)</span> Click on Show filters.</p>
	<p><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-3.jpg'; ?>"></p>
	<p><span>3)</span> Hover over the packages table, click on Set as default package link.</p>
	<p><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-6.jpg'; ?>"></p>
	<p><span>4)</span> Then on Set default package button to confirm.</p>
	<p><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-5.jpg'; ?>"></p>
	Now every time someone registers as a Company type account, they will automatically be assigned the default package for Company type accounts. This whole procedure also applies to User-type accounts.
</div>
<div class="form-row float-right">
	<p><span>2)</span> In User type select Company, then click on Apply filters.</p>
	<p><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-4.jpg'; ?>"></p>
</div>

<div style="clear: both;"><br><br></div>

<!-- Tasks - Assign package manually to an User -->
<h2 class="render-title">Assign package manually to an User:</h2>
<div class="form-row">
	<p><span>1)</span> Go to Users section.</p>
	<p><span>2)</span> Hover over the users table, click on Assign package.</p>
	<p><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-7.jpg'; ?>"></p>
	<p><span>3)</span> Select a package and click on Assign.</p>
</div>
<div class="form-row float-left">
	<img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-8.jpg'; ?>">
</div>
<div class="form-row float-right">
	When a package is assigned or reassigned, the used item counter returns to zero: [Used 0 / 100 Total].
</div>

<div style="clear: both;"><br><br></div>

<!-- FAQ -->
<h2 class="render-title"><strong>FAQ</strong></h2>

<?php osc_run_hook('admin_packages_help_faq'); ?>

<!-- Testing -->
<h2 class="render-title"><strong>Testing</strong></h2>

<!-- Testing - Situation 1 -->
<div class="form-row float-left">
	<h2 class="render-title">Situation [1]:</h2>
	<ul>
		<li>* [1.1] payment_method_enabled() throws false.</li>
		<li>* [1.2] There are NOT assigned package.</li>
		<li><p>Response: Do not allow to reach the form of publish of item (redirect).</p></li>
	</ul>
</div>

<!-- Testing - Situation 1.2 -->
<div class="form-row float-right">
	<h2 class="render-title">Situation [1.2]:</h2>
	<ul>
		<li>* [1.1] payment_method_enabled() throws false.</li>
		<li>* [1.2] There are assigned package, but:
			<ul>
				<li><p>Sold out OR Timed out.</p></li>
			</ul>
		</li>
		<li>Response: Do not allow to reach the form of publish of item (redirect).</li>
	</ul>
</div>

<div style="clear: both;"><br><br></div>

<!-- Testing - Situation 2 -->
<div class="form-row float-left">
	<h2 class="render-title">Situation [2]:</h2>
	<ul>
		<li>* payment_method_enabled() throws true.</li>
		<li>* pay_per_post() throws false.</li>
		<li>* [2.1] There are NOT assigned package.</li>
		<li><p>Response: Do not allow to reach the form of publish of item (redirect).</p></li>
	</ul>
</div>

<!-- Testing - Situation 2.2 -->
<div class="form-row float-right">
	<h2 class="render-title">Situation [2.2]:</h2>
	<ul>
		<li>* payment_method_enabled() throws true.</li>
		<li>* pay_per_post() throws false.</li>
		<li>* [2.2] There are assigned package, but:
			<ul>
				<li><p>Sold out OR Timed out.</p></li>
			</ul>
		</li>
		<li>Response: Do not allow to reach the form of publish of item (redirect).</li>
	</ul>
</div>

<div style="clear: both;"><br><br></div>

<!-- Testing - Situation 3 -->
<div class="form-row float-left">
	<h2 class="render-title">Situation [3]:</h2>
	<ul>
		<li>* payment_method_enabled() throws true.</li>
		<li>* pay_per_post() throws true.</li>
		<li>* There are NOT assigned package.</li>
		<li><p>Response: Allow publish (not redirect), the payment plugin will charge.</p></li>
	</ul>
</div>

<!-- Testing - Situation 3.2 -->
<div class="form-row float-right">
	<h2 class="render-title">Situation [3.2]:</h2>
	<ul>
		<li>* payment_method_enabled() throws true.</li>
		<li>* pay_per_post() throws true.</li>
		<li>* There are assigned package, but:
			<ul>
				<li><p>Sold out OR Timed out.</p></li>
			</ul>
		</li>
		<li>Response: Allow publish (not redirect), the payment plugin will charge.</li>
	</ul>
</div>

<div style="clear: both;"><br><br></div>

<!-- FAQ for developers -->
<h2 class="render-title"><strong>FAQ for developers</strong></h2>

<!-- FAQ - How to pass ID of package as argument in URL of Choose button? -->
<h2 class="render-title">How to pass ID of package as argument in URL of Choose button?</h2>
<div class="form-row">
	<p><span>1)</span> Go to Settings - Promotional Packages System.</p>
	<p><span>2)</span> In URL for 'Choose' button, add this: &pkgId={PACKAGE_ID}</p>
	<p>The complete URL should look like this: {OSC_CONTACT_URL}&pkgId={PACKAGE_ID}</p>
</div>

<div style="clear: both;"><br><br></div>

<!-- FAQ - How to custom Package Profile Information? -->
<h2 class="render-title">How to custom Package Profile Information?</h2>
<div class="form-row float-right">
	<center><img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/osclass-packages-help-9.jpg'; ?>"></center>
</div>
<div class="form-row float-left">
	<p><span>1)</span> Open the following files:</p>
	<p>CSS:</p>
	<p><code>packages/assets/css/user/modal-dialog.css</code></p>
	<p><code>packages/assets/css/user/flex-pricing.css</code></p>
	<p><code>packages/assets/css/user/packages-profile-info.css</code></p>
	<p>PHP:</p>
	<p><code>packages/parts/user/packages_profile_info.php</code></p> This file include the Modal Windows of enables packages.
</div>

<div style="clear: both;"><br><br></div>

<?php osc_run_hook('admin_packages_help_faq_dev'); ?>