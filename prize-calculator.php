<?php namespace Tony\Spinner;
use \Tony\Prizes\Prizes;
use \Tony\Prizes\Prize;
/**
 * The file that determine the winning chance and return the winning prize.
 *
 *
 * @link    http://anthonyho-007.com
 * @since   1.0.0
 *
 * @package Tony\Spinner
 */

 /**
 * The Prize winnging class.
 *
 * This is used to the winning chance and the prize returning 
 *
 *
 * @since   1.0.0
 * @package Tony\Spinner
 * @author  Anthony Ho <anthonyho007@gmail.com>
 */
class StartSpinning 
{
	/**
	 * The predetermined/initialized winnging rate that is asociated with the pool of the prize
	 *
	 * @since     1.0.0
	 * @access    private
	 * @var       int       $winning_rate    It gives the rate in decimal
	 */
	private $winning_rate;


	/**
	 * The array that gets the info of the prize pool
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       array        $prizes    The prizes available for spinning of the ads
	 */
	protected $prizes;

	/**
	 * The array that gets the info of the prize
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       array        $prize    The prize available for spinning of the ads
	 */
	protected $prize;

	/**
	 * The value that store the prize won
	 *
	 * @since     1.0.0
	 * @access    protected
	 * @var       array        $prize    The prize available for spinning of the ads
	 */
	protected $prizeWon;

	/**
	 * Initialize the spinning function
	 *
	 * @since     1.0.0
	 * @access    public 
	 */
	public function __construct($winning_rate)
	{	
		$this->winning_rate = floatval($winning_rate);
		$this->prizes = new Prizes();
	}

	/**
	 * A function that return the end result of the spin
	 * It return either a prize or empty object
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function returnResult()
	{
		if ($this->prizes->remainingPrizes()){
			if ($this->determineWinning()) {
				return $this->determinePrize();
			} else {
				return (object) [];
			}
		} else {
			return (object) [];
		}
	}


	/**
	 * A function that calculate the global winning rate
	 * It return a float between 0 to 1 
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function calcGlobalWinngingRate ()
	{
		$global_winning_rate = ($this->winning_rate) + ((1 - $this->winning_rate)*($this->timeLapseFactor()));
		return $global_winning_rate;
	}


	/**
	 * A function that determine if the player win any prize or not 
	 * and return true or false
	 *
	 * @since     1.0.0
	 * @access    public
	 * 
	 */
	public function determineWinning ()
	{	
		if (($this->random_zero_one()) <= ($this->calcGlobalWinngingRate())) {
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * A function that determine which prize is won
	 *
	 * @since     1.0.0
	 * @access    protected
	 */
	public function determinePrize ()
	{
		$rand = $this->random_zero_one(); // output less than 1
		$total = $this->totalPrizeFactor(); // make chance ends at 1
		$chance = 0;
		foreach ($this->prizes->getPrizes() as $prize) {
			$chance += ($prize->getQuantity()) * ($this->prizeFactor($prize)) / $total;
			
			if ($rand <= $chance) {
				$prize->updateQuantity();
				print_r($prize->getQuantity());
				return $prize;
			}
		}
	}

	/**
	 * A function that calculate the time lapse factor
	 *
	 * @since     1.0.0
	 * @access    public
	 * @param 	  timeLapse	 The factor that calculate the remaining time against the
	 * 						  the total time and fit it against a exponential curve
	 */
	public function timeLapseFactor () 
	{
		$timeLapse = 0;
		$prizes = ($this->prizes->getPrizes());
		foreach ($prizes as $prize){
			$RT = $prize->timePassed();
			$TT = $prize->totalTime();
			//print_r($TT);
			$timeLapse += floatval( 1 - exp(-(3*$RT/$TT)));
		}
		//print_r($timeLapse);
		$sumPrize = $this->prizes->getCount();
		return $timeLapse / $sumPrize;
	}

	/**
	 * A random number generator for determine the winning rate from 0 to 1 
	 * with two decimal
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function random_zero_one ()
	{
		$rand = floatval(rand(0, 100)/ 100) ;
		return $rand;
	}

	/**
	 * A function that return total prize factor
	 *
	 * @since     1.0.0
	 * @access    public 
	 */
	public function totalPrizeFactor () {
		$sumPrizeFactor = 0;
		foreach ($this->prizes->getPrizes() as $prize){
			$prizeFactor = ($prize->getQuantity()) * ($this->prizeFactor($prize));
			$sumPrizeFactor += $prizeFactor;
		}
		return $sumPrizeFactor;
	}

	/**
	 * A function that return individual prize factor
	 *
	 * @since     1.0.0
	 * @access    public
	 */
	public function prizeFactor($prize)
	{
		$RT = $prize->remainingTime();
		$TT = $prize->totalTime();
		$result = floatval(2 - exp(-(3*$RT/$TT)));
		return $result;
	}

	/**
	 * Returns the total number of the prizes remaining
	 *
	 * @access public 
	 * @var integer
	 */
	public function remainingPrizes()
	{
		return $this->prizes->remainingPrizes();
	}
}	
