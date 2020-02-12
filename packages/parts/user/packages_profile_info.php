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

$packageAssigned = Packages::newInstance()->getAssigned(osc_logged_user_id());
$packageAssignedId = (isset($packageAssigned['fk_i_package_id'])) ? $packageAssigned['fk_i_package_id'] : 0;

if ($packageAssigned) {
	$packageItems 			= get_package_free_items($packageAssignedId);
	$publishedItems    		= Packages::newInstance()->getTotalItemsByAssignment($packageAssigned['pk_i_id']);
	$freeItems 	= $packageItems-$publishedItems;

	// Descending count, example: 3/5 (from five to three)
	if ($freeItems < 0) $freeItems = 0;

	// Ascending count, example: 2/5 (from two to five)
    $publishedItems = ($publishedItems-1 < 0) ? 0 : $publishedItems;
}

// Packages
$packages = choose_package_show($packageAssignedId);
?>

<?php
/**
* Package information
*/
if ($packageAssigned) : ?>
<h3><?php _e("Package", 'packages'); ?></h3>
<div class="packages-profile-info">
	<h2>
		<small><?php _e("Used", 'packages'); ?></small><?php echo $publishedItems; ?>/<?php echo $packageItems; ?><sup><small><?php _e("Total", 'packages'); ?></small></sup>
	</h2>
	
	<?php echo get_package_name($packageAssignedId); ?>
	
	<?php if ($packageAssigned && $packages) : ?>
	<button class="btn" onclick="location.href='#choose-package';"><?php _e("Upgrade", 'packages'); ?></button>
	<?php endif; ?>

	<div class="exp-info">
		<center>
			<span class="since"><?php echo osc_format_date($packageAssigned['dt_from_date'], osc_date_format()); ?></span>
			 - 
			<span class="until"><?php echo osc_format_date($packageAssigned['dt_to_date'], osc_date_format()); ?></span>
		</center>
	</div>
</div>
<?php endif; ?>

<?php if (!$packageAssigned && $packages) : ?>
<div class="packages-profile-info">
	<button type="submit" class="btn" onclick="location.href='#choose-package';">¡<?php _e("Choose package", 'packages'); ?>!</button>
</div>
<?php endif; ?>


<?php 
/**
* Modal Windows of enables packages
*/
if ($packages) : ?>
<div id="choose-package" class="modalDialog">
    <div>
    	<a href="#close" title="Close" class="close">X</a>
        <h2><?php _e("Choose package", 'packages'); ?></h2>

        <div class="flex-container">
	    	<?php foreach ($packages as $package) : ?>
				<div class="flex-item">
					<ul class="package">
						<li class="header highlight"><?php echo $package['s_name']; ?></li>

						<?php if (!$package['i_price']) : ?>
						<li class="gray"><?php _e("Free", 'packages'); ?></li>
						<?php else: ?>
						<li class="gray">$ <?php echo $package['i_price']; ?> / <?php echo get_pay_frequency($package['s_pay_frequency']); ?></li>
						<?php endif; ?>

						<li><?php echo get_package_free_items($package['pk_i_id']); ?> <?php _e("Listing(s)", 'packages'); ?></li>
						<li class="gray">
							<div class="packages-profile-info">

								<?php if (!$package['i_price'] || !pay_per_post()): ?>
								<button class="btn" onclick="location.href='<?php echo choose_package_url($package['pk_i_id']); ?>';"><?php _e("CHOOSE", 'packages'); ?></button>
								<?php else: ?>
								<button class="btn" onclick="javascript:addPackage(<?php echo $package['pk_i_id']; ?>);"><?php _e("BUY NOW", 'packages'); ?></button>
								<?php endif; ?>
							
							</div>
						</li>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
    </div>
</div>

<!-- javascript -->
<script>
	function addPackage(id) {
		return false;
	}
</script>
<?php endif; ?>