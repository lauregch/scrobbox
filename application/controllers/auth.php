<?php

class Auth extends Scrobbox_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('layout');
		$this->layout->set_theme( 'default' );
		$this->layout->set_titre( 'Scrobbox options' );

		$this->layout->add_js('ajax_lastfm');
		$this->layout->add_css( 'users_list' );

		$this->load->model('users_model');
		$this->load->model('scrobbles_model');

		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->helper('lastfm');

		$this->load->database();
	}


	public function index()
	{
		$error = $this->_check_add_user();
		// $this->_display_all( $error );
		$this->_display_light(); //TODO
	}


	public function test()
	{
		 return $this->_display_all();
	}


	private function _check_add_user()
	{
		$error = "";
		if ( $this->input->get() )
		{
			$token = $this->input->get('token');
			list( $user, $key ) = get_session_key( $token );
		
			if ( $this->users_model->already_exists($user) )
			{
				$error = $user . " is already authenticated.";
			}
			else if ( ! $this->users_model->add( $user, $key ) )
			{
				$error = "There was a problem adding " . $user . " to the list of registered users.";
			}
		}
		return $error;
	}



	private function _display_all()
	{
		$data = array();
		$users = array();

		$result = $this->users_model->all();
		foreach ( $result as $row )
		{
			$user = array();
		
			$name = $row->name;
			$user['name'] = $name;
		    
		    $user['active'] = $row->active;

		    list( $user_url, $user_icon ) = get_user_info( $name );
		    $user['url'] = $user_url;
		    $user['pic'] = $user_icon;
			
		    $last = $this->scrobbles_model->last_scrobble_by( $name );

		    if ( $last )
		    {			    
			    $user['last_scrobble']['song']['title'] = $last->track;
			    $user['last_scrobble']['artist']['name'] = $last->artist;
				
				$user['last_scrobble']['time_ago'] = strtolower(strstr(timespan(strtotime($last->date)), ',', true)) . ' ago';

				list( $artist_url, $artist_icon ) = get_artist_info( $last->artist, $name );
				list( $track_url, $track_icon )   = get_track_info( $last->artist, $last->track, $name );
				$user['last_scrobble']['artist']['url'] = $artist_url;
				$user['last_scrobble']['song']['url']  = $track_url;
			}

		    $users[] = $user;
		}
		
		$data['users'] = $users;
		// $data['error'] = $error;
		// var_dump($data);
		
		$this->layout->view( 'auth/users_list2', $data );
	}


	function _display_light()
	{
		$this->layout->view( 'auth/users_list', null );
	}


	public function delete_user()
	{
		// $this->users_model->delete( $user_name );

		// $this->_display_all();
		// $this->redirect_to_index();
		if ( $this->input->is_ajax_request() )
		{
			$username = $this->input->post('user');
			$this->users_model->delete( $username );
		}
	}

	public function ajax_get_users()
	{
		$res = $this->users_model->all();
		$users = array();
		foreach ( $res as $user )
		{
			//$users[] =('name') = $user->name;
			$users[] = array( 'name'=>$user->name, 'active'=>$user->active );
		}
		echo json_encode( $users );
	}

	public function ajax_user_active()
	{
		echo 'true';
	}

	public function ajax_delete_user()
	{
		$username = $this->input->post('username');
		$this->users_model->delete( $username );

		// $this->_display_all();
		// $this->redirect_to_index();
	}

	public function ajax_pause_scrobbling()
	{
		$active = $this->input->post('active');
		$username = $this->input->post('username');

		//$this->_scrobble( $username, $paused );
		$this->users_model->scrobble( $username, $active==1 ? false : true );
	}

	public function ajax_toggle_active()
	{
		$username = $this->input->post('username');
		$this->users_model->toggle_active( $username );
	}

	public function ajax_get_last_song()
	{
		$username = $this->input->post('username');
		$last = $this->scrobbles_model->last_scrobble_by( $username );
		if ( $last )
		{
			$last->date = time_ago( $last->date );
		}
		echo json_encode( $last );
	}

	public function activate( $user_id )
	{
		$this->_scrobble( $user_id, true );
	}

	public function deactivate( $user_id )
	{
		$this->_scrobble( $user_id, false );
	}

	private function _scrobble( $user_id, $b )
	{
		$this->users_model->scrobble( $user_id, $b );
		$this->_display_all();
		$this->redirect_to_index();
	}

}

?>