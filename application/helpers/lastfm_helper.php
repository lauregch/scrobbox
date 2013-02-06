<?php


function get_api_key()
{
	return '2d8b5c9bcc8a883cb78e738cb0f569f2';
}

function get_api_secret()
{
	return 'b6f8765ec3cc1db020f81a36674eb465';
}


function get_session_key( $token )
{
	$key = false;

	$xml = call_api_method( 'auth.getSession', array('token'=>$token), true );
	if ( $xml == false )
	{
		echo 'There was an error parsing the XML file';
	}
	else
	{
		$status = $xml->attributes()->status;

		if ( strcmp( $status, "ok" ) != 0 )
		{
			echo 'There was a problem with the authentication : ';
			echo $xml->error;
		}
		else
		{
			$key  = $xml->session->key;
			$user = $xml->session->name;
		}
	}
	return array( $user, $key );
}


function get_user_info( $username )
{
	$xml = call_api_method( 'user.getInfo', array( 'user' => $username ) );
	$url = $xml->user->url;
	$icon = $xml->user->image[1];
	return array( $url, $icon );
}


function get_track_info( $artist, $track, $username )
{
	$xml = call_api_method( 'track.getInfo', array(	'artist' => $artist,
													'track' => $track,
													'username' => $username ) );
	$url = $xml->track->url;
	$icon = $xml->track->album[0]->image[0];
	$loved = $xml->track->userloved;
	return array( $url, $icon, $loved );
}


function get_artist_info( $artist, $username )
{
	$xml = call_api_method( 'artist.getInfo', array(	'artist' => $artist,
														'username' => $username ) );
	$url = $xml->artist->url;
	$icon = $xml->artist[0]->image[0];
	return array( $url, $icon );
}


function link_song( $artist, $artist_url, $track, $track_url, $css_class="" )
{
	$link = "";
	if ( ! empty($css_class) ) $link .= "<span class=\"$css_class\">";
	$link .= "<a href=\"$artist_url\">$artist</a> - <a href=\"$track_url\">$track</a>";
	if ( ! empty($css_class) ) $link .= "</span>";
	return $link;
}


function call_api_method( $method_name, $params, $needs_sig=false )
{
	$params['api_key'] = get_api_key();
	$params['method'] = $method_name;
	ksort( $params, SORT_STRING );

	if ( $needs_sig )
	{
		$str = '';
		foreach ( $params as $key => $value )
		{
			$str .= $key . utf8_encode($value);
		}
		$str .= utf8_encode( get_api_secret() );
		$api_sig = md5( $str );
		$params['api_sig'] = $api_sig;
	}

	$url = 'http://ws.audioscrobbler.com/2.0/';
	$first = true;
	foreach ( $params as $key => $value )
	{
		$url .= ( $first ? '?':'&' ) . $key . '=' . $value;
		$first = false;
	}
	//echo "xml url = $url";

	$xml = simplexml_load_file( $url );
	return $xml;
}




function artist_square_icon( $artist )
{
	$xml = call_api_method( 'artist.getInfo', array( 'artist' => $artist ) );
	
	// http://www.gerbenjacobs.nl/getting-the-square-version-of-similar-artist-images-through-just-one-rest-last-fm-api-call/
	$img = $xml->artist->image[1];
	$tab = explode( '/', $img );
	$i = array_search( 'serve', $tab );
	$tab[$i+1] = '64s';
	$img = implode( '/', $tab );

	return "<img src=\"$img\">";
}




?>