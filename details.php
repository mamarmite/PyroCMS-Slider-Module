<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Slider Module
 *
 * @package		PyroCMS
 * @subpackage	Slider Module
 * @author		Chris Harvey
 * @link 		http://www.chrisnharvey.com/
 *
 */
class Module_Slider extends Module
{
	public $version = '1.0';

	public function __construct()
	{
		parent::__construct();

		// Load the streams driver
		$this->load->driver('Streams');
	}

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Slider'
			),
			'description' => array(
				'en' => 'Allows you to add/edit images to the homepage slider'
			),
			'frontend' => FALSE,
			'backend' => TRUE,
			'menu' => 'content',
			'sections' => array(
				'images'	=> array(
					'name'	=> 'slider:sections:images',
					'uri'	=> 'admin/slider',
					'shortcuts' => array(
						array(
					 	   'name' => 'slider:shortcuts:create',
						   'uri' => 'admin/slider/create',
						   'class' => 'add'
						)
					)
				)
			),
		);
	}

	public function install()
	{
		// Load in the files library
		$this->load->library('files/files');
		
		// Create a folder to store the slider images
		$folder = Files::create_folder(0, 'Slider');

		// Add the stream
		$this->streams->streams->add_stream('Slider', 'slider', 'slider', NULL, 'A selection of images for the homepage.');

		$this->load->config('slider/config');

		// Assign the fields to the stream
		$fields = array(
			array(
				'name'          => 'Title',
				'slug'          => 'title',
				'namespace'     => 'slider',
				'type'          => 'text',
				'extra'         => array('max_length' => 255),
				'assign'        => 'slider',
				'title_column'  => TRUE,
				'required'      => TRUE
			),
			array(
				'name'          => 'Description',
				'slug'          => 'desc',
				'namespace'     => 'slider',
				'type'          => 'textarea',
				'assign'        => 'slider',
				'required'      => TRUE
			),
			array(
				'name'          => 'Button Title',
				'slug'          => 'button_title',
				'namespace'     => 'slider',
				'type'          => 'text',
				'instructions'	=> 'The text that appears on the button link',
				'extra'         => array(
					'max_length' => 30
				),
				'assign'        => 'slider',
				'required'      => TRUE
			),
			array(
				'name'          => 'Button Link',
				'slug'          => 'button_link',
				'namespace'     => 'slider',
				'type'          => 'url',
				'assign'        => 'slider',
				'required'      => TRUE
			),
			array(
				'name'          => 'Image',
				'slug'          => 'image',
				'namespace'     => 'slider',
				'type'          => 'image',
				'extra'			=> array(
					'folder'		=> $folder['data']['id'],
					'allowed_types'	=> $this->config->item('image_extensions')
				),
				'assign'        => 'slider',
				'required'      => TRUE
			),
			array(
				'name'          => 'Status',
				'slug'          => 'status',
				'namespace'     => 'slider',
				'type'          => 'choice',
				'extra'         => array(
					'choice_type' => 'dropdown',
					'choice_data' => 'live : Live
									 draft : Draft',
					'default_value' => 'draft'
				),
				'assign'        => 'slider',
				'required'      => TRUE
			)
		);

		$this->streams->fields->add_fields($fields);

		return TRUE;
	}

	public function uninstall()
	{
		$this->streams->utilities->remove_namespace('slider');

		return TRUE;
	}


	public function upgrade($old_version)
	{
		return TRUE;
	}

	public function help()
	{
		return "
			<h4>Slider</h4>
			<p>For help with this module, please contact <a href='mailto:chris.harvey@ne-web.com?subject=Slider Module'>chris.harvey@ne-web.com</a></p>
		";
	}
}
/* End of file */