baseurl = "manage.php" 

$.ajaxSetup ({
    // Disable caching of AJAX responses
    cache: false
});

$(document).ready(function() {
	devices = getDevices();
});

function getDevices() {
	$.getJSON(baseurl + "?command=devices")
	.done(function(data) {
		for (i=0;i<data.length;i++) {
			drawDevice(data[i]);
		}
	})
	.fail(function(xhr, textStatus, errorThrown) {
		console.log(xhr.responseText);
	});

}

function drawDevice(device) {
	var on = false;
	if (parseInt(device.Basic) > 0) on = true;
	var title = "<h1>" + device.id + ": " + device.name + "</h1>";
	var toggle = '<div id=' + device.id + ' class="tog toggles toggle-modern" data-toggle-on="'+on+'" style="display: inline-block;" data-toggle-height="48" data-toggle-width="110"></div>';
	$('#devices').append(title);
	$('#devices').append(toggle);
	$('#devices').append("<hr/>");
	$('#'+device.id).toggles();
	$('#'+device.id).css('position','absolute');
	$('#'+device.id).on('toggle', function (e, active) {
		if (active) doCommand("on",device.id,device.type);
		if (!active) doCommand("off",device.id,device.type);
	});
	
}

function doCommand(command,id,type) {
	level = 0;
	if (command == "on") {
		level = 100;
	}
	$.post("manage.php", { "command": "control", "node": id, "type": type, "level": level })
        .done(function(data) {
		console.log(data);
	})
	.fail(function(xhr, textStatus, errorThrown) {
		console.log(xhr.responseText);
	});
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
