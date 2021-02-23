function ajax(url, elementId, callback) {
    var r = new XMLHttpRequest();
    r.onreadystatechange = function() {
    	if (r.readyState == 4) {
    		var ap = document.getElementById(elementId);
        	ap.innerHTML = r.responseText;
        	
        	if (callback)
        		callback();
        }
    };
    r.open("GET", url, true);
    r.send();
}
function clear(elementId) {
	var el = document.getElementById(elementId);
	el.innerHTML = '&nbsp;';
}
function show(elementId) {
	var el = document.getElementById(elementId);
	el.style.display = 'block';
}
function hide(elementId) {
	var el = document.getElementById(elementId);
	el.style.display = 'none';
}
function toggle(elementId) {
	var el = document.getElementById(elementId);
	if (el.style.display == 'none')
		el.style.display = 'block';
	else
		el.style.display = 'none';
}
function openAdminPanel() {
	clear('admin-panel');
	show('admin-panel');
	// hide('admin-button');
	ajax('inc/admin_handler.php?a=panel&sender=' + document.location.href, 'admin-panel');
}
function callAdminHandler(params, callback) {
	var url = 'inc/admin_handler.php?sender=' + document.location.href;
	for (key in params)
		url += '&' + key + '=' + params[key];
	clear('admin-work-area');
	ajax(url, 'admin-work-area', function(){
		show('admin-work-area');
		if (callback)
			callback();
	});
}
function editor() {
	tinyMCE.init({
		mode : "textareas",
        theme_advanced_buttons1 :
        	"bold,italic,|," +
        	"justifyleft,justifycenter,justifyright,justifyfull,|," + 
        	"formatselect,forecolorpicker,backcolorpicker,|," + 
        	"bullist,numlist,sub,sup,strikethrough,outdent,indent,|," + 
        	"undo,redo,|," +
        	"link,unlink,anchor,image,charmap,separator," + 
        	"cleanup,removeformat,code",
        theme_advanced_buttons2 : "",
        theme_advanced_buttons3 : "",
        theme_advanced_blockformats : "p,h1,h2,h3,h4,h5,h6,blockquote,address,code,div",
        entity_encoding: 'raw', 
	});
}

