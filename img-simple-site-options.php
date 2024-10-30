<?php
/**
 * @package IMG Simple Site Options
 * @version 1.12
 */
/*
Plugin Name: IMG Simple Site Options
Plugin URI: http://imgiseverything.co.uk/wordpress-plugins/img-simple-site-options/
Description: Functionality to allow the management of social media usernames and contact details
Author: Phil Thompson
Version: 1.12
Author URI: http://imgiseverything.co.uk/
*/


if ( version_compare(PHP_VERSION, '5.2', '<') ) {
	if ( is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
		deactivate_plugins( __FILE__ );
	    wp_die( 'IMG Custom Post Types requires PHP 5.2 or higher, as does WordPress 3.2 and higher. The plugin has now disabled itself' );
	} else {
		return;
	}
}


class IMGSimpleSiteOptions{

	/**
	 *	@var string
	 *	The name of the WordPress NONCE name - used to ensure the form is
	 *	posted by this site and not a spam/malicious attack
	 */
	protected $_nonce = 'imgsimplesiteoptions_noncename';
	
	
	/**
	 *	@var array
	 *	List of social sites
	 */
	public $social_sites = array(
		'facebook',
		'googleplus',
		'instagram',
		'linkedin',
		'pinterest',
		'twitter',
		'youtube',
		'vimeo'
	);
	
	/**
	 *	@var array
	 *	List of address values
	 */
	public $address_items = array(
		'company_address_line_1',
		'company_address_line_2',
		'company_address_line_3',
		'company_city',
		'company_county',
		'company_post_code',
		'company_country',
		'company_telephone',
		'company_fax_number',
		'company_email',
	);
	
	/**
	 *	@var object
	 *	Local version of WordPress' database abstraction object (based upon ezSQL)
	 */
	public $wpdb;

	public function __construct($wpdb){
	
		$this->wpdb = $wpdb;
		
		add_action('admin_menu', array($this, 'adminMenu'));
		
		
		$this->createShortcodes();

	}
	
	/**
	 *	adminMenu
	 *	Create additions to the admin menu.
	 */
	public function adminMenu(){
	
        add_menu_page('Simple Site Options', 'Simple Site Options', 'manage_options', 'imgsimplesiteoptions', array($this, 'generalOptions'));
        
		// Social media
		add_submenu_page('imgsimplesiteoptions', 'Social Media | Simple Site Options', 'Social Media', 'manage_options', 'imgsso_social_media', array($this, 'setSocialMedia'));
			
		// Company address
		add_submenu_page('imgsimplesiteoptions', 'Contact Details | Simple Site Options', 'Contact Details', 'manage_options', 'imgsso_company_address', array($this, 'setContactDetails'));
		
	}
	
	/**
	 *	generalOptions
	 */
	public function generalOptions() {

		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
				
		echo '<div class="wrap">
		<h2>Simple Site Options</h2>
		<p>Click any of the options in the <b>Simple Site Options</b> menu (in the bottom left of your screen).</p>
		<hr>
		<h3>Usage in your Posts/Pages (with shortcodes)</h3>
		<p>To use a social media usernames in your post/page then you need to write one of the following shortcodes:</p>
		<ul> '; 
		foreach($this->social_sites as $site){
			$social_var = $site . '_username';
			echo '<li>[img-simple-site-options ' . $social_var . ']</li>';
		}
		echo '
		</ul>
		<hr>
		<p>To use a address details in your post/page then you need to write one of the following shortcodes:</p>
		<ul> '; 
		foreach($this->address_items as $address){
			echo '<li>[img-simple-site-options ' . $address . ']</li>';
		}
		echo '
		</ul>
		<hr>
		<h3>Usage in your Theme</h3>
		<p>To use a social media usernames in your theme then you need to write:</p><ul> '; 
		foreach($this->social_sites as $site){
			$social_var = $site . '_username';
			echo '<li><b>&lt;?php echo get_option(\'' . $social_var  . '\'); ?&gt;</b> <br>(or maybe &lt;a href="http://' . $site . '.com/&lt;?php echo get_option(\'' . $social_var  . '\'); ?&gt;"&gt;Follow me on ' . ucwords($site)  . '&lt;/a&gt;)</li>';
		}
		echo '
		</ul>
		<hr>
		<p>To use contact details in your theme then you need to write:</p><ul>'; 
		foreach($this->address_items as $address){
			echo '<li><b>&lt;?php echo get_option(\'' . $address  . '\'); ?&gt;</b></li>';
		}
		echo '</ul>
		<p><b>Note:</b> you must have these in your theme - not in one of your posts/pages.</p>
		
		<div>';
		
	}
	
	/**
	 *	setSocialMedia
	 */
	public function setSocialMedia(){
	
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
		if($_SERVER['REQUEST_METHOD'] = 'POST' && !empty($_POST['action']) && $_POST['action'] == 'social_media'){
		
		
			if ( !wp_verify_nonce( $_POST[$this->_nonce], plugin_basename(__FILE__) ) ){
	    		wp_die( 'Oops something went wrong here.' );
			}

			foreach($this->social_sites as $site){
				$social_var = $site . '_username';
				if(!empty($_POST[$social_var])){
					update_option($social_var, $_POST[$social_var]);
				}
			}
			
		}

		echo '<div class="wrap">
			<h2>Set the Social Media usernames</h2>
			<p>Enter your usernames that you use on social media sites and you\'ll be able to easily output these onto your website either via your theme or the visual content editor.</p>';
			
		echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
		echo wp_nonce_field( plugin_basename(__FILE__), $this->_nonce );
		echo '<table>';
		
		foreach($this->social_sites as $site){
				$social_var = $site . '_username';
		
		echo '
		<tr valign="top">
			<th scope="row" align="left"><label for="' . $social_var . '">Enter your ' . ucwords($site) . ' username:</label></th>
			<td><input type="text" value="' . get_option($social_var) . '" id="' . $social_var . '" name="' . $social_var . '" size="20"></td>
			<td align="left">&nbsp;</td>
		</tr>';
		
		}	
		echo '
		</table>
		<input type="hidden" name="action" value="social_media" />
		<p class="submit">
		<input type="submit" value="Update Social Media usernames" class="button-primary" name="submit">
		</p>
		</form>';
			
		echo '</div>';
	
	}
	
	
	/**
	 *	setContactDetails
	 */
	public function setContactDetails(){
	
		if (!current_user_can('manage_options'))  {
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}	
		
		if($_SERVER['REQUEST_METHOD'] = 'POST' && !empty($_POST['action']) && $_POST['action'] == 'company_address'){
		
			if ( !wp_verify_nonce( $_POST[$this->_nonce], plugin_basename(__FILE__) ) ){
	    		wp_die( 'Oops something went wrong here.' );
			}

			foreach($this->address_items as $item){
				update_option($item, $_POST[$item]);
			}
			
		}
		
		echo '<div class="wrap">
			<h2>Set your Contact Details</h2>
			<p>Enter your individual contact detail values and you\'ll be able to easily output these onto your website either via your theme or the visual content editor.</p>';
			
		echo '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
		echo wp_nonce_field( plugin_basename(__FILE__), $this->_nonce );
		echo '<table>';
		
		foreach($this->address_items as $item){
		
		echo '<tr valign="top">
			<th scope="row" align="left"><label for="' . $item . '">' . ucwords(str_replace(array('company_', '_'), array('', ' '), $item)) . ':</label></th>
			<td><input type="text" value="' . get_option($item) . '" id="' . $item . '" name="' . $item . '" size="30"></td>
		</tr>';
			
		}
				
		echo '</table>
		<input type="hidden" name="action" value="company_address" />
		<p class="submit">
		<input type="submit" value="Update your address details" class="button-primary" name="submit">
		</p>
		</form>';
			
		echo '</div>';
	
	}
	
	/**
	 *	createShortcodes
	 */
	public function createShortcodes(){
		add_shortcode( 'img-simple-site-options', array($this, 'getShortcode') );
	}
	
	
	/**
	 *	getShortcode
	 *	@param	array
	 */
	public function getShortcode($attr){
		return get_option($attr[0]);
	}

}

// Instantiate the object
$objSimpleSiteOptions = new IMGSimpleSiteOptions($wpdb);