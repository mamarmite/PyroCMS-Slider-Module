<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Slider Module slide's controller.
 *
 * @package		PyroCMS
 * @subpackage	Slider Module
 * @author		Chris Harvey
 * @link 		http://www.chrisnharvey.com/
 *
 */
class Admin_Slides extends Admin_Controller
{
	public $section = 'slides';

	public $current_namespace = "slides";
	public $current_streamname = "slides";

	public function __construct()
	{
		parent::__construct();
		$this->module_details['sections']['slides']['shortcuts'][1]["uri"] = 'admin/slider/slides/create/1';
		// Load the streams driver
		$this->load->driver('Streams');

		// Load the language file
		$this->lang->load('slider');

		// Load the cache
		$this->load->driver('cache', array('adapter' => 'file'));

		//load the user's helper
		$this->load->helper("user");
	}

	/**
	 * Index
	 * Show all the slides if no ID is set, show current slider slides if ID is set.
	 * @param Integer $id 
	 * @return none
	 * use entries view
	 */
	public function index($id)
	{
		$where = isset($id) ? 'slider_id = '.$id : '';
		//to work, the shortcut must get the active slider id. add the $id here is too late. Must dig further!
		//$this->module_details['sections']['slides']['shortcuts'][1]["uri"] = 'admin/slider/slides/create/'.$id;

		// Display a list of articles
		$params = array(
			'stream'    => $this->current_namespace,
			'namespace' => $this->current_streamname,
			'order_by'  => 'ordering_count',
			'sort'      => 'asc',
			'where'		=> $where
		);
		$current_slider = $this->streams->entries->get_entry($id, "sliders", "sliders", false);
		$data['entries'] = $this->streams->entries->get_entries($params);
		
		$this->template->append_css('module::sortable.css')
					   ->append_js('module::sortable.js')
					   ->set("title",$current_slider->slider_name)
					   ->build('admin/entries', $data);
	}

	/**
	 * Create
	 * Create slide for the current slider_id.
	 * @param integer $id 
	 * @return none
	 */
	public function create($id)
	{
		if (empty($id)) $id = 1;
		role_or_die("slider", "slide_add", 'admin/slider/slides/index/'.$id, lang("slider:role:add:failed"));
		if ($this->input->post()) $this->cache->delete(md5(BASE_URL . $this->modulename));

		$params = array(
			'stream'    => "sliders",
			'namespace' => "sliders",
			'where'		=> 'default_sliders.id='.$id
		);

		$current_slider = $this->streams->entries->get_entry($id, "sliders", "sliders", false);
		$title = lang('slider:slide:create:title').$current_slider->slider_name;
		$extra = array(
			'return'			=> 'admin/slider/slides/index/'.$id,
			'success_message'	=> lang('slider:create:success'),
			'failure_message'	=> lang('slider:create:fail'),
			'title'				=> $title
		);
		$skips = null;
		$tabs = false;
		$hidden = array("slider_id");
		$defaults = array("slider_id" => $id);

		$this->streams->cp->entry_form($this->current_streamname, $this->current_namespace, 'new', null, true, $extra, $skips, $tabs, $hidden, $defaults);
	}

	/**
	 * Edit
	 * Edit the selected slide.
	 * Skip the slider id
	 * @param integer $id the slide id to edit 
	 * @param integer $slider_id the slider ID of the slide.
	 * cp entry form
	 */
	public function edit($id, $slider_id)
	{
		if ($this->input->post()) $this->cache->delete(md5(BASE_URL . $this->modulename));

		$return = isset($slider_id) ? $slider_id : "";
		$extra = array(
 			'return' => 'admin/slider/slides/index/'.$return
 		);
		$skips = null;
		$tabs = false;
		$hidden = array("slider_id");
		$defaults = array("slider_id" => $slider_id);

 		$this->streams->cp->entry_form($this->current_streamname, $this->current_namespace, 'edit', $id, TRUE, $extra, $skips, $tabs, $hidden, $defaults);
	}

	/**
	 * Live
	 * Set slides to live.
	 * @param integer $id 
	 * redirect
	 */
	public function live($id)
	{
		$id = (int)$id;

		$update = $this->db->update($this->current_namespace, array('status' => 'live'), array('id' => $id));

 		if ($update)
 		{
 			$this->cache->delete(md5(BASE_URL . $this->modulename));
 			$this->session->set_flashdata('success', 'Image successfully set to live');
 		}
 		else
 		{
 			$this->session->set_flashdata('error', 'Unable to set the image to live');
 		}

 		redirect('admin/slider/slides');
	}

	/**
	 * draft
	 * Set slides to draft.
	 * @param integer $id 
	 * redirect
	 */
	public function draft($id)
	{
		$id = (int)$id;

		$update = $this->db->update($this->current_streamname, array('status' => 'draft'), array('id' => $id));

 		if ($update)
 		{
 			$this->cache->delete(md5(BASE_URL . $this->modulename));
 			$this->session->set_flashdata('success', 'Image successfully set to draft');
 		}
 		else
 		{
 			$this->session->set_flashdata('error', 'Unable to set the image to draft');
 		}

 		redirect('admin/slider/slides');
	}

	/**
	 * delete
	 * @param integer $id 
	 * redirect
	 */
	public function delete($id, $slider_id)
	{
		$id = (int)$id;
		$return = isset($slider_id) ? $slider_id : "";

		role_or_die("slider", "slide_delete", 'admin/slider/slides/index/'.$return, lang("slider:role:slide:delete:failed"));
 		$delete = $this->db->delete($this->current_streamname, array('id' => $id));

 		if ($delete)
 		{
 			$this->cache->delete(md5(BASE_URL . $this->modulename));
 			$this->session->set_flashdata('success', 'Image deleted successfully');
 		}
 		else
 		{
 			$this->session->set_flashdata('error', 'Unable to delete image');
 		}

 		redirect('admin/slider/slides');
	}

	/**
	 * Assign field, add new fields view to the slide's stream.
	 * @param string $action 'edit', 'new' or 'delete'
	 * @param $field if delete or edit the field to edit.
	 * redirect
	 */
	public function fields($action = null, $field = null)
	{
		role_or_die("slider", "slide_fields", 'admin/slider/slides/index/'.$return, lang("slider:role:slide:fields:failed"));
		if ($action == null) {

			$this->streams->cp->assignments_table($this->current_namespace, $this->current_namespace, null, null, true, array(
				'title'   => 'Slider Fields',
				'buttons' => array(
					array(
						'label' => 'Edit',
						'url'   => "admin/slider/fields/edit/-assign_id-"
					),
					array(
						'label'   => 'Delete',
						'url'     => "admin/slider/fields/delete/-assign_id-",
						'confirm' => true
					)
				)
			));

		} elseif (($action == 'edit' and $field) or $action == 'new') {

			$this->streams->cp->field_form($this->current_streamname, $this->current_namespace, $action, 'admin/slider/fields', $field, array(), true, array(
				'title'   => 'Edit Field',
			));

		} elseif ($action == 'delete' and $field) {

			$query = $this->db->select('data_fields.field_slug')
				->join('data_fields', 'data_field_assignments.field_id = data_fields.id')
				->where('data_field_assignments.id', $field)
				->get('data_field_assignments');

			if ( ! $query->num_rows()) show_404();

			$this->streams->fields->delete_field($query->row()->field_slug, $this->current_streamname);

			$this->session->set_flashdata('success', 'Field deleted successfully');

			redirect('admin/slider/fields');

		} else {

			show_404();

		}
	}

	/**
	 * Reorder the slides
	 */
	public function reorder()
	{
		if ($this->input->is_ajax_request())
 		{
 			$order = explode(',', $this->input->post('order'));

 			// Start the transaction
 			$this->db->trans_start();

 			$i = 1;
 			foreach ($order as $id)
 			{
 				$this->db->update($this->current_streamname, array('ordering_count' => $i), array('id' => $id));
 				$i++;
 			}

 			// End the transaction
 			$this->db->trans_complete();

 			if ($this->db->trans_status() === FALSE)
 			{
 				set_status_header(500);
 			}
 			else
 			{
 				$this->cache->delete(md5(BASE_URL . $this->current_streamname));
 				set_status_header(200);
 			}
 		}
 		else
 		{
 			show_404();
 		}
	}
}