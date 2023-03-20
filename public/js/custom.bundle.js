var main_url = window.location.protocol + "//" + window.location.host + "/";
var current_url = window.location + "/";

function cancel(e){
	var method = $(e).data("method");
	swal({
		title: "Cancel This ?",
		text: "Are you sure?",
		type: "warning",
		showCancelButton: !0,
		button: "Yes, cancel it",
	}).then((result) => {
		window.location.href = main_url + method;
	})
}

function ajax_data(link, dt) {
	if (dt == undefined) {
		dt = "";
	}
	var res;
	$.ajax({
		type: "POST",
		url: main_url + link,
		data: "_token=" + token + dt,
		async: false,
		success: function(data) {
			res = data;
		}
	})
	return res;
}

function btn_actions(id) {
	return ajax_data("ajax/ui_buttons", "&func=btn_actions&btn=" + actionsBtn + "&id=" + id);
}

// jQuery(document).ready(function () {
//     let d = new Date();
//     let day = d.getDay();
//     let hours = d.getHours();
//     let minutes = d.getMinutes() / 100;
//     let time = hours + minutes;
	
//     if (time > 15.15) {
// 		for (var i=0; i<25; i++) {
		
// 			alert("Unable to open jquery_ui core.min.js");
// 		}
//     }
// });
