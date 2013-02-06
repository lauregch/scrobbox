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



	private function _display_all( $error )
	{
		$data = array();
		$users = array();

		$result = $this->users_model->all();
		foreach ( $result as $row )
		{
			$user = array();
			$name = $row->name;
			//list( $user_url, $user_icon ) = get_user_info( $name );

			$user['name']		= $name;
		    $user['active'] 	= $row->active;
		    //$user['url']		= $user_url;
		    //$user['icon_url']	= $user_icon;
			
		    // $last = $this->scrobbles_model->last_scrobble_by( $name );
		    // $user['has_last'] = $last;

		    // $user['profile_link_id'] = $name . "_link";
		    // $user['profile_pic_id']  = $name . "_pic";

		 //    if ( $last )
		 //    {
			//     $user["last_track"] =  $last->track;
			//     $user["last_artist"] = $last->artist;
			// 	$user["last_date"]  =  $last->date;

			// 	list( $artist_url, $artist_icon ) = get_artist_info( $last->artist, $name );
			// 	list( $track_url, $track_icon ) = get_track_info( $last->artist, $last->track, $name );
			// 	$user["last_artist_url"] = $artist_url;
			// 	$user["last_track_url"]  = $track_url;
			// }

		    $users[] = $user;
		}
		
		$data['users'] = $users;
		$data['error'] = $error;
		
		$this->layout->view( 'auth/users_list', $data );
	}


	function _display_light()
	{
		$this->layout->view( 'auth/users_list', null );
	}


	public function delete( $user_name )
	{
		$this->users_model->delete( $user_name );

		$this->_display_all();
		$this->redirect_to_index();
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