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
            add_action('plugins_loaded', [$this, 'plugins_loaded']);
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// public
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function plugins_loaded(){
            if(!defined('WPCF7_VERSION')){
        		return;
        	}
            add_action('wpcf7_enqueue_scripts', [$this, 'wpcf7_enqueue_scripts']);
            add_filter('wpcf7_feedback_response', [$this, 'wpcf7_feedback_response'], 20, 2);
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wpcf7_enqueue_scripts(){
            $src = plugin_dir_url($this->file) . 'assets/bc-cf7-redirect.js';
            $ver = filemtime(plugin_dir_path($this->file) . 'assets/bc-cf7-redirect.js');
            wp_enqueue_script('bc-cf7-redirect', $src, ['contact-form-7'], $ver, true);
            wp_add_inline_script('bc-cf7-redirect', 'bc_cf7_redirect.init();');
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wpcf7_feedback_response($response, $result){
            $contact_form = wpcf7_get_current_contact_form();
            if(null === $contact_form){
                return $response;
            }
            $redirect = $contact_form->pref('bc_redirect');
            if(null === $redirect){
                return $response;
            }
            $submission = WPCF7_Submission::get_instance();
            if(null === $submission){
                return $response;
            }
            if($contact_form->is_true('bc_redirect')){
                $redirect = $submission->get_meta('url');
            } else {
                if(!wpcf7_is_url($redirect)){
                    $redirect = $submission->get_meta('url');
                }
            }
            $uniqid = isset($response['bc_uniqid']) ? $response['bc_uniqid'] : '';
            if('' !== $uniqid){
                $redirect = add_query_arg('bc_referer', $uniqid, $redirect);
            }
            switch($response['status']){
    			case 'mail_sent':
                    $response['bc_redirect_url'] = $redirect;
                    break;
    			default:
                    $response['bc_redirect_url'] = '';
                    break;
    		}
            return $response;
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
