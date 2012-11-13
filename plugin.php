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
class Plugin_Slider extends Plugin
{
	public function __construct()
	{
		// Load the streams driver
		$this->load->driver('Streams');
	}

	public function images()
	{
		$limit = $this->attribute('limit', 5);
		$where = $this->attribute('where', NULL);

		// Display a list of images
		$params = array(
			'stream' => 'slider',
			'namespace' => 'slider',
			'order_by' => 'ordering_count',
			'limit' => $limit,
			'where' => $where
		);

		$data = $this->streams->entries->get_entries($params);

		return $data['entries'];
	}
}