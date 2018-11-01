define(['./../Base/Base'], function(base) {
	var serverBaseUrl = "";
	var initManagers = [];
	var resizeManagers = [];
	
	var _global = {
		setServerBaseUrl : function(url) { serverBaseUrl = url; },
		getServerBaseUrl : function() { return serverBaseUrl; },
		addInitManager : function (initCallback) {
			initManagers.push(initCallback);
		},
		init : function () {
			for ( var i = 0; i < initManagers.length; i++ ) {
				if (typeof initManagers[i] !== 'function') { continue; }
        
				initManagers[i]();
			}
		},
		addResizeManager : function (resizeCallback) {
			resizeManagers.push(resizeCallback);
		},
		resize : function () {
			for ( var i = 0; i < resizeManagers.length; i++ ) {
				if (typeof resizeManagers[i] !== 'function') { continue; }
        
				resizeManagers[i]();
			}
		}		
	};
	
	return _global;
});