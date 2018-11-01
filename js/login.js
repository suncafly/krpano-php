var loginManagerModule = {
	
	module: {
		content: '2018-09-11',
		author: '李长明',
		name: '全景平台登录模块',
		version: '1.0.0.0',
		createdTime: '2018-09-11',
		updatedTime: '',
		desc: function() {
			return ['模块名：' + this.name + '\n', '版本：' + this.version + '\n', '创建时间：' + this.createdTime + '\n', '更新时间：' + this.updatedTime + '\n'].join('');
		}
	},
	
	Global: {
		managers: [],
	},
	SystemManager : {
		
	}
};

/******************************************************************************
 * Desc: 各管理器注册函数
 * 
 * @param 
 *		initCallback 注册器回调函数
 *
 * @return 
 *		void
 */

loginManagerModule.Global.registerManager = function(initCallback) {
	loginManagerModule.Global.managers.push(initCallback);
};

/******************************************************************************
 * Desc: 全局初始化函数，解析http查找字符串，初始化各管理器
 * 
 * @param 
 *		void
 *
 * @return 
 *		void
 */

loginManagerModule.Global.init = function() {
	
	for(var i = 0; i < this.managers.length; i++) {
		if(typeof this.managers[i] !== 'function') {
			continue;
		}

		this.managers[i]();
	}
};

loginManagerModule.SystemManager.loginHandler = {

	/******************************************************************************
	 * Desc: 统一的返回后台执行错误的信息
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	resultErrorInfo: function(error) {

		layer.msg("出错:" + JSON.stringify(error));
	},
	
	/******************************************************************************
	 * Desc: 统一的后台请求数据的接口
	 * 
	 * @param 
	 *		data 				需要传递的参数 
	 * 		url  				需要请求的php文件路径
	 * 		successCallback		成功之后的回调
	 * 		errorCallback		失败之后的回调
	 * 		keepData			转接参数   没有需要就不传递   TODO 李长明 2018-05-14 有些参数需要作为下一级回调函数的参数，只是作为参数继续传递下去
	 *
	 * @return 
	 *		void
	 */
	loginHandlerRequest: function(data, url, successCallback, errorCallback, keepData) {

		//参数校验
		function CheckVariable() {

			//数据对象校验
			if(data) {

				if(typeof data == "undefined" || typeof data != "object") {
					return;
				}
			}

			//url 校验
			if(typeof url == "undefined" || typeof url != "string" || url.length == 0) {
				return;
			}

			//返回回调函数校验	
			if(errorCallback) {

				if(typeof errorCallback != "function") {
					return;
				}
			}

			if(successCallback) {

				if(typeof successCallback != "function") {
					return;
				}
			}

		}

		//请求数据接口
		function requestData(data, url, successCallback, errorCallback) {

			var settings = {
				url: url,
				type: 'post',
				dataType: 'JSON',
				data: data,
				success: function(data, textStatus, jqXHR) {
					if(data.state == 'success') {
						successCallback && successCallback(data.msg, keepData);
					}
					if(data.state == 'error') {
						layer.msg(data.msg);
					}
				},
				error: function(error) {
					errorCallback && errorCallback(error);
				}
			};

			$.ajax(settings);
		}

		CheckVariable();
		requestData(data, url, successCallback, errorCallback);

	},

	/******************************************************************************
	 * Desc: 注册相关回调
	 * 
	 *
	 * @return 
	 *		void
	 */
	initHandler: function() {
		
		$(document).on('click','.loginin',function(evt) {
			
			window.location.href = 'index.html';
			
		});
		
	},

	/******************************************************************************
	 * Desc: 初始化数据唯一入口
	 * 
	 *
	 * @return 
	 *		void
	 */
	init: function() {
		
		//注册回调
	    loginManagerModule.SystemManager.loginHandler.initHandler();
		
	}
}


//注册初始化函数
loginManagerModule.Global.registerManager(loginManagerModule.SystemManager.loginHandler.init);



$(document).ready(function() {
	
	loginManagerModule.Global.init();

})