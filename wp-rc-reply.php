<?php
/*
Plugin Name: WP RC Reply AJAX
Plugin URI: http://www.qiqiboy.com/plugins/
Tags: wordpress, ajax, recentcomments, reply, widget, sidebar
Description: 1. Display recent comments in your blog sidebar. 2. with it, you can reply everyone from widget sidebar by Ajax type.
Version: 2.0.14
Author: QiQiBoY
Author URI: http://www.qiqiboy.com
*/
load_plugin_textdomain('WP-RC-Reply-AJAX', false, basename(dirname(__FILE__)) . '/lang');
require_once(dirname(__FILE__).'/func/function.php');
function wp_rc_reply_the_options() {
?>
<div class="wrap">

	<h2><?php _e('WP-RC-Reply-AJAX Options','WP-RC-Reply-AJAX');?></h2>
	
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		
		<h3><?php _e('Some configuration:','WP-RC-Reply-AJAX');?></h3>
		<label>
			<input name="rc_reply_only_admin" type="checkbox" value="checkbox" <?php if(get_option("rc_reply_only_admin")) echo "checked='checked'"; ?> />
			<?php _e('only admin can reply?', 'WP-RC-Reply-AJAX'); ?>
		</label>
		
		<label>
			<input name="rc_reply_show_admin" type="checkbox" value="checkbox" <?php if(get_option("rc_reply_show_admin")) echo "checked='checked'"; ?> />
			<?php _e('Show admin comment?', 'WP-RC-Reply-AJAX'); ?>
		</label>
		
		<label>
			<input name="rc_reply_show_avatar" type="checkbox" value="checkbox" <?php if(get_option("rc_reply_show_avatar")) echo "checked='checked'"; ?> />
			<?php _e('Show gravatar?', 'WP-RC-Reply-AJAX'); ?>
		</label>
		
		<label>
			<input name="rc_reply_show_nav" type="checkbox" value="checkbox" <?php if(get_option("rc_reply_show_nav")) echo "checked='checked'"; ?> />
			<?php _e('Show the navigator?', 'WP-RC-Reply-AJAX'); ?>
		</label>
		
		<label>
			<input name="rc_reply_show_single" type="checkbox" value="checkbox" <?php if(get_option("rc_reply_show_single")) echo "checked='checked'"; ?> />
			<?php _e('Don\'t show the button for going into full mode of a comment?', 'WP-RC-Reply-AJAX'); ?>
		</label>
		<br>
		<label>
			<input name="rc_reply_show_post" type="checkbox" value="checkbox" <?php if(get_option("rc_reply_show_post")) echo "checked='checked'"; ?> />
			<?php _e('show post link of the comment?', 'WP-RC-Reply-AJAX'); ?>
		</label>
		
		<label>
			<input name="rc_reply_show_refresh" type="checkbox" value="checkbox" <?php if(get_option("rc_reply_show_refresh")) echo "checked='checked'"; ?> />
			<?php _e('show refresh button at first page?', 'WP-RC-Reply-AJAX'); ?>
		</label>
		
		<label>
			<input name="rc_reply_submit_shortcuts" type="checkbox" value="checkbox" <?php if(get_option("rc_reply_submit_shortcuts")) echo "checked='checked'"; ?> />
			<?php _e('enable "ctrl+Enter" submit shortcuts?', 'WP-RC-Reply-AJAX'); ?>
		</label>
		<label>
			<input name="rc_reply_avatar_right" type="checkbox" value="checkbox" <?php if(get_option("rc_reply_avatar_right")) echo "checked='checked'"; ?> />
			<?php _e('put avatar right', 'WP-RC-Reply-AJAX'); ?>
		</label>
		<br>
		<label>
			<input name="rc_reply_btn_list" type="checkbox" value="checkbox" <?php if(get_option("rc_reply_btn_list")) echo "checked='checked'"; ?> />
			<?php _e('output reply button always(Do not need to enter the single comment mode)?', 'WP-RC-Reply-AJAX'); ?>
		</label>
		<table class="form-table">
		<tr valign="top">
		<th scope="row"><?php _e('Show comments number(int, default 8)','WP-RC-Reply-AJAX');?></th>
		<td><input type="text" name="rc_reply_comment_number" value="<?php echo get_option('rc_reply_comment_number'); ?>" /></td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><?php _e('Avatar size(int), default 32:','WP-RC-Reply-AJAX');?></th>
		<td><input type="text" name="rc_reply_avatar_size" value="<?php echo get_option('rc_reply_avatar_size'); ?>" /></td>
		</tr>

		<tr valign="top">
		<th scope="row"><?php _e('Cut comment length(int), default 30:','WP-RC-Reply-AJAX');?></th>
		<td><input type="text" name="rc_reply_comment_length" value="<?php echo get_option('rc_reply_comment_length'); ?>" /></td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><?php _e('reply button name:','WP-RC-Reply-AJAX');?></th>
		<td><input type="text" name="rc_reply_btn_name" value="<?php echo get_option('rc_reply_btn_name'); ?>" /></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php _e('auto add @XXX to comment box', 'WP-RC-Reply-AJAX'); ?></th>
			<td><?php _e('Begin from the ', 'WP-RC-Reply-AJAX'); ?><select style="width:60px;text-align:center" name="rc_reply_auto_at">
				<option value="0" <?php if(get_option("rc_reply_auto_at")=="0") echo "selected='selected'"; ?>><?php _e('none', 'WP-RC-Reply-AJAX'); ?></option>
				<option value="1" <?php if(get_option("rc_reply_auto_at")=="1") echo "selected='selected'"; ?>><?php _e('1', 'WP-RC-Reply-AJAX'); ?></option>
				<option value="2" <?php if(get_option("rc_reply_auto_at")=="2") echo "selected='selected'"; ?>><?php _e('2', 'WP-RC-Reply-AJAX'); ?></option>
				<option value="3" <?php if(get_option("rc_reply_auto_at")=="3") echo "selected='selected'"; ?>><?php _e('3', 'WP-RC-Reply-AJAX'); ?></option>
				<option value="4" <?php if(get_option("rc_reply_auto_at")=="4") echo "selected='selected'"; ?>><?php _e('4', 'WP-RC-Reply-AJAX'); ?></option>
				<option value="5" <?php if(get_option("rc_reply_auto_at")=="5") echo "selected='selected'"; ?>><?php _e('5', 'WP-RC-Reply-AJAX'); ?></option>
			</select><?php _e(' depth?', 'WP-RC-Reply-AJAX'); ?><label><?php _e('("none" means always do not add "@XXX" to comment box.)', 'WP-RC-Reply-AJAX'); ?></label></td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><?php _e('javascript and css files add to', 'WP-RC-Reply-AJAX'); ?></th>
			<td>
			<select style="width:120px;text-align:center" name="rc_reply_files">
				<option value="0" <?php if(get_option("rc_reply_files")=="0") echo "selected='selected'"; ?>><?php _e('header', 'WP-RC-Reply-AJAX'); ?></option>
				<option value="1" <?php if(get_option("rc_reply_files")=="1") echo "selected='selected'"; ?>><?php _e('footer', 'WP-RC-Reply-AJAX'); ?></option>
				<option value="2" <?php if(get_option("rc_reply_files")=="2") echo "selected='selected'"; ?>><?php _e('custom', 'WP-RC-Reply-AJAX'); ?></option>
			</select>
			</td>
		</tr>

		</table>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="rc_reply_show_post,rc_reply_show_single,rc_reply_avatar_right,rc_reply_files,rc_reply_only_admin,rc_reply_btn_name,rc_reply_btn_list,rc_reply_submit_shortcuts,rc_reply_show_refresh,rc_reply_auto_at,rc_reply_comment_number,rc_reply_comment_length,rc_reply_avatar_size,rc_reply_show_avatar,rc_reply_show_admin,rc_reply_show_nav" />

		<p class="submit">
		<input type="submit" name="Submit" value="<?php _e('Save Changes','WP-RC-Reply-AJAX') ?>" />
		</p>

	</form>
	<h3><?php _e('plugin reduce','WP-RC-Reply-AJAX') ?></h3>
	<div id="wp_rc_reply_info">
	<img style="border:2px solid #999;padding:1px;" src="<?php echo WP_CONTENT_URL .'/plugins/wp-rc-reply-ajax/screenshot-1.png' ?>"/>
	<img style="border:2px solid #999;padding:1px;" src="<?php echo WP_CONTENT_URL .'/plugins/wp-rc-reply-ajax/screenshot-2.png' ?>"/>
	<br><br><p></p><p><?php _e('WP-RC-Reply-AJAX plug-in as a display for the latest comments sidebar plugin developed.','WP-RC-Reply-AJAX') ?></p>
	<p><?php _e('use &lt;?php wp_rc_reply_echo("number=&length=&size=&at="); ?> to show the comments list.','WP-RC-Reply-AJAX') ?></p>
	<br>
<h3><?php _e('Go to ','WP-RC-Reply-AJAX') ?><a href="http://www.qiqiboy.com/plugins/wp-rc-reply-ajax"><?php _e('My Blog Plugins page ','WP-RC-Reply-AJAX') ?></a><?php _e(' to get more info.','WP-RC-Reply-AJAX') ?>
</h3></div>
</div>

<?php
}
?>