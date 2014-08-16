<?php  if ( ! defined('CDN_URL')) exit('No direct script access allowed');

	class StateConstants
	{
	    private static $initialized = false;

	    private static $states = array("Alabama","Alaska","Arizona","Arkansas",
							   "California","Colorado","Connecticut","Delaware",
							   "District of Columbia","Florida","Georgia","Hawaii",
							   "Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky",
							   "Louisiana","Maine","Maryland","Massachusetts","Michigan",
							   "Minnesota","Mississippi","Missouri","Montana","Nebraska",
							   "Nevada","New Hampshire","New Jersey","New Mexico","New York",
							   "North Carolina","North Dakota","Ohio","Oklahoma","Oregon",
							   "Pennsylvania","Rhode Island","South Carolina",
							   "South Dakota","Tennessee","Texas","Utah",
							   "Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming");

		private static $state_abbreviations = array("AL","AK","AZ","AR",
							   "CA","CO","CT","DE",
							   "DC","FL","GA","HI",
							   "ID","IL","IN","IA","KS","KY",
							   "LA","ME","MD","MA","MI",
							   "MN","MS","MO","MT","NE",
							   "NV","NH","NJ","NM","NY",
							   "NC","ND","OH","OK","OR",
							   "PA","RI","SC",
							   "SD","TN","TX","UT",
							   "VT","VA","WA","WV","WI","WY");

	    private static function initialize()
	    {
	    	if (self::$initialized)
	    		return;

	    	self::$initialized = true;
	    }

	    public static function abbreviation_to_full_name($state_abbr)
	    {
	    	self::initialize();
	    	$state_abbr = strtoupper($state_abbr);
	    	$match = array_search($state_abbr, self::$state_abbreviations);
	    	if ($match > -1)
	    	{
	    		$state = self::$states[array_search($state_abbr, self::$state_abbreviations)];
		    	return $state;
		    }
		    return false;
	    }
	}



?>