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
            add_action('wpcf7_enqueue_scripts', [$this, 'wpcf7_enqueue_scripts']);
            add_filter('wpcf7_form_hidden_fields', [$this, 'wpcf7_form_hidden_fields']);
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// public
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wpcf7_enqueue_scripts(){
            $src = plugin_dir_url($this->file) . 'assets/bc-cf7-redirect.js';
            $ver = filemtime(plugin_dir_path($this->file) . 'assets/bc-cf7-redirect.js');
            wp_enqueue_script('bc-cf7-redirect', $src, ['contact-form-7'], $ver, true);
            wp_add_inline_script('bc-cf7-redirect', 'bc_cf7_redirect.init();');
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wpcf7_form_hidden_fields($hidden_fields){
            $contact_form = wpcf7_get_current_contact_form();
            if($contact_form !== null){
                $redirect = null;
                if($contact_form->is_true('bc_redirect')){
                    $redirect = '';
                } else {
                    $redirect = $contact_form->pref('bc_redirect');
                    if(null !== $redirect){
                        if(!wpcf7_is_url($redirect)){
                            $redirect = '';
                        }
                    }
                }
                if(null !== $redirect){
                    $hidden_fields['bc_redirect'] = $redirect;
                }
            }
            if(isset($_GET['bc_referer'])){
                $hidden_fields['bc_referer'] = wpcf7_sanitize_query_var($_GET['bc_referer']);
            }
            $hidden_fields['bc_uniqid'] = uniqid('bc_');
            $hidden_fields = apply_filters('bc_cf7_redirect_hidden_fields', $hidden_fields);
            return $hidden_fields;
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
