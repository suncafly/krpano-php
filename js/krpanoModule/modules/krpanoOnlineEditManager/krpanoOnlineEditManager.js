define(['jquery', 'layuiModule', 'bootstrap', 'fileInput', './../uploadOption/uploadOption', './../Utils/Utils', './../html5InterfaceToKrpano/html5InterfaceToKrpano','./../openSourceTypeManager/openSourceTypeManager'], function($, layuiModule, bootstrap, fileInput, uploadOption, utils, html5InterfaceToKrpano,openSourceTypeManager) {

	var _pageId = "system-krpanoOnlineEditManager";
	
	var _title = '全景在线制作平台';

	var _initManagers = [];

	var html5InterfaceModule = {};
	
	var radarSlider;
	
	var curSelectHotSpotLinkUrlOpenStyle = '';
	
	
	
	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.08
	 * 		author: 	李长明    
	 * 		info:		统一接口模块
	 * 
	 ************************************************************************************************/

	html5InterfaceModule = {

		_changeScene: function(sceneName, sceneindex) {

			if(typeof sceneName == "undefined" || typeof sceneindex == "undefined") {
				return;
			}

			html5InterfaceToKrpano.changeScene(sceneName, sceneindex);

		},

		_addTempHotSpot: function(skin_hotspot_style) {

			html5InterfaceToKrpano.addTempHotSpot(skin_hotspot_style);

		},

		_addHotSpot: function(targetSceneName, curSceneIndex,curSceneName,skin_hotspot_style) {

			if(typeof targetSceneName == "undefined" || typeof curSceneIndex == "undefined" || typeof curSceneName == "undefined") {
				return;
			}

			html5InterfaceToKrpano.addHotSpot(targetSceneName,curSceneIndex, curSceneName,skin_hotspot_style);

		},
		
		_addHotSpotLinkUrl :function(curSceneIndex,title, linkStr, linkOpenTyle) {
			
			if(typeof title == "undefined" || title == "" ||
				typeof linkStr == "undefined" || linkStr == "" ||
				typeof linkOpenTyle == "undefined" || linkOpenTyle == "") {
			
				return;
			}
			
			html5InterfaceToKrpano.addHotSpotLinkUrl(curSceneIndex,title, linkStr, linkOpenTyle);
		},

		_initPanoHtml5Content :function() {

			var projectPath = $('#krpanoEditContainer').attr('data-projectPath');
			var projectId =  $('#krpanoEditContainer').attr('data-projectId');
			var projectLayerId =  $('#krpanoEditContainer').attr('data-projectLayerId');

			if(	typeof projectPath == "undefined" 	|| 	projectPath == "" ||
				typeof projectId == "undefined" 	||	projectId == "" ||
				typeof projectLayerId == "undefined" || 	projectLayerId == "") {
					
				return;
			}

			var elem = 'krpanoPanoContent';

			html5InterfaceToKrpano.initPanoHtml5Setting(projectPath, projectId,projectLayerId, elem);
			html5InterfaceToKrpano.initPanoThumbList();

		},
		
		_saveKrPanoAfterEdit: function() {
			
			html5InterfaceToKrpano.saveKrPanoAfterEdit();
		},
		
		
		_initEditRadar: function()
		{
			html5InterfaceToKrpano.initEditRadar();
		},

		_createRadarLayerMap: function(sourceThumbPath) {
			
			if(	typeof sourceThumbPath == "undefined" 	|| 	sourceThumbPath == "") {
				return;
			}
			
			html5InterfaceToKrpano.createRadarLayerMap(sourceThumbPath);
			
			
			
		},
		
		_addRadarSpot: function(targetSceneName) {
			
			if(typeof targetSceneName == "undefined" || targetSceneName == "") {
				return;
			}
			
			html5InterfaceToKrpano.addRadarSpot(targetSceneName);
		},
		
		_changeRadarAngle: function (value) {
			
			if(typeof value == "undefined" || value > 360 || value < 0) {
				
				return;
			}
			
			html5InterfaceToKrpano.changeRadarAngle(value);
		},
		
		_visiableRadarLayerMap: function(bVisiable) {
			html5InterfaceToKrpano.visiableRadarLayerMap(bVisiable);
		}

	}
	
	/******************************************************************************
	 * Desc: 打开右侧设置编辑面板
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _openSilderRightPlan() {
		
	
		$('.sidePlaneRight .sidePlaneRightContainer').css('width', '260px');
		$('.sidePlaneRight .sidePlaneRightContainer').css('right', '0px');
	}
	
	/******************************************************************************
	 * Desc: 关闭右侧设置编辑面板
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _closeSilderRightPlan() {
		
		$('.sidePlaneRight .sidePlaneRightContainer .sidePlaneRightBody').empty();
		
		$('.sidePlaneRight .sidePlaneRightContainer').css('width', '0px');
		$('.sidePlaneRight .sidePlaneRightContainer').css('right', '-10px');
	}
	

	function _buildPageHtml(projectId, projectLayerId, projectPath, projectName) {
		//======================================动态创建页面内容====================================
		var html = "";
		html += '<div id="krpanoEditContainer" data-projectPath=' + projectPath + ' data-projectId= ' + projectId + '  data-projectLayerId=' + projectLayerId + '>';
		html += '	<div class="krpanoEditHeader">';
		html += '		<div class="krpanoEditHeaderContainer">';
		html += '			<a class="back">';
		html += '				<img  src="img/krpanoEdit/back.png"/>返回';
		html += '			</a>';
		html += '			<div class="headerProjectName" id=' + projectId + '>' + projectName + '</div>';
		html += '			<div class="headerOptionBtn">';
		html += '				<span><img src="img/krpanoEdit/save.png"/>保存</span>';
		html += '				<a><img src="img/krpanoEdit/preview1.png"/>浏览</a>';
		html += '			</div>';
		html += '		</div>';
		html += '	</div>';
		html += '	<div class="krpanoEditBody">';
		html += '		<div class="krpanoEditToolBar">';
		html += '			<div style="position: absolute; overflow: hidden; width: 100%; height: 100%;">';
		html += '				<div class="edittoolBar">';
		html += '					 <div class="edittoolBarContainer">';
		html += '						<a data-targetMenu="baseSet">';
		html += '							<div class="toolBarList toolbarMatch">';
		html += '								<div class="toolbarIcon">';
		html += '									<svg width="16" height="12" viewbox="0 0 16 12" xmlns="http://www.w3.org/2000/svg">';
		html += '										<g fill="#fff" fill-rule="evenodd">';
		html += '											<path d="M0 0h2v2H0zM0 5h2v2H0zM0 10h2v2H0zM4 0h12v2H4zM4 5h12v2H4zM4 10h12v2H4z"></path>';
		html += '										</g>';
		html += '									</svg>';
		html += '								</div>基础';
		html += '							</div>';
		html += '						</a>';
		html += '						<a data-targetMenu="viewAngle">';
		html += '							<div class="toolBarList">';
		html += '								<div class="toolbarIcon">';
		html += '<svg width="16" height="12" viewbox="0 0 16 12" xmlns="http://www.w3.org/2000/svg">';
		html += '<g transform="translate(-17 -119)" stroke="#aaa" stroke-width="2" fill="none" fill-rule="evenodd">';
		html += '<path d="M31.808 125a17.781 17.781 0 0 0-1.87-2.306C28.297 120.996 26.602 120 25 120c-1.603 0-3.298.996-4.937 2.694A17.781 17.781 0 0 0 18.192 125a17.781 17.781 0 0 0 1.87 2.306C21.703 129.004 23.398 130 25 130c1.603 0 3.298-.996 4.937-2.694A17.781 17.781 0 0 0 31.808 125z"></path>';
		html += '<circle cx="25" cy="125" r="1"></circle>';
		html += '</g>';
		html += '</svg>';
		html += '								</div>视角';
		html += '							</div>';
		html += '						</a>';
		html += '						<a data-targetMenu="hotsopt">';
		html += '							<div class="toolBarList ">';
		html += '								<div class="toolbarIcon">';
		html += '									<svg width="18" height="18" viewbox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">';
		html += '										<g transform="translate(-16 -164)" fill="none" fill-rule="evenodd">';
		html += '										<circle stroke="#aaa" stroke-width="2" cx="25" cy="173" r="8"></circle>';
		html += '										<circle fill="#aaa" cx="25" cy="173" r="3"></circle>';
		html += '										</g>';
		html += '									</svg>';
		html += '								</div>热点';
		html += '							</div>';
		html += '						</a>';
		html += '						<a data-targetMenu="radar">';
		html += '							<div class="toolBarList ">';
		html += '								<div class="toolbarIcon">';
		html += '									<svg width="12" height="17" viewbox="0 0 12 17" xmlns="http://www.w3.org/2000/svg">';
		html += '										<g transform="translate(-19 -216)" fill="none" fill-rule="evenodd">';
		html += '											<path d="M25 231s5-6.239 5-9a5 5 0 1 0-10 0c0 2.761 5 9 5 9z" stroke="#aaa" stroke-width="2"></path>';
		html += '											<circle fill="#aaa" cx="25" cy="222" r="1"></circle>';
		html += '										</g>';
		html += '									</svg>';
		html += '								</div>沙盘';
		html += '							</div>';
		html += '						</a>';
		html += '						<a data-targetMenu="embed">';
		html += '							<div class="toolBarList ">';
		html += '								<div class="toolbarIcon">';
		html += '									<svg width="18" height="18" viewbox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">';
		html += '										<g transform="translate(-16 -264)" fill="none" fill-rule="evenodd">';
		html += '											<path d="M23 268c.6 0 1 .4 1 1s-.4 1-1 1-1-.4-1-1 .4-1 1-1zM20 276l2-4 2 2 3-4 3 6z" fill="#aaa"></path>';
		html += '											<circle stroke="#aaa" stroke-width="2" cx="25" cy="273" r="8"></circle>';
		html += '										</g>';
		html += '									</svg>';
		html += '								</div>遮罩';
		html += '							</div>';
		html += '						</a>';
		html += '						<a data-targetMenu="inset">';
		html += '							<div class="toolBarList ">';
		html += '								<div class="toolbarIcon">';
		html += '									<svg width="16" height="12" viewbox="0 0 16 12" xmlns="http://www.w3.org/2000/svg">';
		html += '										<g fill="#fff" fill-rule="evenodd">';
		html += '											<path d="M0 0h2v2H0zM0 5h2v2H0zM0 10h2v2H0zM4 0h12v2H4zM4 5h12v2H4zM4 10h12v2H4z"></path>';
		html += '										</g>';
		html += '									</svg>';
		html += '								</div>嵌入';
		html += '							</div>';
		html += '						</a>';
		html += '						<a data-targetMenu="music">';
		html += '							<div class="toolBarList ">';
		html += '								<div class="toolbarIcon">';
		html += '									<svg width="16" height="12" viewbox="0 0 16 12" xmlns="http://www.w3.org/2000/svg">';
		html += '										<g fill="#fff" fill-rule="evenodd">';
		html += '											<path d="M0 0h2v2H0zM0 5h2v2H0zM0 10h2v2H0zM4 0h12v2H4zM4 5h12v2H4zM4 10h12v2H4z"></path>';
		html += '										</g>';
		html += '									</svg>';
		html += '								</div>音乐';
		html += '							</div>';
		html += '						</a>';
		html += '						<a data-targetMenu="specialEffect">';
		html += '							<div class="toolBarList ">';
		html += '								<div class="toolbarIcon">';
		html += '									<svg width="16" height="12" viewbox="0 0 16 12" xmlns="http://www.w3.org/2000/svg">';
		html += '										<g fill="#fff" fill-rule="evenodd">';
		html += '											<path d="M0 0h2v2H0zM0 5h2v2H0zM0 10h2v2H0zM4 0h12v2H4zM4 5h12v2H4zM4 10h12v2H4z"></path>';
		html += '										</g>';
		html += '									</svg>';
		html += '								</div>特效';
		html += '							</div>';
		html += '						</a>';
		html += '						<a data-targetMenu="">';
		html += '							<div class="toolBarList ">';
		html += '								<div class="toolbarIcon">';
		html += '									<svg width="16" height="12" viewbox="0 0 16 12" xmlns="http://www.w3.org/2000/svg">';
		html += '										<g fill="#fff" fill-rule="evenodd">';
		html += '											<path d="M0 0h2v2H0zM0 5h2v2H0zM0 10h2v2H0zM4 0h12v2H4zM4 5h12v2H4zM4 10h12v2H4z"></path>';
		html += '										</g>';
		html += '									</svg>';
		html += '								</div>足迹';
		html += '							</div>';
		html += '						</a>';
		html += '						<a data-targetMenu="">';
		html += '							<div class="toolBarList ">';
		html += '								<div class="toolbarIcon">';
		html += '									<svg width="16" height="12" viewbox="0 0 16 12" xmlns="http://www.w3.org/2000/svg">';
		html += '										<g fill="#fff" fill-rule="evenodd">';
		html += '											<path d="M0 0h2v2H0zM0 5h2v2H0zM0 10h2v2H0zM4 0h12v2H4zM4 5h12v2H4zM4 10h12v2H4z"></path>';
		html += '										</g>';
		html += '									</svg>';
		html += '								</div>音乐';
		html += '							</div>';
		html += '						</a>';
		html += '					 </div>';
		html += '				</div>';
		html += '			</div>';
		html += '		</div>';
		html += '		<div class="krpanoEditCenter">';
		html += '			<div class="krpanoEditCenterPano">';
		html += '				<div class="krpanoBaseSet">';
		html += '				</div>';
		html += '				<div id="krpanoPanoContent" class="krpanoPanoContent selected">';
		html += '				</div>';
		html += '			</div>';
		html += '			<div class="krpanoEditCenterCategory">';
		html += '				<div class="groupList">';
		html += '				</div>';
		html += '				<div class="panoList">';
		html += '					<div class="panoListContainer">';
		html += '						<div class="panoListContainerPlan">';
		html += '							<div class="panoListContainerPlanList">';
		html += '								<div class="panoListContainerPlanNeps">';
		html += '								</div>';
		html += '							</div>';
		html += '						</div>';
		html += '					</div>';

		html += '				</div>';
		html += '			</div>';
		html += '		</div>';
		html += '		<div class="krpanoEditSidebar">';
		html += '		</div>';
		html += '		<div class="slidePlane">';
		html += '			<div class="sidePlaneRight">';
		html += '				<div class="sidePlaneRightContainer">';
		html += '					<div class="sidePlaneRightHeader">';
		html += '						<div class="sidePlaneRightHeaderTitle">开场提示';
		html += '						</div>';
		html += '						<div class="sidePlaneRightHeaderClose">';
		html += '							<img src="img/krpanoEdit/close.png"/>';
		html += '						</div>';
		html += '					</div>';
		html += '					<div class="sidePlaneRightBody">';
		html += '						<h3>测试侧边栏滑出的内容区域</h3>';
		html += '					</div>';
		
		html += '				</div>';
		html += '			</div>';
		html += '		</div>';
		html += '	</div>';
		html += '</div>';
		
		$('body').find('#krpanoEditContainer').remove();

		$('body').append(html);

		$('#krpanoEditContainer .krpanoEditBody .krpanoEditToolBar .edittoolBar .edittoolBarContainer a:first').trigger('click');
	}

	function _loadLayuiElem() {

		var laydate;
		var form;
		layui.use(['laydate', 'form'], function() {

			laydate = layui.laydate;
			form = layui.form;

			form.render();

			form.on('select(hostspotTypeSelectOption)', function(data) {

				switch(data.value) {

					case 1:
						_initHotspotSceneLink();
						break;
					case 2:
						_initHotspotSceneLink();
						break;
					case 3:
						_initHotspotSceneLink();
						break;
					case 4:
						_initHotspotSceneLink();
						break;
					default:
						_initDefaultSpotTypeChange();
						break;

				}

			});
		});
	}

	/************************************************************************************************
	 * 
	 * 		time: 		2018.09.28
	 * 		author: 	李长明    
	 * 		info:		事件处理处理模块（开始） 对应于右侧编辑操作 如需增加 请增加在本区域 
	 * 
	 ************************************************************************************************/

	/******************************************************************************
	 * Desc: 全景制作的基本设置
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _krpanoBaseInitManager() {

		$('#krpanoEditContainer .krpanoEditBody .krpanoEditCenter .krpanoEditCenterPano .krpanoBaseSet').empty();

		var html = "";
		html += '<div class="globalBaseSetFrom">';
		html += '	<div class="fromPlan">';
		html += '		<div class="name">基础设置</div>';
		html += '		<div class="base">';
		html += '			<div class="backImgThumb">';
		html += '				<img src="https://ssl-thumb.720static.com/@/resource/prod/94b3edf9s1t/b37jOdykzy8/12556161/imgs/thumb.jpg?imageMogr2/thumbnail/400" style="width: 100%; height: 100%;">'
		html += '			</div>';
		html += '			<div class="baseInfoFrom">';
		html += '			    <form class="layui-form" >';
		html += '			    	<div class="layui-form-item">';
		html += '			    		<div class="projectName">';
		html += '			    			<input type="text" name="title" required  lay-verify="required" placeholder="请输入作品标题" autocomplete="off" class="layui-input">';
		html += '			    		</div>';
		html += '			    	</div>';
		html += '			    	<div class="layui-form-item">';
		html += '			    		<div class="projectLayer">';
		html += ' 			    			<select name="" lay-search lay-verify="required" >';
		html += '                               <option value=""></option>';
		html += '                               <option value="1">室内设计</option>';
		html += '                               <option value="2">景区</option>';
		html += '                               <option value="3">学校</option>';
		html += '                               <option value="4">酒店</option>';
		html += '                               <option value="5">室内</option>';
		html += '                               <option value="6">室外</option>';
		html += '			    			</select>'
		html += '			    		</div>'
		html += '			    	</div>';
		html += '			    	<div class="layui-form-item">';
		html += '			    		<div class="projectOtherInfo">';
		html += '			    			<textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea>';
		html += '			    		</div>';
		html += '			    	</div>';
		html += '			    </form>';
		html += '			</div>';

		html += '		</div>';
		html += '		<div class="name">全局设置</div>';
		html += '		<div class="global">';
		html += '			<div class="layui-form-item">';
		html += '				<div class="pushProject">';
		html += '					<button class="layui-btn layui-btn-fluid " type="button"style="background-color: #5FB878;"><i class="icon-plus-sign"></i> 开场提示 </button>';
		html += '				</div>';
		html += '			</div>';
		html += '			<div class="layui-form-item">';
		html += '				<div class="pushProject">';
		html += '					<button class="layui-btn layui-btn-fluid " type="button"style="background-color: #5FB878;"><i class="icon-plus-sign"></i> 开场封面 </button>';
		html += '				</div>';
		html += '			</div>';
		html += '			<div class="layui-form-item">';
		html += '				<div class="pushProject">';
		html += '					<button class="layui-btn layui-btn-fluid " type="button"style="background-color: #5FB878;"><i class="icon-plus-sign"></i> 访问密码 </button>';
		html += '				</div>';
		html += '			</div>';
		html += '			<div class="layui-form-item">';
		html += '				<div class="pushProject">';
		html += '					<button class="layui-btn layui-btn-fluid " type="button"style="background-color: #5FB878;"><i class="icon-plus-sign"></i> 自定义右键 </button>';
		html += '				</div>';
		html += '			</div>';
		html += '			<div class="layui-form-item">';
		html += '				<div class="pushProject">';
		html += '					<button class="layui-btn layui-btn-fluid " type="button"style="background-color: #5FB878;"><i class="icon-plus-sign"></i> 自定义作者名 </button>';
		html += '				</div>';
		html += '			</div>';
		html += '		</div>';
		html += '		<div class="name">全局开关</div>';
		html += '		<div class="switchSet">';
		html += '			<form class="layui-form" >';
		html += '				<div class="layui-form-item">';
		html += '   				<label class="layui-form-label">小行星开场</label>';
		html += '					<div class="layui-input-inline">';
		html += '					 	<input type="checkbox" name="switch" lay-skin="switch" data-tipsInfo="以小行星特效进入" lay-text="开启|关闭">';
		html += '					</div>';
		html += '   				<label class="layui-form-label">开启陀螺仪</label>';
		html += '					<div class="layui-input-inline">';
		html += '					 	<input type="checkbox" name="switch" lay-skin="switch" data-tipsInfo="开启/关闭手机陀螺仪" lay-text="开启|关闭">';
		html += '					</div>';
		html += '   				<label class="layui-form-label">场景选择</label>';
		html += '					<div class="layui-input-inline">';
		html += '					 	<input type="checkbox" name="switch" lay-skin="switch" data-tipsInfo="开启/关闭自动打开场景列表" lay-text="开启|关闭">';
		html += '					</div>';
		html += '   				<label class="layui-form-label">视角切换</label>';
		html += '					<div class="layui-input-inline">';
		html += '					 	<input type="checkbox" name="switch" lay-skin="switch" data-tipsInfo="开启/关闭视角切换功能" lay-text="开启|关闭">';
		html += '					</div>';
		html += '				</div>';
		html += '			</form>';
		html += '		</div>';
		html += '	</div>';
		html += '</div>';
		html += '';

		$('#krpanoEditContainer .krpanoEditBody .krpanoEditCenter .krpanoEditCenterPano .krpanoBaseSet').append(html);

		_loadLayuiElem();

		//监听
		function baseSetInitHandler() {
			
			//鼠标滑过
			$(document).on('mouseenter', '.switchSet .layui-form-switch', function(evt) {

				var elem = $(this).siblings('input');
				var tipsInfo = elem.attr('data-tipsInfo');
				layer.tips(tipsInfo, $(this), {
					tips: [1, '#2c2c2c'] //还可配置颜色
				});

			});
			
			//鼠标离开
			$(document).on('mouseleave', '.switchSet .layui-form-switch', function(evt) {

				layer.closeAll('tips'); //关闭所有的tips层
			});

			$(document).on('click', '.globalBaseSetFrom .fromPlan .global .pushProject', function(evt) {

				_openSilderRightPlan();

			})

			$(document).on('click', '.sidePlaneRight .sidePlaneRightHeader .sidePlaneRightHeaderClose img', function(evt) {
				
				_closeSilderRightPlan();
				
			});
		}

		//监听
		baseSetInitHandler();

	}

	

	/******************************************************************************
	 * Desc: 全景视角的设置
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _krpanoViewAngleManager() {

		

	}

	/******************************************************************************
	 * Desc: 全景热点的设置
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _krpanoHotspotManager() {

		$('.krpanoEditBody .krpanoEditSidebar').empty();

		var html = "";
		html += '		<div class="krpanoEditSidebarHotspotContainer">';
		html += '			<form class="layui-form" >';
		html += '				<div class="layui-form-item">';
		//	 	html += '   				<label class="layui-form-label">添加热点</label>';
		html += '					<div class="layui-input-inline">';
		html += '					 	<button class="layui-btn layui-btn-fluid " type="button"style="background-color: #5FB878;"><i class="icon-plus-sign"></i> 添加热点 </button>';
		html += '					</div>';
		html += '				</div>';
		html += '			</from>';
		html += '		</div>';

		$('.krpanoEditBody .krpanoEditSidebar').append(html);

	}

	/******************************************************************************
	 * Desc: 全景雷达的设置
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _krpanoRadarManager() {
		
		
		
		$('.krpanoEditBody .krpanoEditSidebar').empty();
		
		var html = "";
		html += '		<div class="krpanoEditSidebarRadarContainer">';
		html += '			<form class="layui-form" >';
		html += '				<div class="layui-form-item">';
		html += '					<div class="layui-input-inline">';
		html += '   					<span>电子沙盘</span>';
		html += '					 	<button class="layui-btn radarSelectImg" type="button"style="margin-left: 28px;;"><i class="icon-plus-sign"></i>选择图片 </button>';
		html += '					</div>';
		html += '				</div>';
		html += '			</from>';
		html += '			<div class="radarImageContainer radarSidebarSandtable"></div>';
		html += '			<div>';
		html += '			<form class="layui-form" >';
		html += '				<div class="layui-form-item">';
		html += '					<div class="layui-input-inline">';
		html += '					 	<button class="layui-btn layui-btn-fluid addRadarSpot" type="button"style="margin-top: 28px;;"><i class="icon-plus-sign"></i>添加标记点 </button>';
		html += '					</div>';
		html += '				</div>';
		html += '			</from>';
		html += '			</div>';
		html += '		</div>';
		
		$('.krpanoEditBody .krpanoEditSidebar').append(html);
		

	}

	/******************************************************************************
	 * Desc: 全景嵌入的设置
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _krpanoEmbedManager() {
		
		
		

		
	}

	/******************************************************************************
	 * Desc: 全景音乐的设置
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _krpanoMusicManager() {

	
	}

	/******************************************************************************
	 * Desc: 全景特效的设置
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _krpanoSpecialEffectManager() {

	
	}

	/************************************************************************************************
	 * 
	 * 		time: 		2018.09.28
	 * 		author: 	李长明    
	 * 		info:		事件处理处理模块 （结束） 
	 * 
	 ************************************************************************************************/

	/******************************************************************************
	 * Desc: 相关监听回调函数
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initHandlers() {
		
		//顶部功能模块监听
		function topToolBarInitHandlers() {

			$(document).on('click', '#krpanoEditContainer .krpanoEditHeader .krpanoEditHeaderContainer a.back', function(evt) {

				$('.workPlaceContainer').css('display', 'inherit');

				$('#krpanoEditContainer').css('display', 'none');
			});
			
			$(document).on('click','#krpanoEditContainer .krpanoEditHeader .krpanoEditHeaderContainer .headerOptionBtn span',function(evt) {
				
				html5InterfaceModule._saveKrPanoAfterEdit();
			})
		}

		//左侧功能模块监听
		function leftToolBarInitHandlers() {

			$(document).on('click', '#krpanoEditContainer .krpanoEditBody .krpanoEditToolBar .edittoolBar .edittoolBarContainer a', function(evt) {

				//改变样式
				$(this).siblings('a').children('.toolBarList').removeClass('toolbarMatch'); // 删除其他兄弟元素的样式

				$(this).children('.toolBarList').addClass('toolbarMatch');

				//根据类别执行对应的回调函数
				var targetMenu = $(this).attr('data-targetMenu');

				//初始化全景
				html5InterfaceModule._initPanoHtml5Content();

				//显示基础设置面板
				if(targetMenu == "baseSet") {
					$('#krpanoEditContainer .krpanoEditBody .krpanoEditCenter .krpanoEditCenterPano .krpanoBaseSet').siblings('div').addClass('selected');
					$('#krpanoEditContainer .krpanoEditBody .krpanoEditCenter .krpanoEditCenterPano .krpanoBaseSet').removeClass('selected');

				}
				//显示全景面板
				else {
					$('#krpanoEditContainer .krpanoEditBody .krpanoEditCenter .krpanoEditCenterPano .krpanoPanoContent').siblings('div').addClass('selected');
					$('#krpanoEditContainer .krpanoEditBody .krpanoEditCenter .krpanoEditCenterPano .krpanoPanoContent').removeClass('selected');

				}

				_opManagers(targetMenu);
			})

			$(document).on('click', '.panoListContainerPlanNeps .list .panoItemThumb', function(evt) {

				//改变样式
				$(this).parent().siblings('div').children('.panoItemThumb').removeClass('active'); // 删除其他兄弟元素的样式

				$(this).addClass('active');

				var sceneName = $(this).parent().attr('data-scenename');
				var sceneindex = $(this).parent().attr('data-sceneindex');

				html5InterfaceModule._changeScene(sceneName, sceneindex);

			})
		}

		//右侧显示区域变化监听
		function sliderBarInitHandlers() {
			
			//热点相关
			function initHotsoptHandler() {
				
				$(document).on('click', '.krpanoEditSidebarHotspotContainer button', function(evt) {
					
					_closeSilderRightPlan();
				
					$('.sidePlaneRight .sidePlaneRightContainer .sidePlaneRightHeader .sidePlaneRightHeaderTitle').text('添加热点');
				
				
					var html = "";
				
					html += '<div class="hotsoptTypeContainer">';
					html += '	<div class="hotsoptTypeContainerList">';
					html += '		<div class="hotspotTypeText">选择图标</div>';
					html += '		<form class="layui-form" style="width:100%">';
					html += '			<div class="layui-form-item" >';
					html += '				<div class="">';
					html += ' 					<select name="" lay-search lay-verify="required" >';
					html += '                       <option value=""></option>';
					html += '                       <option value="1">系统图标</option>';
					html += '                       <option value="2">自定义图标</option>';
					html += '                       <option value="3">多边形图标</option>';
					html += '					</select>'
					html += '				</div>'
					html += '			</div>';
					html += '		</form>';
					html += '		<div class="typeList">';
					html += '			<div class="typeListContainer">';
				
					html += '				<div class="hotspotIcon active">';
					html += '					<img class="skin_hotspotstyle01" src="https://ssl-player.720static.com/hotspot/80/new_spotd1_gif.png?v2" style="width: 100%; height: 100%;">';
					html += '				</div>';
					html += '				<div class="hotspotIcon ">';
					html += '					<img class="skin_hotspotstyle02" src="https://ssl-player.720static.com/hotspot/80/new_spotd2_gif.png?v2" style="width: 100%; height: 100%;">';
					html += '				</div>';
					html += '				<div class="hotspotIcon ">';
					html += '					<img class="skin_hotspotstyle03" src="https://ssl-player.720static.com/hotspot/80/spot13.png?v2" style="width: 100%; height: 100%;">';
					html += '				</div>';
					html += '				<div class="hotspotIcon ">';
					html += '					<img class="skin_hotspotstyle04" src="https://ssl-player.720static.com/hotspot/80/spotd5_gif.png?v2" style="width: 100%; height: 100%;">';
					html += '				</div>';
					html += '			</div>';
					html += '		</div>';
					html += '		<div class="hotspotTypeText">选择热点类型</div>';
					html += '		<div class="hostspotTypeSelectOption">';
					html += '		    <form class="layui-form" style="width:100%">';
					html += '		    	<div class="layui-form-item" >';
					html += '		    		<div class="">';
					html += ' 		    			<select name="" lay-search lay-verify="required" lay-filter="hostspotTypeSelectOption">';
					html += '                           <option value=""></option>';
					html += '                           <option value="1">全景切换</option>';
					html += '                           <option value="2">超链接</option>';
					html += '                           <option value="3">图片热点</option>';
					html += '                           <option value="4">视屏热点</option>';
					html += '                           <option value="5">文本热点</option>';
					html += '                           <option value="6">音频热点</option>';
					html += '		    			</select>'
					html += '		    		</div>'
					html += '		    	</div>';
					html += '		    </form>';
					html += '		</div>';
					html += '		<div class="hostspotTypeSelectDisplayContent">';
					html += '		</div>';
					html += '		<div class="sidePlaneRightFooter">';
					html += '			<a class="sidePlaneRightFooterBtn" href="javascript: void 0;" style="width: 120px; height: 34px; padding-left: 0px; padding-right: 0px;">完 成</a>';
					html += '		</div>';
					html += '	</div>';
					html += '</div>';
				
					$('.sidePlaneRight .sidePlaneRightContainer .sidePlaneRightBody').append(html);
					
					_openSilderRightPlan();
				
					
				
					/******************************************************************************
					 * Desc: 全景切换
					 * 
					 * @param 
					 *
					 * @return 
					 *		void
					 */
					function _initHotspotSceneLink() {
				
						$('.hostspotTypeSelectDisplayContent').empty();
				
						var sceneList = html5InterfaceToKrpano.getHtmlSceneList();
				
						var htmlSceneList = '';
				
						$(sceneList).each(function(idx) {
							if(idx == 0) {
								htmlSceneList += '<div class="hotspotSwitchPanoListItem">';
								htmlSceneList += '	<div class="thumb active">';
								htmlSceneList += '		<img src=' + this.thumburl + ' alt=' + this.name + ' style="width: 100%; height: 100%;">';
								htmlSceneList += '	</div>';
								htmlSceneList += '	<div class="itemName ellipsis">' + this.name + '</div>';
								htmlSceneList += '</div>';
							} else {
								htmlSceneList += '<div class="hotspotSwitchPanoListItem">';
								htmlSceneList += '	<div class="thumb ">';
								htmlSceneList += '		<img src=' + this.thumburl + ' alt=' + this.name + ' style="width: 100%; height: 100%;">';
								htmlSceneList += '	</div>';
								htmlSceneList += '	<div class="itemName ellipsis">' + this.name + '</div>';
								htmlSceneList += '</div>';
							}
				
						});
				
						var html = "";
						html += '		<div class="hotspotSwitchHeader">';
						html += '			<div class="title">场景列表</div>';
						html += '		</div>';
						html += '		<div class="sceneList">';
						html += '			<div class="hotspotSceneListContainer">';
						html += '				<div class="hotspotSwitchPanoList">';
						html += htmlSceneList
						html += '				</div>';
						html += '			</div>';
						html += '		</div>';
				
						$('.hostspotTypeSelectDisplayContent').append(html);
					}
				
					/******************************************************************************
					 * Desc: 超链接
					 * 
					 * @param 
					 *
					 * @return 
					 *		void
					 */
					function _initHotspotLinkUrl() {
						
						$('.hostspotTypeSelectDisplayContent').empty();
						
						var html = '';
				
						html += '<form class="layui-form" >';
						html += '	<div class="layui-form-item">';
						html += '		<div class="linkUrlTitle">';
						html += '			<input type="text" name="title" required  lay-verify="required" placeholder="填写标题" autocomplete="off" class="layui-input">';
						html += '		</div>';
						html += '	</div>';
						html += '	<div class="layui-form-item">';
						html += '		<div class="linkUrl">';
						html += '			<input type="text" name="title" required  lay-verify="required" placeholder="填写链接地址" autocomplete="off" class="layui-input">';
						html += '		</div>';
						html += '	</div>';
						html += '	<div class="layui-form-item">';
						html += '		<div class="hostspotTypeLinkUrlOpen">';
						html += ' 			<select name="" lay-search lay-verify="required" >';
						html += '               <option value=""></option>';
						html += '               <option value="1">本窗口打开</option>';
						html += '               <option value="2">新窗口打开</option>';
						html += '               <option value="3">弹出层</option>';
						html += '			</select>'
						html += '		</div>'
						html += '	</div>';
						html += '</form>';
						
						$('.hostspotTypeSelectDisplayContent').append(html);
						
						//热点切换事件监听
						layui.use(['laydate', 'form'], function() {
						
							laydate = layui.laydate;
							form = layui.form;
						
							form.render();
						
							form.on('select(hostspotTypeLinkUrlOpen)', function(data) {
								console.log(data.value);
								curSelectHotSpotLinkUrlOpenStyle = data.value;
						
							});
						});
						
						
						
						//html5InterfaceModule._addHotSpotLinkUrl(title, link, openStyle);
					}
				
					/******************************************************************************
					 * Desc: 图片热点
					 * 
					 * @param 
					 *
					 * @return 
					 *		void
					 */
					function _initHotspotImgLink() {
				
						$('.hostspotTypeSelectDisplayContent').empty();
				
						var html = "<p>图片热点类型正在努力开发中！</p>";
				
						$('.hostspotTypeSelectDisplayContent').append(html);
						
						
					}
				
					/******************************************************************************
					 * Desc: 视屏热点
					 * 
					 * @param 
					 *
					 * @return 
					 *		void
					 */
					function _initHotspotVideoLink() {
				
						$('.hostspotTypeSelectDisplayContent').empty();
				
						var html = "<p>视屏热点类型正在努力开发中！</p>";
				
						$('.hostspotTypeSelectDisplayContent').append(html);
					}
				
					/******************************************************************************
					 * Desc: 文本热点
					 * 
					 * @param 
					 *
					 * @return 
					 *		void
					 */
					function _initHotspotTextLink() {
				
						$('.hostspotTypeSelectDisplayContent').empty();
				
						var html = "<p>文本热点类型正在努力开发中！</p>";
				
						$('.hostspotTypeSelectDisplayContent').append(html);
				
					}
				
					/******************************************************************************
					 * Desc: 音频热点
					 * 
					 * @param 
					 *
					 * @return 
					 *		void
					 */
					function _initHotspotVoiceLink() {
				
						$('.hostspotTypeSelectDisplayContent').empty();
				
						var html = "<p>音频热点类型正在努力开发中！</p>";
				
						$('.hostspotTypeSelectDisplayContent').append(html);
				
					}
				
					/******************************************************************************
					 * Desc: 其他类型
					 * 
					 * @param 
					 *
					 * @return 
					 *		void
					 */
					function _initDefaultSpotTypeChange() {
				
						$('.hostspotTypeSelectDisplayContent').empty();
				
						var html = "<p>其他类型正在努力开发中！</p>";
				
						$('.hostspotTypeSelectDisplayContent').append(html);
				
					}
				
					//热点切换事件监听
					layui.use(['laydate', 'form'], function() {
				
						laydate = layui.laydate;
						form = layui.form;
				
						form.render();
				
						form.on('select(hostspotTypeSelectOption)', function(data) {
							console.log(data.value);
							switch(data.value) {
				
								case '1':
									_initHotspotSceneLink();
									break;
								case '2':
									_initHotspotLinkUrl();
									break;
								case '3':
									_initHotspotImgLink();
									break;
								case '4':
									_initHotspotVideoLink();
									break;
								case '5':
									_initHotspotTextLink();
									break;
								case '6':
									_initHotspotVoiceLink();
									break;
								default:
									_initDefaultSpotTypeChange();
									break;
				
							}
				
						});
					});
				
				});
				
				$(document).on('click', '.hotsoptTypeContainer .typeListContainer .hotspotIcon', function(evt) {
				
					//改变样式
					$(this).siblings('div').removeClass('active'); // 删除其他兄弟元素的样式
				
					$(this).addClass('active');
				
					var skin_hotspot_style = $(this).children('img').attr('class');
				
					//html5InterfaceModule._addTempHotSpot(skin_hotspot_style);
				
					//html5InterfaceModule._addHotSpot("scene_cr-0tgwb", '0')
				
				});
				
				$(document).on('click', '.hotspotSceneListContainer .hotspotSwitchPanoListItem .thumb', function(evt) {
				
					//改变样式
					$(this).parent().siblings('div').children('.thumb').removeClass('active'); // 删除其他兄弟元素的样式
				
					$(this).addClass('active');
				});
				
				$(document).on('click', '.hotsoptTypeContainer .sidePlaneRightFooter .sidePlaneRightFooterBtn', function(evt) {
				
				
					var hotspotTypeValue = $('.hostspotTypeSelectOption').find('select').val();
					
					switch (hotspotTypeValue) {
						
						case '1':
							//完成添加场景热点
							_finishAddhotspotSceneChange();
							break;
						case '2':
							//完成添加超链接热点
							_finishAddhotspotLinkUrl();
							break;
						case '3':
							//完成添加图片热点
							_finishHotspotImgLink();
							break;
						case '4':
							//完成添加视频热点
							_finishHotspotVideoLink();
							break;
						case '5':
							//完成添加文字热点
							_finishHotspotTextLink();
							break;
						case '6':
							//完成添加音频热点
							_finishHotspotVoiceLink();
							break;
						
					}
					
					
					
					//完成添加场景热点
					function _finishAddhotspotSceneChange() {
					
						//获取选择的热点图标
						var skin_hotspot_style = '';
						var targetSceneName = '';
						var curSceneName = '';
						var curSceneIndex = '';
						var imgHtml = '';
						var elem = $('.hotsoptTypeContainer .typeListContainer div.active');
						if(elem.length > 0) {
						
							skin_hotspot_style = elem.children('img').attr('class');
							imgHtml = elem.children('img').prop('outerHTML');
						} else {
						
							layer.msg('请先选择热点图标');
							return;
						}
						
						var targetSceneElem = $('.hotspotSceneListContainer .hotspotSwitchPanoListItem .thumb.active');
						
						if(targetSceneElem.length > 0) {
						
							targetSceneName = targetSceneElem.children('img').attr('alt');
						
						} else {
							layer.msg('请选择目标场景');
							return;
						}
						
						//获取当前场景
						var curSceneElem = $('.panoListContainerPlanNeps .list .panoItemThumb.active');
						
						if(curSceneElem.length > 0) {
						
							curSceneName = curSceneElem.parent('div').attr('data-scenename');
							curSceneIndex = curSceneElem.parent('div').attr('data-sceneindex');
						
						} else {
							layer.msg('请选择当前场景');
							return;
						}
						
						html5InterfaceModule._addHotSpot(targetSceneName, curSceneIndex, curSceneName, skin_hotspot_style);
						
						_initHotSplotDisplayContent(curSceneName, curSceneIndex, imgHtml);
					}
					
					//完成添加超链接热点
					function _finishAddhotspotLinkUrl() {
						
						var linkOpenTyle = $('.hostspotTypeLinkUrlOpen').find('select').val();
						if(typeof linkOpenTyle == "undefined") {
							linkOpenTyle = '1';
						}
						
						//标题
						var title = $('.linkUrlTitle').find('input').val();
						
						//连接
						var linkStr = $('.linkUrl').find('input').val();
						
						if(linkStr.indexOf('http://') != 0 && linkStr.indexOf('https://') != 0) {
							linkStr = 'http://' + linkStr;
						}
						
						//获取当前场景
						var curSceneElem = $('.panoListContainerPlanNeps .list .panoItemThumb.active');
						
						if(curSceneElem.length > 0) {
						
							curSceneName = curSceneElem.parent('div').attr('data-scenename');
							curSceneIndex = curSceneElem.parent('div').attr('data-sceneindex');
						
						} else {
							layer.msg('请选择当前场景');
							return;
						}
						
						html5InterfaceModule._addHotSpotLinkUrl(curSceneIndex,title, linkStr, linkOpenTyle);
						
						
						
					}
					
					//完成添加图片热点
					function _finishHotspotImgLink() {
						
					}
					
					
					//完成添加视频热点
					function _finishHotspotVideoLink() {
						
					}
					
					//完成添加文字热点
					function _finishHotspotTextLink() {
						
					}
					
					//完成添加音频热点
					function _finishHotspotVoiceLink() {
						
					}
					
				
					/******************************************************************************
					 * Desc: 添加热点成功之后显示热点列表
					 * 
					 * @param 
					 *
					 * @return 
					 *		void
					 */
					function _initHotSplotDisplayContent(curSceneName, curSceneIndex, imgHtml) {
				
						if(typeof(curSceneName) == "undefined" || typeof curSceneIndex == "undefined" || typeof imgHtml == "undefined") {
				
							return;
						}
				
						var html = '';
						html += '<div class="sidebarHotspotItem">';
						html += '	<div class="sidebarHotspotItemIcon">';
						html += imgHtml
						html += '	</div>';
						html += '	<div class="sidebarHotspotItemName ellipsis" data-sceneIndex=' + curSceneIndex + '>' + curSceneName + '</div>';
						html += '	<div class="sidebarHotspotItemTypeName">全景切换</div>';
						html += '</div>';
				
						$('.krpanoEditSidebarHotspotContainer').append(html);
				
						$('.sidePlaneRight .sidePlaneRightHeader .sidePlaneRightHeaderClose img').trigger('click');
				
						$(document).on('click', '.sidebarHotspotItem', function(evt) {
				
							$('.krpanoEditSidebarHotspotContainer button').trigger('click');
						})
				
					}
				
				});
			}
			
			function initRadarHandler() {
				
				
				$(document).on('click', '.krpanoEditSidebarRadarContainer .radarSelectImg', function(evt) {
				
				
					var opt = {
					
						openResourceType: 'BasicMaterialLayer',
					
						title: "从资源库中选择",
					
						before: function() {
					
						},
					
						success: function(response,resourceIndex) {
		
							layer.close(resourceIndex);
							
							//初始化雷达信息
							$('.radarImageContainer').empty();
							
							//参数校验
							if(typeof response == "undefined" || response.length == 0) {
								return;
							}
							
							if(response.length != 1) {
								
								layer.msg('雷达底图每次只能选择一个');
								return;
							}
							
							
							
							var html = '';
							var sourceThumbPath = response[0].sourceThumbPath;
							html += '<img class="radarBackgroudImg" src='+sourceThumbPath+'>';
//							html += '<img class="radarBackgroudDel" src="https://ssl-media.720static.com/@/b37jOdykzy8/2/62bb1538eabdc1e2939b5aa3c52e1fc5.png">';
							
							$('.radarImageContainer').append(html);
										
//							//创建radar map			
							html5InterfaceModule._createRadarLayerMap(sourceThumbPath);

						},
					
					}

					openSourceTypeManager.openCurSource(opt);	
				});
				
				$(document).on('click', '.krpanoEditSidebarRadarContainer .addRadarSpot', function(evt) {
				
				
					_closeSilderRightPlan();
					
					$('.sidePlaneRight .sidePlaneRightContainer .sidePlaneRightHeader .sidePlaneRightHeaderTitle').text('场景选择');
					
					var sceneList = html5InterfaceToKrpano.getHtmlSceneList();
					
					var htmlSceneList = '';
					
					$(sceneList).each(function(idx) {
						if(idx == 0) {
							htmlSceneList += '<div class="hotspotSwitchPanoListItem">';
							htmlSceneList += '	<div class="thumb active">';
							htmlSceneList += '		<img src=' + this.thumburl + ' alt=' + this.name + ' style="width: 100%; height: 100%;">';
							htmlSceneList += '	</div>';
							htmlSceneList += '	<div class="itemName ellipsis">' + this.name + '</div>';
							htmlSceneList += '</div>';
						} else {
							htmlSceneList += '<div class="hotspotSwitchPanoListItem">';
							htmlSceneList += '	<div class="thumb ">';
							htmlSceneList += '		<img src=' + this.thumburl + ' alt=' + this.name + ' style="width: 100%; height: 100%;">';
							htmlSceneList += '	</div>';
							htmlSceneList += '	<div class="itemName ellipsis">' + this.name + '</div>';
							htmlSceneList += '</div>';
						}
					
					});
					
					var html = "";
					html += '		<div class="sceneList">';
					html += '			<div class="radarSceneListContainer">';
					html += '				<div class="hotspotSwitchPanoList">';
					html += htmlSceneList
					html += '				</div>';
					html += '			</div>';
					html += '		</div>';
					html += '		<div class="sidePlaneRightRadarFooter">';
					html += '			<a class="sidePlaneRightRadarFooterBtn" href="javascript: void 0;" style="width: 120px; height: 34px; padding-left: 0px; padding-right: 0px;">完 成</a>';
					html += '		</div>';

					
					$('.sidePlaneRight .sidePlaneRightContainer .sidePlaneRightBody').append(html);	
					
					_openSilderRightPlan();
					
				
				});
				
				$(document).on('click', '.radarSceneListContainer .hotspotSwitchPanoListItem .thumb', function(evt) {
				
					//改变样式
					$(this).parent().siblings('div').children('.thumb').removeClass('active'); // 删除其他兄弟元素的样式
				
					$(this).addClass('active');
				});
				
				$(document).on('click', '.sidePlaneRightRadarFooter .sidePlaneRightRadarFooterBtn', function(evt) {
				
					//获取选择的热点图标
					
					var targetSceneName = '';
					
					var targetSceneElem = $('.radarSceneListContainer .hotspotSwitchPanoListItem .thumb.active');
		
					if(targetSceneElem.length > 0) {
		
						targetSceneName = targetSceneElem.children('img').attr('alt');
		
					} else {
						layer.msg('请选择目标场景');
						return;
					}
		
		
					html5InterfaceModule._addRadarSpot(targetSceneName);
		
					_initRadarSpotDisplayContent(targetSceneName);
					
					
					/******************************************************************************
					 * Desc: 添加热点成功之后显示热点列表
					 * 
					 * @param 
					 *
					 * @return 
					 *		void
					 */
					function _initRadarSpotDisplayContent(targetSceneName) {
					
						if(typeof(targetSceneName) == "undefined" || typeof targetSceneName == "undefined") {
					
							return;
						}
						
						
						var html = '';
						html += '<div class="radarSpotItem">';
						html += '	<div class="radarSpotItemActive"></div>';
						html += '	<div class="radarSpotItemTitle ellipsis">'+targetSceneName+'</div>';
						html += '</div>';
						
						if($('.radarSpotItem').length < 1) {
							
							html += '<div class="radarFlovSet">';
							html += '</div>';	
						}
						
					
						$('.krpanoEditSidebarRadarContainer').append(html);
					
						_closeSilderRightPlan();
						
						
						$('.radarFlovSet').empty();
						
						var html = '';
						html += '<span>调整雷达角度</span>';
						html += '<div id="slideRadarFlovSet" class="demo-slider"></div>';
						
						$('.radarFlovSet').append(html);
					}
			 	});
				
				$(document).on('click', '.radarSpotItem', function(evt) {
					
							
					$(this).siblings('div').children('.radarSpotItemActive').removeClass('active');
					
					$(this).children('.radarSpotItemActive').addClass('active');
			
					if(typeof radarSlider == "undefined") { 
						
						layui.use('slider', function() {
						
							var slider = layui.slider;
						
							//开启输入框
							radarSlider = slider.render({
								min: 0,
								max: 360,
								elem: '#slideRadarFlovSet',
								input: true, //输入框
								change: function(value) {
						
									console.log(value) //得到开始值
									html5InterfaceModule._changeRadarAngle(value);
						
								}
							});
						
						})
					}
				
				});
					
			}
			//热点相关
			initHotsoptHandler();
			
			//雷达相关
			initRadarHandler();
			
		}

		//下侧全景图层区域监听
		function panoBottomBarInitHandlers() {

		}

		//顶部功能模块监听
		topToolBarInitHandlers();

		//左侧功能模块监听
		leftToolBarInitHandlers();

		//右侧显示区域变化监听
		sliderBarInitHandlers();

		//下侧全景图层区域监听
		panoBottomBarInitHandlers();
		
		
		openSourceTypeManager.initHandlerSourceChoose();
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
				
				_closeSilderRightPlan();
				if(targetName != 'radar') {
					html5InterfaceModule._visiableRadarLayerMap(false);
				}
				else
				{
					html5InterfaceModule._initEditRadar();
					html5InterfaceModule._visiableRadarLayerMap(true);
				}
				
				return _initManagers[targetName]();

			}
		}

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

			$('#krpanoEditContainer .krpanoEditBody .krpanoEditToolBar .edittoolBar .edittoolBarContainer a:first').trigger('click');
		}

		//默认触达第一图层
		initResource();
		
		

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

		//基础设置
		_initManagers['baseSet'] = _krpanoBaseInitManager;

		//视角
		_initManagers['viewAngle'] = _krpanoViewAngleManager;

		//热点
		_initManagers['hotsopt'] = _krpanoHotspotManager;

		//雷达
		_initManagers['radar'] = _krpanoRadarManager;

		//嵌入
		_initManagers['embed'] = _krpanoEmbedManager;

		//音乐
		_initManagers['music'] = _krpanoMusicManager;

		//特效
		_initManagers['specialEffect'] = _krpanoSpecialEffectManager;

	}

	function _init() {
		
		_registerManager();

		_initHandlers();

		_initReadyData();

	}

	function _resize() {

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