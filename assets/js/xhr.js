
function getXMLHttpRequest()
{
	var xhr = null;
	
	if (window.XMLHttpRequest || window.ActiveXObject)
	{
		if (window.ActiveXObject)
		{
			try
			{
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e) 
			{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		else
		{
			xhr = new XMLHttpRequest(); 
		}
	}
	else
	{
		alert("Votre navigateur ne supporte pas l'objet XMLHTTPRequest...");
		return null;
	}
	return xhr;
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
function ajax_lastfm( method, params, result_node_name, display_fct, needs_sig=false )
{
	params['api_key'] = get_api_key();
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
		str += encodeURIComponent( 'b6f8765ec3cc1db020f81a36674eb465' );
		api_sig = md5( str );
		
		params['api_sig'] = api_sig;
	}

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

	var xhr = getXMLHttpRequest();
	xhr.onreadystatechange = function()
	{
		if ( xhr.readyState==4 && ( xhr.status==200 || xhr.status==0 ) )
		{
			callback( xhr.responseXML, elem_in_xml, display_callback );
		}
	};
    xhr.open( "POST", 'http://ws.audioscrobbler.com/2.0/', true );
	xhr.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" );
	xhr.send( str );
}


// http://www.w3schools.com/dom/dom_nodes_get.asp
function ajax_read_data( xml, result_node_name, display_fct )
{
	var res = xml.getElementsByTagName( result_node_name )[0].childNodes[0].nodeValue;
	display_fct( res );
}


function get_user_info( username )
{
	ajax_lastfm( 'user.getInfo', {'user':username}, 'url', false );
}


onload="request(readData);"
