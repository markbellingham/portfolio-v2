// These are the variables; you can change these if you want
var expDays = 1;  // How many days to remember the setting
var stylesheetIds = ['1','2','3','4'];
var currentStyle = stylesheetIds[Math.floor(Math.random() * stylesheetIds.length)]; // Choose a random style sheet; this will be used when the user did not do anything.
var nameOfCookie = 'switchstyle'; // This is the name of the cookie that is used.
var urlToCSSDirectory = '/portfolio/css/'; // This is the URL to your directory where your .css files are placed on your site.  For example: http://www.seniorennet.be/URL_TO_STYLESHEET_DIRECTORY_OF_YOUR_SITE/

// These are the names of your different .css files; use the name exactly as on your Web site
var ScreenCSS_1 = 'style1.css';
var ScreenCSS_2 = 'style2.css';
var ScreenCSS_3 = 'style3.css';
var ScreenCSS_4 = 'style4.css';

/***********************************************************************************************

	DO NOT CHANGE ANYTHING UNDER THIS LINE, UNLESS YOU KNOW WHAT YOU ARE DOING

***********************************************************************************************/

// This is the main function that does all the work
function switchStyleOfUser(){
	var fontSize = GetCookie(nameOfCookie);
	if (fontSize == null) {
		fontSize = currentStyle;
    SetCookie(nameOfCookie, currentStyle)
	}

	if (fontSize == "1") { document.write('<link rel="stylesheet" type"text/css" href="' + urlToCSSDirectory + ScreenCSS_1 + '" media="screen">'); }
	if (fontSize == "2") { document.write('<link rel="stylesheet" type"text/css" href="' + urlToCSSDirectory + ScreenCSS_2 + '" media="screen">'); }
	if (fontSize == "3") { document.write('<link rel="stylesheet" type"text/css" href="' + urlToCSSDirectory + ScreenCSS_3 + '" media="screen">'); }
	if (fontSize == "4") { document.write('<link rel="stylesheet" type"text/css" href="' + urlToCSSDirectory + ScreenCSS_4 + '" media="screen">'); }

	var fontSize = "";
	return fontSize;
}

var exp = new Date();
exp.setTime(exp.getTime() + (expDays*24*60*60*1000));

// Function to get the settings of the user
function getCookieVal (offset) {
	var endstr = document.cookie.indexOf (";", offset);
	if (endstr == -1)
	endstr = document.cookie.length;
	return unescape(document.cookie.substring(offset, endstr));
}

// Function to get the settings of the user
function GetCookie (name) {
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	while (i < clen) {
		var j = i + alen;
		if (document.cookie.substring(i, j) == arg)
		return getCookieVal (j);
		i = document.cookie.indexOf(" ", i) + 1;
		if (i == 0) break;
	}
	return null;
}

// Function to remember the settings
function SetCookie (name, value) {
	var argv = SetCookie.arguments;
	var argc = SetCookie.arguments.length;
	var expires = (argc > 2) ? argv[2] : null;
	var path = (argc > 3) ? argv[3] : null;
	var domain = (argc > 4) ? argv[4] : null;
	var secure = (argc > 5) ? argv[5] : false;
	document.cookie = name + "=" + escape (value) +
	((expires == null) ? "" : ("; expires=" + expires.toGMTString())) +
	((path == null) ? "; path=/" : ("; path=/")) +
	((domain == null) ? "" : ("; domain=markbellingham.me")) +
	((secure == true) ? "; secure" : "");
}

// Function to remove the settings
function DeleteCookie (name) {
	var exp = new Date();
	exp.setTime (exp.getTime() - 1);
	var cval = GetCookie (name);
	document.cookie = name + "=" + cval + "; expires=" + exp.toGMTString();
}

// This function is used when the user gives his selection
function doRefresh(){
	location.reload();
}

// This will call the main function.  Do not remove this, because otherwise this script will do nothing...
document.write(switchStyleOfUser());
