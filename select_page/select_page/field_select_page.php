<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @author      Dovy Paukstys
 * @version     3.1.5
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Don't duplicate me!
if (!class_exists('ReduxFramework_select_page')) {

    class ReduxFramework_select_page
    {
        function __construct($field = array(), $value = '', $parent)
        {
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

            if (empty($this->extension_dir)) {
                $this->extension_dir = trailingslashit(str_replace('\\', '/', dirname(__FILE__)));
                $this->extension_url = site_url(str_replace(trailingslashit(str_replace('\\', '/', ABSPATH)), '', $this->extension_dir));
            }

            // Set default args for this field to avoid bad indexes. Change this to anything you use.
            $defaults = array(
                'options' => array(),
                'stylesheet' => '',
                'output' => true,
                'enqueue' => true,
                'enqueue_frontend' => true
            );
            $this->field = wp_parse_args($this->field, $defaults);

        }

        public function render()
        {
            $args = array(
                'sort_order' => 'asc',
                'sort_column' => 'post_title',
                'hierarchical' => 1,
                'exclude' => '',
                'include' => '',
                'meta_key' => '',
                'meta_value' => '',
                'authors' => '',
                'child_of' => 0,
                'parent' => -1,
                'exclude_tree' => '',
                'number' => '',
                'offset' => 0,
                'post_type' => 'page',
                'post_status' => 'publish'
            );

            $pages = get_pages($args);
            echo '<select name="', $this->field['name'], '" id="', $this->field['id'], '">';
            foreach ($pages as $page) {
                echo '<option value="',$page->ID.'" ' ,$this->get_checked_str($page->ID),'> ',$page->post_title.'</option>';
            }
            echo '</select>';
        }

        function get_checked_str($page_id):string {
            if(!isset($this->value) || empty($this->value)){
                return '';
            }

            if ($page_id == $this->value){
                return 'selected';
            }

            return '';
        }

        public function enqueue()
        {
            wp_enqueue_script(
                'redux-field-icon-select-js',
                $this->extension_url . 'field_select_page.js',
                array('jquery'),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-icon-select-css',
                $this->extension_url . 'field_select_page.css',
                time(),
                true
            );

        }

        public function output()
        {
            if ($this->field['enqueue_frontend']) {

            }
        }
    }
}