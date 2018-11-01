define(['jquery', './../Global/Global'], function($, global){
	
	
	
	
	function _requestFn(url, data, successCallback, errorCallback,keepData)
	{
		var settings = {
			url: global.getServerBaseUrl() + url,
			type: 'post',
			dataType: 'JSON',
			data : data,
			success: function(data, textStatus, jqXHR) {
				if (data.state == 'success') {
					successCallback && successCallback(data.msg,keepData);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				errorCallback && errorCallback(textStatus);
			}
		};
			
		$.ajax(settings);
	}
	
	/******************************************************************************
	 * Desc: 统一的返回后台执行错误的信息
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _resultErrorInfo(error) {
	
		alert("出错:" + JSON.stringify(error));
	}
	
	return {
		requestFn : _requestFn,
		resultError : _resultErrorInfo
		
	};
});