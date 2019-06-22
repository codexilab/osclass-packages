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
 
$logs = __get('logs');
$vqmodLogsPath = packages_vqmod_logs_path();
?>
<a href="<?php echo osc_route_admin_url('packages-admin-mods'); ?>" class="btn btn-mini">« <?php _e("Back", 'packages'); ?></a>
<div class="clear"></div>
<?php if ($logs) : ?>
	<?php $i = 0; ?>
	<?php foreach ($logs as $log) : ?>
		<?php $i++; ?>
		<?php $logName = current(explode(".", $log)); ?>
		<div id="<?php echo $i; ?>" class="icon-box">
			<div class="icon" onclick="open_source_dialog(<?php echo $i; ?>, '<?php echo $log; ?>');">
				<img src="<?php echo '../oc-content/plugins/'.PACKAGES_FOLDER.'assets/img/log.png'; ?>"><br>
				<?php echo $log; ?><br><span id="<?php echo $i; ?>_info_bytes">(<?php echo packages_get_filesize($vqmodLogsPath, $log); ?>)</span>
			</div>
			<span class="symbols">
				<a title="empty" href="javascript:empty_file(<?php echo $i; ?>, '<?php echo $log; ?>')">&#9744;</a><br>
				<a title="delete" href="javascript:delete_file(<?php echo $i; ?>, '<?php echo $log; ?>')">&#9746;</a><br>
				<a title="download" href="<?php echo osc_route_admin_url('packages-admin-mods-log').'&plugin_action=download_vqmod_log&file='.$log.'&'.osc_csrf_token_url(); ?>">&#9047;</a>
			</span>
		</div>
	<?php endforeach; ?>
<?php else : ?>
	<center><?php _e("There are not log files.", 'packages'); ?></center>
<?php endif; ?>
<div class="clear"></div>

<!-- Dialog source file -->
<div id="dialog-source-file" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__("Source file", 'packages')); ?>">
    <div class="form-horizontal">
        <div class="form-row" id="show-source-file">
            <center>Loading...</center>
        </div>
        <div class="form-actions">
            <div class="wrapper">
            	<a class="btn" href="javascript:void(0);" onclick="$('#dialog-source-file').dialog('close'); $('#show-source-file').html('<center>Loading...</center>');"><?php _e("Close", 'packages'); ?></a>
            	<a class="btn" id="open-source-reload" href="javascript:void(0);" onclick=""><?php _e("Reload", 'packages'); ?></a>
            	<br><br>
            </div>
        </div>
    </div>
</div>

<!-- Dialog when it want empty a log file -->
<div id="dialog-file-empty" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__("Empty file", 'packages')); ?>">
    <div class="form-horizontal">
        <div class="form-row" id="empty-file-info"></div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#dialog-file-empty').dialog('close');"><?php _e("Cancel", 'packages'); ?></a>
                <a id="button-file-empty" href="javascript:void(0);" class="btn btn-red"><?php echo osc_esc_html( __("Empty", 'packages') ); ?></a>
                <br><br>
            </div>
        </div>
    </div>
</div>

<!-- Dialog when it want delete a log file -->
<div id="dialog-file-delete" method="get" action="<?php echo osc_route_admin_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__("Delete file", 'packages')); ?>">
    <div class="form-horizontal">
        <div class="form-row" id="delete-file-info"></div>
        <div class="form-actions">
            <div class="wrapper">
                <a class="btn" href="javascript:void(0);" onclick="$('#dialog-file-delete').dialog('close');"><?php _e("Cancel", 'packages'); ?></a>
                <a id="button-file-delete" href="javascript:void(0);" class="btn btn-red"><?php echo osc_esc_html( __("Delete", 'packages') ); ?></a>
                <br><br>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
	// Dialog source file
    $("#dialog-source-file").dialog({
        autoOpen: false,
        modal: true,
        width: "1000px",
        position: "top"
    });

    // Dialog source file
    $("#dialog-file-delete").dialog({
        autoOpen: false,
        modal: true,
        width: "350px"
    });

    // Dialog source file
    $("#dialog-file-empty").dialog({
        autoOpen: false,
        modal: true,
        width: "350px"
    });
});

function copy(element) {
	var el = document.getElementById(element);
	var range = document.createRange();
	range.selectNodeContents(el);
	var sel = window.getSelection();
	sel.removeAllRanges();
	sel.addRange(range);
	document.execCommand('copy');
	$("#copied").show().delay(1500).fadeOut();
	return false;
}

function opensource(grid, file) {
	// Loading
    $('#show-source-file').html('<center>Loading...</center>');

    // Menu buttons
    var empty 		= '<a href="javascript:empty_file('+grid+', \''+file+'\')"><?php echo __("Empty", 'packages'); ?></a>';
    var del 		= '<a href="javascript:delete_file('+grid+', \''+file+'\')"><?php echo __("Delete", 'packages'); ?></a>';
    var download 	= '<a href="<?php echo osc_route_admin_url('packages-admin-mods-log').'&plugin_action=download_vqmod_log&file='; ?>'+file+'&<?php echo osc_csrf_token_url(); ?>"><?php echo __("Download", 'packages'); ?></a>';
    var copy 		= '<a href="javascript:void(0);" onclick="copy(\'pre_'+grid+'\')"><?php echo __("Copy content", 'packages'); ?></a>';
    var reload 		= '<a href="javascript:void(0);" onclick="opensource('+grid+', \''+file+'\')"><?php echo __("Reload", 'packages'); ?></a>';
    var close 		= '<a href="javascript:void(0);" onclick="$(\'#dialog-source-file\').dialog(\'close\'); $(\'#show-source-file\').html(\'<center>Loading...</center>\');"><?php echo __("Close", 'packages'); ?></a>';
    
    // Ajax URL
    var url = '<?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=packages_admin_ajax&route=log_source_iframe&file='+file;
    $.ajax({
        method: "GET",
        url: url,
        dataType: "html"
    }).done(function(data) {
    	if (data == '') {
    		$("#show-source-file").html(file+" | "+del+" "+download+" "+reload+" "+close+"<center><?php echo __("Empty file", 'packages'); ?>.</center>");
    	} else {
    		$("#show-source-file").html(file+" | "+empty+" "+del+" "+download+" "+copy+" "+reload+" "+close+" <span id=\"copied\">· <?php echo __("¡Copied!", 'packages'); ?></span><textarea id=\"pre_"+grid+"\" readonly>"+data+"</textarea>");
    	}
        
    });
}

// Dialog assign function
function open_source_dialog(grid, file) {
    $("#dialog-source-file input[name='id[]']").attr('value', file);
    $("#dialog-source-file").dialog('open');
    opensource(grid, file);
    $("#open-source-reload").attr('onclick', "opensource("+grid+", '"+file+"');");
}

function empty_file(grid, file) {
	$("#dialog-file-empty").dialog('open');
	$("#empty-file-info").html("<center>"+file+"<br> <?php echo __("Are you sure you want to empty this file?", 'packages'); ?></center>");
	$("#button-file-empty").click(function() {
		//$("#dialog-file-empty").dialog('close');
		var url = '<?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=packages_admin_ajax&route=empty_vqmod_log&file='+file;
		$.ajax({
			type: "POST",
			url: url,
			dataType: 'json',
			success: function(data) {
				if (data.error == '') {
					$("#dialog-file-empty").dialog('close');
					if ($('#dialog-source-file').dialog('isOpen') === true) {
						opensource(grid, file);
					}
					$("#"+grid+"_info_bytes").html("(0 bytes)");
				} else {
					$("#empty-file-info").html("<center>"+data.msg+"</center>");
				}
			}
		});
	});
}

function delete_file(grid, file) {
	$("#dialog-file-delete").dialog('open');
	$("#delete-file-info").html("<center>"+file+"<br> <?php echo __("Are you sure you want to delete this file?", 'packages'); ?></center>");
	$("#button-file-delete").click(function() {
		//$('#dialog-file-delete').dialog('close');
        var url = '<?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=packages_admin_ajax&route=delete_vqmod_log&file='+file+'&<?php echo osc_csrf_token_url(); ?>';
		$.ajax({
			type: "POST",
			url: url,
			dataType: 'json',
			success: function(data) {
				if(data.error == 0) {
					$('#dialog-file-delete').dialog('close');
					if ($('#dialog-source-file').dialog('isOpen') === true) {
						$('#dialog-source-file').dialog('close');
					}
					$("#"+grid).hide();
				} else {
					$("#delete-file-info").html("<center>"+data.msg+"</center>");
				}
			}
		});
    });
}
</script>