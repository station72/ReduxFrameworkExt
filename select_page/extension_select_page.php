<?php

if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists('ReduxFramework_extension_select_page') ) {

    class ReduxFramework_extension_select_page {
        
        public static $version       = '1.0.0';

        public $ext_name             = 'Select Page';

        public $min_redux_version    = '3.0.0';

        // Protected vars
        protected $parent;
        public $extension_url;
        public $extension_dir;
        public static $theInstance;

        public function __construct( $parent ) {
            
            $this->parent = $parent;
            
            if (is_admin() && !$this->is_minimum_version()) {
                return;
            }
            
            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
            }
            
            $this->field_name = 'select_page';

            self::$theInstance = $this;

            add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/class/'.$this->field_name, array( &$this, 'overload_field_path' ) ); // Adds the local field

        }

        public function getInstance() {
            return self::$theInstance;
        }

        // Forces the use of the embeded field path vs what the core typically would use    
        public function overload_field_path($field) {
            return dirname(__FILE__).'/'.$this->field_name.'/field_'.$this->field_name.'.php';
        }

        private function is_minimum_version () {
            $redux_ver = ReduxFramework::$_version;

            if ($this->min_redux_version != '') {
                if (version_compare($redux_ver, $this->min_redux_version) < 0) {
                    $msg = '<strong>' . esc_html__( 'The', 'redux-framework') . ' ' .  $this->ext_name . ' ' .  esc_html__('extension requires', 'redux-framework') . ' Redux Framework ' . esc_html__('version', 'redux-framework') . ' ' . $this->min_redux_version . ' ' .  esc_html__('or higher.','redux-framework' ) . '</strong>&nbsp;&nbsp;' . esc_html__( 'You are currently running', 'redux-framework') . ' Redux Framework ' . esc_html__('version','redux-framework' ) . ' ' . $redux_ver . '.<br/><br/>' . esc_html__('This field will not render in your option panel, and featuress of this extension will not be available until the latest version of','redux-framework' ) . ' Redux Framework ' . esc_html__('has been installed.','redux-framework' );
                    
                    $data = array(
                        'parent'    => $this->parent,
                        'type'      => 'error',
                        'msg'       => $msg,
                        'id'        => $this->ext_name . '_notice_' . self::$version,
                        'dismiss'   => false
                    );
                    
                    if (method_exists('Redux_Admin_Notices', 'set_notice')) {
                        Redux_Admin_Notices::set_notice($data);
                    } else {
                        echo '<div class="error">';
                        echo     '<p>';
                        echo         $msg;
                        echo     '</p>';
                        echo '</div>';
                    }

                    return false;
                }
            }
            
            return true;
        }
    }
}
