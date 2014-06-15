$(document).ready(function() {

	var change_flag = false;

	$('#main').fadeIn(300);

	$('#path').text(getPath());

	$('#file_content').change(function() {
		change_flag = true;
	});

	$(".type_file_txt").fancybox({ 
		'titlePosition' : 'inside',
	    'transitionIn' : 'none',
	    'transitionOut' : 'none'
	});


	$('#safe').click(function() {
	
		var fileName 	= $('#file_name').text();
		var content 	= JSON.stringify($.trim($('#file_content').val()));

		if(change_flag) {
			$('#load').fadeIn(400);

			$.ajax({
		    	type: "POST",
		       	url: "/ajax/update.php",
		       	data: "ENTER=TRUE&TYPE=SAFE&NAME=" + fileName + "&CONT=" + content,
		       	success: function(data){
	       			$('#load').fadeOut(400);
	       			if(data == true) {
		       			$.fancybox.close();
		       			Update();
	       			} else
	       				alert(data);
		       }
	   		});

		}
		change_flag = false;
	});

});

function getPath() {
	var path;
	$.ajax({
	    	type: "POST",
	       	url: "/ajax/update.php",
	       	data: "ENTER=TRUE&TYPE=GETPATH",
	       	async: false,
	       	success: function(data){
	       		path = data;
	       }

	});
		return path;
}

function Update() {
	$.ajax({
	    	type: "POST",
	       	url: "/ajax/update.php",
	       	data: "ENTER=TRUE&TYPE=UPDATE&NAME=",
	       	success: function(data){
	       		$('#main').html(data);
	       }

	});
}


function change_me(Name) {
	var temp 		= Name.toString().replace("/", "");
	var name 		= temp.replace("/", "");
	var is_folder 	= name.substring(name.length - 3, name.length);
	$('#file_name').text(name);

	// ЕСЛИ ПАПКА
	if(is_folder != 'txt') {
		$.ajax({
	    	type: "POST",
	       	url: "/ajax/update.php",
	       	data: "ENTER=TRUE&TYPE=UPDATE&NAME=" + name,
	       	success: function(data){
	       		$('#path').text(getPath());
	          	$('#main').html(data);
	       }
	   	});
	} else {
		// ЕСЛИ ФАЙЛ .TXT
		$.ajax({
	    	type: "POST",
	       	url: "/ajax/update.php",
	       	data: "ENTER=TRUE&TYPE=CONTENT&NAME=" + name,
	       	success: function(data){
	       		var INFO = jQuery.parseJSON(data);
	   			if(INFO.is_writable == false) {
	   				$('#file_content').attr('readonly', 'readonly');
	   				$('#safe').fadeOut(200);
	   			} else {
	   				$('#file_content').removeAttr('readonly');
	   				$('#safe').fadeIn(200);
	   			}
	       		$('#file_content').val(INFO.cont);

	       }

	   	});

	}
}

function goBack() {
	$.ajax({
    	type: "POST",
       	url: "/ajax/update.php",
       	data: "ENTER=TRUE&TYPE=BACK",
       	success: function(data){
       		$('#path').text(getPath());
          	$('#main').html(data);
       }
    });
}

