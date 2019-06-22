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
?>

<label>
    <select name="packageId" id="packageId" class="select-box-extra">
        <option value=""><?php _e("Package list", 'packages'); ?></option>
        <?php
        $usersPackages 		= get_packages_by_user_type(0);
        $companyPackages	= get_packages_by_user_type(1);
        ?>
        <?php if ($usersPackages) : ?>
        <option value=""> <?php _e('User'); ?></option>
        <?php foreach ($usersPackages as $userPackage) : ?>
        <option value="<?php echo $userPackage['pk_i_id']; ?>">  |— <?php echo $userPackage['s_name']; ?></option>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($companyPackages) : ?>
        <option value=""> <?php _e('Company'); ?></option>
        <?php foreach ($companyPackages as $companyPackage) : ?>
        <option value="<?php echo $companyPackage['pk_i_id']; ?>">  |— <?php echo $companyPackage['s_name']; ?></option>
        <?php endforeach; ?>
        <?php endif; ?>
    </select>
</label>