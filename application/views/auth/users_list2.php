

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?= site_url('assets/js/jquery.tmpl.min.js') ?>"></script>

<!-- <div class="actions">
	Options > <a href="<?=current_url()?>">Users</a>
</div>

<div id="users"></div>

<div class="actions">
	<a href="http://www.last.fm/api/auth/?api_key=<?= get_api_key() ?>&cb=<?=current_url()?>">Add a user</a>
</div> -->


<div class="actions">
	Options > <a href="<?=current_url()?>">Users</a>
</div>

<div id="users"></div>

<div class="actions">
	<a href="http://www.last.fm/api/auth/?api_key=<?= get_api_key() ?>&cb=<?=current_url()?>">Add a user</a>
</div> 

<script id="users_tmpl" type="text/x-jquery-tmpl"> 
	{{if users.length==0}}
	 	<p>There is no registered user yet.</p>
	{{else}}
	 	{{each(i,user) users}}
	    	<div class="user {{if user.active==1}}active{{else}}inactive{{/if}}" data-user="${user.name}">
	    		<div class="pic"><img src="${user.pic}"></div>
				<div class="status">
					<a href="${user.url}">${user.name}</a> is
					<span class="state">{{if user.active==1}}active{{else}}inactive{{/if}}</span>
					<span class="last-scrobble">
					{{if user.active==1}}
						{{if user.last_scrobble}}
							and scrobbled <a href="${user.last_scrobble.artist.url}">${user.last_scrobble.artist.name}</a> - 
							<a href="${user.last_scrobble.song.url}">${user.last_scrobble.song.title}</a>
							${user.last_scrobble.time_ago}.
						{{else}} and did not scrobble anything yet.
						{{/if}}
					{{/if}}
					</span>
				</div>
				<div class="actions">
					<a href="#" class="switch-btn">{{if user.active==1}} Pause {{else}} Resume {{/if}} scrobbling</a>,
					<a href="<?= site_url('history/${user.name}') ?>">see history</a>, or <a href="#" class="delete-btn">revoke</a>.
				</div>
			</div>
	  	{{/each}}
	{{/if}}
</script>





<script type="text/javascript">


$( function() {
	
var json = { "users" : <?= json_encode($users); ?> };
$('#users_tmpl').tmpl( json ).appendTo( '#users' );


$('.delete-btn').click( function() {
	// alert( $(this).closest('#user').data('user') );
	var wrapper = $(this).closest('.user');
	var username = wrapper.data('user');
	$.ajax( {
			url : "<? site_url('auth/delete_user') ?>",
			data : { user : username },
			type : 'post'
		}).complete( function() {
				alert('done');
				wrapper.slideUp( { duration : 1000, queue : false });
				wrapper.fadeOut( { duration : 1000, queue : false,
					complete : function() { $(this).remove(); }
				});
			});
});


$('.switch-btn').click( function() {
	var wrapper = $(this).closest('.user');
	var username = wrapper.data('user');
	$.ajax( {
		url : '',
		data : { user : username },
		type : 'post'
	}).complete( function() {
		alert( 'complete' );
		wrapper.toggleClass( 'active inactive' );
		wrapper.trigger( 'stateChanged' );
		// wrapper.find('.last-scrobble').toggle();
		// wrapper.toggle(
		// 	function() {
		// 		alert('on');
		// 		$(this).find('.state').class = 'active';
		// 		$(this).find('.switch-btn').text( 'Pause scrobbling' );
		// 		$(this).find('.last-scrobble').show();
		// 	},
		// 	function() {
		// 		alert('off');
		// 		$(this).find('.state').class = 'inactive';
		// 		$(this).find('.switch-btn').text( 'Resume scrobbling' );
		// 		$(this).find('.last-scrobble').hide();
		// 	});
	});
});

$('.user').bind( 'stateChanged', function() {
	$(this).find('.last-scrobble').toggle();
	$(this).find('.switch-btn').text( $(this).hasClass('active') ? 'Pause scrobbling' : 'Resume scrobbling' );
	$(this).find('.state').text( $(this).hasClass('active') ? 'active' : 'inactive' );
});



});




</script>

