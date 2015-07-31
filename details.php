<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Slider Module
 *
 * @package		PyroCMS
 * @subpackage	Slider Module
 * @author		Chris Harvey 
 * @link 		http://www.chrisnharvey.com/
 * @author		Marc-André Martin
 * @link 		http://www.mamarmite.com
  * 
 *	@todo Add filters to slide views
 * 	@todo manage to modify the shortcut of the slides view to modify the current slider id.
 */
class Module_Slider extends Module
{
	public $version = '1.0.4';
	public $module_namespace = "sliders";
	public $module_streamname = "sliders";
	public $module_slides_streamname = "slides";
	public $module_slides_namespace = "slides";

	public function __construct()
	{
		parent::__construct();

		// Load the streams driver
		$this->load->driver('Streams');
		$this->load->library(array('files/files'));
	}

	public function info()
	{
		$info = array(
			'name' => array(
				'en' => 'Slider',
				'fr' => 'Diaporama'
			),
			'description' => array(
				'en' => 'Allows you to add/edit images to the homepage slider',
				'fr' => 'Ajoutez et éditez des images pour le diaporama principal'
			),
			'frontend' => FALSE,
			'backend' => TRUE,
			'menu' => 'content',
			'sections' => array(
				'sliders'	=> array(
					'name'	=> 'slider:sections:sliders',
					'uri'	=> 'admin/slider',
					'shortcuts' => array()
				),
				'slides'	=> array(
					'name'	=> 'slider:sections:all_slides',
					'uri'	=> 'admin/slider/slides',
					'shortcuts' => array()
				)
			),
			'roles' => array('slider_delete', 'slider_add', 'slider_fields', 'slide_add', 'slide_delete', 'slide_fields')
		);
		
		//user can add slider?
		if (group_has_role('slider', 'slider_add')) {
			array_push($info['sections']['sliders']['shortcuts'], array(
				'name' => 'slider:shortcuts:create',
				'uri' => 'admin/slider/create',
				'class' => 'add'
			));
		}

		//user can edit slider's field?
		if (group_has_role('slider', 'slider_fields')) {
			array_push($info['sections']['sliders']['shortcuts'], array(
				'name' => 'slider:shortcuts:fields',
				'uri' => 'admin/slider/fields',
				'class' => 'add'
			));
		}

		//user can edit slide's field?
		if (group_has_role('slider', 'slide_fields')) {
			array_push($info['sections']['slides']['shortcuts'], array(
				'name' => 'slider:shortcuts:fields',
				'uri' => 'admin/slider/slides/fields',
				'class' => 'add'
			));
		}
		
		if ($this->uri->segment(3) == 'fields') {
			$info['sections']['sliders']['shortcuts'] = array(
				array(
					'name'  => 'slider:shortcuts:add_field',
					'uri'   => 'admin/slider/fields/new',
					'class' => 'add'
				)
			);
		}
		if ($this->uri->segment(3) == 'slides' && $this->uri->segment(4) == 'fields') {
			$info['sections']['slides']['shortcuts'] = array(
				array(
					'name'  => 'slider:shortcuts:add_field',
					'uri'   => 'admin/slider/slides/fields/new',
					'class' => 'add'
				)
			);
		}
		return $info;
	}

	public function install()
	{
		// Load in the files library
		$this->load->library('files/files');
		
		
		$folder = $this->get_folder_byname("sliders");

		//Check if it already exist..
		if (!$folder["status"])
		{
			// Create a folder to store the slider images
			$folder = Files::create_folder(0, 'sliders');
		}
		//die (var_dump($folder));
		// Add the streams
		$this->streams->streams->add_stream('Sliders', $this->module_streamname, $this->module_namespace, NULL, lang('slider:about'));
		$this->streams->streams->add_stream('Slides', $this->module_slides_streamname, $this->module_slides_namespace, NULL, lang('slider:about'));

		$this->load->config('slider/config');

		// Assign the fields to the stream
		$fields_sliders = array(
			array(
				'name'          => 'Name',
				'slug'          => 'slider_name',
				'namespace'     => $this->module_namespace,
				'type'          => 'text',
				'extra'         => array('max_length' => 255),
				'assign'        => $this->module_streamname,
				'title_column'  => TRUE,
				'required'      => TRUE
			),
			array(
				'name'          => 'Slug',
				'slug'          => 'slider_slug',
				'namespace'     => $this->module_namespace,
				'type'          => 'slug',
				'extra'         => array('space_type' => '-', 'slug_field'=>'slider_name'),
				'assign'        => $this->module_streamname,
				'title_column'  => TRUE,
				'required'      => TRUE
			),
			array(
				'name'          => 'Status',
				'slug'          => 'slider_status',
				'namespace'     => $this->module_namespace,
				'type'          => 'choice',
				'extra'         => array(
					'choice_type' => 'dropdown',
					'choice_data' => 'live : Live
									 draft : Draft',
					'default_value' => 'draft'
				),
				'assign'        => $this->module_streamname,
				'required'      => TRUE
			),
			array(
				'name'          => 'Language',
				'slug'          => 'slider_language',
				'namespace'     => $this->module_namespace,
				'type'          => 'pyro_lang',
				'extra'         => array(
					'filter_theme' => 'yes'
				),
				'assign'        => $this->module_streamname,
				'required'      => TRUE
			)
		);

		// Assign the fields to the stream
		$fields_slides = array(
			array(
				'name'          => 'sliderId',
				'slug'          => 'slider_id',
				'namespace'     => $this->module_slides_namespace,
				'type'          => 'text',
				'extra'         => array('max_length' => 255),
				'assign'        => $this->module_slides_streamname,
				'title_column'  => TRUE,
				'required'      => TRUE
			),
			array(
				'name'          => 'Title',
				'slug'          => 'slide_title',
				'namespace'     => $this->module_slides_namespace,
				'type'          => 'text',
				'extra'         => array('max_length' => 255),
				'assign'        => $this->module_slides_streamname,
				'title_column'  => TRUE,
				'required'      => TRUE
			),
			array(
				'name'          => 'Description',
				'slug'          => 'slide_desc',
				'namespace'     => $this->module_slides_namespace,
				'type'          => 'textarea',
				'assign'        => $this->module_slides_streamname,
				'required'      => TRUE
			),
			array(
				'name'          => 'Link',
				'slug'          => 'slide_link',
				'namespace'     => $this->module_slides_namespace,
				'type'          => 'internal_url',
				'assign'        => $this->module_slides_streamname,
				'required'      => FALSE
			),
			array(
				'name'          => 'Image',
				'slug'          => 'slide_image',
				'namespace'     => $this->module_slides_namespace,
				'type'          => 'imagebrowser',
				'extra'			=> array(
					'folder'		=> $folder['data']['folder'][0]->id,
					'allowed_types'	=> $this->config->item('image_extensions')
				),
				'assign'        => $this->module_slides_streamname,
				'required'      => TRUE
			),
			array(
				'name'          => 'Status',
				'slug'          => 'slide_status',
				'namespace'     => $this->module_slides_namespace,
				'type'          => 'choice',
				'extra'         => array(
					'choice_type' => 'dropdown',
					'choice_data' => 'live : Live
									 draft : Draft',
					'default_value' => 'draft'
				),
				'assign'        => $this->module_slides_streamname,
				'required'      => TRUE
			)
		);

		$this->streams->fields->add_fields($fields_sliders);
		$this->streams->fields->add_fields($fields_slides);

		return TRUE;
	}

	public function uninstall()
	{
		$this->streams->utilities->remove_namespace($this->module_namespace);
		$this->streams->utilities->remove_namespace($this->module_slides_namespace);

		return TRUE;
	}


	public function upgrade($old_version)
	{
		switch ($old_version)
		{
			case "1.0":
			 	//in 1.0.1 we have created 2 new streams with a different name.
				//todo: migration from slider streams to id 1 0 slider in 1.0.1
			 	$this->streams->utilities->remove_namespace("slider");
			break;
			case "1.0.1":
				$this->streams->fields->add_fields(array(array(
					'name'          => 'From Existing Folder',
					'slug'          => 'from_folder',
					'namespace'     => $this->module_namespace,
					'type'          => 'file_folders',
					'assign'        => $this->module_streamname,
					'required'      => true
				)));
			break;
			case "1.0.2":
			case "1.0.3":
			default:
				//no edit field exist? so delete it and recreate it again. //update_field exist but it's commented and seem to miss some feature.
				if ($this->streams->fields->delete_field("from_folder",$this->module_namespace))
				{
					$this->streams->fields->add_fields(array(array(
						'name'          => 'From Existing Folder',
						'slug'          => 'from_folder',
						'namespace'     => $this->module_namespace,
						'type'          => 'file_folders',
						'assign'        => $this->module_streamname,
						'required'      => false
					)));
				} else {
					return false;
				}
			break;
		}
			
		return true;
	}

	public function help()
	{
		return "
			<h4>Slider</h4>
			<p>For help with this module</p>
		";
	}

	/**
    *  	Copied from Files:search and adapt to get all properties
    *	In: file_folders_m->select()
    */
    public function get_folder_byname($name)
    {
        if (!isset($name)) return FALSE;
        $results = array();
        $this->file_folders_m->select('id, parent_id, slug, name, location, remote_container, date_added, sort');

        $this->file_folders_m->like('name', $name)
                ->or_like('location', $name)
                ->or_like('remote_container', $name);

        $results['folder'] = $this->file_folders_m->get_all();
        
        if ($results['folder'])
        {
                return Files::result(TRUE, NULL, NULL, $results);
        }
        return Files::result(FALSE, lang('files:no_records_found'));
    }
}
/* End of file */
