<?php namespace Tony\Prizes;

use \Tony\Prizes\Prizes;

/**
 * The Prize post factory
 *
 * A class that organize prize into class object
 *
 * @link    http://wwww.anthonyho-007.com
 * @since   1.0.0
 *
 * @package Tony\Prizes
 */

/**
 * Prize class that organize 
 *
 * Helpers functions for the prizes 
 *
 * @since   1.0.0
 * @package Tony\Prizes
 * @author  Anthony Ho <anthonyho007@gmail.com>
 */
class Prize 
{
	/**
	 * Prize object
	 *
	 * @since     1.0.0
	 * @var       \WP_Post      
	 */
	protected $post;

	/**
	 * Looks up the parent post of the current page (if any).
	 *
	 * @access public
	 * @param \WP_Post|null $post
	 */
	public function __construct($post = null)
	{
		if ($post instanceof \WP_Post) {
			$this->post = $post;
		} elseif (is_numeric($post)) {
			$this->post = get_post($post);
		}
	}

	/**
	 * Returns the ID of the current post.
	 *
	 * @access public
	 * @return integer
	 */
	public function getID()
	{
		return apply_filters('get_id', $this->post->ID);
	}

	/**
	 * Outputs the ID of the current post.
	 *
	 * @access public
	 */
	public function theID()
	{
		echo apply_filters('the_id', $this->getID());
	}

	/**
	 * Return the formatted title of the post.
	 *
	 * @access public
	 */
	public function getTitle()
	{
		return apply_filters('get_title', $this->post->post_title);
	}

	/**
	 * Outputs the the title of the current post.
	 *
	 * @access public
	 */
	public function theTitle()
	{
		echo apply_filters('the_title', $this->getTitle());
	}

	/**
	 * Returns whether the post has content or not. Returns false if content
	 * doesn't exist or is empty.
	 *
	 * @access public
	 * @return boolean
	 */
	public function hasContent()
	{
		$content = $this->getContent();

		return apply_filters('has_content', ! empty($content));
	}

	/**
	 * Returns the formatted content of the post.
	 *
	 * @access public
	 * @return string
	 */
	public function getContent()
	{
		global $post;

		$post = $this->host;
		setup_postdata($post);
		$content = get_the_content();
		wp_reset_postdata();
		return apply_filters('get_content', $this->post->post_content);
	}

	/**
	 * Outputs the formatted content of the post.
	 *
	 * @access public
	 */
	public function theContent()
	{
		echo apply_filters('the_content', $this->getContent());
	}

	/**
	 * Returns true if the post has a thumbnail. Otherwise false.
	 *
	 * @access public
	 * @return boolean
	 */
	public function hasThumbnail()
	{
		return apply_filters('has_thumbnail', has_post_thumbnail($this->getID()));
	}

	/**
	 * Returns the post thumbnail url.
	 *
	 * @access public
	 * @param string $size The size of the image to return.
	 * @return string
	 */
	public function getThumbailUrl($size= 'thumbnail')
	{
		$id = get_post_thumbnail_id($this->getID());
		$attachment = wp_get_attachment_image_src($id, $size);
		return apply_filters('get_thumbnail_url', $attachment[0]);
	}



	/**
	 * Returns the start time of the prize
	 *
	 * @access public
	 * @return time
	 */
	public function startTime()
	{
		$startTime =strtotime(get_field('starttime', $this->getID()));
		return $startTime;
	}


	/**
	 * Returns the quantity of each prize from advance custom field
	 *
	 * @access public
	 * @return string
	 */
	public function getQuantity ()
	{
		$quantity = get_field('quantity', $this->getID());
		return apply_filters('get_quantity', $quantity);
	}

	/**
	 * Returns the update the quantity of prize each time it is being drawn
	 *
	 * @access public
	 * @return string
	 */
	public function updateQuantity()
	{
		$quan = $this->getQuantity();
		$quans = $quan - 1;
		update_field('quantity', $quans, $this->getID());
	}

	/**
	 * Returns the end time of the prize
	 *
	 * @access public
	 * @return time
	 */
	public function endTime ()
	{
		$end =strtotime(get_field('end_time', $this->getID()));
		return $end;
	}

	/**
	 * Returns the total time of the prize
	 *
	 * @access public
	 * @return time
	 */
	public function totalTime ()
	{
		$initial = $this->startTime();
		$final = $this->endTime();
		$total = ($final - $initial);
		return apply_filters('total_time', $total);
	}

	/**
	 * Returns the remaining time of the prize
	 *
	 * @access public
	 * @return time
	 */
	public function remainingTime ()
	{
		$now = strtotime(date("Y-m-d"));
		$final = $this->endTime();
		$rem = ($final - $now);
		if ($rem < 0 ){
			$rem = 0;
		}
		return apply_filters('remaining_time', $rem);
	}

	/**
	 * Returns the time passed of the prize
	 *
	 * @access public
	 * @return string
	 */
	public function timePassed ()
	{
		$initial = $this->startTime();
		$now = strtotime(date("Y-m-d"));
		//print_r($initial);
		$final = $this->endTime();
		$rem = $now - $initial;
		if ($now >= $this->totalTime()){
			$rem = $this->totalTime;
		}
		return apply_filters( 'time_passed',$rem);
	}


}