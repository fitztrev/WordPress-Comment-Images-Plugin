<?php
/*
Plugin Name: Comment Image Uploader
Plugin URI: http://trevorfitzgerald.com/
Description: Customized plugin that allows your visitors to upload images with their comments.
Version: 2.0.4
Author: Trevor Fitzgerald
Author URI: http://trevorfitzgerald.com/
*/

class commentImageUploader {

	var $optionName     = 'commentImageUploader';

	/* **************************************** */

	private $version           = '2.0';
	private $options           = null;

	public function __construct() {
		if ( is_admin() ) {
			add_action('admin_menu', array(&$this, 'options_menu'));
			register_activation_hook( __FILE__, array(&$this, 'activate') );
			register_deactivation_hook( __FILE__, array(&$this, 'deactivate') );
		} else {
			add_action('wp_head', array(&$this, 'wp_head'));
			wp_enqueue_script('jquery');
			wp_enqueue_script('uploadify', plugins_url('vendor/jquery.uploadify.v2.1.0.min.js', __FILE__));
			wp_enqueue_script('swfobject', plugins_url('vendor/swfobject.js', __FILE__));
			wp_enqueue_style('uploadify', plugins_url('vendor/uploadify.css', __FILE__));
			wp_enqueue_script('commentImageUploader', plugins_url('js/custom.js', __FILE__));
		}

		add_action('comment_form', array( &$this, 'comment_form' ));
		add_filter('preprocess_comment', array( &$this, 'preprocess_comment' ));
		add_filter('comment_text', array( &$this, 'embed_images' ));

		if ( $this->optionName ) {
			$this->options = get_option($this->optionName);
		}

		if ( $this->options['lightbox'] ) {
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
		}

	}

	public function activate() {
		if ( $this->optionName ) {
			$defaultOptions = array(
				'tmaxwidth' => 100,
				'tmaxheight' => 100,
				'lmaxwidth' => 700,
				'lmaxheight' => 700,
				'lightbox' => 1,
			);
			update_option($this->optionName, $defaultOptions);
		}
	}

	public function deactivate() {
		delete_option($this->optionName);
	}

	public function comment_form() {
		echo "\n" . '<input name="uploaded_comment_files" type="hidden" id="uploaded_comment_files" value="" />' . "\n";
	}

	public function preprocess_comment($commentdata) {
		$commentdata['comment_content'] .= "\n\n" . $_POST['uploaded_comment_files'];
		return $commentdata;
	}

	public function wp_head() {
		$path = WP_PLUGIN_URL . '/' . basename(dirname(__FILE__));
		require 'includes/wp_head.php';
	}

	public function options_menu() {
		add_options_page('Comment Image Upload', 'Comment Image Upload Plugin', 9, plugin_basename(__FILE__), array(&$this, 'options_page'));
	}

	public function options_page() {
		if ( !$this->userAdmin() ) {
			$message = 'You do not have sufficient privileges to access this page. Please contact your site administrator.';
			require dirname(__FILE__) . '/html/error.html.php';
			return;
		}
		if ( !empty($_POST) ) {
			check_admin_referer('commentImageUploader');
			update_option($this->optionName, $_POST['option']);
			$updated = true;
			$values = $_POST['option'];
		} else {
			$values = $this->options;
		}
		require dirname(__FILE__) . '/html/options.html.php';
	}

	private function userAdmin() {
		if ( current_user_can('manage_options') ) return true;
		else                                      return false;
	}

	public function uploadPath() {
		$paths = wp_upload_dir();
		if ( $paths['error'] ) {
			die('Error: Could not create uploads directory. Please ensure permissions are properly set.');
		}
		return array(
			'url' => $paths['baseurl'] . '/comments',
			'dir' => $paths['basedir'] . '/comments',
		);
	}

	public function embed_images($content) {
		return preg_replace_callback('/\[img\]([a-z0-9\-_\.]+)\[\/img\]/', array(&$this, 'commentCallback'), $content);
	}

	public function commentCallback($matches) {
		$uploadPath = $this->uploadPath();
		$tPath = $uploadPath['dir'].'/t_'.$matches[1];
			if ( file_exists($tPath) ) {
				$tPath = $uploadPath['url'].'/t_'.$matches[1];
			} else {
				$tPath = $uploadPath['url'].'/'.$matches[1];
			}
		$lPath = $uploadPath['dir'].'/l_'.$matches[1];
			if ( file_exists($lPath) ) {
				$lPath = $uploadPath['url'].'/l_'.$matches[1];
			} else {
				$lPath = $uploadPath['url'].'/'.$matches[1];
			}
		if ( $this->options['lightbox'] ) {
			return '<a href="'.$lPath.'" class="thickbox" rel="commentImages"><img src="'.$tPath.'" alt="" /></a>';
		} else {
			return '<a href="'.$lPath.'"><img src="'.$tPath.'" alt="" /></a>';
		}
	}

}

$commentImageUploader = new commentImageUploader;

function upload_comment_images() {
        echo '<input id="commentImageInput" type="file" size="22" name="commentImage" /><ul id="uploadedFiles"></ul>';
}

?>
