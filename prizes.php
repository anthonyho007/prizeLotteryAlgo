<?php namespace Tony\Prizes;
use \Tony\Prizes\Prize;
/**
 * The Prizes post type factory
 *
 * A class that organize the prizes pool into prize
 *
 * @link    http://anthonyho-007.com
 * @since   1.0.0
 *
 * @package Tony\Prizes
 */

/**
 * Prizes Class that organize prizes into prize
 *
 * Helpers functions for the prizes 
 *
 * @since   1.0.0
 * @package Tony\Prizes
 * @author  Anthony Ho <anthonyho007@gmail.com>
 */
class Prizes 
{

	/**
	 * Holds all of the Prizes objects.
	 *
	 * @access protected
	 * @var array
	 */
	protected $prizes = array();

	/**
	 * Holds all of the Prizes query objects.
	 *
	 * @access protected
	 * @var object
	 */
	protected $query;


	/**
	 * Creates the Prizes objects
	 *
	 * @access public 
	 * @var array $args
	 */
	public function __construct()
	{
		global $wp_query;
		$args = get_posts(array(
		'post_type'		=> 'prize'
		));
		
		if(is_array($args)) {
			if (isset($args[0]) && $args[0] instanceof \WP_Post) {
				 $this->query=$prizes = $args;
			} else {
				$this->query = new \WP_Query($args);
				$prizes = $this->query->posts;
			}
		} elseif ( $args instanceof \WP_Query) {
			$prizes = $args->posts;
			$this->query = $args;
		} else {
			$prizes = $wp_query->posts;
			$this->query = $wp_query;
		}

		// create all the prize object
		if ( ! empty($prizes)) {
			foreach ($prizes as $prize) {
				$this->prizes[] = new Prize($prize);
			}
		}

	}

	/**
	 * Returns a array of prize objects
	 *
	 * @access public 
	 * @var integer
	 */
	public function getPrizes()
	{
		return $this->prizes;
	}

	/**
	 * Returns the total number of the prizes remaining
	 *
	 * @access public 
	 * @var integer
	 */
	public function remainingPrizes()
	{
		$total = 0;
		foreach ($this->prizes as $prize){
			$total += $prize->getQuantity();
		}
		return $total;
	}

	/**
	 * Returns total count of the prizes type
	 *
	 * @access public 
	 * @var integer
	 */
	public function getCount()
	{
		return count($this->query);
	}

	/**
	 * Output the total count of the prizes type 
	 *
	 * @access private
	 * @var array
	 */
	public function theCount()
	{
		return $this->getCount();
	}


}

 ?>