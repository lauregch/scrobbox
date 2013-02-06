<?php

class Scrobbox_Controller extends CI_Controller
{
	public function MyController()
	{
    	parent::CI_Controller();
    	$this->load->library( 'uri' );
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