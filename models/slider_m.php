<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Slider module model.
 *
 * @author 		Marc-André Martin
 * @category 	Modules
 */
class Slider_m extends MY_Model
{

    public function get_sliders($id=null)
	{
		$where = isset($id) ? array('slider_status' => 'live', 'id' => (int)$id) : array('slider_status' => 'live');
		// Get the page
		$sliders = $this->CI->db
						->select('id, slider_name, slider_slug, slider_status')
						->where($where)
						->get('sliders')
						->result();

		return $sliders;
	}

}