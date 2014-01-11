

/* from the pages of volax-tinos.gr */
function openWindow(URL,x,y) {
	x=x+15;
	y=y+25;
	if (x<screen.availWidth) {
		var nLeft,nTop;
		nLeft=(screen.availWidth-x)/2;
		nTop=(screen.availHeight-y)/2;
		newWindow = window.open(URL, "myNewWindow", 
			"toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=0, " + 
			"width="+x+", height="+y+", left="+nLeft+", top="+nTop);
	} else {
			newWindow = window.open(URL, "myNewWindow", 
				"toolbar=0, location=0, directories=0, status=0, menubar=0, scrollbars=1, resizable=1, " + 
				"width="+(screen.availWidth-30)+", height="+y+", left=15, top=100")
	}
	//newWindow.document.title="PocketBiz Demo"
}
