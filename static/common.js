	xc_ajax = {};

	xc_ajax.xhr = function (method, url, data, check_function){
		url += url.indexOf('?') == -1 ? '?' : '&';
		url += Math.random();

		var xmlHttp  =  window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
		xmlHttp.open(method, url, true);
		xmlHttp.setRequestHeader('Request-Type','ajax');
		if (method == 'post') {
			xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
		}
		xmlHttp.send(data);
		xmlHttp.onreadystatechange = function(){
			if(xmlHttp.readyState == 4&&xmlHttp.status == 200) {
			check_function(xmlHttp.responseText);
			}
		}
	}

	xc_ajax.get = function (url,check_function){
		this.xhr('get', url, null, check_function);
	}

	xc_ajax.post = function (url,data,check_function){
		this.xhr('post', url, data, check_function);
	}