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

<!-- Dialog assign package -->
<form id="dialog-assign-package" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__("Assign package", 'packages')); ?>">
    <input type="hidden" name="page" value="users" />
    <input type="hidden" name="action" value="assign_package" />
    <input type="hidden" name="id[]" value="" />

    <div class="form-horizontal">
        <div class="form-row" id="show-packages-list">
            Loading...
        </div>
        <div class="form-actions">
            <div class="wrapper">
            <a class="btn" href="javascript:void(0);" onclick="$('#dialog-assign-package').dialog('close');"><?php _e("Cancel", 'packages'); ?></a>
            <input id="assign-package-submit" type="submit" value="<?php echo osc_esc_html( __("Assign", 'packages') ); ?>" class="btn btn-submit" />
            </div>
        </div>
    </div>
</form>

<!-- Dialog package assigned -->
<div id="dialog-package-assigned" class="has-form-actions hide" title="<?php echo osc_esc_html(__("Package assigned", 'packages')); ?>">
    <div class="form-horizontal">
        <div class="form-horizontal">
            <div class="form-row" id="show-package-assigned">
                Loading...
            </div>
            <div class="form-actions">
                <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#dialog-package-assigned').dialog('close');"><?php _e("Close", 'packages'); ?></a>
                <br><br>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal windows of Bulk actions for dialog remove package -->
<form id="dialog-remove-package" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__("Remove package", 'packages')); ?>">
    <input type="hidden" name="page" value="users" />
    <input type="hidden" name="action" value="remove_package" />
    <input type="hidden" name="id[]" value="" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e("Are you sure you want to remove this package?", 'packages'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
            <a class="btn" href="javascript:void(0);" onclick="$('#dialog-remove-package').dialog('close');"><?php _e("Cancel", 'packages'); ?></a>
            <input id="remove-package-submit" type="submit" value="<?php echo osc_esc_html( __("Remove", 'packages') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>
<script>
	$(document).ready(function() {
		// Dialog assign package
	    $("#dialog-assign-package").dialog({
	        autoOpen: false,
	        modal: true,
            width: "700px",
            height: 520
	    });

        // Dialog package assigned
        $("#dialog-package-assigned").dialog({
            autoOpen: false,
            modal: true,
            width: "340px"
        });

        // Dialog remove
        $("#dialog-remove-package").dialog({
            autoOpen: false,
            modal: true
        });

        // dialog bulk actions function
        $("#datatablesFormCustom").submit(function() {
            if( $("#custom_bulk_actions option:selected").val() == "" ) {
                return false;
            }

            if( $("#datatablesFormCustom").attr('data-dialog-open') == "true" ) {
                return true;
            }

            $("#dialog-bulk-actions .form-row").html($("#custom_bulk_actions option:selected").attr('data-dialog-content'));
            $("#bulk-actions-submit").html($("#custom_bulk_actions option:selected").text());
            $("#datatablesFormCustom").attr('data-dialog-open', 'true');
            $("#dialog-bulk-actions").dialog('open');
            return false;
        });
        // /dialog bulk actions
	});

    // Dialog assign function
    function assign_package_dialog(user_id) {
        $("#dialog-assign-package input[name='id[]']").attr('value', user_id);
        $("#dialog-assign-package").dialog('open');
        var url = '<?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=packages_admin_ajax&route=assign_package_iframe&user='+user_id;
        $.ajax({
            method: "GET",
            url: url,
            dataType: "html"
        }).done(function(data) {
            $("#show-packages-list").html(data);
        });
    }

    // Dialog for show info about package assigned
    function package_assigned_dialog(user_id) {
        $("#dialog-package-assigned").dialog('open');
        var url = '<?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=packages_admin_ajax&route=package_assigned_iframe&user='+user_id;
        $.ajax({
            method: "GET",
            url: url,
            dataType: "html"
        }).done(function(data) {
            $("#show-package-assigned").html(data);
        });
    }

    // Dialog remove package function
    function remove_package_dialog(user_id) {
        $("#dialog-remove-package input[name='id[]']").attr('value', user_id);
        $("#dialog-remove-package").dialog('open');
        return false;
    }
</script>