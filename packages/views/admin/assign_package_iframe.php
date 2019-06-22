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
 
$userId = __get('userId');
$userType = get_user_type($userId);
$packages = get_packages_by_user_type($userType);
?>
<h3><?php echo (!$userType) ? __("User type accounts", 'packages') : __("Company type accounts", 'packages'); ?>:</h3>

<?php if ($packages) : ?>
<ul class="package-box">
    <?php foreach ($packages as $package) : ?>
    <li>
        <input class="mk-item-parent" type="radio" id="a<?php echo $package['pk_i_id']; ?>" name="packageId" value="<?php echo $package['pk_i_id']; ?>" />
        <label for="a<?php echo $package['pk_i_id']; ?>" class="mk-item-parent is-featured">
            <div class="mk-item mk-item-language">
                <div class="banner" style="background-image:url(http://localhost/osclass375/oc-admin/themes/modern/images/gr-b.png);">
                    <?php echo $package['i_free_items']; ?>
                    <div style="position: absolute; left: 17px; top: 30px; font-size: 25px"><?php _e("listings", 'packages'); ?></div>
                </div>
                <div class="mk-info">
                    
                    <!-- Mark for default package -->
                    <?php if ($package['pk_i_id'] == get_default_package_id() || $package['pk_i_id'] == get_default_company_package_id()) : ?>
                        <i class="flag"></i>
                    <?php endif; ?>
                    
                    <h3><?php echo $package['s_name']; ?></h3>
                    <i class="author">ID Package: <?php echo $package['pk_i_id']; ?></i>
                    <div class="market-actions">
                        <span class="more"><?php echo get_pay_frequency($package['s_pay_frequency']); ?></span>
                        
                        <?php if ($package['i_price'] > 0) : ?>
                        <span class="buy-btn"><?php echo "Cost $".$package['i_price']; ?></span>
                        <?php else : ?>
                        <span class="download-btn"><?php _e("Free", 'packages'); ?></span>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </label>
    </li>
    <?php endforeach; ?>
</ul>
<?php else : ?>
<center>
    <?php _e("Not there are packages for this type user", 'packages'); ?><br />
    <h4><a href="<?php echo osc_route_admin_url("packages-admin"); ?>"><?php _e("Set package", 'packages'); ?></a></h4>
</center>
<?php endif; ?>