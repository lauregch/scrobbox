<?php

class History extends Scrobbox_Controller
{

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model( 'scrobbles_model' );
		$this->load->model( 'users_model' );

		$this->load->library('layout');
		$this->layout->set_theme( 'default' );
		$this->layout->set_titre( 'Scrobbox history' );
		
		$this->layout->add_css( 'history' );
		$this->layout->add_ext_js('http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js');
	}

	public function user_history( $username )
	{
		$data = array();
		$scrobbles = array();

		$last = $this->scrobbles_model->last_scrobbles_by( $username, 20 );
		foreach ( $last as $row )
		{
			$s = array();
			$s['id'] = $row->id;
			$s['artist'] = $row->artist;
			$s['track'] = $row->track;
			$s['date'] = $row->date;

			list( $track_url, $track_icon, $loved ) = get_track_info( $row->artist, $row->track, $row->user );
			list( $artist_url, $artist_icon ) = get_artist_info( $row->artist, $row->user );
			$s['track_icon'] = $track_icon;
			$s['track_url'] = $track_url;
			$s['artist_url'] = $artist_url;
			$s['track_loved'] = $loved;

			$scrobbles[] = $s;
		}
		$data['scrobbles'] = $scrobbles;
		$data['username'] = $username;

		$this->layout->view( 'history/history', $data );
	}


	public function delete( $scrobble_id )
	{
		//TODO http://www.last.fm/api/show/library.removeScrobble
		$this->scrobbles_model->delete( $scrobble_id );
	}


	public function _ajax_love_track()
	{
		$love_b   = $this->input->post('love_b');
		$track_id = $this->input->post('track_id');
	}


}

?>