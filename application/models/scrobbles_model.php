<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scrobbles_model extends CI_Model
{
	protected $table = 'scrobbles';


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	public function last_scrobbles_by( $username, $nb )
	{
		//echo $user_id . <br>;
		// $q = $this->db->get_where( $this->table, array('user'=>"$user_id"), $nb );
		// return $q->result();

		// $query = $this->db->where( "user", $user_id )
		// 				->order_by( "date", "desc" )->limit( $nb );
		// return $query->get( $this->table );

		return $this->db->where( 'user', $username )
				->from( $this->table )
				->limit( $nb )
				->order_by( 'date', 'desc' )
				->get()
				->result();
	}


	public function last_scrobble_by( $username )
	{
		$query = $this->db->where( "user", $username )
				->order_by( "date", "desc" )->limit(1);
		$result = $query->get( $this->table );

		if ( $result->num_rows() > 0 )
		{
			return $result->row();
		}
		return false;
	}


	public function delete( $id )
	{
		return $this->db->delete( $table, array('id' => $id) );
	}

}


/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */