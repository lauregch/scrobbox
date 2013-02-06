
function getXMLHttpRequest()
{
	var xhr = null;
	
	if (window.XMLHttpRequest || window.ActiveXObject)
	{
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			} catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		else {
			xhr = new XMLHttpRequest(); 
		}
	}
	else {
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return null;
	}
	return xhr;
}


function make_params_string( params )
{
	var str = '';
	var first = true;
	for ( var key in params )
    {
        if ( params.hasOwnProperty(key) )
        {
        	str += (first ?'':'&') + key + '=' + encodeURIComponent(params[key]);
        	first = false;
        }
    }
    return str;
}


function ajax_post( url, params, async, callback_fct )
{
	var str = make_params_string( params );
	var xhr = getXMLHttpRequest();

	xhr.onreadystatechange = function()
	{
		var done = 4, ok = 200;
		if ( xhr.readyState==done && ( xhr.status==ok || xhr.status==0 ) )
		{
			callback_fct( xhr );
		}
	};
	xhr.open( "POST", url, async );
	xhr.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" );
	xhr.send( str );
}


function keys(obj)
{
    var keys = [];
    for(var key in obj)
    {
        if(obj.hasOwnProperty(key))
        {
            keys.push(key);
        }
    }
    return keys;
}




//var params = { "un" : 1, "deux" : 2, "trois": 3 };
//http://crypto-js.googlecode.com/svn/tags/3.0.2/build/rollups/md5.js
function ajax_lastfm( method, params, async, needs_sig, result_node_name, callback_fct )
{
	params['api_key'] = '2d8b5c9bcc8a883cb78e738cb0f569f2';
	params['method'] = method;
	keys( params ).sort();

	if ( needs_sig )
	{
		str = '';
		for ( var key in params )
	    {
	        if ( params.hasOwnProperty(key) )
	        {
	        	str +=  key + encodeURIComponent( params[key] );
	        }
	    }
		str += encodeURIComponent( get_api_secret() );
		api_sig = md5( str );
		
		params['api_sig'] = api_sig;
	}

    ajax_post(	'http://ws.audioscrobbler.com/2.0/', params, async,
    			lastfm_callback(result_node_name, callback_fct) );
}



function lastfm_callback( result_node_name, callback_fct )
{
	return function ( xhr )
	{
		var xml = xhr.responseXML;
		var res = xml.getElementsByTagName(result_node_name)[0].childNodes[0].nodeValue;
		callback_fct( res );
	}
}


function lastfm_user_pic( username, async, callback )
{
	ajax_lastfm(	'user.getInfo',			// api method to call
					{'user':username},		// api method parameters
					async,
					false,					// api method needs signature
					'image',				// node to read in xml response
					callback				// callback function to call
				);
}

function lastfm_user_url( username, async, callback )
{
	ajax_lastfm(	'user.getInfo',			// api method to call
					{ 'user' : username },	// api method parameters
					async,
					false,					// api method needs signature?
					'url',					// node to read in xml response
					callback 			 	// callback to call
				);
}

function lastfm_artist_url( artist, async, callback )
{
	ajax_lastfm(	'artist.getInfo',
					{ 'artist' : artist },
					async,
					false,
					'url',
					callback 
				);
}

function lastfm_track_url( artist, track, async, callback )
{
	ajax_lastfm(	'track.getInfo',
					{ 'artist' : artist, 'track' : track },
					async,
					false,
					'url',
					callback 
				);
}



