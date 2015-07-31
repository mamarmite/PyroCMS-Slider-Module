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
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Load the streams driver
		$this->load->driver('Streams');
		$this->load->driver('cache', array('adapter' => 'file'));
	}


	/**
	 *	images
	 * 	Return the images of defined slider id
	 * 
	 * 	@attributes
	 * 		limit integer 	The limit of image to call 		@default 15
	 * 		where string 	precise the where statements 	@default NULL
	 * 		random bool 	Mix up the slide return?		@default FALSE
	 * 		id integer 		The slider id to 				@default 1 
	 * 		slug string 	The slider id to 				@default NULL 
	 * 	@return Array images entries with streams.
	 */
	public function images()
	{
		// Variables
		$limit     	= (int)$this->attribute('limit', 15);
		$where     	= (string)$this->attribute('where', NULL);
		$random    	= (bool)$this->attribute('random', FALSE);
		$id			= (int)$this->attribute('id', 1);//1 is the home
		$slug		= (string)$this->attribute('slug', NULL);
		
		$slider_cache_key 	= !empty($id) && isset($id) ? md5(BASE_URL . 'slider/'.$id) : null;

		//define the where statement to get the appropriate slider from slug or id. //todo get the current prefix_ of the installation..
		$slider_where = !empty($slug) && isset($slug) ? " slider_slug='".$slug."'" : " default_sliders.id = ".$id;

		// Get the called Slider from cache
		if( ! $slider_cache_key || ! $slider = $this->cache->get($slider_cache_key) )
		{
			$params = array(
				'stream'    => 'sliders',
				'namespace' => 'sliders',
				'order_by'  => 'ordering_count',
				'sort'	    => 'asc',
				'where'     => "slider_status = 'live' AND".$slider_where
			);

			// Get results
			$slider = $this->streams->entries->get_entries($params);

			// Cache
			$this->cache->save($slider_cache_key, $slider, 67400);
		}
		$slider_id = $slider['entries'][0]["id"];
		
		//Save the slider id as part of the key to cache each one in theirs.
		$slides_cache_key 	= md5(BASE_URL . 'slider/slides/'.$slider_id);

		//Add where to the desired slider id called.
		$slides_where = !empty($where) && isset($where) ? $where." AND slider_id =".$slider_id : " slider_id=".$slider_id;
		
		// Get from cache
		if( ! $data = $this->cache->get($slides_cache_key) )
		{
			// Display a list of images
			$params = array(
				'stream'    => 'slides',
				'namespace' => 'slides',
				'order_by'  => 'ordering_count',
				'sort'	    => 'asc',
				'limit'		=> $limit,
				'where'     => "slide_status = 'live' AND ".$slides_where
			);

			// Get results			
			$data = $this->streams->entries->get_entries($params);

			// Cache
			$this->cache->save($slides_cache_key, $data, 67400);
		}

		// Randomise?
		if( $random )
		{
			shuffle($data['entries']);
		}

		// Limit (moved to the bd request, cache use module name, you must remove the cash to test the new limit)
		/*if( count($data['entries']) > $limit )
		{
			$data['entries'] = array_slice($data['entries'], 0, $limit);
		}*/
		return $data['entries'];
	}

}
