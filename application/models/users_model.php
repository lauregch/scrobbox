<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *	Users_model
 *
 *		ajouter_news($auteur, $titre, $contenu)
 *		editer_news($id, $titre = null, $contenu = null)
 *		supprimer_news($id)
 *		count($where = array())
 *		liste_news($nb = 10, $debut = 0)
 */

class Users_model extends CI_Model
{
	protected $table = 'users';

	/**
	 *	Ajoute une clé de session.
	 *
	 *	@param string $user 	Le login de l'utilisateur lastfm
	 *	@param string $date 	La date d'ajout de la clé
	 *	@param string $key 		La clé
	 *	@param bool   $scrobble	Scrobble actif
	 *	@return bool			Le résultat de la requête
	 */

	public function add( $username, $key )
	{
		$this->db->set( 'name', "$username" );
		$this->db->set( 'key',  "$key" );
		$this->db->set( 'active', true );
		$this->db->set( 'date', $this->db->nowstring, false );
		return $this->db->insert( $this->table );
	}
	
	/**
	 *	Supprime une clé.
	 *	
	 *	@param integer $id	L'id de la clé à modifier
	 *	@return bool		Le résultat de la requête
	 */
	public function delete( $name )
	{
		return $this->db->where( 'name', $name )
				->delete( $this->table );
	}


	public function scrobble( $username, $b )
	{
		$data = array( 'active'=>$b );

		$this->db->where( 'name', $username );
		$this->db->update( $this->table, $data ); 
	}

	public function toggle_active( $username )
	{
		// $this->db->update( $this->table, array('active'=>'NOT \'active\'') )
		// 		->where( 'name', $username );

		$active = $this->db->where( 'name', $username )
				->from( $this->table )
				->get()->row()->active;
		
		$this->db->update( $this->table , array('active'=>!$active), array('name'=>$username) );

		echo ( $active ? 'inactive' : 'active' );

		// $data = array( 'active'=>$b );

		// $this->db->where( 'name', $username );
		// $this->db->update( $this->table, $data ); 
	}
	
	/**
	 *	Retourne le nombre de clés.
	 *	
	 *	@param array $where	Tableau associatif permettant de définir des conditions
	 *	@return integer		Le nombre de clés satisfaisant la condition
	 */
	public function count( $where = array() )
	{
		return (int) $this->db->where($where)
				      ->count_all_results( $this->table );
	}
	
	/**
	 *	Retourne une liste de $nb dernières clés.
	 *	
	 *	@param integer $nb		Le nombre de clés
	 *	@param integer $debut	Nombre de clés à sauter
	 *	@return objet			La liste de clés
	 */
	
	public function list_users( $nb = 10, $debut = 0 )
	{
		return $this->db->select('*')
				->from( $this->table )
				->limit( $nb, $debut )
				->order_by( 'id', 'desc' )
				->get()
				->result();
	}

	public function already_exists( $username )
	{
		//$count = $this->db->query( "SELECT COUNT(*) FROM " . $this->table . " WHERE user = " . $user );
		$nb = $this->count( array('name'=>"$username") );
		return ( $nb > 0 );
	}


	public function all()
	{
		$query = $this->db->get( $this->table );
		return $query->result();
	}



}


/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */