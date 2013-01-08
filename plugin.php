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
		$this->load->driver('cache', array('adapter' => 'file'));
	}

	public function images()
	{
		// Variables
		$limit     = (int)$this->attribute('limit', 15);
		$where     = $this->attribute('where', NULL);
		$random    = (bool)$this->attribute('random', FALSE);
		$cache_key = 'slider';

		// Get from cache
		if( ! $data = $this->cache->get($cache_key) )
		{

			// Display a list of images
			$params = array(
				'stream'    => 'slider',
				'namespace' => 'slider',
				'order_by'  => 'ordering_count',
				'sort'	    => 'asc',
				'where'     => "status = 'live'"
			);

			// Get results			
			$data = $this->streams->entries->get_entries($params);

			// Cache
			$this->cache->save($cache_key, $data, 67400);
		}

		// Randomise
		if( $random )
		{
			shuffle($data['entries']);
		}

		// Limit
		if( count($data['entries']) > $limit )
		{
			$data['entries'] = array_slice($data['entries'], 0, $limit);
		}

		return $data['entries'];
	}
}
