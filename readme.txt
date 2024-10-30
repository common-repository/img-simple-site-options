=== IMG Simple Site Options ===
Contributors: imgiseverything
Author URL: imgiseverything.co.uk/wordpress-plugins/img-simple-site-options/
Tags: custom, posts
Requires at least: 3.8
Tested up to: 3.8.1
Stable tag: 1.12


== Description ==

Manage social media usernames and contact details via WordPress.

= Usage =

In your theme files:

* // Company address details
* $address_line_1 = get_option('company_address_line_1'); 
* $address_line_2 = get_option('company_address_line_2'); 
* $address_line_3 = get_option('company_address_line_3'); 
* $city = get_option('company_city');
* $post_code = get_option('company_post_code');
* $country = get_option('company_country');

* $email = get_option('company_email');
* $telephone = get_option('company_telephone');

* // Social media
* $twitter = get_option('twitter_username');
* $pinterest = get_option('pinterest_username');
* $instagram = get_option('instagram_username');
* $facebook = get_option('facebook_username');
* $googleplus = get_option('googleplus_username');
* $linkedin = get_option('linkedin_username');
* $blog_url = get_option('blog_url');


Then in your theme echo out the values:

* Twitter username is: @<?php echo $twitter; ?>
* Company postal code is: <?php echo $post_code; ?>

or you could just do:

Twitter username is: @<?php echo get_option('twitter_username'); ?>

Or in the Visual Editor the following shortcodes work:

[img-simple-site-options twitter_username]

and

[img-simple-site-options $company_post_code]


== Installation ==

1. Upload the `img-simple-site-options` folder to the `/wp-content/plugins/` directory
1. Activate the IMG Simple Site Options plugin through the 'Plugins' menu in WordPress


== Screenshots ==

1. Edit Social Media usernames form
2. Edit Contact Details values form
3. Using a shortcode to show titter username and your post code via the Visual Editor



== Changelog ==

= 1.12 =
* Linkedin added to list of social sites

= 1.11 =
* Google plus added to list of social sites

= 1.1 =
* Shortcodes added so users can output content via the WordPress Visual Editor in Posts/Pages
* Instructions added to aid user outputting content into theme files using basic PHP and, alternatively, via the WordPress Visual Editor in Posts/Pages

= 1.0 =
* Simple options added so users can output data via their theme files using basic PHP 