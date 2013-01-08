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
class Admin extends Admin_Controller
{
	public $section = 'images';

	public function __construct()
	{
		parent::__construct();

		// Load the streams driver
		$this->load->driver('Streams');

		// Load the language file
		$this->lang->load('slider');

		// Load the cache
		$this->load->driver('cache', array('adapter' => 'file'));
	}

	public function index()
	{
		// Display a list of articles
		$params = array(
			'stream'    => 'slider',
			'namespace' => 'slider',
			'order_by'  => 'ordering_count',
			'sort'      => 'asc'
		);

		$data['entries'] = $this->streams->entries->get_entries($params);

		$this->template->append_css('module::sortable.css')
					   ->append_js('module::sortable.js')
					   ->build('admin/entries', $data);
	}

	public function create()
	{
		$this->cache->delete('slider');

		$extra = array(
			'return'			=> 'admin/slider',
			'success_message'	=> lang('slider:create:success'),
			'failure_message'	=> lang('slider:create:fail'),
			'title'				=> lang('slider:create:title')
		);

		$this->streams->cp->entry_form('slider', 'slider', 'new', NULL, TRUE, $extra);
	}

	public function edit($id)
	{
		$this->cache->delete('slider');

		$extra = array(
 			'return' => site_url('admin/slider')
 		);

 		$this->streams->cp->entry_form('slider', 'slider', 'edit', $id, TRUE, $extra);
	}

	public function live($id)
	{
		$id = (int)$id;

		$update = $this->db->update('slider', array('status' => 'live'), array('id' => $id));

 		if ($update)
 		{
 			$this->cache->delete('slider');
 			$this->session->set_flashdata('success', 'Image successfully set to live');
 		}
 		else
 		{
 			$this->session->set_flashdata('error', 'Unable to set the image to live');
 		}

 		redirect('admin/slider');
	}

	public function draft($id)
	{
		$id = (int)$id;

		$update = $this->db->update('slider', array('status' => 'draft'), array('id' => $id));

 		if ($update)
 		{
 			$this->cache->delete('slider');
 			$this->session->set_flashdata('success', 'Image successfully set to draft');
 		}
 		else
 		{
 			$this->session->set_flashdata('error', 'Unable to set the image to draft');
 		}

 		redirect('admin/slider');
	}

	public function delete($id)
	{
		$id = (int)$id;

 		$delete = $this->db->delete('slider', array('id' => $id));

 		if ($delete)
 		{
 			$this->cache->delete('slider');
 			$this->session->set_flashdata('success', 'Image deleted successfully');
 		}
 		else
 		{
 			$this->session->set_flashdata('error', 'Unable to delete image');
 		}

 		redirect('admin/slider');
	}

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
 				$this->db->update('slider', array('ordering_count' => $i), array('id' => $id));
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
 				$this->cache->delete('slider');
 				set_status_header(200);
 			}
 		}
 		else
 		{
 			show_404();
 		}
	}
}