define(['jquery', 'layuiModule', 'bootstrap', './../Utils/Utils'], function($, layuiModule, bootstrap, utils) {

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.10
	 * 		author: 	李长明    
	 * 		info:		打开资源窗口的通用模块
	 * 
	 ************************************************************************************************/
	
	
	//当前需要访问的资源类型
	var openResourceType = '';
	
	//获取资源的自定义条件
	var beforeGetResourceCallBack = '';
	
	//确定选择之后的回调
	var successCallBack = '';
	
	//弹出框显示的title
	var title = '';
	
	//资源弹出框唯一Id
	var resourceIndex = '';
	
	
	/******************************************************************************
	 * Desc: 更新(全景资源)资源显示区域
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initPanoContentInfo(fileName, filePath, fileThumbPath, id, targetElem, layerId) {
	
		var html = "";
	
		html += '			<div class="panoResourceCurLayerInfo" data-resourceId=' + id + ' data-layerId=' + layerId + '>';
		html += '				<div class="panoResourceCurLayerInfoImg" data-resourceId=' + id + ' data-layerId=' + layerId + '>';
		html += '					<img src=' + fileThumbPath + ' alt=' + fileName + ' data-sourceThumbPath=' + fileThumbPath + ' data-sourceName=' + fileName + ' data-resourceId=' + id + ' data-layerId=' + layerId + ' style="width: 100%; height: 100%;">';
		html += '				</div>';
		html += '				<div class="ThumbItem_name_1J1HIB ellipsis">' + fileName + '</div>';
		html += '			</div>';
	
		$(targetElem).append(html);
	}
	
	/******************************************************************************
	 * Desc: 切换图层的时候拉取数据
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _displayCurResourceLayer(data, keepData) {
	
		if(typeof keepData == "undefined" || typeof data == "undefined") {
			return;
		}
	
	
		var layerId = keepData['layerId'];
	
		var curEntityClass = keepData['curEntityClass'];
	
		var targetElem = '#panoImgDisplayContentInfo .layer' + '#layer' + layerId;
	
		$(targetElem).empty();
	
		$.each(data, function(index, elem) {
	
			//文件名
			var fileName = elem['fileName'];
	
			//文件路径
			var filePath = elem['filePath'];
	
			//缩略图文件路径
			var fileThumbPath = elem['fileThumbPath'].substring(6);
	
			//数据库中对应资源信息id
			var id = elem['id'];
			
			_initPanoContentInfo(fileName, filePath, fileThumbPath, id, targetElem, layerId);
	
		});
	}
	
	 /******************************************************************************
	  * Desc: 获取全景资源图层 
	  * 
	  * @param 
	  *
	  * @return 
	  *		void
	  */
	 function _afterGetResourceLayerInfo(data, keepData) {
	 
	 	//参数校验
	 	if(typeof data == "undefined" || data.length == 0) {
	 		return;
	 	}
	 
	 	if(typeof keepData == "undefined" || typeof data == "undefined" || data.length == 0) {
	 		return;
	 	}
	 
	 	var curEntityClass = keepData['curEntityClass'];
	 	var title = keepData['title'];
	 
	 	var htmlLayerInfo = "";
	 	var htmlOptionLayerInfo = "";
	 	var htmlCardsLayerInfo = "";
	 
	 	$.each(data, function(index, elem) {
	 
	 		var id = elem['id'];
	 		var layerName = elem['layerName'];
	 
	 		//默认图层
	 		if(id == 1) {
	 
	 			htmlLayerInfo += '<li data-layerId = ' + id + ' class="selected" data-entityClass = ' + curEntityClass + '><a href="javascript:;"><span class="">' + layerName + '</span></a></li>';
	 			htmlCardsLayerInfo += '<div class="layer row selected"id="layer' + id + '"  data-entityClass = ' + curEntityClass + '></div>';
	 		} else {
	 			htmlLayerInfo += '<li data-layerId = ' + id + ' class="" data-entityClass = ' + curEntityClass + '><a href="javascript:;"><span class="">' + layerName + '</span></a></li>';
	 			htmlOptionLayerInfo += '<option value=' + id + '>' + layerName + '</option>';
	 			htmlCardsLayerInfo += '<div class="layer row" id="layer' + id + '" data-entityClass = ' + curEntityClass + '></div>';
	 		}
	 
	 	})
	 
	 	var html = '';
	 
	 	html += '<div class="row" id="resourceListInfo">';
	 	html += '	<div class="col-md-2 projectLayer">';
	 	html += '		<nav class="menu" data-toggle="menu">';
	 	html += '			<ul class="nav nav-primary">';
	 	html += htmlLayerInfo;
	 	html += '			</ul>';
	 	html += '		</nav>';
	 	html += '	</div>';
	 	html += '	<div class="col-md-9" >';
	 	html += '		<div class="row">';
	 	html += '			<div class="col-md-11">';
	 	html += '				<input type="text" id="pano_name" class="form-control" placeholder="资源名称">';
	 	html += '			</div>';
	 	html += '			<div class="col-md-1">';
	 	html += '				<button class="btn btn-info" onclick="">搜索</button>';
	 	html += '			</div>';
	 	html += '		</div>';
	 	html += '		<div class="row" id="panoImgDisplayContentInfo">';
	 	html += htmlCardsLayerInfo
	 	html += '		</div>';
	 	html += '		<div class="row ">';
	 	html += '			<div class="col-md-12" style="text-align:right">';
	 	html += '				<button class="btn btn-info choosedSourceBtn" style="width:150px" onclick="">确认</button>';
	 	html += '			</div>'
	 	html += '		</div>'
	 	html += '	</div>';
	 	html += '</div>';
	 
	 	//弹出全景资源窗口
	 	resourceIndex = layer.open({
	 		title: title,
	 		type: 1,
	 		skin: 'layui-layer-rim', //加上边框
	 		area: ['70%', '60%'], //宽高
	 		content: html
	 	});
	 
	 	//触发默认图层点击事件
	 	var elem = '#resourceListInfo .projectLayer nav.menu ul li :first ';
	 	
	 
	 	$(elem).trigger('click');
	 
	 }
	 
	/******************************************************************************
	  * Desc: 初始化弹出框监听事件 
	  * 
	  * @param 
	  *
	  * @return 
	  *		void
	  */
	 function _initHandlerSourceChoose() {

		//全景资源图层选择
	 	$(document).on('click', '#resourceListInfo .projectLayer nav.menu ul li', function(evt) {
	 	
	 		evt.preventDefault();
	 	
	 		var layerId = $(this).attr('data-layerid');
	 		
	 		var curEntityClass = $(this).attr('data-entityClass');
	 		
	 	
	 		$(this).siblings('li').removeClass('selected'); // 删除其他兄弟元素的样式
	 	
	 		$(this).addClass('selected');
	 	
	 		//控制只显示当前图层的信息
	 		$('#panoImgDisplayContentInfo .layer' + "#layer" + layerId).siblings('div').removeClass('selected');
	 	
	 		$('#panoImgDisplayContentInfo .layer' + "#layer" + layerId).addClass('selected');
	 	
	 		//请求当前图层下面的数据
	 	
	 		var keepData = {
	 	
	 			'layerId': layerId,
	 			'curEntityClass': curEntityClass,
	 		}
	 		
	 		
	 		utils.requestFn(
	 			"php/ResourceHandllerManager/ResourceManagerDispatcher.php", {
	 				'opCode': 'getCurLayerSourceInfo',
	 				'entityClass': curEntityClass,
	 				'layerId': layerId,
	 				'curEntityClass': curEntityClass,
	 			},
	 			_displayCurResourceLayer,
	 			utils.resultError,
	 			keepData
	 		);
	 	
	 	});
	 	
	 	//全景资源图层选择
	 	$(document).on('click', '#resourceListInfo #panoImgDisplayContentInfo .panoResourceCurLayerInfo .panoResourceCurLayerInfoImg img', function(evt) {
	 	
	 		evt.preventDefault();
	 		if($(this).hasClass('selected')) {
	 			$(this).removeClass('selected');
	 		} else {
	 			$(this).addClass('selected');
	 		}
	 	
	 	});
	 	
	 	$(document).on('click', '#resourceListInfo .choosedSourceBtn', function(evt) {
	 	
	 		evt.preventDefault();
	 		var selectedImginfoList = new Array;
	 	
	 		$('#panoImgDisplayContentInfo .panoResourceCurLayerInfo .panoResourceCurLayerInfoImg img').each(function() {
	 	
	 			if($(this).hasClass('selected')) {
	 	
	 				var resourceid = $(this).attr('data-resourceid');
	 				var layerid = $(this).attr('data-layerid');
	 				var sourceThumbPath = $(this).attr('data-sourceThumbPath');
	 				var sourceName = $(this).attr('data-sourceName');
	 	
	 				infoList = {
	 					'resourceid': resourceid,
	 					'layerid': layerid,
	 					'sourceThumbPath': sourceThumbPath,
	 					'sourceName': sourceName,
	 				};
	 				selectedImginfoList.push(infoList);
	 			}
	 		});
	 	
	 		if(selectedImginfoList.length == 0) {
	 			
	 		} else {
	 	
	 			successCallBack(selectedImginfoList,resourceIndex);
	 		}
	 	
	 	});
	 }
	 
	 
	/******************************************************************************
	  * Desc: 获取当前需要获取的资源 
	  * 
	  * @param 
	  *
	  * @return 
	  *		void
	  */
	function _getCurResource(openResourceType,title) {
		
		if(typeof openResourceType == "undefined" || openResourceType == "" ||
		   typeof openResourceType == "undefined" || openResourceType == "") {
			
			return;
		}
		
		
		//获取全景资源
		
		var keepData = {
			
			'curEntityClass': openResourceType,
			'title': title,
		}
		
		//获取当前资源的具体信息
		utils.requestFn(
			"php/ResourceHandllerManager/ResourceManagerDispatcher.php", {
				
				'opCode': 'getCurResourceLayerInfo',
				'entityClass': 'ResourceAllOpHandler',
				'curResourceTypeId': '2',
				'curEntityClass': openResourceType,
			},
			_afterGetResourceLayerInfo,
			utils.resultError,
			keepData
		);
		
	}
	
	/******************************************************************************
	 * Desc: 通用的打开上传配置
	 * 
	 * @param 获取资源 	opt为参数配置   
	 *
	 * @return 
	 *		void
	 */
	function _openCurSource(opt) {
		
		
		//判断参数配置opt
		if(typeof opt != 'object') {
			layer.msg('参数错误');
			return;
		}
		
		if(opt.success) {
			 successCallBack = opt.success;
		}
		
		if(opt.before) {
			
			beforeGetResourceCallBack = opt.before;
		}
		
		
		if(opt.openResourceType) {
			openResourceType = opt.openResourceType;
		}
		
		if(opt.title) {
			
			title = opt.title;
		}
		
		
		//通过所给参数获取数据
		_getCurResource(openResourceType,title);
		
	}

	
	return {
		
		openCurSource:_openCurSource,
		initHandlerSourceChoose: _initHandlerSourceChoose,
	}
});