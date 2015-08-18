<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 
 * @author      Marc-André Martin
 * @link		http://mamarmite.com
 * @package 	PyroCMS
 * @subpackage  Slider
 * @category	module
 */

$route['slider/admin/slides(:any)?'] = 'admin_slides$1';
$route['slider/(:num)'] = 'slider/index/$1';