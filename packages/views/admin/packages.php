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
 
$packageById = __get('packageById');

$aData          = __get('aData');
$iDisplayLength = __get('iDisplayLength');
$sort           = Params::getParam('sort');
$direction      = Params::getParam('direction');

$columns        = $aData['aColumns'];
$rows           = $aData['aRows'];
?>
<h2 class="render-title">
    <?php _e('Manage packages', 'packages'); ?> <a id="set-package-button" href="javascript:void(0);" class="btn btn-mini"><?php _e('Add new package', 'packages'); ?></a>
    
    <?php if (get_default_package_id() > 0) : ?>
    <a href="<?php echo osc_route_admin_url('packages-admin').'&package='.get_default_package_id(); ?>" class="btn btn-mini">✔ <?php _e('Default package for users', 'packages'); ?></a>
    <?php endif; ?>

    <?php if (get_default_company_package_id() > 0) : ?>
    <a href="<?php echo osc_route_admin_url('packages-admin').'&package='.get_default_company_package_id(); ?>" class="btn btn-mini">✔ <?php _e('Default package for companies', 'packages'); ?></a>
    <?php endif; ?>
</h2>
<form id="set-package" method="post" action="<?php echo osc_route_admin_url('packages-admin').'&package='.Params::getParam('package'); ?>" <?php echo ($packageById) ? 'style="display: block"' : 'style="display: none"'; ?>>
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin" />
    <input type="hidden" name="plugin_action" value="set" />

    <div class="form-horizontal">
        <div class="form-row">
            <div class="form-label"><?php _e('User type'); ?>:</div>
            <div class="form-controls">
                <div class="select-box undefined">
                    <select name="b_company" style="opacity: 0;">
                        <option value="0" <?php if ($packageById) echo get_html_selected(0, $packageById['b_company']); ?>><?php _e('User', 'packages'); ?></option>
                        <option value="1" <?php if ($packageById) echo get_html_selected(1, $packageById['b_company']); ?>><?php _e('Company', 'packages'); ?></option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-label"><?php _e('Name'); ?>:</div>
            <div class="form-controls"><input type="text" class="xlarge" name="s_name" value="<?php if (isset($packageById['s_name']) && $packageById['s_name']) echo $packageById['s_name']; ?>"></div>
        </div>

        <div class="form-row">
            <div class="form-label"></div>
            <div class="form-controls">
                <?php _e('Free listings', 'packages'); ?>: <input type="text" class="input-small" name="i_free_items" value="<?php if (isset($packageById['i_free_items']) && $packageById['i_free_items']) echo $packageById['i_free_items']; ?>">  <?php _e('Price', 'packages'); ?>: <input type="text" class="input-small" name="i_price" value="<?php if (isset($packageById['i_price']) && $packageById['i_price']) echo $packageById['i_price']; ?>"> $.
            </div>
        </div>

        <div class="form-row">
            <div class="form-label"><?php _e('Pay frequency', 'packages'); ?>:</div>
            <div class="form-controls">
                <div class="select-box undefined">
                    <select name="s_pay_frequency" style="opacity: 0;">
                        <option value="month" <?php if ($packageById) echo get_html_selected('month', $packageById['s_pay_frequency']); ?>><?php _e('Monthly', 'packages'); ?></option>
                        <option value="quarterly" <?php if ($packageById) echo get_html_selected('quarterly', $packageById['s_pay_frequency']); ?>><?php _e('Quarterly', 'packages'); ?></option>
                        <option value="year" <?php if ($packageById) echo get_html_selected('year', $packageById['s_pay_frequency']); ?>><?php _e('Yearly', 'packages'); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label"></div>
            <div class="form-controls">
                <label>
                    <input type="checkbox" <?php if (isset($packageById['b_active']) && $packageById['b_active']) echo 'checked="true"'; if (!$packageById) echo 'checked="true"'; ?> name="b_active" value="1"> 
                    <?php _e('Activate this package', 'packages'); ?>.
                 </label>
            </div>
        </div>

        <?php if ($packageById) : ?>
        <div id="set-default-package" class="form-row" style="display: block">
            <div class="form-controls">
                <?php if (isset($packageById['pk_i_id']) && $packageById['pk_i_id'] == get_default_package_id() || $packageById['pk_i_id'] == get_default_company_package_id()) : ?>
                <a href="#" onclick="unset_default_dialog(<?php echo $packageById['pk_i_id']; ?>);return false;"><?php _e('Unset default package', 'packages'); ?></a>
                <?php else : ?>
                <a href="#" onclick="set_default_dialog(<?php echo $packageById['pk_i_id']; ?>);return false;"><?php _e('Set as default package', 'packages'); ?></a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-actions">
            <input id="submit-set-package" type="submit" value="<?php echo ($packageById) ? __('Update package', 'packages') : __('Add package', 'packages'); ?>" class="btn btn-submit">
            <a id="reset-set-package" href="javascript:void(0);" class="btn"><?php _e('Reset', 'packages'); ?></a>
            <a id="cancel-set-package" href="javascript:void(0);" class="btn"><?php _e('Cancel'); ?></a>
        </div>
    </div>
</form>

<!-- DataTable -->
<div class="relative">
    <div id="users-toolbar" class="table-toolbar">
        <div class="float-right">
            <form method="get" action="<?php echo osc_admin_base_url(true); ?>"  class="inline nocsrf">
                <?php foreach ( Params::getParamsAsArray('get') as $key => $value ) : ?>
                <?php if ( $key != 'iDisplayLength' ) : ?>
                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo osc_esc_html($value); ?>" />
                <?php endif; ?>
                <?php endforeach; ?>

                <select name="iDisplayLength" class="select-box-extra select-box-medium float-left" onchange="this.form.submit();" >
                    <option value="10"><?php printf(__('%d Packages', 'packages'), 10); ?></option>
                    <option value="25" <?php if ( Params::getParam('iDisplayLength') == 25 ) echo 'selected'; ?> ><?php printf(__('%d Packages', 'packages'), 25); ?></option>
                    <option value="50" <?php if ( Params::getParam('iDisplayLength') == 50 ) echo 'selected'; ?> ><?php printf(__('%d Packages', 'packages'), 50); ?></option>
                    <option value="100" <?php if ( Params::getParam('iDisplayLength') == 100 ) echo 'selected'; ?> ><?php printf(__('%d Packages', 'packages'), 100); ?></option>
                </select>
            </form>

            <form method="get" action="<?php echo osc_admin_base_url(true); ?>" id="shortcut-filters" class="inline nocsrf">
                <input type="hidden" name="page" value="plugins" />
                <input type="hidden" name="action" value="renderplugin" />
                <input type="hidden" name="route" value="packages-admin" />

                <a id="btn-display-filters" href="#" class="btn"><?php _e('Show filters'); ?></a>
            </form>
        </div>
    </div>

    <form id="datatablesForm" method="post" action="<?php echo osc_route_admin_url('packages-admin'); ?>">
        <input type="hidden" name="page" value="plugins" />
        <input type="hidden" name="action" value="renderplugin" />
        <input type="hidden" name="route" value="packages-admin" />

        <!-- Bulk actions -->
        <div id="bulk-actions">
            <label>
                <?php osc_print_bulk_actions('bulk_actions', 'plugin_action', __get('bulk_options'), 'select-box-extra'); ?>
                <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ); ?>" />
            </label>
        </div>

        <div class="table-contains-actions">
            <table class="table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <?php foreach($columns as $k => $v) {
                            echo '<th class="col-'.$k.' '.($sort==$k?($direction=='desc'?'sorting_desc':'sorting_asc'):'').'">'.$v.'</th>';
                        }; ?>
                    </tr>
                </thead>
                <tbody>
                <?php if( count($rows) > 0 ) { ?>
                    <?php foreach($rows as $key => $row) {
                        $status = $row['status'];
                        $row['status'] = osc_apply_filter('datatable_packages_status_text', $row['status']);
                         ?>
                        <tr class="<?php echo osc_apply_filter('datatable_packages_status_class',  $status); ?>">
                            <?php foreach($row as $k => $v) { ?>
                                <td class="col-<?php echo $k; ?>"><?php echo $v; ?></td>
                            <?php }; ?>
                        </tr>
                    <?php }; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="<?php echo count($columns)+1; ?>" class="text-center">
                            <p><?php _e('No data available in table'); ?></p>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div id="table-row-actions"></div> <!-- used for table actions -->
        </form>
    </div>
</div>

<!-- DataTable pagination -->
<?php
function showingResults(){
    $aData = __get('aData');
    echo '<ul class="showing-results"><li><span>'.osc_pagination_showing((Params::getParam('iPage')-1)*$aData['iDisplayLength']+1, ((Params::getParam('iPage')-1)*$aData['iDisplayLength'])+count($aData['aRows']), $aData['iTotalDisplayRecords'], $aData['iTotalRecords']).'</span></li></ul>';
}
osc_add_hook('before_show_pagination_admin','showingResults');
osc_show_pagination_admin($aData);
?>

<div id="import-export-options" class="float-right">
    <fieldset>
        <legend><?php _e('Import / Export packages', 'packages'); ?></legend>

        <?php if (file_exists(PACKAGES_PATH . 'packages.xml')) : ?>
        <div class="float-right">packages.xml 
            <?php if (XmlValidator::isXMLFileValid(PACKAGES_PATH . 'packages.xml')) : ?>
            <span class="bg-valid-xml">
                <img alt="<?php _e('Valid XML file', 'packages'); ?>" title="<?php _e('Valid XML file', 'packages'); ?>" width="10" height="10" src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/noun_Check_334829.png'; ?>">
            </span>
            <?php else : ?>
            <span class="bg-invalid-xml">
                <img alt="<?php _e('Invalid XML file', 'packages'); ?>" title="<?php _e('Invalid XML file', 'packages'); ?>" width="10" height="10" src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/noun_important_2095249.png'; ?>">
            </span>
            <?php endif; ?>
        </div>

        <div class="float-right icon"><a href="#" onclick="simple_open_dialog('delete-packages');return false;"><img alt="<?php _e('Delete'); ?>" title="<?php _e('Delete'); ?>" src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/noun_Delete_936890.png'; ?>"></a></div>
        <div class="float-right icon"><a href="<?php echo osc_route_admin_url('packages-admin').'&plugin_action=download_packages&'.osc_csrf_token_url(); ?>"><img alt="<?php _e('Download', 'packages'); ?>" title="<?php _e('Download', 'packages'); ?>" src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/noun_Download_1372228_2.png'; ?>"></a></div>
        <?php endif; ?>

        <div class="float-right icon"><a href="#" onclick="simple_open_dialog('export-packages');return false;"><img alt="<?php _e('Export', 'packages'); ?>" title="<?php _e('Export', 'packages'); ?>" src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/noun_export_1387515_2.png'; ?>"></a></div>
        
        <?php if (file_exists(PACKAGES_PATH . 'packages.xml')) : ?>
        <div class="float-right icon"><a href="#" onclick="simple_open_dialog('import-packages');return false;"><img alt="<?php _e('Import', 'packages'); ?>" title="<?php _e('Import', 'packages'); ?>" src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/noun_import_1387515_1.png'; ?>"></a></div>
        <?php endif; ?>
        
        <div class="float-right icon"><a href="#" onclick="simple_open_dialog('upload-packages');return false;"><img alt="<?php _e('Upload'); ?>" title="<?php _e('Upload'); ?>" src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/noun_Upload_1372228.png'; ?>"></a></div>
    </fieldset>
</div>

<!-- Dialog when it want delete a package -->
<form id="package-delete" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Delete package', 'packages')); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin" />
    <input type="hidden" name="plugin_action" value="delete" />
    <input type="hidden" name="id[]" value="" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to delete this package?', 'packages'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#package-delete').dialog('close');"><?php _e('Cancel'); ?></a>
                <input type="submit" value="<?php echo osc_esc_html( __('Delete') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<!-- Dialog when it want activate a package -->
<form id="package-activate" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Activate package', 'packages')); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin" />
    <input type="hidden" name="plugin_action" value="activate" />
    <input type="hidden" name="id[]" value="" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to activate this package?', 'packages'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#package-activate').dialog('close');"><?php _e('Cancel'); ?></a>
                <input id="package-activate-submit" type="submit" value="<?php echo osc_esc_html( __('Activate') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<!-- Dialog when it want deactivate a package -->
<form id="package-deactivate" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__("Deactivate package", 'packages')); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin" />
    <input type="hidden" name="plugin_action" value="deactivate" />
    <input type="hidden" name="id[]" value="" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to deactivate this package?', 'packages'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#package-deactivate').dialog('close');"><?php _e('Cancel'); ?></a>
                <input id="package-deactivate-submit" type="submit" value="<?php echo osc_esc_html( __('Deactivate') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<!-- Dialog when it want set a default package -->
<form id="dialog-set-default-package" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Set default package', 'packages')); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin" />
    <input type="hidden" name="plugin_action" value="set_default" />
    <input type="hidden" name="id" value="" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to set this package like default?', 'packages'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#dialog-set-default-package').dialog('close');"><?php _e('Cancel'); ?></a>
                <input id="set-default-package-submit" type="submit" value="<?php echo osc_esc_html( __('Set default package') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<!-- Dialog when it want unset a default package -->
<form id="dialog-unset-default-package" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Unset default package', 'packages')); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin" />
    <input type="hidden" name="plugin_action" value="unset_default" />
    <input type="hidden" name="id" value="" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to unset this package like default?', 'packages'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#dialog-unset-default-package').dialog('close');"><?php _e('Cancel'); ?></a>
                <input id="unset-default-package-submit" type="submit" value="<?php echo osc_esc_html( __('Unset default package', 'packages') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<!-- Dialog for bulk actions of toolbar -->
<div id="dialog-bulk-actions" title="<?php _e('Bulk actions'); ?>" class="has-form-actions hide">
    <div class="form-horizontal">
        <div class="form-row"></div>
        <div class="form-actions">
            <div class="wrapper">
                <a id="bulk-actions-cancel" class="btn" href="javascript:void(0);"><?php _e('Cancel'); ?></a>
                <a id="bulk-actions-submit" href="javascript:void(0);" class="btn btn-red" ><?php echo osc_esc_html( __('Delete') ); ?></a>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>

<form id="upload-packages" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Upload packages', 'packages')); ?>" method="post" enctype="multipart/form-data">
<?php if (!is_writable(PACKAGES_PATH)) : ?>
    <div id="flash_message">
        <p>
            <?php
                $msg  = sprintf(__('The folder %s is not writable on your server', 'packages'), PACKAGES_PATH ) .", ";
                $msg .= __('Osclass can\'t upload files', 'packages') . '. ';
                $msg .= __('Please make the mentioned folder writable', 'packages') . '.';
                echo $msg;
            ?>
        </p>
        <p>
            <?php _e('To make a directory writable under UNIX execute this command from the shell', 'packages'); ?>:
        </p>
        <p style="background-color: white; border: 1px solid black; padding: 8px;">
            chmod a+w <?php echo PACKAGES_PATH; ?>
        </p>
    </div>
<?php else: ?>
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin" />
    <input type="hidden" name="plugin_action" value="upload_packages" />

    <div class="form-horizontal">
        <div class="form-row">
            <div class="form-label"><?php _e('XML file (packages.xml)', 'packages'); ?></div>
            <div class="form-controls">
                <div class="form-label-checkbox"><input type="file" name="file" /></div>
            </div>
        </div>
        <div class="form-actions">
            <div class="wrapper">
            <a class="btn" href="javascript:void(0);" onclick="$('#upload-packages').dialog('close');"><?php _e('Cancel'); ?></a>
            <input type="submit" value="<?php echo osc_esc_html( __('Upload') ); ?>" class="btn btn-submit" />
            </div>
        </div>
    </div>
<?php endif; ?>
</form>

<!-- Dialog when it want delete packages.xml -->
<form id="delete-packages" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Delete packages.xml', 'packages')); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin" />
    <input type="hidden" name="plugin_action" value="delete_packages" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to delete file?', 'packages'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#delete-packages').dialog('close');"><?php _e('Cancel'); ?></a>
                <input type="submit" value="<?php echo osc_esc_html( __('Delete') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<!-- Dialog when it want import data from packages.xml -->
<form id="import-packages" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Import packages', 'packages')); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin" />
    <input type="hidden" name="plugin_action" value="import_packages" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('This action will add more packages, it not remove or overwrite current ones. Are you sure you want to import packages?', 'packages'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#import-packages').dialog('close');"><?php _e('Cancel'); ?></a>
                <input type="submit" value="<?php echo osc_esc_html( __('Import packages', 'packages') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<!-- Dialog when it want export data to packages.xml -->
<form id="export-packages" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Export packages', 'packages')); ?>">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin" />
    <input type="hidden" name="plugin_action" value="export_packages" />

    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('This action will generate an exportable XML file with the current data. Are you sure you want to export packages?', 'packages'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#export-packages').dialog('close');"><?php _e('Cancel'); ?></a>
                <input type="submit" value="<?php echo osc_esc_html( __('Export packages', 'packages') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>

<!-- Form of 'Show filters' -->
<form method="get" action="<?php echo osc_admin_base_url(true); ?>" id="display-filters" class="has-form-actions hide nocsrf">
    <input type="hidden" name="page" value="plugins" />
    <input type="hidden" name="action" value="renderplugin" />
    <input type="hidden" name="route" value="packages-admin" />

    <input type="hidden" name="iDisplayLength" value="<?php echo Params::getParam('iDisplayLength'); ?>" />

    <div class="form-horizontal">
        <div class="grid-system">
            <!-- Grid left -->
            <div class="grid-row grid-50">
                <div class="row-wrapper">
                    <!-- Name -->
                    <div class="form-row">
                        <div class="form-label"><?php _e('Name'); ?></div>
                        <div class="form-controls">
                            <input type="text" name="s_name" class="xlarge" value="<?php echo Params::getParam('s_name'); ?>">
                        </div>
                    </div>

                    <!-- User type -->
                    <div class="form-row">
                        <div class="form-label"><?php _e('User type'); ?></div>
                        <div class="form-controls">
                            <select name="b_company">
                                <option value="" <?php echo ((Params::getParam('b_company') == "") ? 'selected="selected"' : '' )?>><?php _e("Choose an option", 'packages'); ?></option>
                                <option value="0" <?php echo ((Params::getParam('b_company') == "0") ? 'selected="selected"' : '' )?>><?php _e("User", 'packages'); ?></option>
                                <option value="1" <?php echo ((Params::getParam('b_company') == "1") ? 'selected="selected"' : '' )?>><?php _e("Company", 'packages'); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Pay frequency -->
                    <div class="form-row">
                        <div class="form-label"><?php _e('Pay frequency', 'packages'); ?></div>
                        <div class="form-controls">
                            <select name="s_pay_frequency">
                                <option value="" <?php echo ( (Params::getParam('s_pay_frequency') == "") ? 'selected="selected"' : '' )?>><?php _e('Choose an option'); ?></option>
                                <option value="month" <?php echo ( (Params::getParam('s_pay_frequency') == 'month') ? 'selected="selected"' : '' )?>><?php _e('Monthly', 'packages'); ?></option>
                                <option value="quarterly" <?php echo ( (Params::getParam('s_pay_frequency') == 'quarterly') ? 'selected="selected"' : '' )?>><?php _e('Quarterly', 'packages'); ?></option>
                                <option value="year" <?php echo ( (Params::getParam('s_pay_frequency') == 'year') ? 'selected="selected"' : '' )?>><?php _e('Yearly', 'packages'); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Free items -->
                    <div class="form-row">
                        <div class="form-label"><?php _e('Free listings', 'packages'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="package_items" value="<?php echo Params::getParam('package_items'); ?>">
                            <select name="packageItemsControl">
                                <option value="equal" <?php echo ( (Params::getParam('packageItemsControl') == 'equal') ? 'selected="selected"' : '' )?>>=</option>
                                <option value="greater" <?php echo ( (Params::getParam('packageItemsControl') == 'greater') ? 'selected="selected"' : '' )?>>></option>
                                <option value="greater_equal" <?php echo ( (Params::getParam('packageItemsControl') == 'greater_equal') ? 'selected="selected"' : '' )?>>>=</option>
                                <option value="less" <?php echo ( (Params::getParam('packageItemsControl') == 'less') ? 'selected="selected"' : '' )?>><</option>
                                <option value="less_equal" <?php echo ( (Params::getParam('packageItemsControl') == 'less_equal') ? 'selected="selected"' : '' )?>><=</option>
                                <option value="not_equal" <?php echo ( (Params::getParam('packageItemsControl') == 'not_equal') ? 'selected="selected"' : '' )?>>!=</option>
                            </select>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="form-row">
                        <div class="form-label"><?php _e('Price', 'packages'); ?></div>
                        <div class="form-controls">
                            <input type="text" class="xlarge" name="price" value="<?php echo Params::getParam('price'); ?>">
                            <select name="priceControl">
                                <option value="equal" <?php echo ( (Params::getParam('priceControl') == 'equal') ? 'selected="selected"' : '' )?>>=</option>
                                <option value="greater" <?php echo ( (Params::getParam('priceControl') == 'greater') ? 'selected="selected"' : '' )?>>></option>
                                <option value="greater_equal" <?php echo ( (Params::getParam('priceControl') == 'greater_equal') ? 'selected="selected"' : '' )?>>>=</option>
                                <option value="less" <?php echo ( (Params::getParam('priceControl') == 'less') ? 'selected="selected"' : '' )?>><</option>
                                <option value="less_equal" <?php echo ( (Params::getParam('priceControl') == 'less_equal') ? 'selected="selected"' : '' )?>><=</option>
                                <option value="not_equal" <?php echo ( (Params::getParam('priceControl') == 'not_equal') ? 'selected="selected"' : '' )?>>!=</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid right -->
            <div class="grid-row grid-50">
                <div class="row-wrapper">
                    <!-- Date -->
                    <div class="form-row">
                        <div class="form-label"><?php _e('Date'); ?></div>
                        <div class="form-controls">
                            <input id="date" type="text" class="xlarge" name="date" value="<?php echo Params::getParam('date'); ?>" placeholder="<?php echo todaydate(null, null, '00:00:00'); ?>">
                            <select name="dateControl">
                                <option value="equal" <?php echo ( (Params::getParam('dateControl') == 'equal') ? 'selected="selected"' : '' )?>>=</option>
                                <option value="greater" <?php echo ( (Params::getParam('dateControl') == 'greater') ? 'selected="selected"' : '' )?>>></option>
                                <option value="greater_equal" <?php echo ( (Params::getParam('dateControl') == 'greater_equal') ? 'selected="selected"' : '' )?>>>=</option>
                                <option value="less" <?php echo ( (Params::getParam('dateControl') == 'less') ? 'selected="selected"' : '' )?>><</option>
                                <option value="less_equal" <?php echo ( (Params::getParam('dateControl') == 'less_equal') ? 'selected="selected"' : '' )?>><=</option>
                                <option value="not_equal" <?php echo ( (Params::getParam('dateControl') == 'not_equal') ? 'selected="selected"' : '' )?>>!=</option>
                            </select>
                        </div>
                    </div>

                    <!-- Update -->
                    <div class="form-row">
                        <div class="form-label">
                            <?php _e('Update', 'packages'); ?>
                        </div>
                        <div class="form-controls">
                            <input id="update" type="text" class="xlarge" name="update" value="<?php echo Params::getParam('update'); ?>" placeholder="<?php echo todaydate(null, null, '00:00:00'); ?>">
                            <select name="updateControl">
                                <option value="equal" <?php echo ( (Params::getParam('updateControl') == '=') ? 'selected="selected"' : '' )?>>=</option>
                                <option value="greater" <?php echo ( (Params::getParam('updateControl') == '>') ? 'selected="selected"' : '' )?>>></option>
                                <option value="greater_equal" <?php echo ( (Params::getParam('updateControl') == '>=') ? 'selected="selected"' : '' )?>>>=</option>
                                <option value="less" <?php echo ( (Params::getParam('updateControl') == '<') ? 'selected="selected"' : '' )?>><</option>
                                <option value="less_equal" <?php echo ( (Params::getParam('updateControl') == '<=') ? 'selected="selected"' : '' )?>><=</option>
                                <option value="not_equal" <?php echo ( (Params::getParam('updateControl') == '!=') ? 'selected="selected"' : '' )?>>!=</option>
                            </select>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-row">
                        <div class="form-label">
                            <?php _e('Status'); ?>
                        </div>
                        <div class="form-controls">
                            <select name="b_active">
                                <option value="" <?php echo ( (Params::getParam('b_active') == '') ? 'selected="selected"' : '' )?>><?php _e('Choose an option'); ?></option>
                                <option value="1" <?php echo ( (Params::getParam('b_active') == '1') ? 'selected="selected"' : '' )?>><?php _e('ACTIVE'); ?></option>
                                <option value="0" <?php echo ( (Params::getParam('b_active') == '0') ? 'selected="selected"' : '' )?>><?php _e('DEACTIVE'); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- Order by -->
                    <div class="form-row">
                        <div class="form-label">
                            <?php _e('Order by', 'packages'); ?>
                        </div>
                        <div class="form-controls">
                            <select name="sort">
                                <option value="date" <?php echo ( (Params::getParam('sort') == 'date') ? 'selected="selected"' : '' )?>><?php _e('DATE', 'packages'); ?></option>
                                <option value="update" <?php echo ( (Params::getParam('sort') == 'update') ? 'selected="selected"' : '' )?>><?php _e("UPDATE DATE", 'packages'); ?></option>
                                <option value="package_items" <?php echo ( (Params::getParam('sort') == 'package_items') ? 'selected="selected"' : '' )?>><?php _e('FREE LISTINGS', 'packages'); ?></option>
                                <option value="price" <?php echo ( (Params::getParam('sort') == 'price') ? 'selected="selected"' : '' )?>><?php _e('PRICE', 'packages'); ?></option>
                            </select>
                            <select name="direction">
                                <option value="desc" <?php echo ( (Params::getParam('direction') == 'desc') ? 'selected="selected"' : '' )?>><?php _e('DESC', 'packages'); ?></option>
                                <option value="asc" <?php echo ( (Params::getParam('direction') == 'asc') ? 'selected="selected"' : '' )?>><?php _e('ASC', 'packages'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    </div>

    <div class="form-actions">
        <div class="wrapper">
            <input id="show-filters" type="submit" value="<?php echo osc_esc_html( __('Apply filters') ); ?>" class="btn btn-submit" />
            <a class="btn" href="<?php echo osc_route_admin_url('packages-admin'); ?>"><?php _e('Reset filters'); ?></a>
        </div>
    </div>
</form>

<script>
$(document).ready(function(){
    $('#set-package-button').click(function() {
        reset_form('#set-package', '<?php echo osc_route_admin_url('packages-admin'); ?>');
        $('#set-default-package').hide();
        $('#submit-set-package').attr('value', '<?php echo osc_esc_js( __('Add package', 'packages') ); ?>');
        if ($('#set-package').is(':hidden')) {
            $('#set-package').show();
        }
    });

    $('#reset-set-package').click(function() {
        reset_form('#set-package');
    });

    $('#cancel-set-package').click(function() {
        if (!$('#set-package').is(':hidden')) {
            $('#set-package').hide();
            reset_form('#set-package', '<?php echo osc_route_admin_url('packages-admin'); ?>');
            $('#set-default-package').hide();
        }
    });

    // Dialog delete
    $("#package-delete, #delete-packages, #package-activate, #package-deactivate, #import-packages, #export-packages").dialog({
        autoOpen: false,
        modal: true
    });

    $("#dialog-set-default-package").dialog({
        autoOpen: false,
        modal: true
    });

    $("#dialog-unset-default-package").dialog({
        autoOpen: false,
        modal: true
    });

    // Check_all Bulk actions
    $("#check_all").change(function() {
        var isChecked = $(this).prop("checked");
        $('.col-bulkactions input').each( function() {
            if(isChecked == 1) {
                this.checked = true;
            } else {
                this.checked = false;
            }
        });
    });

    // Dialog Bulk actions
    $("#dialog-bulk-actions").dialog({
        autoOpen: false,
        modal: true
    });
    $("#bulk-actions-submit").click(function() {
        $("#datatablesForm").submit();
    });
    $("#bulk-actions-cancel").click(function() {
        $("#datatablesForm").attr('data-dialog-open', 'false');
        $('#dialog-bulk-actions').dialog('close');
    });

    // Dialog bulk actions function
    $("#datatablesForm").submit(function() {
        if( $("#bulk_actions option:selected").val() == "" ) {
            return false;
        }

        if( $("#datatablesForm").attr('data-dialog-open') == "true" ) {
            return true;
        }

        $("#dialog-bulk-actions .form-row").html($("#bulk_actions option:selected").attr('data-dialog-content'));
        $("#bulk-actions-submit").html($("#bulk_actions option:selected").text());
        $("#datatablesForm").attr('data-dialog-open', 'true');
        $("#dialog-bulk-actions").dialog('open');
        return false;
    });

    // Form filters
    $('#display-filters').dialog({
        autoOpen: false,
        modal: true,
        width: 700,
        title: '<?php echo osc_esc_js( __('Filters') ); ?>'
    });
    $('#btn-display-filters').click(function(){
        $('#display-filters').dialog('open');
        return false;
    });

    // Show the datepicker jquery in the text input date on Filters
    $('#date').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    // Show the datepicker jquery in the text input update on Filters
    $('#update').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    // Dialog submit mod
    $("#upload-packages").dialog({
        autoOpen: false,
        modal: true,
        width: "550px"
    });
});

// Reset form attributes and fields
function reset_form(form_id, action = false) {
    if (action) $(form_id).attr('action', action);
    $(form_id).closest('form').find("input[type=text], textarea").val("");
}

function action_dialog(elementId, item_id) {
    $("#"+elementId+" input[name='id[]']").attr('value', item_id);
    $("#"+elementId).dialog('open');
}

// Dialog set default package function
function set_default_dialog(item_id) {
    $("#dialog-set-default-package input[name='id']").attr('value', item_id);
    $("#dialog-set-default-package").dialog('open');
    return false;
}

// Dialog unset default package function
function unset_default_dialog(item_id) {
    $("#dialog-unset-default-package input[name='id']").attr('value', item_id);
    $("#dialog-unset-default-package").dialog('open');
    return false;
}

function simple_open_dialog(elementId) {
    $("#"+elementId).dialog('open');
    return false;
}
</script>