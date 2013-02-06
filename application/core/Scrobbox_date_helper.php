<?php

if ( ! function_exists('time_ago') )
{
	function time_ago( $date )
	{
		return timespan( strtotime($user['date']), time() );
	}
}

?>