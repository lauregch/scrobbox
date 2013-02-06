<?php

if ( ! function_exists('time_ago') )
{
	function time_ago( $date )
	{
		$span = timespan( strtotime($date), time() );
		$pieces = explode( ',', $span );
		if ( $pieces == false ) return false;
		$span = trim( $pieces[0] );
		$span .= " ago";
		return strToLower( $span );
	}
}

?>