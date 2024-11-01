function findGetParameter(parameterName) {
	var result = null,tmp = [];
	location.search.substr(1).split("&").forEach(function (item) {
		tmp = item.split("=");
		if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
		});
	return result;
	}

function YouTubeGetID (url) {
	var ID = '';
	url = url.replace(/(>|<)/gi,'').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
	if(url[2] !== undefined) {
		ID = url[2].split(/[^0-9a-z_\-]/i);
		ID = ID[0];
		}
	else {
		ID = url;
		}
	return ID;
	}
	
function sigFigs(n, sig) {
	var mult = Math.pow(10,
		sig - Math.floor(Math.log(n) / Math.LN10) - 1);
	return Math.round(Math.round(n * mult) / mult);
	}

function TS(v){
	var val = v.toString();
	var result = "";
	var len = val.length;
	while (len > 3){
	result = ","+val.substr(len-3,3)+result;
	len -=3;
	}
	return val.substr(0,len)+result;
	}
	
function validateEmail(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
	}