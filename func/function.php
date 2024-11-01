<?php
/*author: QiQiBoY
 *date: 2011/07/13
 *contact: imqiqiboy#gmail.com(#->@)
 *blog: http://www.qiqiboy.com

 ################################################
 contact me: 1. imqiqiboy#gmail.com
			2. http://www.qiqiboy.com/contact
			3. http://www.qiqiboy.com/guestbook
 ################################################
 
 */
add_action('init', 'do_rc_reply');
function do_rc_reply(){
		if(!isset( $_GET['action'] ) && !isset($_POST['action']))return;
		global $wpdb;
		if($_GET['action'] == 'rc_reply_comment'){
			if(!isset($_SERVER['HTTP_AJAX_REQUEST'])||$_SERVER['HTTP_AJAX_REQUEST']!='wp-rc-reply-ajax'){
				echo 'unknow request!';
				die();
			}
			$start 	 =  (int)$_GET["start"];
			$number  =  isset($_GET["number"])  ?  (int)$_GET["number"] : 8 ;
			header('Content-Type: text/javascript;charset=UTF-8');
			echo 'wp-rec-reply-'.json_encode(wp_rc_reply (1, $start, $number)).'-wp-rec-reply';
			die();
		}elseif($_GET['action'] == 'rc_reply_options'){
			if(!isset($_SERVER['HTTP_AJAX_REQUEST'])||$_SERVER['HTTP_AJAX_REQUEST']!='wp-rc-reply-ajax'){
				echo 'unknow request!';
				die();
			}
			global $user_ID;
			$jsonarr=array( "uname"			=>			(is_user_logged_in()) ? get_the_author_meta('user_nicename', $user_ID) : stripslashes($_COOKIE['comment_author_'.COOKIEHASH]),
							"umail"			=>			(is_user_logged_in()) ? get_the_author_meta('user_email', $user_ID) : stripslashes($_COOKIE['comment_author_email_'.COOKIEHASH]),
							"uurl"			=>			(is_user_logged_in()) ? get_the_author_meta('user_url', $user_ID) : stripslashes($_COOKIE['comment_author_url_'.COOKIEHASH]),
							"isuser"		=>			(is_user_logged_in()) ? 1 : 0,
							"isrefresh"		=>			get_option('rc_reply_show_refresh')?1:0,
							"issingle"		=>			get_option('rc_reply_show_single')?1:0,
							"ispost"		=>			get_option('rc_reply_show_post')?1:0,
							"shortcut"		=>			get_option('rc_reply_submit_shortcuts')?1:0,
							"newest"		=>			"".__('newest','WP-RC-Reply-AJAX'),
							"newer"			=>			"".__('newer','WP-RC-Reply-AJAX'),
							"older"			=>			"".__('older','WP-RC-Reply-AJAX'),
							"refresh"		=>			"".__('refresh','WP-RC-Reply-AJAX'),
							"back"			=>			"".__('back','WP-RC-Reply-AJAX'),
							"reply"			=>			get_option("rc_reply_btn_name")!=''?get_option("rc_reply_btn_name"):"".__('reply','WP-RC-Reply-AJAX'),
							"navbtn"		=>			get_option("rc_reply_show_nav")?1:0,
							"number"		=>			get_option('rc_reply_comment_number')!=''?(int)get_option('rc_reply_comment_number'):8,
							"onlyadmin"		=>			get_option('rc_reply_only_admin')?1:0,
							"at"			=>			get_option('rc_reply_auto_at')!=''?(int)get_option('rc_reply_auto_at'):0,
							"replybtn"		=>			get_option("rc_reply_btn_list")?1:0,
							"avaright"		=>			get_option("rc_reply_avatar_right")?1:0,
							"strs"			=>			array(
							"str1"			=>			"".__('You have already reply to this comment.','WP-RC-Reply-AJAX'),
							"str2"			=>			"".__('is submiting, please wait...','WP-RC-Reply-AJAX'),
							"str3"			=>			"".__('ERROR: Please input your name!','WP-RC-Reply-AJAX'),
							"str4"			=>			"".__('ERROR: Please input a right email!','WP-RC-Reply-AJAX'),
							"str5"			=>			"".__('ERROR: Please input a comment!','WP-RC-Reply-AJAX'),
							"str6"			=>			"".__('submit succussed! Thank you.','WP-RC-Reply-AJAX'),
							"str7"			=>			"".__('Welcome back, ','WP-RC-Reply-AJAX'),
							"str8"			=>			"".__('change ','WP-RC-Reply-AJAX'),
							"str9"			=>			"".__('cancle submit','WP-RC-Reply-AJAX'),
							"str0"			=>			"".__('login in as ','WP-RC-Reply-AJAX')
							));
			header('Content-Type: text/javascript;charset=UTF-8');
			echo 'wp-rec-reply-'.json_encode($jsonarr).'-wp-rec-reply';
			die();
		}
		if($_POST['action'] == 'rc_reply'){
			if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
				header('Allow: POST');
				header('HTTP/1.1 405 Method Not Allowed');
				header('Content-Type: text/plain');
				exit;
			}
			require_once(dirname(__FILE__)."/../../../../wp-load.php");
			nocache_headers();
			$comment_post_ID = (int) $_POST['comment_post_ID'];
			$status = $wpdb->get_row( $wpdb->prepare("SELECT post_status, comment_status FROM $wpdb->posts WHERE ID = %d", $comment_post_ID) );
			function err($ErrMsg) {
				header('HTTP/1.0 500 Internal Server Error');
				echo $ErrMsg;
				exit;
			}
			if ( empty($status->comment_status) ) {
				do_action('comment_id_not_found', $comment_post_ID);
				err(__('The post you are trying to comment on does not currently exist in the database.'));
			} elseif ( !comments_open($comment_post_ID) ) {
				do_action('comment_closed', $comment_post_ID);
				err(__('Sorry, comments are closed for this item.'));	
			} elseif ( in_array($status->post_status, array('draft', 'pending') ) ) {
				do_action('comment_on_draft', $comment_post_ID);
				err(__('The post you are trying to comment on has not been published.'));	
			} else {
				do_action('pre_comment_on_post', $comment_post_ID);
			}
			$comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
			$comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
			$comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : null;
			$comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;	
			$user = wp_get_current_user();
			if ( $user->ID ) {
				if ( empty( $user->display_name ) )
					$user->display_name=$user->user_login;
				$comment_author       = $wpdb->escape($user->display_name);
				$comment_author_email = $wpdb->escape($user->user_email);
				$comment_author_url   = $wpdb->escape($user->user_url);
				if ( current_user_can('unfiltered_html') ) {
					if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
						kses_remove_filters();
						kses_init_filters();
					}
				}
			} else {
				if ( get_option('comment_registration') || 'private' == $status->post_status )
					err(__('Sorry, you must be logged in to post a comment.'));	
			}
			$comment_type = '';
			if ( get_option('require_name_email') && !$user->ID ) {
				if ( 6 > strlen($comment_author_email) || '' == $comment_author )
					err( __('Error: please fill the required fields (name, email).'));	
				elseif ( !is_email($comment_author_email))
					err(__('Error: please enter a valid email address.'));	
			}
			if ( '' == $comment_content )
				err(__('Error: please type a comment.'));	
			$dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
			if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
			$dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
			if ( $wpdb->get_var($dupe) ) {
				err(__('Duplicate comment detected; it looks as though you&#8217;ve already said that!'));
			}
			if ( $lasttime = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) { 
			$time_lastcomment = mysql2date('U', $lasttime, false);
			$time_newcomment  = mysql2date('U', current_time('mysql', 1), false);
			$flood_die = apply_filters('comment_flood_filter', false, $time_lastcomment, $time_newcomment);
			if ( $flood_die ) {
				err(__('You are posting comments too quickly.  Slow down.'));
				}
			}
			$comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
			$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');
			$comment_id = wp_new_comment( $commentdata );
			$comment = get_comment($comment_id);
			if ( !$user->ID ) {
				$comment_cookie_lifetime = apply_filters('comment_cookie_lifetime', 30000000);
				setcookie('comment_author_' . COOKIEHASH, $comment->comment_author, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
				setcookie('comment_author_email_' . COOKIEHASH, $comment->comment_author_email, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
				setcookie('comment_author_url_' . COOKIEHASH, esc_url($comment->comment_author_url), time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
			}?>
			<?php if ($comment->comment_approved == '0') : ?>
				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php _e('Your comment is awaiting moderation.','WP-RC-Reply-AJAX') ?></a>
			<?php else: ?>
				<strong><?php echo get_comment($comment->comment_parent)->comment_author ?></strong><?php _e(' has received your reply.<br>And now you can ','WP-RC-Reply-AJAX') ?> <a href="<?php echo htmlspecialchars( get_comment_link($comment->comment_ID) ) ?>"><strong><?php _e('View New Reply','WP-RC-Reply-AJAX') ?></strong></a><?php _e(' or Go Back.','WP-RC-Reply-AJAX') ?>
			<?php endif; ?>
<?php die();
		}
}
function wp_rc_reply_get ($limitclause=""){
		global $wpdb;$my_email ="''";
		if(!get_option("rc_reply_show_admin"))
			$my_email = "'" . get_bloginfo ('admin_email') . "'";
		$q = "SELECT ID, post_title, comment_ID, comment_post_ID, comment_parent, comment_author, comment_author_email, comment_content FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' AND comment_author_email != $my_email ORDER BY comment_date_gmt DESC $limitclause";
		return $wpdb->get_results($q);
}
function wp_rc_reply ($isJson, $start, $number){
		global $wpdb, $user_ID;

		$sub_len=get_option('rc_reply_comment_length')!=''?(int)get_option('rc_reply_comment_length'):30;
		$size=get_option('rc_reply_avatar_size')!=''?(int)get_option('rc_reply_avatar_size'):32;
		$limitclause="LIMIT " . $start . "," . $number;
		$comments = wp_rc_reply_get ($limitclause);

		foreach ($comments as $comment) {
			$comment_depth = 1;
			$tmp_c = $comment;$comment_parent;
			while($tmp_c->comment_parent != 0){
				$comment_depth++;
				$tmp_c = get_comment($tmp_c->comment_parent);
			}
			if($comment_depth < get_option('thread_comments_depth'))
				$comment_parent=$comment->comment_ID;
			else $comment_parent=get_comment($comment->comment_parent)->comment_ID;
			
			$rc_content=wp_rc_reply_substr(strip_tags($comment->comment_content),$sub_len);//the excerpt of comment
			
			$flag=wp_rc_reply_str_len($rc_content);

			$showavatar='';
			
			if(get_option("rc_reply_show_avatar"))
				$showavatar=get_avatar($comment->comment_author_email, $size);
				
			if($isJson){
				if(!$output)$output=array();
				$output[]=array("comId"=>$comment->comment_ID,
								"comDepth"=>$comment_depth,
								"author"=>$comment->comment_author,
								"comParent"=>$comment_parent,
								"comPostId"=>$comment->comment_post_ID,
								"post"=>$comment->post_title,
								"avatar"=>$showavatar,
								"comlink"=>htmlspecialchars(get_comment_link($comment->comment_ID)),
								"excerpt"=>convert_smilies($rc_content),
								"content"=>convert_smilies($comment->comment_content)
						);
			}else{
				if(!$output)$output='';
				$output.='<li id="rc_com_'.$comment->comment_ID.'" class="rc_comment">';
				if($showavatar!='')$output.='<div class="rc_avatar"'.(get_option("rc_reply_avatar_right")?' style="float:right"':'').'>'.$showavatar.'</div>';
				$output.='<div class="rc_author"><a href="'.get_comment_link($comment->comment_ID).'">'.$comment->comment_author.'</a></div>';
				if(get_option('rc_reply_show_post'))$output.='<div class="rc_post_title"><a title="'.$comment->post_title.'" href="'.get_comment_link($comment->comment_ID).'">'.$comment->post_title.'</a></div>';
				$output.='<div class="rc_excerpt">'.convert_smilies($rc_content).'</div></li>';
			}
		}
		return $output;
}
function wp_rc_reply_comments(){
	$number=(get_option('rc_reply_comment_number')&&get_option('rc_reply_comment_number')!='')?(int)get_option('rc_reply_comment_number'):8;
	return wp_rc_reply(0, 0, $number);
}
function wp_rc_reply_substr($str,$length){
		$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
		preg_match_all($pa, $str, $t_str);
		if(count($t_str[0]) > $length) {
			$ellipsis = '...';
			$str = join('', array_slice($t_str[0], 0, $length)) . $ellipsis;
		}
		return $str;
}
add_action('admin_menu', 'wp_rc_reply_add_options');

function wp_rc_reply_add_options() {
	add_options_page('wp_rc_reply options', __("wp_rc_reply","WP-RC-Reply-AJAX"), 8, __FILE__, 'wp_rc_reply_the_options');
}

function wp_rc_reply_str_len($str){
    $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));
    if ($length){
        return strlen($str) - $length + intval($length / 3) ;
    }
    else{
        return strlen($str);
    }
}
function wp_rc_reply_echo ($args=''){
		echo "<ul id='wp-rc-reply'>".wp_rc_reply_comments()."</ul><div class='fixed'></div>";
}
function addScript(){//add css, js
	$css = '<link rel="stylesheet" href="' .get_bloginfo("wpurl") . '/wp-content/plugins/wp-rc-reply-ajax/css/wp-rc-reply.css" type="text/css" media="screen" />';
	$script = '<script type="text/javascript" src="' . get_bloginfo('wpurl') . '/wp-content/plugins/wp-rc-reply-ajax/js/wp-rc-reply-min.js"></script>';
	echo $css . $script;
}
if(get_option("rc_reply_files")=="2"){}
elseif(get_option("rc_reply_files")=="0"||get_option("rc_reply_files")=="")add_action ('wp_head', 'addScript');
else add_action ('wp_footer', 'addScript');
//widget
class wp_rc_reply_widget extends WP_Widget{
	function wp_rc_reply_widget(){
		$widget_des = array('classname'=>'wp_rc_reply','description'=>__('show recent comments and reply anyone @ sidebar widget.', 'WP-RC-Reply-AJAX'));
		$this->WP_Widget(false,__('WP-RC-Reply', 'WP-RC-Reply-AJAX'),$widget_des);
	}
	function form($instance){
		$instance = wp_parse_args((array)$instance,array(
		'title'=>__('recent comments', 'WP-RC-Reply-AJAX')));
		echo '<p><label for="'.$this->get_field_name('title').'">'.__('widget title: ', 'WP-RC-Reply-AJAX').'<input style="width:200px;" name="'.$this->get_field_name('title').'" type="text" value="'.htmlspecialchars($instance['title']).'" /></label></p>';
	}
	function update($new_instance,$old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		return $instance;
	}
	function widget($args,$instance){
		extract($args);
		$title = apply_filters('widget_title',empty($instance['title']) ? __('recent comments', 'WP-RC-Reply-AJAX') : $instance['title']);
		echo $before_widget;
		echo $before_title . $title . $after_title;
		wp_rc_reply_echo();
		echo $after_widget;
	}
}
function wp_rc_reply_widget_init(){
	register_widget('wp_rc_reply_widget');
}
add_action('widgets_init','wp_rc_reply_widget_init');
?>