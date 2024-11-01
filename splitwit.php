<?php

/*

Plugin Name: SplitWit
Plugin URI: https://www.splitwit.com/
Description: This plugin automatically adds the SplitWit code snippet to your WordPress site. SplitWit lets you create a variation of your web page using our visual editor. It splits traffic between the original version and your variation. You can track key metrics, and let SplitWit determine a winner. No code needed. Go to <a href="https://www.splitwit.com/">SplitWit</a> to register for free.
Author: SplitWit
Version: 1.0
License: GPLv2+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

*/

add_action( 'wp_head', 'splitwit_header_code', 1 );
add_action( 'admin_menu', 'splitwit_plugin_menu' );
add_action( 'admin_init', 'splitwit_settings' );
add_action( 'admin_notices','splitwit_warning');

function splitwit_header_code(){
	//inject SplitWit code snippet
	 
	$splitwit_project_id = get_option('splitwit_project_id');
	 
	if($splitwit_project_id){
		wp_enqueue_script( 'splitwit-snippet', 'https://www.splitwit.com/snippet/'.$splitwit_project_id.'.js' );
	}
}


function splitwit_plugin_menu() {
	add_options_page('SplitWit Experiments', 'SplitWit Experiments', 'publish_posts', 'splitwit_settings', 'splitwit_plugin_menu_page');
}

function splitwit_settings(){
	register_setting('splitwit_settings_group','splitwit_project_id','string');
}

function splitwit_warning(){
    if (!is_admin()){
        return;
    }

  $splitwit_project_id = get_option("splitwit_project_id");
  if (!$splitwit_project_id || $splitwit_project_id < 1){
    echo "<div class='notice notice-error'><p><strong>SplitWit is missing a project ID code.</strong> You need to enter <a href='options-general.php?page=splitwit_settings'>a SplitWit project ID code</a> for the plugin to work.</p></div>";
  }
}

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'splitwit_link');

function splitwit_link( $links ) {
     $links[] ='<a href="' . admin_url( 'options-general.php?page=splitwit_settings' ) .'">Settings</a>';
    return $links;
}

function splitwit_plugin_menu_page() { ?>

	<div>
		<h1>SplitWit Experiments</h1>
		<p>This plugin automatically adds the <a href="https://www.splitwit.com" target="_blank">SplitWit</a> code snippet to your WordPress site. <a href="https://www.splitwit.com" target="_blank">SplitWit</a> lets you create a variation of your web page using our visual editor. It splits traffic between the original version and your variation. You can track key metrics, and let SplitWit determine a winner. No code needed.
		</p>
		<p>You'll need to create an account at SplitWit.com - it's free. After signing up, you can create a new SplitWit project for your website. Find that project's ID code, and copy/paste it into this page.</p>
		
		<form method="post" action="options.php">
			<?php settings_fields( 'splitwit_settings_group' ); ?>
			<input style="width: 340px;display: block; margin-bottom: 10px;" type="text" name="splitwit_project_id" value="<?php echo get_option('splitwit_project_id'); ?>" />
			<input type="submit" class="button-primary" value="Save" />
		</form>
	</div>

<?php }


?>