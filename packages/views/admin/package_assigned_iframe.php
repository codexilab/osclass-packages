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
 
$assigment = __get('assigment');
$packageAssigned = __get('packageAssigned');
?>

<?php if ($packageAssigned) : ?>
<ul class="package-box">
	<li>
		<label for="a<?php echo $packageAssigned['pk_i_id']; ?>" class="mk-item-parent is-featured active">
			<div class="mk-item mk-item-language">
                <div class="banner" style="background-image:url(themes/modern/images/gr-b.png);">
                    <?php echo $packageAssigned['i_free_items']; ?>
                    <div style="position: absolute; left: 17px; top: 30px; font-size: 25px"><?php _e("listings", 'packages'); ?></div>
                </div>
                <div class="mk-info">
                    
                    <?php if ($packageAssigned['pk_i_id'] == get_default_package_id() || $packageAssigned['pk_i_id'] == get_default_company_package_id()) : ?>
                    <i class="flag"></i>
                    <?php endif; ?>
                    
                    <h3><?php echo $packageAssigned['s_name']; ?></h3>
                    <i class="author">ID Package: <?php echo $packageAssigned['pk_i_id']; ?></i>
                    <div class="market-actions">
                        <span class="more"><?php echo get_pay_frequency($packageAssigned['s_pay_frequency']); ?></span>
                        
                        <?php if ($packageAssigned['i_price'] > 0) : ?>
                        <span class="buy-btn"><?php echo "Cost $".$packageAssigned['i_price']; ?></span>
                        <?php else : ?>
                        <span class="download-btn"><?php _e("Free", 'packages'); ?></span>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
		</label>
	</li>
</ul>
<div style="clear: both;"><br></div>
<center>
	<?php echo '<strong>'.__("From date", 'packages').": </strong>" . osc_format_date($assigment['dt_from_date'], osc_date_format() . ' ' . osc_time_format() ); ?><br>
	<?php echo '<strong>'.__("Until date", 'packages').": </strong>" . osc_format_date($assigment['dt_to_date'], osc_date_format() . ' ' . osc_time_format() ); ?>
</center>
<?php else : ?>
<center><?php _e("Has no assigned package", 'packages'); ?></center>
<?php endif; ?>