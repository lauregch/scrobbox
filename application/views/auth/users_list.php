


<div class="actions">
	Options > <a href="<?=current_url()?>">Users</a>
</div>

<div id="users"></div>

<div class="actions">
	<a href="http://www.last.fm/api/auth/?api_key=<?= get_api_key() ?>&cb=<?=current_url()?>">Add a user</a>
</div>




<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript">

$( function() {
	load_users_list();
});


function update_src_in_tag( tag ) {
	return function( url ) {
		tag.src = url;
	}
}

// on va appeler update_href_in_id(url)
function update_href_in_id( id ) {
	return function( url ) {
		document.getElementById( id ).href = url;
	}
}

function update_href_in_tag( tag, username ) {
	return function( url ) {
		tag.innerHTML = username;
		tag.href = url;
	}
}


function load_user_url( username ) {
	var tag = document.querySelector( '#user_' + username + ' .status .profile_link' );
	if ( tag != null ) {
		async = true;
		lastfm_user_url( username, async, update_href_in_tag( tag, username ) );
	}
}

function load_user_pic( username )
{
	var tag = document.querySelector( '#user_' + username + ' .pic img' );
	if ( tag != null ) {
		async = true;
		lastfm_user_pic( username, async, update_src_in_tag(tag) );
	}
}



function load_last_song( username ) {
	ajax_post(	"<?= site_url('auth/ajax_get_last_song') ?>",
				{ username : username },
				true,
				function( resp ) {
					var pre = "#user_" + username + " .status";
					//var last_tag = document.querySelector( pre + " span[class='last_song']" );
					var tag = document.querySelector( '#user_' + username + ' .status' );
					
					var last_tag = document.createElement('span');
					last_tag.className = 'last_song';

					// alert( resp.responseText );
					var song = JSON.parse( resp.responseText );
					if ( song ) {
						//alert(song['artist']);
						var async = false;
						lastfm_artist_url(	song['artist'], async,
											function(str) { song['artist_url'] = str; } );
						lastfm_track_url(	song['artist'], song['track'], async,
											function(str) { song['track_url'] = str; } );

						last_tag.appendChild( document.createTextNode(' and scrobbled ') );
 						artist = document.createElement('a');
 						artist.href = song['artist_url'];
 						artist.innerHTML = song['artist'];
						last_tag.appendChild( artist );
						last_tag.appendChild( document.createTextNode(' - ') );
						track = document.createElement('a');
 						track.href = song['track_url'];
 						track.innerHTML = song['track'];
						last_tag.appendChild( track );
						last_tag.appendChild( document.createTextNode( ' ' + song['date'] ) );
						last_tag.appendChild( document.createTextNode('.') );

						//last_tag.style.visibility = 'visible';
					}
					else {
						last_tag.appendChild( document.createTextNode(' and did not scrobble anything yet.') );
						//last_tag.style.visibility = 'hidden';
					}
					tag.appendChild(last_tag);
					
				}
			);
}



function prepare_switch_active_user( name ) {
	var pause_tag = document.querySelectorAll( "#user_" + name + " .actions > a" )[0];
	pause_tag.onclick = function() {
		ajax_post(	"<?= site_url('auth/ajax_toggle_active') ?>",
					{ username : name },
					true,
					function( resp ) {
						//alert('resp toggle');
						var str = resp.responseText;
						if ( str=='active' || str=='inactive' ) {
							var pre = "#user_" + name + " .status";
							var active_tag = document.querySelector( pre + " span[class$='active']" );
							var last_tag   = document.querySelector( pre + " span[class='last_song']" );

							active_tag.innerHTML = str;
							active_tag.className = str;
							//last_tag.style.visibility = ( (str=='active') ? 'visible' : 'hidden' );
							pause_tag.innerHTML = ( (str=='active') ? 'Pause' : 'Resume' ) + ' scrobbling';
						}


						// var active = (resp.responseText=='active');
						// var tag = document.querySelector( '#user_' + name + ' .status' );

						// tag.appendChild( document.createTextNode(' is ') );
						// var activetag = document.createElement( 'span' );
						// activetag.className = ( active ? 'active' : 'inactive' );
						// activetag.innerHTML = ( active ? 'active' : 'inactive' );
						// tag.appendChild( activetag );
						// /*if (active)*/ load_last_song( name );
						// //if (!active) tag.appendChild( document.createTextNode('.') );
					}
				);
	};
}


function prepare_delete_user( name ) {
	var delete_tag = document.querySelectorAll( "#user_" + name + ' .actions > a' )[2];

	delete_tag.onclick = function() {
		ajax_post(	"<?= site_url('auth/ajax_delete_user') ?>", { username : name },
			true,
			function( resp ) {
				var user_tag = document.querySelector( '#user_' + name );
				user_tag.parentNode.removeChild(user_tag);
			} );
		//alert( 'delete ' + name );
		// ajax_post(	"<?= site_url('auth/ajax_delete_user') ?>",
		// 			{ username : name },
		// 			true,
		// 			function( resp )
		// 			{
		// 				var str = resp.responseText;
		// 				if ( str=='active' || str=='inactive' )
		// 				{
		// 					var pre = "#user_" + name + " .status";
		// 					var active_tag = document.querySelector( pre + " span[class$='active']" );
		// 					var last_tag   = document.querySelector( pre + " span[class='last_song']" );

		// 					active_tag.innerHTML = str;
		// 					active_tag.className = str;
		// 					last_tag.style.visibility = ( (str=='active') ? 'visible' : 'hidden' );
		// 					pause_tag.innerHTML = ( (str=='active') ? 'pause' : 'resume' ) + ' scrobbling';
		// 				}
		// 			}
		// 		);
	};
}


function load_user_active( username ) {
	ajax_post(	"<?= site_url('auth/ajax_user_active') ?>",
		{ user: username },
		true,
		function ( resp ) {
			if ( resp=='true' ) {
				var tag = document.querySelector( '#user_' + username + ' .status' );
				tag.appendChild( document.createTextNode(' is active') );
			}
		} );
}


function load_users_list() {
	$.ajax( {
			url : "<?= site_url('auth/ajax_get_users') ?>",
			type : 'post',
			async : true
		} ).complete( function(resp) {
			//alert( resp.responseText );
			var users = JSON.parse( resp.responseText );
			
			for ( var i=0 ; i<users.length ; i++ ) {
				var user = users[i];
				var name = user['name'];
				var active = (user['active']==1);

				var userdiv = document.createElement('div');
			    userdiv.id = 'user_' + name;
			   
			    var picdiv = document.createElement('div');
			    picdiv.className = 'pic';

			    var statusdiv = document.createElement('div');
			    statusdiv.className = 'status';
			
			    var imgtag = document.createElement('img');

			    var profile_link = document.createElement('a');
			    profile_link.className = 'profile_link';
			   

			    document.getElementById('users').appendChild( userdiv );
			    picdiv.appendChild( imgtag );
			    statusdiv.appendChild( profile_link );
			    // statusdiv.appendChild( document.createTextNode(" is ") );
				userdiv.appendChild( picdiv );
				userdiv.appendChild( statusdiv )


				load_user_url( name );
				load_user_pic( name );
				
				//load_user_active( name );

				////
				var tag = document.querySelector( '#user_' + name + ' .status' );

				tag.appendChild( document.createTextNode(' is ') );
				var activetag = document.createElement( 'span' );
				activetag.className = ( active ? 'active' : 'inactive' );
				activetag.innerHTML = ( active ? 'active' : 'inactive' );
				tag.appendChild( activetag );
				/*if (active)*/ load_last_song( name );
				//if (!active) tag.appendChild( document.createTextNode('.') );
				var last_tag = document.createElement('span');
				/////


				var user_actions = document.createElement('div');
				user_actions.className = 'actions';

				var toggle_active = document.createElement('a');
				toggle_active.href = '#';
				toggle_active.innerHTML = ( active ? 'Pause scrobbling' : 'Resume scrobbling' );

				user_actions.appendChild( toggle_active );
				userdiv.appendChild( user_actions );

				prepare_switch_active_user( name );


				var history = document.createElement( 'a' );
				history.innerHTML = 'see history';
				history.href = '/scrobbox/history/' + name;

				user_actions.appendChild( document.createTextNode(', ') );
				user_actions.appendChild( history );

				var revoke = document.createElement( 'a' );
				revoke.innerHTML = 'revoke';
				revoke.href = '#';

				user_actions.appendChild( document.createTextNode(' or ') );
				user_actions.appendChild( revoke );
				user_actions.appendChild( document.createTextNode('.') );

				prepare_delete_user( name );
			}
		} );
}


</script>

