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
 
if (!osc_get_preference('packages_profile_info', 'packages')) { 
    $display = 'inline';
} else {
    $display = 'none';
}
echo '<style type="text/css">
  .show-script {
    display: '.$display.'
}
</style>';
?>
<style type="text/css">
.show-tags {
    display: none;
}

.url-tag {
    background-color: #dddddd;
    border-radius: 2px;
    margin: 0px 3px 0px 0px;
    padding: 1px 0px 2px 0px;
    cursor: pointer;
}

.url-tag:hover {
    background-color: #bababe;
    color: #474749;
}
</style>
<h2 class="render-title"><?php _e("Packages", 'packages'); ?></h2>
<form>
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin-settings" />
    <input type="hidden" name="plugin_action" value="done" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e("URL for 'Choose' button", 'packages'); ?><br />
            <input id="choose-package-url" type="text" class="xlarge" style="width: 350px;" name="choose_package_url" value="<?php echo osc_get_preference('choose_package_url', 'packages'); ?>"><br />
            <?php _e("The ID of package can be passed as argument with following tag: {PACKAGE_ID}.", 'packages'); ?> <a id="show-more-tags" href="#"><?php _e("Show more tags", 'packages'); ?></a>
            <div class="show-tags form-row">
                <br />
                <?php $i = 0;
                foreach (get_url_tags() as $tag) {
                    $i++; printf('<span id="tag-'.$i.'" class="url-tag" onclick="copytag('.$i.')">%s</span>', $tag);
                } ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label-checkbox">
                <strong><h3><?php _e("Package Information Profile", 'packages'); ?></h3></strong>
                <label>
                    <input type="radio" name="choose_package_show" value="3" <?php echo (osc_get_preference('choose_package_show', 'packages') == "3" ? 'checked="true"' : ''); ?>>
                    <?php _e("Show only upgradeable packages", 'packages'); ?>
                </label><br>
                <label>
                    <input type="radio" name="choose_package_show" value="2" <?php echo (osc_get_preference('choose_package_show', 'packages') == "2" ? 'checked="true"' : ''); ?>>
                    <?php _e("Show all packages (include free)", 'packages'); ?>
                </label><br>
                <label>
                    <input type="radio" name="choose_package_show" value="1" <?php echo (osc_get_preference('choose_package_show', 'packages') == "1" ? 'checked="true"' : ''); ?>>
                    <?php _e("Do not show free packages", 'packages'); ?>
                </label><br>
                <label>
                    <input type="radio" name="choose_package_show" value="0" <?php echo (osc_get_preference('choose_package_show', 'packages') == "0" ? 'checked="true"' : ''); ?>>
                    <?php _e("Do not show packages", 'packages'); ?>
                </label>
            </div>
            <p><label><input id="packages_profile_info" type="checkbox" <?php echo (osc_get_preference('packages_profile_info', 'packages') ? 'checked="true"' : ''); ?> name="packages_profile_info" value="1"><?php _e("Show from user menu", 'packages'); ?></label></p>
            </div>
        </div>
        <div class="show-script form-row">
            <input type="text" class="xlarge" style="width: 300px;" value="&lt;?php osc_run_hook('packages_profile_info'); ?&gt;" disabled><br />
            <?php _e("Use this script for show manually (if you want)", 'packages'); ?>.
        </div>
        
        <?php osc_run_hook('packages_into_form_settings'); ?>

        <div class="form-actions">
            <input type="submit" value="<?php _e("Save all changes", 'packages'); ?>" class="btn btn-submit">
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('input#packages_profile_info').click(function () {
            if($(this).is(':checked')) {
            	$(".show-script").hide();
            } else {
                $(".show-script").show();
            }
        });

        $('#show-more-tags').click(function () {
            $('#show-more-tags').html("<?php echo __("Show more tags", 'packages'); ?>");
            if ($('.show-tags').css('display') == 'block') {
                $(".show-tags").hide();
            } else {
                $('#show-more-tags').html("<?php echo __("Hide tags", 'packages'); ?>");
                $(".show-tags").show();
            }
        });
    });

    function copytag(num) {
        var value1 = document.getElementById('choose-package-url').value;
        var value2 = $("#tag-"+num).text();
        document.getElementById('choose-package-url').value = value1+value2;
    }
</script>