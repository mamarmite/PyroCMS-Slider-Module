<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Slider Field Type
 *
 * @package		PyroCMS\Addons\Shared Addons\Modules\Slider\Field Types
 * @author 		Marc-André Martin (adapted from page field type if Samul Goodwin to fit slider needs)
 */
class Field_slider
{
	public $field_type_name			= 'Slider';
	public $field_type_slug			= 'slider';
	
	public $db_col_type				= 'int';

	public $alt_process				= false;
	public $version					= '1.0.0';

	public $author					= array('name'=>'Marc-Andre Martin', 'url'=>'mamarmite.com');

	private $_slider_list;

	/**
	 * Output form input
	 *
	 * @param	array 		$data
	 * @param	array 		$entry_id
	 * @param 	stdclass 	$field The current field.
	 * @return	string 		The form.
	 */
	public function form_output($data, $entry_id, $field)
	{
		return form_dropdown($data['form_slug'], $this->_build_select_array($this->_get_sliders(), $field->is_required), $data['value'], 'id="'.$data['form_slug'].'"');
	}

	/**
	 * Output form input
	 *
	 * @param	array $input the input sets.
	 * @return	array
	 */
	public function pre_output($input)
	{
		$sliders = $this->_get_sliders($input);
		
		if (trim($input) != '')
		{
			$return['name'] 	= $sliders->slider_name;
			$return['slug'] 	= $sliders->slider_slug;
			$return['id']		= (int)$input;
			$return['status'] 	= $sliders->slider_status;
			return $return;
		}
		else
		{
			return null;
		}
	}


	/**
	 * Output form input (not active)
	 *
	 * @param integer $input The actual input sets
	 * @return	Array
	 */
	public function pre_output_plugin($input)
	{
		$sliders = $this->_get_sliders($input);
		

		if (trim($input) != '')
		{
			$return['name'] 	= $sliders[0]->slider_name;
			$return['slug'] 	= $sliders[0]->slider_slug;
			$return['id']		= (int)$input;
			$return['status'] 	= $sliders[0]->slider_status;
	
			return $return;
		}
		else
		{
			return null;
		}
	}

	/**
	 * This must be in a model 
	 * @param integer $id 
	 * @return Array $sliders as a stdclass list.
	 */
	private function _get_sliders($id=null)
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

	/**
	 * Build the array to set the select within the form.
	 * If not required, add a null choice to the top.
	 * @param Array $sliders The sliders list. 
	 * @param String $is_required from the stream, yes or no string value.
	 * @return Array
	 */
	private function _build_select_array($sliders, $is_required)
	{
		if (empty($sliders) || !isset($sliders)) return null;
		
		$choices = array(); $this->_slider_list = array();
		
		//add the null choice if not required.
		if ($is_required == 'no')
		{
			$choices[null] = get_instance()->config->item('dropdown_choose_null');
		}
		
		foreach ($sliders as $slider)
		{
			$this->_slider_list[(int)$slider->id] = $slider->slider_name;
		}

		return $choices + $this->_slider_list;
	}

	/**
	 * Build the html for for the select, in case you don't want to use the CI form helper.
	 * @param Array $sliders The sliders list. 
	 * @param String $params All other params you want to pass to it.
	 * @return String
	 */
	private function _build_tree_select($sliders, $params)
	{
		extract($params);
		$html = '';
		foreach ($sliders as $slider => $name)
		{
			$html .= '<option value="' . $slider . '"';
			$html .= $current_parent == $slider ? ' selected="selected">': '>';
			$html .= $name . '</option>';
		}
		return $html;
	}
	
}
