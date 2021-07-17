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
            if(null !== self::$instance){
                return self::$instance;
            }
            if('' === $file){
                wp_die(__('File doesn&#8217;t exist?'));
            }
            if(!is_file($file)){
                wp_die(sprintf(__('File &#8220;%s&#8221; doesn&#8217;t exist?'), $file));
            }
            self::$instance = new self($file);
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
            add_action('bc_cf7_loaded', [$this, 'bc_cf7_loaded']);
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    	private function get_redirect($contact_form = null, $submission = null){
            $redirect = bc_cf7()->pref('bc_redirect', $contact_form);
            if(bc_cf7()->is_true('bc_redirect', $contact_form)){
                if(null === $submission){
                    $submission = WPCF7_Submission::get_instance();
                }
                if(null === $submission){
                    return home_url();
                } else {
                    return $submission->get_meta('url');
                }
            }
            if(!wpcf7_is_url($redirect)){
                return '';
            }
            return $redirect;
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// public
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function bc_cf7_loaded(){
            add_action('wpcf7_enqueue_scripts', [$this, 'wpcf7_enqueue_scripts']);
            add_filter('wpcf7_feedback_response', [$this, 'wpcf7_feedback_response'], 20, 2);
            bc_build_update_checker('https://github.com/beavercoffee/bc-cf7-redirect', $this->file, 'bc-cf7-redirect');
            do_action('bc_cf7_redirect_loaded');
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wpcf7_enqueue_scripts(){
            $src = plugin_dir_url($this->file) . 'assets/bc-cf7-redirect.js';
            $ver = filemtime(plugin_dir_path($this->file) . 'assets/bc-cf7-redirect.js');
            wp_enqueue_script('bc-cf7-redirect', $src, ['contact-form-7'], $ver, true);
            wp_add_inline_script('bc-cf7-redirect', 'bc_cf7_redirect.init();');
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wpcf7_feedback_response($response){
            if('mail_sent' === $response['status']){
                $redirect = $this->get_redirect();
                if('' !== $redirect){
                    $uniqid = isset($response['bc_uniqid']) ? $response['bc_uniqid'] : '';
                    if('' !== $uniqid){
                        $redirect = add_query_arg('bc_referer', $uniqid, $redirect);
                    }
                }
                $response['bc_redirect'] = $redirect;
            } else {
                $response['bc_redirect'] = '';
            }
            return $response;
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
