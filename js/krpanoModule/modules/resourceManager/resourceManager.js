define(['jquery','layuiModule','bootstrap','fileInput','./../uploadOption/uploadOption','./../Utils/Utils','rightKeyMenuModule'],function($,layuiModule,bootstrap,fileInput,uploadOption,utils,rightKeyMenuModule){
	
	
	var _pageId = "system-resourceManager";
	var _title = '资源管理平台';
	var _initManagers = [];
	
	var firstActive = false;
	
	
	
	
	
	
	
	
	function _buildPageHtml()
	{

		//======================================动态创建页面内容====================================
		
		$('#system-resourceManager').empty();
		
		//创建资源类型头部页面
		function buildHeader() {
					
			var html = '';
			
			html += '<div class="row resource-nav-list">';
			html += '	<div class="col-md-2">';
			html += '		<p class="name">资源管理平台</p>';
			html += '	</div>';
			html += '	<div class="col-md-9 typeList">';
			html += '		<ul class="nav nav-tabs" role="tablist">';
			html += '		</ul>';
			html += '	</div>';
			html += '</div>'
			html += '<div class="row resource-content">';
			html += '	<div class="tab-content">';
			html += '		<div id="ResMenu1" class=" tab-pane active">1</div>';
			html += '		<div id="ResMenu2" class=" tab-pane ">2</div>';
			html += '		<div id="ResMenu3" class=" tab-pane ">3</div>';
			html += '		<div id="ResMenu4" class=" tab-pane ">4</div>';
			html += '	</div>';
			html += '</div>';
			html += '<div class="addlayer-modal-content" id="addlayer-modal-content-index">';
			html += '</div>'
			
			$('#system-resourceManager').append(html);
		}
		
		
		
		//初始化资源类型
		buildHeader();
		
	}
	
	/******************************************************************************
	 * Desc: 类型面板初始化
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initResourceContentDisplay(targetName, curUploaderName, curEntityClass, data) {
		
		var htmlLayerInfo = "";
		var htmlOptionLayerInfo = "";
		var htmlCardsLayerInfo = "";
		
		$.each(data, function(index, elem) {
		
			var id = elem['id'];
			var layerName = elem['layerName'];
			
			//默认图层
			if(id == 1) {
				htmlLayerInfo += '<li data-layerId = '+id+' data-target='+targetName+'  class="selected" data-entityClass = '+curEntityClass+'><a href="javascript:;"><span class="">'+layerName+'</span></a></li>';
				htmlCardsLayerInfo += '<div class="layer row selected"id="layer'+id+'" data-target='+targetName+'  data-entityClass = '+curEntityClass+'></div>';
			}
			else {
				htmlLayerInfo += '<li data-layerId = ' + id + ' data-target='+targetName+'  data-entityClass = '+curEntityClass+'><a href="javascript:;"><span class="">' + layerName + '</span><span class="pull-right"><img class="edit"  data-layerId = ' + id + ' data-entityClass = '+curEntityClass+' src="img/left-nav/edit3.png"/><img class="del" data-layerId = ' + id + ' data-entityClass = '+curEntityClass+' src="img/left-nav/del.png"/></span></a></li>';
				htmlOptionLayerInfo += '<option value=' + id + '>' + layerName + '</option>';	
				htmlCardsLayerInfo += '<div class="layer row" id="layer'+id+'" data-target='+targetName+'  data-entityClass = '+curEntityClass+'></div>';
			}
			
			
			
		})
		
		
		
		$('#' + targetName).empty();
		
		var html = '';
		
		html += '<div class="row">';
		html += '	<div class="col-md-2">';
		html += '		<nav class="menu" data-toggle="menu">';
		html += '			<button class="btn btn-primary" data-target='+targetName+' data-entityClass = '+curEntityClass+' data-><i class="icon-plus-sign"><img src="img/left-nav/add_layer3.png"/></i> 新建图册 </button>';
		html += '			<ul class="nav nav-primary">';
		html += 				htmlLayerInfo;
		html += '			</ul>';
		html += '		</nav>';
		html += '	</div>';
		html += '	<div class="col-md-9 pano_optionPlan">';
		html += '		<div class=" row pano_option">';
		html += '			<div class="col-md-2">';
		html += '				<div class="checkbox" >';
		html += '					<label><input type="checkbox" onclick="" >&nbsp;&nbsp;全选</label>';
		html += '				</div>';
		html += '			</div>';
		html += '			<div class="col-md-3">';
		html += '				<select class="form-control" id="" data-target='+targetName+'  data-entityClass = '+curEntityClass+'>';
		html += '					<option value="-1" selected="selected">移动到其他图册</option>';
		html += 					htmlOptionLayerInfo
		html += '				</select>';		
		html += '			</div>';
		html += '			<div class="col-md-3">';
		html += '				<input type="text" id="pano_name" class="form-control" placeholder="资源名称">';
		html += '			</div>';
		html += '			<div class="col-md-2">';
		html += '				<button class="btn btn-info" onclick="">搜索</button>';
		html += '			</div>';
		html += '			<div class="col-md-2">';
		html += '				<button class="btn btn-primary up_btn uploader" data-entityClass = '+curEntityClass+' data-target='+targetName+' >上传'+curUploaderName+'</button>';
		html += '			</div>';
		html += '		</div>';
		html += '		<div class="cards row">';
		html += 			htmlCardsLayerInfo
		html += '	</div>';
		html += '</div>';
		
		$('#' + targetName).append(html);
		
		//触发默认图层点击事件
		var elem = '.resource-content ' +'#'+ targetName +' nav.menu ul li :first ';
	
		$(elem).trigger('click');
		
	}
	
	
	 
	function _opManagers(targetName,data) {
	
		if(typeof targetName == "undefined" || targetName == "" || typeof data != "object" ) {
	
			return;
		}
	
		for(var type in _initManagers) {
	
			if(type == targetName) {
	
				if(typeof _initManagers[targetName] !== 'function') {
					return;
				}
	
				return _initManagers[targetName](data);
	
			}
		}
	
	}
	
	
	/******************************************************************************
	 * Desc: 新增图层
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _addResourceLayer(id,keepData) {
		
		
		if(typeof keepData == "undefined" || keepData.length == 0 || id == 0 || typeof id == "undefined" || id ==  null ) {
			return ;
		}
		
		var value = keepData['value'];
		var targetElem = keepData['targetElem'];
		var index = keepData['index'];
		var curEntityClass = keepData['curEntityClass'];
		
		
		//同步数据库成功之后修改内存中的数据 前端增加图层
		var target = '#system-resourceManager .resource-content ' + '#' + targetElem +' nav.menu ul';
		$(target).append('<li data-layerId = ' + id + ' data-entityClass = '+curEntityClass+' data-target='+targetElem+'><a href="javascript:;"><span class="">' + value + '</span><span class="pull-right"><img class="edit" data-layerId = ' + id + ' data-entityClass = '+curEntityClass+' src="img/left-nav/edit3.png"/><img class="del" data-layerId = ' + id + ' data-entityClass = '+curEntityClass+' src="img/left-nav/del.png"/></span></a></li>');
		
		
		//前端增加类型选择
		var htmlOptionLayerInfo = '<option value=' + id + '>' + value + '</option>';
		var targetOption = '#system-resourceManager .resource-content ' + '#' + targetElem +' .pano_option select'; 
		$(targetOption).append(htmlOptionLayerInfo);
		
		layer.close(index);
		
		layer.msg('增加图层分类成功!');
		
	}
	
	/******************************************************************************
	 * Desc: 删除图层
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _delResourceLayer(data,keepData) {
		
		if(typeof keepData == "undefined" || keepData.length == 0 || typeof data == "undefined" || data == null) {
			return;
		}
		
		var index = keepData['index'];
		var curElme = keepData['curElme'];
		
		
		if(data) {
			
			layer.close(index);
			curElme.parent().parent().parent().remove();
			layer.msg('删除成功', {
				icon: 1
			});
		}
	}
	
	/******************************************************************************
	 * Desc: 移动图层
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _moveResourceLayer(data, keepData) {
		
		
	
		if(typeof keepData == "undefined" || keepData.length == 0 || typeof data == "undefined" || data == null) {
			return;
		}
	
		var removeElem = keepData['removeElem'];
		var ids = keepData['ids'];
		
		$.each(ids, function(index,elem) {
			
			var tmp = removeElem + ' div#' + elem;
			$(tmp).remove();
			
		});
		
	
		if(data) {
			layer.msg('移动成功', {
				icon: 1
			});
			
	
		}
	}
	
	/******************************************************************************
	 * Desc: 重命名图层
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _renameResourceLayer(data, keepData) {
	
		if(typeof keepData == "undefined" || keepData.length == 0 || typeof data == "undefined" || data == null) {
			return;
		}
	
		var index = keepData['index'];
		var curElme = keepData['curElme'];
		var value = keepData['value'];
	
		if(data) {
	
			layer.close(index);
			curElme.parent().prev().text(value);
			layer.msg('重命名成功!', {
				icon: 1
			});
		}
	}
	
	function checkImgWidthAndHeight(width, height, previewId) {
		if(width != 2 * height) {
			$('#imgUpload').fileinput('_showError', '球面全景图片必须为2:1比例');
			return false;
		}
		return true;
	}
	/******************************************************************************
	 * Desc: 上传处理函数
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _uploaderOption(curEntityClass,layerid,targetElem) {
		
	
		//参数校验
		if(typeof curEntityClass == "undefined" || curEntityClass == "" || typeof layerid == "undefined" || layerid < 1) {
			
			return;
		}
		
		
		//上传区域
		var html = "";
		html += '<div class="" id="pranoimg" style="margin-top:20px; overflow-y:auto">';
		html += '	<div class="" style="padding:0;width:100%">';
		html += '		<input id="imgUpload" name="file" type="file" multiple="" accept="" class="">';
		html += '	</div>';
		html += '</div>';
		html += '<div class="uploaderBtn">';
		html += '	<button class="btn btn-block btn-primary" type="button" id="uploaderBtn">立即上传</button>';
		html += '</div>';
		
		
		//上传弹出框
		layer.open({
			title: '上传资源',
			type: 1,
			skin: 'layui-layer-rim', //加上边框
			area: ['70%', '60%'], //宽高
			content: html
		});
		
		//获取对应配置
		var data = {
			
			'curEntityClass':curEntityClass,
			'layerid':layerid,
			'curElem':targetElem,
		}
		var opt = _opManagers(curEntityClass,data);
		
		
		var uploaderElem = 'imgUpload';
		
		//初始化上传控件
		var targetUploader = uploadOption.initUpload(opt,uploaderElem);
		
		
		
		$(document).on('click','#uploaderBtn',function(evt) {
			
			if(targetUploader) {
			
				var files = $('#' + uploaderElem).fileinput('getFileStack');
				if(files.length > 0) {
					$('#' + uploaderElem).fileinput("upload");
				}
				else {
					$('#' + uploaderElem).fileinput('_showError', '请先上传文件');
					return false;
				}
			}
		})
		
		

	}
	/******************************************************************************
	 * Desc: 更新视频资源显示区域
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initVideoCardsInfo(fileName, filePath, fileThumbPath, id, curTargetElem, targetMenu, layerid, curEntityClass) {
	
		var html = "";
	
		html += '			<div class="col-md-4 col-sm-6 col-lg-3 " id=' + id + '>';
		html += '				<input type="checkbox" name="pano_checkbox" id = ' + id + ' class="pano_checkbox">';
		html += '				<a class="card" href="###">';
		html += '					<img src=' + fileThumbPath + ' style="height:160px;width:100%"  alt=' + fileName + '>';
		html += '					<div class="card-content" style="padding-right:20px;padding-bottom:0;">';
		html += '						<div class="row">';
		html += '							<div class="col-md-10">';
		html += '								<span class="text-muted card_text" id="">' + fileName + '</span>';
		html += '							</div>';
		html += '							<div class="col-md-2 moreInfo"><span data-layerid=' + layerid + ' data-curTargetElem= ' + curTargetElem + ' data-curEntityClass=' + curEntityClass + '><img class="more" src="img/left-nav/more.png"></span></div>';
		html += '						</div>';
		html += '					</div>';
		html += '				</a>';
		html += '			</div>';
	
		$(curTargetElem).append(html);
	}
	
	
	/******************************************************************************
	 * Desc: 更新语音音频资源显示区域
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initVoiceCardsInfo(fileName, filePath, fileThumbPath, id, curTargetElem, targetMenu, layerid,curEntityClass) {
		
		var html = "";
		
		html += '			<div class="col-md-4 col-sm-6 col-lg-3 " id=' + id + '>';
		html += '				<input type="checkbox" name="pano_checkbox" id = ' + id + ' class="pano_checkbox">';
		html += '				<a class="card" href="###">';
		html += '					<img src=' + fileThumbPath + ' style="height:160px;width:100%"  alt=' + fileName + '>';
		html += '					<div class="card-content" style="padding-right:20px;padding-bottom:0;">';
		html += '						<div class="row">';
		html += '							<div class="col-md-10">';
		html += '								<span class="text-muted card_text" id="">' + fileName + '</span>';
		html += '							</div>';
		html += '							<div class="col-md-2 moreInfo"><span data-layerid=' + layerid + ' data-curTargetElem= ' + curTargetElem + ' data-curEntityClass=' + curEntityClass + '><img class="more" src="img/left-nav/more.png"></span></div>';
		html += '						</div>';
		html += '					</div>';
		html += '				</a>';
		html += '			</div>';
		
		$(curTargetElem).append(html);
	}
	/******************************************************************************
	 * Desc: 更新(全景与素材)资源显示区域
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initCardsInfo(fileName, filePath, fileThumbPath, id, curTargetElem, targetMenu, layerid,curEntityClass) {
	
		
		var html = "";
	
		html += '			<div class="col-md-4 col-sm-6 col-lg-3" id='+id+'>';
		html += '				<input type="checkbox" name="pano_checkbox" id = ' + id + ' class="pano_checkbox">';
		html += '				<a class="card" href="###">';
		html += '					<img src=' + fileThumbPath + ' style="height:160px;width:100%" alt=' + fileName + '>';
		html += '					<div class="card-content" style="padding-right:20px;padding-bottom:0;">';
		html += '						<div class="row">';
		html += '							<div class="col-md-10">';
		html += '								<span class="text-muted card_text" id="">' + fileName + '</span>';
		html += '							</div>';
		html += '							<div class="col-md-2 moreInfo"><span data-layerid='+layerid+' data-curTargetElem= '+curTargetElem+' data-curEntityClass='+curEntityClass+'><img class="more" src="img/left-nav/more.png"></span></div>';
		html += '						</div>';
		html += '					</div>';
		html += '				</a>';
		html += '			</div>';
	
		$(curTargetElem).append(html);
	}
	
	/******************************************************************************
	 * Desc: 上传全景成功之后更新显示区域
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initPanoImgContent(response,data) {
		
		if(typeof response == "undefined"  || typeof response.msg == "undefined" || typeof data == "undefined") {
			return;
		}
		
		
		var msg = response.msg;
		
		//文件名
		var fileName = msg['fileName'];
		
		//文件路径
		var filePath = msg['filePath'];
		
		//缩略图文件路径
		var fileThumbPath = msg['fileThumbPath'].substring(6);
		
		//数据库中对应资源信息id
		var id = msg['id'];
		
		//目标元素
		var curTargetElem = data['curElem'];
		
		var layerid = data['layerid'];
		
		var curEntityClass = data['curEntityClass'];
		
		var elem = '#system-resourceManager .resource-content ' + "#" + curTargetElem + ' .cards #layer' + layerid;
		
		_initCardsInfo(fileName, filePath, fileThumbPath, id, elem, curTargetElem,layerid,curEntityClass);
		
	}
	
	
	
	
	/******************************************************************************
	 * Desc: 获取上传全景图配置
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _getPanoImgLayerUploaderOpt(data) {
		
		
		//配置上传插件
		var opt = {
		
			uploadUrl: 'php/fileOptionManager/uploaderSource.php',
			
			browseLabel: "选择本地全景图片",
		
			layoutTemplates: {
				//actionDelete:'true',
				actionUpload: '',
			},
		
			uploadExtraData: function(previwId) {
				
				data['opCode'] = "uploaderPanoImg";
				return data;
		
			},
		
			fileloaded: function(event, file, previewId, index, reader) {
		
				if(file.name.substr(file.name.lastIndexOf(".")) == '.tiff' || file.name.substr(file.name.lastIndexOf(".")) == '.tif') {
					var tiff = new Tiff({
						buffer: reader.result
					});
					var width = tiff.width();
					var height = tiff.height();
					checkImgWidthAndHeight(width, height, previewId);
					return;
				}
				var objUrl = window.URL || window.webkitURL;
				var url = objUrl.createObjectURL(file);
				var img = new Image();
				img.src = url;
				img.onload = function() {
					checkImgWidthAndHeight(img.naturalWidth, img.naturalHeight, previewId);
					objUrl.revokeObjectURL(url);
				}
		
			},
		
			success: function(response) {
				
				
				console.log(response);
				
				_initPanoImgContent(response,data);
				layer.msg('上传资源成功!');
		
			},
			error: function(err) {},
		
			allowedFileExtensions: ["jpg", "jpeg", "tif", "tiff", "JPG"],
		
		}
		
		
		return opt;
	}
	
	
	/******************************************************************************
	 * Desc: 上传素材图片成功之后更新显示区域
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initBasicMaterialContent(response, data) {
	
		if(typeof response == "undefined" || typeof response.msg == "undefined" || typeof data == "undefined") {
			return;
		}
	
		var msg = response.msg;
	
		//文件名
		var fileName = msg['fileName'];
	
		//文件路径
		var filePath = msg['filePath'];
	
		//缩略图文件路径
		var fileThumbPath = msg['fileThumbPath'].substring(6);
	
		//数据库中对应资源信息id
		var id = msg['id'];
	
		//目标元素
		var curTargetElem = data['curElem'];
	
		var layerid = data['layerid'];
	
		var curEntityClass = data['curEntityClass'];
	
		var elem = '#system-resourceManager .resource-content ' + "#" + curTargetElem + ' .cards #layer' + layerid;
	
		_initCardsInfo(fileName, filePath, fileThumbPath, id, elem, curTargetElem, layerid, curEntityClass);
	
	}
	
	/******************************************************************************
	 * Desc: 获取上传素材资源配置
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _getBasicMaterialLayerUploaderOpt(data) {
		
		//配置上传插件
		var opt = {
		
			uploadUrl: 'php/fileOptionManager/uploaderSource.php',
		
			browseLabel: "选择素材图片",
		
			layoutTemplates: {
				//actionDelete:'true',
				actionUpload: '',
			},
		
			uploadExtraData: function(previwId) {
		
				data['opCode'] = "uploaderBasicMaterialImg";
				return data;
		
			},
		
			fileloaded: function(event, file, previewId, index, reader) {
				
		
			},
		
			success: function(response) {
		
				console.log(response);
		
				_initBasicMaterialContent(response, data);
				layer.msg('上传素材图片成功!');
				
		
			},
			error: function(err) {},
		
			allowedFileExtensions: ["jpg", "jpeg", "tif", "tiff", "JPG"],
		
		}
		
		return opt;
	}
	
	/******************************************************************************
	 * Desc: 上传语音音频成功之后更新显示区域
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initVoiceResourceContent(response, data) {
	
		if(typeof response == "undefined" || typeof response.msg == "undefined" || typeof data == "undefined") {
			return;
		}
		
		
	
		var msg = response.msg;
	
		//文件名
		var fileName = msg['fileName'];
	
		//文件路径
		var filePath = msg['filePath'];
	
		//缩略图文件路径
		var fileThumbPath = msg['fileThumbPath'].substring(6);
	
		//数据库中对应资源信息id
		var id = msg['id'];
	
		//目标元素
		var curTargetElem = data['curElem'];
	
		var layerid = data['layerid'];
	
		var curEntityClass = data['curEntityClass'];
	
		var elem = '#system-resourceManager .resource-content ' + "#" + curTargetElem + ' .cards #layer' + layerid;
	
		_initVoiceCardsInfo(fileName, filePath, fileThumbPath, id, elem, curTargetElem, layerid, curEntityClass);
	
	}
	
	
	/******************************************************************************
	 * Desc: 获取上传语音音频资源配置
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _getVoiceLayerUploaderOpt(data) {
		
		
		//配置上传插件
		var opt = {
		
			uploadUrl: 'php/fileOptionManager/uploaderSource.php',
		
			browseLabel: "选择本地语音音频",
			
		
			layoutTemplates: {
				//actionDelete:'true',
				actionUpload: '',
			},
			
			//拖拽显示区
			dropZoneTitle: "选择本地音频文件/支持(mp3, oga, wav)",
			
			//文件默认目录
			previewFileType: "audio",
			
//			allowedFileTypes: ['audio'],
		
			uploadExtraData: function(previwId) {
		
				data['opCode'] = "uploaderVoiceResource";
				data['imgBlackgroud'] = "../../img/content/voiceMemo.png";
				return data;
		
			},
		
			fileloaded: function(event, file, previewId, index, reader) {
				
			},
		
			success: function(response) {
		
				console.log(response);
		
				_initVoiceResourceContent(response, data);
				layer.msg('上传语音成功!');
		
			},
			
			error: function(err) {},
		
			allowedFileExtensions: ["mp3", "oga", "wav"],
		
		}
		
		return opt;

	}
	
	/******************************************************************************
	 * Desc: 上传视频资源成功之后更新显示区域
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initVideoResourceContent(response, data) {
	
		if(typeof response == "undefined" || typeof response.msg == "undefined" || typeof data == "undefined") {
			return;
		}
	
		var msg = response.msg;
	
		//文件名
		var fileName = msg['fileName'];
	
		//文件路径
		var filePath = msg['filePath'];
	
		//缩略图文件路径
		var fileThumbPath = msg['fileThumbPath'].substring(6);
	
		//数据库中对应资源信息id
		var id = msg['id'];
	
		//目标元素
		var curTargetElem = data['curElem'];
	
		var layerid = data['layerid'];
	
		var curEntityClass = data['curEntityClass'];
	
		var elem = '#system-resourceManager .resource-content ' + "#" + curTargetElem + ' .cards #layer' + layerid;
	
		_initVoiceCardsInfo(fileName, filePath, fileThumbPath, id, elem, curTargetElem, layerid, curEntityClass);
	
	}
	
	/******************************************************************************
	 * Desc: 获取上传视频资源配置
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _getVideoLayerUploaderOpt(data) {
		
		//配置上传插件
		var opt = {
		
			uploadUrl: 'php/fileOptionManager/uploaderSource.php',
			
			browseLabel: "选择本地视频资源",
		
			layoutTemplates: {
				//actionDelete:'true',
				actionUpload: '',
			},
			maxFileSize: '1024000',
		
			//拖拽显示区
			dropZoneTitle: "单个视频大小不能超过100M",
		
			previewFileType: "video",
		
			uploadExtraData: function(previwId) {
		
				data['opCode'] = "uploaderVideoResource";
				data['imgBlackgroud'] = "../../img/content/videoMemo.png";
				return data;
		
			},
		
			fileloaded: function(event, file, previewId, index, reader) {
		
			},
		
			success: function(response) {
		
				console.log(response);
		
				_initVoiceResourceContent(response, data);
				layer.msg('上传视频资源成功!');
		
			},
		
			error: function(err) {},
		
			allowedFileExtensions: ["mp4",'avi','wma'],
		
		}
		
		return opt;
		
	}
	
	
	/******************************************************************************
	 * Desc: 注册上传配置函数
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _registerManager() {

		_initManagers['PanoImgLayer'] = _getPanoImgLayerUploaderOpt;
		_initManagers['BasicMaterialLayer'] = _getBasicMaterialLayerUploaderOpt;
		_initManagers['VoiceLayer'] = _getVoiceLayerUploaderOpt;
		_initManagers['VideoLayer'] = _getVideoLayerUploaderOpt;
	}
	

	
	/******************************************************************************
	 * Desc: 获取资源信息的回调函数
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _afterGetResourceLayerInfo(data,keepData) {
		
		if(typeof keepData == "undefined" || typeof data == "undefined" || data.length == 0){
			return;
		}
		
		
		var targetName = keepData['curMenuName'];
		var curUploaderName = keepData['curUploaderName'];
		var curEntityClass = keepData['curEntityClass'];
		
		
		_initResourceContentDisplay(targetName, curUploaderName, curEntityClass, data);
		
		
		
		
	}
	
	/******************************************************************************
	 * Desc: 初始化每项资源信息页面内容
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initResourceMenuContent(curEntityClass, curResourceTypeId, curMenuName,curUploaderName){
		
		//参数校验
		if(	typeof curEntityClass == "undefined" 
			|| typeof curMenuName == "undefined" 
			|| typeof curResourceTypeId == "undefined"
			|| typeof curUploaderName == "undefined"
			|| curMenuName == ""
			|| curEntityClass == "" 
			|| curResourceTypeId < 0
			|| curUploaderName == ""
		) {
			return;
		}
		
		var keepData = {
			'curMenuName':curMenuName,
			'curUploaderName':curUploaderName,
			'curEntityClass':curEntityClass,
		}
		
		//获取当前资源的具体信息
		utils.requestFn(
			"php/ResourceHandllerManager/ResourceManagerDispatcher.php", {
				'opCode': 'getCurResourceLayerInfo',
				'entityClass': 'ResourceAllOpHandler',
				'curResourceTypeId':curResourceTypeId,
				'curEntityClass':curEntityClass,
			},
			_afterGetResourceLayerInfo,
			utils.resultError,
			keepData
		);
		
		
		
	}
	/******************************************************************************
	 * Desc: 切换图层的时候拉取数据
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _displayCurResourceLayer(data,keepData) {
		
		if(typeof keepData == "undefined" || typeof data == "undefined" ) {
			return;
		}
		
		
		//目标元素
		var curTargetElem = keepData['curElem'];
		
		var layerId = keepData['layerId'];
		
		var curEntityClass = keepData['curEntityClass'];
		
		var targetElem = '#system-resourceManager .resource-content ' + "#" + curTargetElem + ' .cards #layer' + layerId;
		
		
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
			
			
			
			if(curEntityClass == "VoiceLayer") {
				
				_initVoiceCardsInfo(fileName, filePath, fileThumbPath, id, targetElem,curTargetElem,layerId,curEntityClass);
			}
			else if (curEntityClass == "VideoLayer"){
				_initVideoCardsInfo(fileName, filePath, fileThumbPath, id, targetElem,curTargetElem,layerId,curEntityClass);
			}
			else {
				
				_initCardsInfo(fileName, filePath, fileThumbPath, id, targetElem,curTargetElem,layerId,curEntityClass);
			}
		
		
		});
	}
	
	/******************************************************************************
	 * Desc: 点击更多菜单
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _optionMoreMenu(curElem,curEntityClass,layerId,curTargetElem) {
		
	
		
		function delItem(o) {
			var $this = $(o.ele);
		
			var srcUrl = $this.attr('src');
			var resourceid = $this.parent().parent().attr('data-resourceid');
			var companyId = $this.parent().parent().attr('data-companyId');
			var curlistid = $this.parent().parent().attr('data-curlistid');
			var curlistname = $this.parent().parent().attr('data-curlistname');
		
			if(typeof srcUrl == "undefined" ||
				typeof resourceid == "undefined" ||
				typeof companyId == "undefined" ||
				typeof curlistid == "undefined" ||
				typeof curlistname == "undefined") {
		
				return;
			}
		
			var content = "你确定删除 <span style='color: red;'>" + '编号：' + resourceid + '，' +
				'名称：' + curlistname + '</span>资源信息？';
			layer.confirm(
				content, {
					title: ' 提示',
					btn: ['确定', '取消']
				},
				function(index, layerno) {
					var data = {
						typeId: curlistid,
						resourceId: resourceid,
						companyId: companyId,
						srcUrl: srcUrl
					};
		
					var url = "php/secondPlanManager/delSecondPlansResource.php";
					ZZSoftApp.SystemManager.secondLevelQueryPlan.secondLevelQueryRequest(data,
						url,
						function(data) {
							layer.close(index);
							$this.parent().parent().remove();
							o.removeSelf();
							layer.msg("删除成功");
						},
						ZZSoftApp.SystemManager.secondLevelQueryPlan.resultErrorInfo
		
					);
		
				},
				function(index, layerno) {}
			);
			o.removeSelf();
		}
		
		var menuSettings = {
			
			width: 110, // width
			itemHeight: 30, // 菜单项height
			bgColor: "#4d3d5d", // 背景颜色
			color: "#fff", // 字体颜色
			fontSize: 14, // 字体大小
			hoverBgColor: "#6a5d85", // hover背景颜色
			target: function(ele) { // 当前元素
				// ...
			},
			menu: [{ // 菜单项
				text: "删除",
				icon: "img/rightKeyMenu/del.png",
				callback: delItem
			}]
		};
		
		curElem.contextMenu(menuSettings);
	}
	
	/******************************************************************************
	 * Desc: 切换图层之后需要清除全选状态
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _changeCheckBox(elem,targetClass){
		
		if(typeof (elem) =="undefined" || typeof targetClass == "undefined") {
			return;
		}
		
		if(elem.is(':checked')) {
			$("input[name='"+targetClass+"']").each(function() {
				this.checked = true;
			});
		} else {
			$("input[name='"+targetClass+"']").each(function() {
				this.checked = false;
			});
		}
		
		
	}
	
	/******************************************************************************
	 * Desc: 获取类型成功之后的回调函数
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _successGetResourceType(data) {
		
		//参数校验
		if(typeof data == "undefined" || data.length == 0) {
			return;
		}
		
		//数据清空
		$('.resource-nav-list .typeList ul').empty();
		$('.resource-content .tab-content').empty();
		
		//循环生成数据
		$.each(data,function(index,elem) {
			
			var id = elem['id'];
			var typeName = elem['typeName'];
			var entityClass = elem['entityClass']; 
			
			var htmlMenu = '';
			var htmlMenuContent = '';
			
			//首项激活
			if(id == 1) {
				htmlMenu = '<li class="nav-item"><a class="nav-link active" data-toggle="tab" data-typeId = ' + id + ' data-entityClass = '+entityClass+' data-name = '+typeName+'  data-targetElem="ResMenu' + id + '" href="#ResMenu' + id + '">' + typeName + '</a></li>';
				htmlMenuContent = '<div id="ResMenu'+id+'" class=" tab-pane active"></div>'
			}
			else {
				htmlMenu = '<li class="nav-item"><a class="nav-link" data-toggle="tab" data-typeId = '+id+' data-entityClass = '+entityClass+' data-name = '+typeName+' data-targetElem="ResMenu'+id+'" href="#ResMenu'+id+'">'+typeName+'</a></li>';
				htmlMenuContent = '<div id="ResMenu'+id+'" class=" tab-pane"></div>'
			}
			
			$('.resource-nav-list .typeList ul').append(htmlMenu);
			
			$('.resource-content .tab-content').append(htmlMenuContent);
		});
		
		//自动触发第一类资源的第一图层
		$('.resource-nav-list ul li a:first').trigger('click');
		
	}
	
	function _initReadyData() {
	
	
		//初始化资源类型列表
		function initResource() {
			
			utils.requestFn(
				"php/ResourceHandllerManager/ResourceManagerDispatcher.php", {
					'opCode': 'getResourceList',
					'entityClass': 'ResourceAllOpHandler'
				},
				_successGetResourceType,
				utils.resultError
			);
		}
		
		//初始化资源类型列表
		initResource();
		
		
		
	}
	
	function _initHandlers() {
		
		//监听资源类型切换操作
		$(document).on('click', '.resource-nav-list ul li a', function(evt) {
			
			
	
			var targetName = $(this).attr('data-targetElem');
			var entityClass = $(this).attr('data-entityClass');
			var curResourceTypeId = $(this).attr('data-typeId');
			var curUploaderName = $(this).attr('data-name');
			
			//如果已经选择了图层就不去拉取数据
			var targetElem = '#system-resourceManager .resource-content ' + "#" + targetName + ' .cards div.selected';
			if($(targetElem).length > 0) {
				return;
			}
			else {
				//初始化资源信息面板
				_initResourceMenuContent(entityClass, curResourceTypeId, targetName, curUploaderName);
			}
			
			
	
	
		});
	
		//监听新增图层类型按钮
		$(document).on('click', '#system-resourceManager .resource-content nav.menu>.btn', function(evt) {
	
			var targetElem = $(this).attr('data-target');
			var curEntityClass = $(this).attr('data-entityclass');
			layer.open({
	
				title: '新建图层',
				area: ['520px', '195px'],
				content: '<input class="form-control form-focus" autofocus type="text" placeholder="请输入名称" id="newLayerPano_name">',
				yes: function(index, layero) {
					var value = layero.find('input').val();
					if(value.length <= 0 || value.length > 20) {
						layer.msg("请输入0到20长度的名称！");
						layer.close(index);
						return;
					}
					var keepData = {
						'value': value,
						'targetElem': targetElem,
						'index': index,
						'curEntityClass': curEntityClass,
					}
	
					//同步数据到数据库
					utils.requestFn(
						"php/ResourceHandllerManager/ResourceManagerDispatcher.php", {
							'opCode': 'addCurResourceLayer',
							'entityClass': 'ResourceAllOpHandler',
							'newName': value,
							'curEntityClass': curEntityClass,
						},
						_addResourceLayer,
						utils.resultError,
						keepData
					);
	
				}
			});
	
		});
		
		//选择图层
		$(document).on('click', '#system-resourceManager .resource-content nav.menu ul li', function(evt) {
		
			var layerId = $(this).attr('data-layerid');
			var curEntityClass = $(this).attr('data-entityClass');
			var curElem = $(this).attr('data-target');
			
			
			$(this).siblings('li').removeClass('selected'); // 删除其他兄弟元素的样式
			
			$(this).addClass('selected');
			
			//控制只显示当前图层的信息
			$('#system-resourceManager .resource-content .cards .layer' + "#layer" + layerId).siblings('div').removeClass('selected');
			
			$('#system-resourceManager .resource-content .cards .layer' + "#layer" + layerId).addClass('selected');
			
			//切换图层时取消选中状态
			if($('#system-resourceManager .resource-content .checkbox input').is(':checked')) {
				$('#system-resourceManager .resource-content .checkbox input').prop('checked',false);
			}
			
			//请求当前图层下面的数据
			
			//同步数据到数据库
			
			var keepData = {
				
				'layerId': layerId,
				'curEntityClass': curEntityClass,
				'curElem':curElem
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
		
		
	
		//删除图层
		$(document).on('click', '#system-resourceManager .resource-content nav.menu ul li a img.del', function(evt) {
	
			var layerId = $(this).attr('data-layerid');
			var curEntityClass = $(this).attr('data-entityClass');
			var elem = $(this);
	
			layer.confirm('删除图层信息,将会删除图层下面所有信息是否确认？', {
				btn: ['确认删除', '取消'], //按钮
				icon: 7,
				title: '提示',
				anim: 6
			}, function(index, layero) {
	
				var keepData = {
					'index': index,
					'curElme': elem
				}
	
				//同步数据到数据库
				utils.requestFn(
					"php/ResourceHandllerManager/ResourceManagerDispatcher.php", {
						'opCode': 'delCurResourceLayerInfo',
						'entityClass': 'ResourceAllOpHandler',
						'curEntityClass': curEntityClass,
						'layerId': layerId,
					},
					_delResourceLayer,
					utils.resultError,
					keepData
				);
	
			}, function(index, layero) {
	
			});
	
		});
	
		//修改名字
		$(document).on('click', '#system-resourceManager .resource-content nav.menu ul li a img.edit', function(evt) {
	
			var layerId = $(this).attr('data-layerid');
			var curEntityClass = $(this).attr('data-entityClass');
			var elem = $(this);
	
			layer.open({
	
				title: '重命名',
				area: ['520px', '195px'],
				content: '<input class="form-control form-focus" autofocus type="text" placeholder="请输入名称" id="newLayerPano_name">',
				yes: function(index, layero) {
					var value = layero.find('input').val();
					if(value.length <= 0 || value.length > 20) {
						layer.msg("请输入0到20长度的名称！");
						layer.close(index);
						return;
					}
	
					var keepData = {
						'index': index,
						'curElme': elem,
						'value': value
					}
	
					//同步数据到数据库
					utils.requestFn(
						"php/ResourceHandllerManager/ResourceManagerDispatcher.php", {
							'opCode': 'renameCurResourceLayerInfo',
							'entityClass': 'ResourceAllOpHandler',
							'curEntityClass': curEntityClass,
							'layerId': layerId,
							'newName': value
						},
						_renameResourceLayer,
						utils.resultError,
						keepData
					);
	
				}
			});
	
		});
	
		//上传按钮监听
		$(document).on('click', '#system-resourceManager .resource-content button.up_btn', function(evt) {
	
			//获取当前类型
			var curEntityClass = $(this).attr('data-entityClass');
			
			//获取当前目标元素
			
			var targetElem = $(this).attr('data-target');
			
			
			//获取当前选中图层ID
			var elem = '#system-resourceManager .resource-content ' + "#" + targetElem + ' nav.menu ul li.selected';
			
			var layerid = $(elem).attr('data-layerId');
			
			_uploaderOption(curEntityClass,layerid,targetElem);
		});
		
		$(document).on('click','#system-resourceManager .resource-content .cards .moreInfo span',function(evt) {
			
			//获取当前类型
			var curEntityClass = $(this).attr('data-curentityclass');
			
			var layerId = $(this).attr('data-layerid');
			
			var curTargetElem = $(this).attr('data-curtargetelem');

		})
		
		//移动图层
		$(document).on('change', '#system-resourceManager .resource-content select', function(evt) {
		
			//获取当前类型
			var curEntityClass = $(this).attr('data-entityclass');
		
			var curTargetElem = $(this).attr('data-target');
//			system-resourceManager .resource-content nav.menu ul li
			
			var targetElem = '#system-resourceManager .resource-content ' + "#" + curTargetElem + ' nav.menu ul li.selected';
			var curLayerId;
			
			if($(targetElem).length > 0) {
				curLayerId = $(targetElem).attr('data-layerid');
			}
			else {
				return;
			}
			
			
			var layerId = $(this).val();
			if(layerId < 0) {
				return;
			}
			//如果移动是当前图层则直接返回不做操作
			if(curLayerId == layerId) {
				layer.msg("本资源就在当前需要移动的图层");
				return;
			}
			var ids = new Array;
			
			$("input[name='pano_checkbox']").each(function() {
				if($(this).is(':checked')) {
					ids.push($(this).attr("id"));
				}
			});
			
			if(ids.length == 0) {
				
				layer.msg("请先勾选要移动的资源");
				return;
			}
			
			var removeElem = '#system-resourceManager .resource-content ' + "#" + curTargetElem + ' .cards #layer' + curLayerId;
			
			var keepData = {
				'removeElem': removeElem,
				'ids': ids,
//				'value': value
			}
			
			//同步数据到数据库
			utils.requestFn(
				"php/ResourceHandllerManager/ResourceManagerDispatcher.php", {
					'opCode': 'moveCurResourceToOtherLayer',
					'entityClass': 'ResourceAllOpHandler',
					'curEntityClass': curEntityClass,
					'targetLayerId': layerId,
					'curLayerId':curLayerId,
					"ids": JSON.stringify(ids)
				},
				_moveResourceLayer,
				utils.resultError,
				keepData
			);
		
		})
		
		$(document).on('change','#system-resourceManager .resource-content .checkbox input',function(evt) {
			
			_changeCheckBox($(this),'pano_checkbox');
			
		});
		
	}
	
	function _init()
	{
		
		
		_initReadyData();
		
		_initHandlers();
		
		_registerManager();
		
		
	}
	
	function _resize()
	{
	}
	
	
	


	
	function _getTitle() {
		return _title;
	}
	
	function _getUrl() {
		return '#' + _pageId;
	}
	
	return {
		
		init: _init,
		resize: _resize,
		getTitle: _getTitle,
		getUrl: _getUrl,
		buildPageHtml: _buildPageHtml
	}
});
