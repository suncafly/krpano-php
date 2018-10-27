define(['jquery','layuiModule','bootstrap','fileInput','./../uploadOption/uploadOption','./../Utils/Utils','./../krpanoOnlineEditManager/krpanoOnlineEditManager',],function($,layuiModule,bootstrap,fileInput,uploadOption,utils,krpanoOnlineEditManager){
	
	var _pageId = "system-editPushManager";
	var _title = '预览编辑发布平台';
	
	var _initManagers = [];
	
	
	
	
	
	
	function _buildPageHtml()
	{
		//======================================动态创建页面内容====================================
		
		$('#system-editPushManager').empty();
		
		//创建资源类型头部页面
		function buildHeader() {
		
			var html = '';
			
		
			html += '<div class="row edit-nav-list">';
			html += '	<div class="col-md-2 ">';
			html += '		<p class="name">编辑预览发布平台</p>';
			html += '	</div>';
			html += '	<div class="col-md-9 typeList">';
			html += '		<ul class="nav nav-tabs" role="tablist">';
			html += '			<li class="nav-item"><a class="nav-link active" data-toggle="tab" data-TargetOption="projectInfoManager" data-targetelem="EditMenu1" href="#EditMenu1">作品管理</a></li>';
			html += '			<li class="nav-item"><a class="nav-link " data-toggle="tab" data-TargetOption="pushProjectManager" data-targetelem="EditMenu2" href="#EditMenu2">作品发布</a></li>';
			html += '		</ul>';
			html += '	</div>';
			html += '</div>'
			
			html += '<div class="row edit-content">';
			html += '	<div class="tab-content">';
			html += '		<div id="EditMenu1" class="tab-pane active">';
			html += '		</div>';
			html += '		<div id="EditMenu2" class="tab-pane">';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';
			
			
			$('#system-editPushManager').append(html);
		}
		
		//初始化资源类型
		buildHeader();
	
	}
	
	/******************************************************************************
	 * Desc: 获取到作品信息然后初始化
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initProjectContentLeftAndRight(htmlLayerInfo,htmlProjectLayerInfo,htmlOptionLayerInfo,layerId) {
		
		if(typeof htmlLayerInfo == "undefined" || typeof htmlProjectLayerInfo == "undefined" || typeof htmlOptionLayerInfo == "undefined") {
			return;
		}
		
		var html = "";
		
		html += '<div class="row">';
		html += '	<div class="left">';
		html += '		<div class="projectLayer">';
		html += '			<nav class="menu" data-toggle="menu">';
		html += '				<button class="btn btn-primary" ><i class="icon-plus-sign"><img src="img/left-nav/add_layer3.png"/></i> 新建图册 </button>';
		html += '				<ul class="nav nav-primary">';
		html += htmlLayerInfo
		html += '				</ul>';
		html += '			</nav>';
		html += '		</div>';
		html += '	</div>';
		html += '	<div class="right ">';
		html += '		<div class="inner">';
		html += '			<div class="main_wrap" id="pic_wrap" style="margin:2rem">';
		html += '				<div class="row">';
		html += '					<div class="col-md-1">';
		html += '						共 <strong style="font-size:18px;">1</strong> 个作品';
		html += '					</div>';
		html += '					<div class="col-md-1">';
		html += '						<div class="checkbox" >';
		html += '							<label><input type="checkbox" onclick="" >&nbsp;&nbsp;全选</label>';
		html += '						</div>';
		html += '					</div>';
		html += '					<div class="col-md-2">';
		html += '						<select class="form-control" id="" >';
		html += '							<option value="-1" selected="selected">移动到其他图册</option>';
		html += 							htmlOptionLayerInfo;
		html += '						</select>';
		html += '					</div>';
		html += '					<div class="col-md-2">';
		html += '						<div class=" date form-date " data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd">'
		html += '							<input class="form-control" size="16" id="time_s" type="text" value="" readonly="" placeholder="开始时间">'
		html += '						</div>'
		html += '					</div>';
		html += '					<div class="col-md-2">';
		html += '						<div class=" date form-date " data-date="" data-date-format="dd MM yyyy" data-link-format="yyyy-mm-dd">'
		html += '							<input class="form-control" size="16" id="time_e" type="text" value="" readonly="" placeholder="结束时间">'
		html += '						</div>'
		html += '					</div>';
		html += '					<div class="col-md-2">';
		html += '						<input type="text" id="name" class="form-control" placeholder="作品名">';
		html += '					</div>';
		html += '					<div class="col-md-2">';
		html += '						<button class="btn btn-info" onclick="list(1)">搜索</button>';
		html += '					</div>';
		html += '				</div>';
		html += '			</div>';
		html += '			<div class="projectManager">';
		html += 				htmlProjectLayerInfo;
		html += '			</div>';
		html += '		</div>';
		html += '	</div>';
		html += '</div>';
		
		$('.edit-content #EditMenu1').append(html);
	}
	
	/******************************************************************************
	 * Desc: 初始化项目图层列表
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initProjectContent(layerId,layerName,projectList) {
		
		if(typeof layerId == "undefined" || layerId < 0 || typeof layerName == "undefined" || layerName == "") {
			return;
		}
		
		//返回项目当前图层列表
		var htmlCurLayerProjectList = "";
		$.each(projectList, function(index,elem) {
			
			htmlCurLayerProjectList += _initProjectListInfo(layerId,elem);
			
		});
		
		
		
		var htmlLayerInfo = "";
		var htmlProjectLayerInfo = "";
		var htmlOptionLayerInfo = "";
		//默认图层
		if(layerId == 1) {
			
			htmlLayerInfo += '<li data-layerId = ' + layerId + ' class="selected"><a href="javascript:;"><span class="">' + layerName + '</span></a></li>';
			htmlProjectLayerInfo += '<div class="selected" id="projectLayer'+layerId+'">'+htmlCurLayerProjectList+'</div>';
		} else {
			htmlLayerInfo += '<li data-layerId = ' + layerId + '><a href="javascript:;"><span class="">' + layerName + '</span><span class="pull-right"><img class="edit"  data-layerId = ' + layerId + ' src="img/left-nav/edit3.png"/><img class="del" data-layerId = ' + layerId + ' src="img/left-nav/del.png"/></span></a></li>';
			htmlProjectLayerInfo += '<div class="" id="projectLayer'+layerId+'">'+htmlCurLayerProjectList+'</div>';
			htmlOptionLayerInfo += '<option value=' + layerId + '>' + layerName + '</option>';
		}
		
		
		var data = {
			'htmlLayerInfo':htmlLayerInfo,
			'htmlProjectLayerInfo':htmlProjectLayerInfo,
			'htmlOptionLayerInfo':htmlOptionLayerInfo,
		};
		
		return data;
		
	}
	
	
	/******************************************************************************
	 * Desc: 获取到作品信息然后初始化
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	
	function _initProjectListInfo(projectLayerId,projectList) {
		
		
		if(typeof projectLayerId == "undefined" || projectLayerId == "" || typeof projectList != "object") {
			return;
		}
		
		
		
	
		//项目ID
		var projectId = projectList['projectId'];
		//项目名称
		var projectName = projectList['projectName'];
		//项目类型名
		var projectTypeName = projectList['projectType'];
		
		//项目路径
		var projectPath = projectList['projectPath'];
		//创建时间
		var createTime = projectList['projectCreateTime'];
		
		
		//更新项目列表项
		
		
		var html = ""
		
		html += '            	<div class="list_wrap" data-layerId='+projectLayerId+' id='+projectId+'>'
		html += '					<ul class="List_row_2vTSLP">';
		html += '						<li>';
		html += '							<div class="">';
		html += '								<div class="checkBoxContainer">';
		html += '									<input type="checkbox" name="pano_checkbox" id='+projectId+' class="pano_checkbox">';
		html += '								</div>';
		html += '							</div>';
		html += '						</li>';
		html += '						<li>';
		html += '							<a href="https://720yun.com/t/964jvpsmsy8" class="listProimg">';
		html += '								<img src="https://ssl-thumb.720static.com/@/resource/prod/94b3edf9s1t/b37jOdykzy8/12556171/imgs/thumb.jpg?imageMogr2/thumbnail/80" style="width: 100%; height: 100%;">';
		html += '							</a>';
		html += '						</li>';
		html += '						<li>';
		html += '							<h3>';
		html += '								<span class="listChannel">[' + projectTypeName + ']</span>';
		html += '								<a href="" class="ellipsis">' + projectName + '</a>';
		html += '								<a href=' + projectPath + ' target="_blank" class="listPerview">预览</a>';
		html += '							</h3>';
		html += '							<div class="ListDesc">';
		html += '								<span>' + createTime + '</span>';
		html += '							</div>';
		html += '						</li>';
		html += '						<li class="listBtnGroup">';
		html += '							<a href="" class="listBtn">离线下载</a>';
		html += '							<a href="" class="listBtn">分享</a>';
		html += '							<a href="javascript:void(0)" data-projectName='+projectName+' data-layerId='+projectLayerId+' data-projectId='+projectId+' data-projectPath='+projectPath+' class="edit listBtn">编辑</a>';
		html += '							<a href="javascript:void(0)" class="listBtn">删除</a>';
		html += '						</li>';
		html += '					</ul>';
		html += '           	</div>'
		
		return html;
	}
	
	
	
	/******************************************************************************
	 * Desc: 获取到作品信息然后初始化
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _successGetProjectInfo(data) {
		
		if(typeof data == "undefined" || data.length == 0) {
			return;
		}
		
		$('.edit-content #EditMenu1').empty();
		
		//图层信息
		var htmlLayerInfo = "";
		//项目目录信息
		var htmlProjectLayerInfo = "";
		//图层分类信息
		var htmlOptionLayerInfo = ""; 
		//项目分类
		var htmlOptionLayerInfo = "";
		
		$.each(data,function(index,elem) {
			
			//图层id
			var layerId = elem['layerId'];
			
			//图层名称
			var layerName = elem['layerName'];
			
			//当前图层下面的所有项目
			var projectList = elem['projectList'];
			
			//初始化图层信息
			var htmlData = _initProjectContent(layerId,layerName,projectList);
			
			
			if(typeof htmlData == "object" || htmlData.length > 0) {
				
				htmlLayerInfo += htmlData['htmlLayerInfo'];
				
				htmlProjectLayerInfo += htmlData['htmlProjectLayerInfo'];
				
				htmlOptionLayerInfo += htmlData['htmlOptionLayerInfo'];
			}
			
			
			
		});
		
		
		//通过数据动态初始化页面
		_initProjectContentLeftAndRight(htmlLayerInfo,htmlProjectLayerInfo,htmlOptionLayerInfo);
		
		_loadLayuiElem();
			
	}
	
	/******************************************************************************
	 * Desc: 作品管理
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */	
	function _projectInfoManager() {
		
		
		//获取数据
		utils.requestFn(
			"php/projectHandllerManager/projectDispatchMenu.php", {
				'opCode': 'getProjectInfo',
				'curOptionMenu': 'projectInfoManager'
			},
			_successGetProjectInfo,
			utils.resultError
		);

	}
	
	/******************************************************************************
	 * Desc: 获取项目类型
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _successGetProjectType(data) {
		console.log(data);
		
		if(typeof data == "undefined" || data.length == 0) {
			return;
		}
		
		
		var htmlProjectTypeListOption = '';
		var htmlProjectLayerListOption = '';
		
		var projectTypeList = data['projectTypeList'];
		var projectLayerList = data['projectLayerList'];
		
		$.each(projectTypeList,function(index,elem) {
			
			htmlProjectTypeListOption += '<option value='+elem['id']+'>'+elem['name']+'</option>';
		});
		
		$.each(projectLayerList, function(index, elem) {
		
			htmlProjectLayerListOption += '<option value=' + elem['id'] + '>' + elem['name'] + '</option>';
		})
		
		
		$('.edit-content #EditMenu2').empty();
		
		var html = "";
		
		html += '			<div class="row ">';
		html += '				<div class="left">';
		html += '					<div class="projectLayer">';
		html += '						<form class="layui-form" >';
		html += '							<div class="layui-form-item">';
		html += '								<div class="pushProject">';
		html += '									<button class="layui-btn layui-btn-fluid " type="button"style="background-color: #5FB878;"><i class="icon-plus-sign"></i> 发布 </button>';
		html += '								</div>';
		html += '							</div>';
		html += '							<div class="layui-form-item">';
		html += '								<div class="projectName">';
		html += '									<input type="text" name="title" required  lay-verify="required" placeholder="请输入作品标题" autocomplete="off" class="layui-input">';
		html += '								</div>';
		html += '							</div>';
		html += '							<div class="layui-form-item">';
		html += '								<div class="projectType">';
		html += ' 									<select name="" lay-search lay-verify="required" >';
		html += '                                       <option value=""></option>';
		html += 										htmlProjectTypeListOption
		html += '									</select>'
		html += '								</div>'
		html += '							</div>';
		html += '							<div class="layui-form-item">';
		html += '								<div class="projectLayer">';
		html += ' 									<select name="" lay-search lay-verify="required" >';
		html += '                                       <option value=""></option>';
		html += 										htmlProjectLayerListOption
		html += '									</select>'
		html += '								</div>'
		html += '							</div>';
		html += '							<div class="layui-form-item">';
		html += '								<div class="projectOtherInfo">';
		html += '									<textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea>';
		html += '								</div>';
		html += '							</div>';
		html += '						</form>';
		html += '					</div>';
		html += '				</div>';
		html += '				<div class="right ">';
		html += '					<div class="inner">';
		html += '						<div class="" id="pranoimg" style="">';
		html += '							<div class="chooseResource">';
		html += '								 <button class="layui-btn layui-btn-fluid" id="openPanoSource">从素材库中选择全景图</button>';
		html += ' 							</div>';
		html += '						</div>';
		html += '						<div class="projectPanoContent" style="border-top: 4px solid #3c3c3c;margin-top: 3px;">';
		html += '							<h1 style="color: #353535;text-align: center; vertical-align: middle;margin-top: 18rem;">请先填写项目名称和选择项目类型并且选择需要发布的资源</h1>';
		html += '						</div>';
		html += '					</div>';
		html += '				</div>';
		html += '			</div>';
		
		
		$('.edit-content #EditMenu2').append(html);
		
		_loadLayuiElem();
		
		
	}
	
	/******************************************************************************
	 * Desc: 作品发布
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */	
	function _pushProjectManager() {
		
		//获取数据
		utils.requestFn(
			"php/projectHandllerManager/projectDispatchMenu.php", {
				'opCode': 'getProjectType',
				'curOptionMenu': 'pushProjectManager'
			},
			_successGetProjectType,
			utils.resultError
		);
	}
	
	
	/******************************************************************************
	 * Desc: 页面初始化准备数据
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initReadyData() {
	
		//初始化资源类型列表
		function initResource() {
	
			$('.edit-nav-list ul li a:first').trigger('click');
		}
	
		//默认触达第一图层
		initResource();
		
		
		
		
	
	}
	
	function _loadLayuiElem() {
		
		var laydate;
		var form;
		layui.use(['laydate', 'form'], function() {
		
			laydate = layui.laydate;
			form = layui.form;
		
			form.render();
		
			//执行一个laydate实例
			laydate.render({
				elem: '#time_s' //指定元素
					,
				change: function(value, date, endDate) {
					ins1.hint(value); //在控件上弹出value值
					console.log(value); //得到日期生成的值，如：2017-08-18
					console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
					console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
				},
				done: function(value, date, endDate) {
					console.log(value); //得到日期生成的值，如：2017-08-18
					console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
					console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
				}
			});
			//执行一个laydate实例
			laydate.render({
				elem: '#time_e' //指定元素
			});
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
	function _successGetPanoImgLayer(data,keepData) {
		
		
		//参数校验
		if(typeof data == "undefined" || data.length == 0) {
			return;
		}
		
		if(typeof keepData == "undefined" || typeof data == "undefined" || data.length == 0) {
			return;
		}
		
		var targetName = keepData['curMenuName'];
		var curEntityClass = keepData['curEntityClass'];
		
		var htmlLayerInfo = "";
		var htmlOptionLayerInfo = "";
		var htmlCardsLayerInfo = "";
		
		
		$.each(data, function(index, elem) {
		
			var id = elem['id'];
			var layerName = elem['layerName'];
		
			//默认图层
			if(id == 1) {
				
				htmlLayerInfo += '<li data-layerId = ' + id + ' data-target=' + targetName + '  class="selected" data-entityClass = ' + curEntityClass + '><a href="javascript:;"><span class="">' + layerName + '</span></a></li>';
				htmlCardsLayerInfo += '<div class="layer row selected"id="layer' + id + '" data-target=' + targetName + '  data-entityClass = ' + curEntityClass + '></div>';
			} else {
				htmlLayerInfo += '<li data-layerId = ' + id + ' data-target=' + targetName + '  class="selected" data-entityClass = ' + curEntityClass + '><a href="javascript:;"><span class="">' + layerName + '</span></a></li>';
				htmlOptionLayerInfo += '<option value=' + id + '>' + layerName + '</option>';
				htmlCardsLayerInfo += '<div class="layer row" id="layer' + id + '" data-target=' + targetName + '  data-entityClass = ' + curEntityClass + '></div>';
			}
			
		
		})
		
		
		var html = '';
		
		html += '<div class="row" id="projectPanoImgInfo">';
		html += '	<div class="col-md-2 projectLayer">';
		html += '		<nav class="menu" data-toggle="menu">';
		html += '			<ul class="nav nav-primary">';
		html += 				htmlLayerInfo;
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
		html += 			htmlCardsLayerInfo
		html += '		</div>';
		html += '		<div class="row ">';
		html += '			<div class="col-md-12" style="text-align:right">';
		html += '				<button class="btn btn-info choosedSourceBtn" style="width:150px" onclick="">确认</button>';
		html += '			</div>'
		html += '		</div>'
		html += '	</div>';
		html += '</div>';
		
		
		//弹出全景资源窗口
		layer.open({
			title: '从素材库中添加全景',
			type: 1,
			skin: 'layui-layer-rim', //加上边框
			area: ['70%', '60%'], //宽高
			content: html
		});
		
		
		
		//触发默认图层点击事件
		var elem = '#projectPanoImgInfo .projectLayer nav.menu ul li :first ';
		
		$(elem).trigger('click');

		
	}
	
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
		
		html += '			<div class="panoResourceCurLayerInfo" data-resourceId='+id+' data-layerId='+layerId+'>';
		html += '				<div class="panoResourceCurLayerInfoImg" data-resourceId='+id+' data-layerId='+layerId+'>';
		html += '					<img src=' + fileThumbPath + ' alt=' + fileName + ' data-sourceThumbPath='+fileThumbPath+' data-sourceName='+fileName+' data-resourceId='+id+' data-layerId='+layerId+' style="width: 100%; height: 100%;">';
		html += '				</div>';
		html += '				<div class="ThumbItem_name_1J1HIB ellipsis">' + fileName + '</div>';
		html += '			</div>';
	
		$(targetElem).append(html);
	}
	
	/******************************************************************************
	 * Desc: 项目发布完成
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _afterAddPushProject(data,keepData) {
		
		//参数校验
		if(typeof data == "undefined" || data.length == 0) {
			return;
		}
		
		if(typeof keepData == "undefined" || typeof data == "undefined" || data.length == 0) {
			return;
		}
		
		//项目名称
		var projectName = keepData['projectName'];
		//项目类型名
		var projectTypeName = keepData['projectTypeName'];
		//项目类型编号
		var projectTypeVal = keepData['projectTypeVal'];
		//项目所属图层
		var projectLayerId = keepData['projectLayerId'];
		//项目ID
		var projectId = data['projectId'];
		//项目路径
		var projectPath = data['projectPath'];
		//创建时间
		var createTime = data['createTime'];
		
		
		
		var projectList = {
			
			'projectId' : projectId,
			'projectName' : projectName,
			'projectType' : projectTypeName,
			'projectPath' : projectPath,
			'projectCreateTime' : createTime,
			
		}
		
		var elem = $('.edit-content #EditMenu1 .projectManager ' + '#' + 'projectLayer' + projectLayerId);
		
		var html = _initProjectListInfo(projectLayerId,projectList);
		
		elem.append(html);
		
		
		layer.closeAll(); //关闭加载层
		
	
		
		$('#EditMenu2 .right .inner .projectPanoContent').empty();
		
		var html = "";
		html += '<h1></h1>';
		html += '<div style="text-align: center;    margin-top: 200px;">'
		html += '	<img src="img/content/pushSuccess.png" class="PanoTaskSuccess_img_33EqWs" style="height: 80px;margin-bottom: 15px;width: 80px;">';
		html += '	<div class="PanoTaskSuccess_title_3QOijx" style="font-size: 18px;margin-bottom: 10px;">上传成功</div>';
		html += '	<div class="PanoTaskSuccess_text_3hIV6q" style="color: #9b9b9b;font-size: 12px;">恭喜！作品上传成功, 已开始制作。</div>';
		html += '	<div class="PanoTaskSuccess_btns_2UrB-3" style="margin-top: 30px;">';
		html += '		<a class="StyledButton_button_3hxqk3 StyledButton_default_25Ch8E" href="javascript: void 0;" style="width: 120px; height: 34px; padding-left: 0px; padding-right: 0px;background-color: #427afb;color: #fff;line-height: 34px;    display: inline-block;">继续上传作品</a>';
		html += '		<a class="StyledButton_button_3hxqk3 StyledButton_outline_1FIY_T" href="javascript: void 0;" style="width: 120px; height: 34px; padding-left: 0px; padding-right: 0px;border: 1px solid #427afb;background-color: #427afb;color: #fff;line-height: 34px;    display: inline-block;">前往编辑作品</a>';
		html += '	</div>';
		html += '</div>';
		
		$('#EditMenu2 .right .inner .projectPanoContent').append(html);
		
	}
	
	/******************************************************************************
	 * Desc: 校验是否可以项目发布
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _checkPushProjectStep(name,typeName,layerId,selectedImginfoList) {
		
		
		if(typeof name == 'undefined' || typeof typeName == "undefined" || typeof selectedImginfoList == "undefined") {
			
			return false;
		}
		
		//项目名
		
		if(name == "") {
			
			layer.msg('请先填写项目名称', {			
				icon: 7,
				title: '提示',
				anim: 6
			});
			
			return "ignoreName";
		}
		
		//项目类型
		
		if(typeName == "") {
			
			layer.msg('请选择项目类型', {
				icon: 7,
				title: '提示',
				anim: 6
			});
			
			return "ignoreType";
		}
		
		
		//所属图层
		if(layerId == "") {
			
			layer.msg('请选择项目所属图册', {
				icon: 7,
				title: '提示',
				anim: 6
			});
			
			return "ignoreLayer";
		}
		
		//资源
		
		if(selectedImginfoList.length == 0) {
			
			layer.msg('请选择需要发布的全景资源', {
				icon: 7,
				title: '提示',
				anim: 6
			});
	
			return "ignoreSource";
		}
		
		return "nextStep";
	
	}
	
	/******************************************************************************
	 * Desc: 初始化选择之后的窗口
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initChoosedPanoImgDisplay(selectedImginfoList) {
		
		//参数校验
		if(typeof selectedImginfoList == "undefined" || selectedImginfoList.length == 0) {
			return;
		}
		
		//初始化界面
		if($('#EditMenu2 .right .inner .projectPanoContent').find('h1').length > 0) {
			$('#EditMenu2 .right .inner .projectPanoContent').empty();
		}
		
		
		var html = '';
		
		$.each(selectedImginfoList,function(index,elem) {
			
			
			var resourceId = elem['resourceid'];
			var layerId = elem['layerid'];
			var sourceThumbPath = elem['sourceThumbPath'];
			var sourceName = elem['sourceName'];
			
			html += '<div class="panoImgListDisCon">';
			html += '	<div class="panoImgListDisConSub">';
			html += '		<a href="javascript: void 0" class="panoImgListDisConSubClose">×</a>';
			html += '		<div class="panoImgListDisConSubImg">';
			html += '			<div class="panoImgListDisConSubImg">';
			html += '				<img src=' + sourceThumbPath + ' alt=' + sourceName + ' data-sourceThumbPath='+sourceName+' data-sourceName='+sourceName+' data-resourceId='+resourceId+' data-layerId='+layerId+' style="width: 100%; height: 100%;">';
			html += '			</div>';
			html += '		</div>';
			html += '		<div class="panoImgListName">';
			html += '			<div class="panoImgListNameContent">'+sourceName+'</div>';
			html += '		</div>';
			html += '	</div>';
			html += '</div>';
			
			
		});
		
		$('#EditMenu2 .right .inner .projectPanoContent').append(html);
		
		layer.closeAll();
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
	
		//目标元素
		var curTargetElem = keepData['curElem'];
	
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
	 * Desc: 新增项目图层
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _addCurProjectLayer(layerId,keepData) {
		
		
		if(typeof keepData == "undefined" || keepData.length == 0 ||  layerId == 0 || typeof layerId == "undefined" || layerId ==  null ) {
			return;
		}
		
		var layerName = keepData['value'];
		var index = keepData['index'];
		
		
		//同步数据库成功之后修改内存中的数据 前端增加图层
		var target = '#system-editPushManager .edit-content ' + '#EditMenu1' + ' .projectLayer nav.menu ul';
		$(target).append('<li class="" data-layerId = ' + layerId + '><a href="javascript:;"><span class="">' + layerName + '</span><span class="pull-right"><img class="edit"  data-layerId = ' + layerId + ' src="img/left-nav/edit3.png"/><img class="del" data-layerId = ' + layerId + ' src="img/left-nav/del.png"/></span></a></li>');
		
		//前端增加类型选择
		var htmlOptionLayerInfo = '<option value=' + layerId + '>' + layerName + '</option>';
		var targetOption = '#system-editPushManager .edit-content ' + '#EditMenu1' + ' .right .main_wrap select';
		$(targetOption).append(htmlOptionLayerInfo);
		
		layer.close(index);
		
		layer.msg('增加图层分类成功!');
	}
	
	/******************************************************************************
	 * Desc: 获取当前图层的所有项目
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _displayCurProjectLayerInfo(data,keepData) {
		
		if(typeof keepData == "undefined" || keepData.length == 0 || typeof data == "undefined" ) {
			return;
		}
		
		var layerId = keepData['layerId'];
		
		var htmlCurLayerProjectList = '';
		$.each(data, function(index, elem) {
		
			htmlCurLayerProjectList += _initProjectListInfo(layerId, elem);
		
		});
		
		var elem = $('.edit-content #EditMenu1 .projectManager ' + '#' + 'projectLayer' + layerId);
		
		elem.empty();
		
		elem.append(htmlCurLayerProjectList);
		
		
		
	}
	
	
	/******************************************************************************
	 * Desc: 删除项目图层
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _delProjectLayer(data,keepData) {
		
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
	 * Desc: 重命名项目图层
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _renameProjectLayer(data, keepData) {
	
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
	
	/******************************************************************************
	 * Desc: 移动项目图层
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _moveProjectToOtherLayer(data, keepData) {
	
		if(typeof keepData == "undefined" || keepData.length == 0 || typeof data == "undefined" || data == null) {
			return;
		}
	
		var removeElem = keepData['removeElem'];
		var ids = keepData['ids'];
	
		$.each(ids, function(index, elem) {
	
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
	 * Desc: 切换图层之后需要清除全选状态
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _changeCheckBox(elem, targetClass) {
	
		if(typeof(elem) == "undefined" || typeof targetClass == "undefined") {
			return;
		}
	
		if(elem.is(':checked')) {
			$("input[name='" + targetClass + "']").each(function() {
				this.checked = true;
			});
		} else {
			$("input[name='" + targetClass + "']").each(function() {
				this.checked = false;
			});
		}
	
	}
	
	
	
	
	
	
	/******************************************************************************
	 * Desc: 相关监听回调函数
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initHandlers()
	{
		
		
		//作品管理
		function initProjectMangerHandlers() {
			
			
			//监听资源类型切换操作
			$(document).on('click', '.edit-nav-list ul li a', function(evt) {
			
				var optionMenu = $(this).attr('data-TargetOption');
			
				//通过当前注册事件去执行对应的处理函数
				_opManagers(optionMenu);
			
			});
			
			
			//监听新增图层类型按钮
			$(document).on('click', '#system-editPushManager .edit-content nav.menu>.btn', function(evt) {
			
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
							'index': index,
						}
						
						//传递数据到数据库
						utils.requestFn(
							"php/projectHandllerManager/projectDispatchMenu.php", {
								'opCode': 'addCurProjectLayer',
								'curOptionMenu': 'projectInfoManager',
								'newName': value,
							},
							_addCurProjectLayer,
							utils.resultError,
							keepData
						);
			
					}
				});
			
			});
			
			
			//选择图层
			$(document).on('click', '#system-editPushManager .edit-content #EditMenu1 .projectLayer nav.menu ul li', function(evt) {
			
				var layerId = $(this).attr('data-layerid');
				
			
				$(this).siblings('li').removeClass('selected'); // 删除其他兄弟元素的样式
			
				$(this).addClass('selected');
			
				//控制只显示当前图层的信息
				$('.edit-content #EditMenu1 .projectManager ' + '#' + 'projectLayer' + layerId).siblings('div').removeClass('selected');
			
				$('.edit-content #EditMenu1 .projectManager ' + '#' + 'projectLayer' + layerId).addClass('selected');
			
				//切换图层时取消选中状态
				if($('#system-editPushManager .edit-content #EditMenu1 .right .main_wrap .checkbox input').is(':checked')) {
					$('#system-editPushManager .edit-content #EditMenu1 .right .main_wrap .checkbox input').prop('checked', false);
				}
			
				
			
				//同步数据到数据库
			
				var keepData = {
			
					'layerId': layerId,
				}
			
				utils.requestFn(
					
					"php/projectHandllerManager/projectDispatchMenu.php", {
						'opCode': 'getCurProjectLayerInfo',
						'curOptionMenu': 'projectInfoManager',
						'layerId': layerId,
					},
					_displayCurProjectLayerInfo,
					utils.resultError,
					keepData
				);
			
			});
			
			
			//删除图层
			$(document).on('click', '#system-editPushManager .edit-content nav.menu ul li a img.del', function(evt) {
			
				var layerId = $(this).attr('data-layerid');
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
						"php/projectHandllerManager/projectDispatchMenu.php", {
							'opCode': 'delCurProjectLayerInfo',
							'curOptionMenu': 'projectInfoManager',
							'layerId': layerId,
						},
						_delProjectLayer,
						utils.resultError,
						keepData
					);
			
				}, function(index, layero) {
			
				});
			
			});
			
			//修改名字
			$(document).on('click', '#system-editPushManager .edit-content nav.menu ul li a img.edit', function(evt) {
			
				var layerId = $(this).attr('data-layerid');
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
							"php/projectHandllerManager/projectDispatchMenu.php", {
								'opCode': 'renameCurProjectLayerInfo',
								'curOptionMenu': 'projectInfoManager',
								'layerId': layerId,
								'newName': value
							},
							_renameProjectLayer,
							utils.resultError,
							keepData
						);
			
					}
				});
			
			});
			
			//移动图层
			$(document).on('change', '#system-editPushManager .edit-content #EditMenu1 .main_wrap select', function(evt) {
			
			
				var targetElem = '#system-editPushManager .edit-content #EditMenu1' + ' nav.menu ul li.selected';
				var curLayerId;
			
				if($(targetElem).length > 0) {
					curLayerId = $(targetElem).attr('data-layerid');
				} else {
					return;
				}
			
				var layerId = $(this).val();
				if(layerId < 0) {
					return;
				}
				
				//如果移动是当前图层则直接返回不做操作
				if(curLayerId == layerId) {
					layer.msg("本项目就在当前需要移动的图层");
					return;
				}
				var ids = new Array;
				var elem = '#system-editPushManager .edit-content #EditMenu1 .projectManager' + ' #projectLayer' + curLayerId + ' .list_wrap input[name="pano_checkbox"]';
				
				$(elem).each(function() {
					if($(this).is(':checked')) {
						ids.push($(this).attr("id"));
					}
				});
			
				if(ids.length == 0) {
			
					layer.msg("请先勾选要移动的资源");
					return;
				}
			
				var removeElem = '#system-editPushManager .edit-content #EditMenu1'+ '	#projectLayer' + curLayerId;
			
				var keepData = {
					'removeElem': removeElem,
					'ids': ids,
				}
			
				//同步数据到数据库
				utils.requestFn(
					"php/projectHandllerManager/projectDispatchMenu.php", {
						'opCode': 'moveCurProjectToOtherLayer',
						'curOptionMenu': 'projectInfoManager',
						'targetLayerId': layerId,
						'curLayerId': curLayerId,
						"ids": JSON.stringify(ids)
					},
					_moveProjectToOtherLayer,
					utils.resultError,
					keepData
				);
			
			});
			
			$(document).on('change', '#system-editPushManager .edit-content #EditMenu1 .main_wrap .checkbox input', function(evt) {
			
				_changeCheckBox($(this), 'pano_checkbox');
			
			});
			
		}
		
		//作品发布
		function initProjectPushHandlers() {
			
			//选择当前生成的全景资源
			$(document).on('click', '#EditMenu2 .right .chooseResource #openPanoSource', function(evt) {
				
				
				//获取全景资源
				
				var keepData = {
					'curMenuName': 'EditMenu2',
					'curEntityClass': 'PanoImgLayer',
				}
				
				utils.requestFn(
					"php/projectHandllerManager/projectDispatchMenu.php", {
						'opCode': 'getPanoImgLayer',
						'curEntityClass':'PanoImgLayer',
						'curResourceTypeId':'1',
						'curOptionMenu': 'pushProjectManager'
					},
					_successGetPanoImgLayer,
					utils.resultError,
					keepData
				);
			
			});
			
			//全景资源图层选择
			$(document).on('click','#projectPanoImgInfo .projectLayer nav.menu ul li',function(evt) {
				
				var layerId = $(this).attr('data-layerid');
				var curEntityClass = $(this).attr('data-entityClass');
				var curElem = $(this).attr('data-target');
				
				$(this).siblings('li').removeClass('selected'); // 删除其他兄弟元素的样式
				
				$(this).addClass('selected');
				
				//控制只显示当前图层的信息
				$('#panoImgDisplayContentInfo .layer' + "#layer" + layerId).siblings('div').removeClass('selected');
				
				$('#panoImgDisplayContentInfo .layer' + "#layer" + layerId).addClass('selected');
				
				//请求当前图层下面的数据
				
				var keepData = {
				
					'layerId': layerId,
					'curEntityClass': curEntityClass,
					'curElem': curElem
				}
				
				utils.requestFn(
					"php/projectHandllerManager/projectDispatchMenu.php", {
						'opCode': 'getCurLayerSourceInfo',
						'curOptionMenu': 'pushProjectManager',
						'entityClass': curEntityClass,
						'layerId': layerId,
					},
					_displayCurResourceLayer,
					utils.resultError,
					keepData
				);
			});
			
			
			//全景资源图层选择
			$(document).on('click', '#projectPanoImgInfo #panoImgDisplayContentInfo .panoResourceCurLayerInfo .panoResourceCurLayerInfoImg img', function(evt) {
				
				if($(this).hasClass('selected')) {
					$(this).removeClass('selected');
				}else {
					$(this).addClass('selected');
				}
				
			});
			
			$(document).on('click','#projectPanoImgInfo .choosedSourceBtn',function(evt) {
				
				
				var selectedImginfoList = new Array;
				
				$('#panoImgDisplayContentInfo .panoResourceCurLayerInfo .panoResourceCurLayerInfoImg img').each(function() {
					
					if($(this).hasClass('selected')) {
						
						var resourceid = $(this).attr('data-resourceid');
						var layerid = $(this).attr('data-layerid');
						var sourceThumbPath = $(this).attr('data-sourceThumbPath');
						var sourceName = $(this).attr('data-sourceName');
						
						infoList = {
							'resourceid':resourceid,
							'layerid':layerid,
							'sourceThumbPath':sourceThumbPath,
							'sourceName':sourceName,
						};
						selectedImginfoList.push(infoList);
					}
				});
				
				if(selectedImginfoList.length == 0) {
					layer.msg('请选择资源');
					return;
				}
				
				else {
					
					_initChoosedPanoImgDisplay(selectedImginfoList);
				}
				
				
			});
			
			$(document).on('click','#EditMenu2 .left .projectLayer .pushProject button',function(evt) {
				
				//项目名
				var name = $('.projectName>input').val();
				
				//校验项目类型
				var typeName = $('.projectType>select  option:selected').val();
				var typeText = $('.projectType>select  option:selected').text();
				
				//校验项目所属图层
				var layerId = $('.projectLayer>select  option:selected').val();
				var layerName = $('.projectLayer>select  option:selected').text();
				
				//校验全景资源
				var selectedImginfoList = new Array;
				$('#EditMenu2 .right .inner .projectPanoContent .panoImgListDisCon .panoImgListDisConSubImg>img').each(function() {
					var resourceid = $(this).attr('data-resourceid');
					selectedImginfoList.push(resourceid);
				});
				
				//判断是否完成的发布之前的满足的条件工作
				var ret = _checkPushProjectStep(name,typeName,layerId,selectedImginfoList);
				
				if(ret == "nextStep") {
					
					//加载层-风格4
					
					layer.msg('发布中，请耐心等待！发布完成之后会自动跳转', {
						icon: 16,
						shade: 0.3,
						time:0
					});
					
					var keepData = {
						'projectName':name,
						'projectTypeName':typeText,
						'projectTypeVal':typeName,
						'projectLayerId':layerId
					};
					utils.requestFn(
						"php/krpanoOptionManager/krpanoDispatchOption.php", {
							'opCode': 'addPushProject',
							'selectedImginfoList': selectedImginfoList,
							'projectName':name,
							'projectTypeVal':typeName,
							'projectTypeName':typeText,
							'projectLayerId':layerId
							
						},
						_afterAddPushProject,
						utils.resultError,
						keepData
					);
					
					
				
					
				}
				else {
					//return;
				}
				
			});
			
			//删除选中的资源
			$(document).on('click','.projectPanoContent .panoImgListDisConSubClose',function(evt) {
				
				if($(this).parent().parent().length > 0) {
					$(this).parent().parent().remove();
				}
				
			});
			
			//跳转到项目管理 ，临时查看
			$(document).on('click','.StyledButton_outline_1FIY_T',function(evt) {
				$('.edit-nav-list ul li a:first').trigger('click');
			})
			
			
			//全景编辑
			$(document).on('click','#system-editPushManager .edit-content .projectManager ul li.listBtnGroup a.edit',function(evt) {
				
				var projectLayerId = $(this).attr('data-layerId');
				var projectId = $(this).attr('data-projectId');
				var projectPath = $(this).attr('data-projectPath');
				var projectName = $(this).attr('data-projectName');
		
				$('.workPlaceContainer').css('display', 'none');
				
				krpanoOnlineEditManager.buildPageHtml(projectId,projectLayerId,projectPath,projectName);
				
				krpanoOnlineEditManager.initReadyData();
				
				$('#krpanoEditContainer').css('display', 'flex');
				
				
			})
		}
		
		
		//作品管理
		initProjectMangerHandlers();
		
		//作品发布
		initProjectPushHandlers();

	}	
	
	/******************************************************************************
	 * Desc: 执行注册的操作类
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _opManagers(targetName) {
	
		if(typeof targetName == "undefined" || targetName == "") {
	
			return;
		}
	
		for(var type in _initManagers) {
	
			if(type == targetName) {
	
				if(typeof _initManagers[targetName] !== 'function') {
					return;
				}
	
				return _initManagers[targetName]();
	
			}
		}
	
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
	
		_initManagers['projectInfoManager'] = _projectInfoManager;
		_initManagers['pushProjectManager'] = _pushProjectManager;
	}
	
	
	
	
	function _init()
	{
		_registerManager();
		
		_initHandlers();
		
		_initReadyData();
		
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
