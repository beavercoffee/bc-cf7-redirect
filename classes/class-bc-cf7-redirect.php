<?php

if(!class_exists('BC_CF7_Redirect')){
    final class BC_CF7_Redirect {

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// private static
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private static $instance = null;

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// public static
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    	public static function get_instance($file = ''){
            if(null === self::$instance){
                if(@is_file($file)){
                    self::$instance = new self($file);
                } else {
                    wp_die(__('File doesn&#8217;t exist?'));
                }
            }
            return self::$instance;
    	}

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// private
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private $file = '';

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    	private function __clone(){}

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    	private function __construct($file = ''){
            $this->file = $file;
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// public
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function l10n(){
            $l10n = [];
			$posts = get_posts([
                'post_type' => 'wpcf7_contact_form',
                'posts_per_page' => -1,
            ]);
            if($posts){
                foreach($posts as $post){
                    $contact_form = wpcf7_contact_form($post->ID);
					if($contact_form){
						if($contact_form->is_true('bc_redirect')){
							$l10n[$post->ID] = '';
						} else {
							$redirect = $contact_form->pref('bc_redirect');
							if(null !== $redirect){
								if(wpcf7_is_url($redirect)){
									$l10n[$post->ID] = $redirect;
								} else {
									$l10n[$post->ID] = '';
								}
							}
						}
					}
                }
            }
			return $l10n;
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function l10n(){
            $src = plugin_dir_url($this->file) . 'assets/bc-cf7-redirect.js';
            $ver = filemtime(plugin_dir_path($this->file) . 'assets/bc-cf7-redirect.js');
            wp_add_inline_script('bc-cf7-redirect', 'bc_cf7_redirect.init();');
            wp_enqueue_script('bc-cf7-redirect', $src, ['contact-form-7'], $ver, true);
			wp_localize_script('bc-cf7-redirect', 'bc_cf7_redirects', $this->l10n());
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
