<?php

class Scrobbox_Controller extends CI_Controller
{
	function __construct()
	{
    	parent::__construct();
	}


	function redirect_to_index()
	{
		if ( $this->uri->total_segments() > 1 || $this->input->get() )
		{
			redirect( $this->uri->segment(1) );
			return true;
		}
		return false;
	}

	function method()
 	{
   		$method = 'undefined';
		if ( isset( $_SERVER['REQUEST_METHOD'] ) )
	    {
	    	$method = strToLower($_SERVER["REQUEST_METHOD"]);
		}
		return $method;
	}


	function method_is_get()
	{
		return ( $this->method() == 'get' );
	}
 
	function method_is_post()
	{
		return ( $this->method() == 'post' );
	}

}

?>