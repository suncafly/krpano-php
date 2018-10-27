define(['jquery', 'layuiModule', 'bootstrap', 'fileInput', './tour', './../Utils/Utils'], function($, layuiModule, bootstrap, fileInput, tour, utils) {

	/************************************************************************************************
	 * 
	 * 		time: 		2018.09.29
	 * 		author: 	李长明    
	 * 		info:		js统一调用krpano的方法 对外接口模块
	 * 
	 ************************************************************************************************/

	var krpano = null;

	//场景列表
	var sceneList = [];

	var sceneArray = [];

	var htmlSceneList = '';

	var movingSpot;

	var tempAddSpot = {};

	var curAddHotspotSceneIndex;

	//雷达点列表
	var radarList = [];

	var radarMap = {};

	var dataHotSpotList = {
		list: []
	};
	
	var xmlName = '';
	
	var curProjectPath = '';
	

	function _getHtmlSceneList() {
		
		return sceneList;
	}

	/******************************************************************************
	 * Desc: 获得根目录
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _getRootPath() {

		var strFullPath = window.document.location.href;
		var strPath = window.document.location.pathname;
		var pos = strFullPath.indexOf(strPath);
		var prePath = strFullPath.substring(0, pos);
		var postPath = strPath.substring(0, strPath.substr(1).indexOf('/') + 1);
		return(prePath + postPath);
	}

	/******************************************************************************
	 * Desc: 初始化成功之后的回调函数 获取唯一ID
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function krpano_onready_callback(krpano_interface) {

		krpano = krpano_interface;
		krpano = document.getElementById("panoSettingObject");

	}

	/******************************************************************************
	 * Desc: 初始化全景场景
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initPanoHtml5Setting(projectPath, projectId, projectLayerId, elem) {

		if(typeof projectPath == "undefined" || typeof elem == "undefined" || projectPath == "" || elem == "") {
			return;
		}

		var baseUrl = _getRootPath();

		curProjectPath = projectPath.substring(0, projectPath.lastIndexOf('/'))

		var settings = {};
		settings['skin_settings.thumbs'] = false;
		settings["layer[skin_control_bar].visible"] = false;
		settings["layer[skin_splitter_bottom].visible"] = false;
		settings["layer[skin_scroll_window].visible"] = false;
		settings["layer[skin_layer].visible"] = false;
		settings["layer[skin_btn_next_fs].visible"] = false;
		settings["layer[skin_btn_prev_fs].visible"] = false;
		settings["layer[skin_btn_next].visible"] = false;
		settings["layer[skin_btn_prev].visible"] = false;

		var swf = baseUrl + "/" + curProjectPath + "/tour.swf";
		xmlName = baseUrl + "/" + curProjectPath + "/tour.xml";

		if($('#panoSettingObject').length > 0) {
			return;
		}
		embedpano({
			id: "panoSettingObject",
			swf: swf,
			xml: xmlName,
			target: elem,
			html5: 'auto',
			consolelog: true,
			wmode: 'opaque-flash',
			mobilescale: 0.7,
			webglsettings: {
				preserveDrawingBuffer: true
			},
			vars: settings,
			onready: krpano_onready_callback,
		});

	}

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.08
	 * 		author: 	李长明    
	 * 		info:		热点相关  begin
	 * 
	 ************************************************************************************************/

	/******************************************************************************
	 * Desc: 修改全局热点数据
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _updateHotSpotData(newHotSpotName) {

		var hotSpotData = [];

		krpano.get("hotspot").getArray().forEach(function(everySpot) {

			if(everySpot.name !== "vr_cursor" && everySpot.name !== 'webvr_prev_scene' &&
				everySpot.name !== 'webvr_next_scene' &&
				everySpot.name !== "skin_webvr_prev_scene" &&
				everySpot.name !== "skin_webvr_next_scene") {
				dataHotSpotList.list.push(everySpot);
				var hotSpot = {};
				hotSpot.ath = everySpot.ath.toString();
				hotSpot.atv = everySpot.atv.toString();
				hotSpot.linkedscene = everySpot.linkedscene;
				hotSpot.name = everySpot.name;
				hotSpot.style = everySpot.style;
				hotSpot.title = everySpot.title;
				hotSpot.dive = everySpot.dive;
				hotSpotData.push(hotSpot);
			}
		});

		if(hotSpotData.length > 0) {
			sceneArray[curAddHotspotSceneIndex].hotSpots = hotSpotData; //todo
		}

	}

	/******************************************************************************
	 * Desc:  热点移动
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _autoMove() {

		var isAddHotSpot = true;
		krpano.call("screentosphere(mouse.x, mouse.y, mouseath, mouseatv);");
		krpano.set("hotspot[" + movingSpot.name + "].ath", krpano.get("mouseath") + movingSpot.athDis);
		krpano.set("hotspot[" + movingSpot.name + "].atv", krpano.get("mouseatv") + movingSpot.atvDis);
	}

	/******************************************************************************
	 * Desc:  获取最近的热点
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _selectHotSpot() {

		krpano.call("screentosphere(mouse.x, mouse.y, mouseath, mouseatv);");

		var nearHotSpot = {};

		krpano.get("hotspot").getArray().forEach(function(thisHotSpot) {

			var thisAthDis = krpano.get("hotspot[" + thisHotSpot.name + "]").ath - krpano.get("mouseath");
			var thisAtvDis = krpano.get("hotspot[" + thisHotSpot.name + "]").atv - krpano.get("mouseatv");
			var thisDis = Math.abs(thisAthDis) + Math.abs(thisAtvDis);
			if(!nearHotSpot.name) {
				nearHotSpot = {
					name: thisHotSpot.name,
					athDis: thisAthDis,
					atvDis: thisAtvDis,
					dis: thisDis
				};
			} else {
				if(thisDis < nearHotSpot.dis) {
					nearHotSpot = {
						name: thisHotSpot.name,
						athDis: thisAthDis,
						atvDis: thisAtvDis,
						dis: thisDis
					};
				}
			}
		});
		return nearHotSpot;

	}

	/******************************************************************************
	 * Desc: 注册热点事件
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _hotSpotInitEvent(spotName) {

		//热点按下
		krpano.get("hotspot[" + spotName + "]").ondown = function() {

			movingSpot = _selectHotSpot();
			var intervalId = setInterval(_autoMove, 1000.0 / 30.0);
			krpano.set("autoMoveIntervalId", intervalId);

		};

		krpano.get("hotspot[" + spotName + "]").onup = function() {

			window.clearInterval(krpano.get("autoMoveIntervalId"));
			_updateHotSpotData();

		};

		krpano.get("hotspot[" + spotName + "]").onclick = function() {};

		krpano.get("hotspot[" + spotName + "]").onover = function() {

		};
		krpano.get("hotspot[" + spotName + "]").onout = function() {

		};

	}

	/******************************************************************************
	 * Desc: 添加临时热点
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _addTempHotSpot(skin_hotspot_style) {

		_removeHotSpot('tempHotspot');

		// 计算中间位置的球面坐标

		krpano.set("halfHeight", krpano.get("stageheight") / 2);
		krpano.set("halfWidth", krpano.get("stagewidth") / 2);

		krpano.call("screentosphere(halfWidth,halfHeight,init_h,init_v);");

		var init_h = krpano.get("init_h");
		var init_v = krpano.get("init_v");

		//添加热点
		var newHotSpotName = 'tempHotspot';

		krpano.call("addhotspot(" + newHotSpotName + ");");
		krpano.get("hotspot[" + newHotSpotName + "]").loadstyle(skin_hotspot_style);
		krpano.set("hotspot[" + newHotSpotName + "].ath", init_h);
		krpano.set("hotspot[" + newHotSpotName + "].atv", init_v);
		krpano.set("hotspot[" + newHotSpotName + "].title", '');
		krpano.set("hotspot[" + newHotSpotName + "].linkedscene", '');

		//注册热点事件
		_hotSpotInitEvent(newHotSpotName);

	}

	/******************************************************************************
	 * Desc: 删除热点
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _removeHotSpot(name) {

		krpano.call("removehotspot(" + name + ")");

		//保存全局数据
		_updateHotSpotData();
	}

	/******************************************************************************
	 * Desc: 确定添加热点
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _addHotSpot(targetSceneName, curSceneIndex, curSceneName, skin_hotspot_style) {
		

		curAddHotspotSceneIndex = curSceneIndex;

		// 计算中间位置的球面坐标

		krpano.set("halfHeight", krpano.get("stageheight") / 2);
		krpano.set("halfWidth", krpano.get("stagewidth") / 2);

		krpano.call("screentosphere(halfWidth,halfHeight,init_h,init_v);");

		var init_h = krpano.get("init_h");
		var init_v = krpano.get("init_v");

		//添加热点
		var newHotSpotName = "spot" + new Date().getTime();

		var title = "spot" + '_' + curSceneIndex;

		krpano.call("addhotspot(" + newHotSpotName + ");");
		krpano.get("hotspot[" + newHotSpotName + "]").loadstyle(skin_hotspot_style);
		krpano.set("hotspot[" + newHotSpotName + "].ath", init_h);
		krpano.set("hotspot[" + newHotSpotName + "].atv", init_v);
		krpano.set("hotspot[" + newHotSpotName + "].title", title);
		krpano.set("hotspot[" + newHotSpotName + "].linkedscene", targetSceneName);

		//注册热点事件
		_hotSpotInitEvent(newHotSpotName);

		//保存全局数据
		_updateHotSpotData(newHotSpotName);

	}
	
	/******************************************************************************
	 * Desc: 确定添加热点
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _addHotSpotLinkUrl(curSceneIndex,title, linkStr, linkOpenTyle) {
		
		var param = {};
		
		
		curAddHotspotSceneIndex = curSceneIndex;
		
		// 计算中间位置的球面坐标
		
		krpano.set("halfHeight", krpano.get("stageheight") / 2);
		krpano.set("halfWidth", krpano.get("stagewidth") / 2);
		
		krpano.call("screentosphere(halfWidth,halfHeight,init_h,init_v);");
		
		var init_h = krpano.get("init_h");
		var init_v = krpano.get("init_v");
		
		//添加热点
		var newHotSpotName = "spot" + new Date().getTime();
		
		var title = title;
		
		krpano.call("addhotspot(" + newHotSpotName + ");");
		
		krpano.get("hotspot[" + newHotSpotName + "]").loadstyle('skin_hotspotstyle04');
		krpano.set("hotspot[" + newHotSpotName + "].ath", init_h);
		krpano.set("hotspot[" + newHotSpotName + "].atv", init_v);
		krpano.set("hotspot[" + newHotSpotName + "].title", title);
		krpano.set("hotspot[" + newHotSpotName + "].hotspotlink", linkStr);
		
		krpano.set("hotspot[" + newHotSpotName + "].hotspotlink", linkStr);
		
		//热点按下
		krpano.get("hotspot[" + newHotSpotName + "]").ondown = function() {
		
			movingSpot = _selectHotSpot();
			var intervalId = setInterval(_autoMove, 1000.0 / 30.0);
			krpano.set("autoMoveIntervalId", intervalId);
		
		};
		
		krpano.get("hotspot[" + newHotSpotName + "]").onup = function() {
		
			window.clearInterval(krpano.get("autoMoveIntervalId"));
			_updateHotSpotData();
		
		};
		
		krpano.get("hotspot[" + newHotSpotName + "]").onclick = function() {
			
			var link = krpano.get("hotspot[" + newHotSpotName + "]").hotspotlink;
			
			window.open(link, "_blank");
			
		};
	
	
		//保存全局数据
		_updateHotSpotData();
		
	}

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.08
	 * 		author: 	李长明    
	 * 		info:		热点相关  end
	 * 
	 ************************************************************************************************/

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.08
	 * 		author: 	李长明    
	 * 		info:		场景相关  begin
	 * 
	 ************************************************************************************************/

	/******************************************************************************
	 * Desc: 切换场景
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _changeScene(sceneName, sceneindex) {

		//加载指定场景
		krpano.call('loadscene(' + sceneName + ', null, MERGE);');

		//不在显示缩率图
		krpano.call("skin_showthumbs(false);");

		//当前存储对象展示
		var currentScene = sceneArray[sceneindex];

		if(currentScene.initH) krpano.set("view.hlookat", currentScene.initH);
		if(currentScene.initV) krpano.set("view.vlookat", currentScene.initV);
		if(currentScene.fov) krpano.set("view.fov", currentScene.fov);
		if(currentScene.fovmax) krpano.set("view.fovmax", currentScene.fovmax);
		if(currentScene.fovmin) krpano.set("view.fovmin", currentScene.fovmin);
		if(currentScene.autorotate) {
			krpano.set("autorotate.enabled", currentScene.autorotate.enabled);
			krpano.set("autorotate.waittime", currentScene.autorotate.waitTime);
		}
		if(currentScene.hotSpots) {
			krpano.get("hotspot").getArray().forEach(function(everySpot) {
				if(everySpot.name !== "vr_cursor" && everySpot.name !== 'webvr_prev_scene' &&
					everySpot.name !== 'webvr_next_scene' &&
					everySpot.name !== "skin_webvr_prev_scene" &&
					everySpot.name !== "skin_webvr_next_scene") {
					krpano.call("removehotspot(" + everySpot.name + ")");
				}
			});
			currentScene.hotSpots.forEach(function(everySpot) {
				krpano.call("addhotspot(" + everySpot.name + ");");
				krpano.set("hotspot[" + everySpot.name + "].ath", everySpot.ath);
				krpano.set("hotspot[" + everySpot.name + "].atv", everySpot.atv);
				krpano.set("hotspot[" + everySpot.name + "].title", everySpot.title);
				krpano.set("hotspot[" + everySpot.name + "].linkedscene", everySpot.linkedscene);
				krpano.set("hotspot[" + everySpot.name + "].dive", everySpot.dive);
				krpano.get("hotspot[" + everySpot.name + "]").loadstyle(everySpot.style);
				_hotSpotInitEvent(everySpot.name);
			});
		}

		//覆盖原热点选中事件,添加热点点击移动事件
		krpano.get("hotspot").getArray().forEach(function(oldHotSpot) {

			if(oldHotSpot.name !== 'vr_cursor' && oldHotSpot.name !== 'webvr_prev_scene' &&
				oldHotSpot.name !== 'webvr_next_scene' &&
				oldHotSpot.name !== "skin_webvr_prev_scene" &&
				oldHotSpot.name !== "skin_webvr_next_scene") {
				_hotSpotInitEvent(oldHotSpot.name);
			}
		});

	}

	/******************************************************************************
	 * Desc: 初始化場景列表
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initPanoThumbList() {

		var dataSceneList = {};
		krpano.get("scene").getArray().forEach(function(scene) {

			console.log(scene);

		});

		var sceneObj = krpano.get('scene');
		var sceneArr = sceneObj.getArray ? sceneObj.getArray() : sceneObj.indexmap;
		htmlSceneList = '';

		$('.panoListContainerPlanNeps').empty();

		$(sceneArr).each(function(idx) {

			sceneList.push(this);

			if(idx == 0) {
				htmlSceneList += '<div title=' + this.name + ' class="list" data-sceneName=' + this.name + ' data-sceneIndex=' + this.index + ' draggable="true">';
				htmlSceneList += '	<div class="panoItemThumb active">';
				htmlSceneList += '		<img src=' + this.thumburl + ' alt=' + this.name + ' style="width: 100%; height: 100%;">'
				htmlSceneList += '		<div class="more">...</div>';
				htmlSceneList += '	</div>';
				htmlSceneList += '	<p class="">' + this.name + '</p>';
				htmlSceneList += '</div>';
			} else {
				htmlSceneList += '<div title=' + this.name + ' class="list" data-sceneName=' + this.name + ' data-sceneIndex=' + this.index + ' draggable="true">';
				htmlSceneList += '	<div class="panoItemThumb ">';
				htmlSceneList += '		<img src=' + this.thumburl + ' alt=' + this.name + ' style="width: 100%; height: 100%;">'
				htmlSceneList += '		<div class="more">...</div>';
				htmlSceneList += '	</div>';
				htmlSceneList += '	<p class="">' + this.name + '</p>';
				htmlSceneList += '</div>';
			}

		});

		//初始化提交数据
		krpano.get("scene").getArray().forEach(function(scene) {
			var sceneObj = {};
			sceneObj.index = scene.index;
			sceneObj.name = scene.name;
			if(scene.name == krpano.get("startscene")) {
				sceneObj.welcomeFlag = true;
			}
			sceneArray.push(sceneObj);
		});

		$('.panoListContainerPlanNeps').append(htmlSceneList);

	}

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.08
	 * 		author: 	李长明    
	 * 		info:		场景相关  end
	 * 
	 ************************************************************************************************/
	
	
	 /************************************************************************************************
	  * 
	  * 		time: 		2018.10.08
	  * 		author: 	李长明    
	  * 		info:		雷达相关  begin
	  * 
	  ************************************************************************************************/
	 
	 /******************************************************************************
	  * Desc: 初始化雷达map，使所有雷达点位可拖拽
	  * 
	  * @param 
	  *
	  * @return 
	  *		void
	  */
	 function _initEditRadar()
	 {
		 if(krpano)
		 {
			krpano.call("InitEditRadar()");
		 }
	 }

	 
	 /******************************************************************************
	  * Desc: 创建雷达map
	  * 
	  * @param 
	  *
	  * @return 
	  *		void
	  */
	 
	 function _createRadarLayerMap(sourceThumbPath) {

		if(krpano)
		{
			radarMap.path = sourceThumbPath;
			radarMap.width = 1024;
			radarMap.height = 768;
			krpano.call("SetRadarLayerMap("+radarMap.path+","+radarMap.width+","+radarMap.height+")");
		}
	 }
	 
	 /******************************************************************************
	  * Desc: 添加雷达标记点
	  * add: 1 添加  -1 删除 0 修改
	  * @param 
	  *
	  * @return 
	  *		void
	  */
	 
	 function _addRadarSpot(targetSceneName) {
		if (krpano)
		{
			var layerobj = radarList.find(function(x) {return x.name == targetSceneName;});
			if(layerobj)
			{
				layerobj.text = targetSceneName;
				layerobj.sceneName = targetSceneName.toLowerCase();
				layerobj.x = 0;
				layerobj.y = 0;
				layerobj.rot = 0;
				layerobj.add = 0;

				krpano.call("AddRadarLayer("+layerobj.name+","+layerobj.text+","+layerobj.sceneName+")");
			}
			else
			{
				var layerobj = {
					name:targetSceneName,
					text:targetSceneName,
					sceneName:targetSceneName.toLowerCase(),
					x:0,
					y:0,
					rot:0,
					add:1
				};
				radarList.push(layerobj);
				krpano.call("AddRadarLayer("+layerobj.name+","+layerobj.text+","+layerobj.sceneName+")");
			}
		}
	 }
	 
	/******************************************************************************
	  * Desc: 删除雷达标记点
	  * 
	  * @param 
	  *
	  * @return 
	  *		void
	  */
	 
	 function _subRadarSpot(targetSceneName) {
		if (krpano)
		{
			var layerobj = radarList.find(function(x) {return x.sceneName == targetSceneName.toLowerCase();});
			if(layerobj)
			{
				radarList.remove(layerobj);
			}
			else
			{
				var layerobj = {
					name:targetSceneName,
					add:-1
				};
				radarList.push(layerobj);
			}
			krpano.call("SubRadarLayer("+targetSceneName+")");
		}
	 }

	 /******************************************************************************
	  * Desc: 设置雷达角度
	  * 
	  * @param 
	  *
	  * @return 
	  *		void
	  */
	 function _changeRadarAngle(angle) {
		if (krpano)
		{
			var sceneName = krpano.get("scene[get(xml.scene)].name");
			var layerobj = radarList.find(function(x) {return x.sceneName == sceneName;});
			if(layerobj)
			{
				layerobj.rot = angle;
			}
			krpano.call("RotRadarAngle("+angle+")");
		}
	 }
	 
	 /******************************************************************************
	  * Desc: 显隐雷达图层
	  * 
	  * @param 
	  *
	  * @return 
	  *		void
	  */
	 function _visiableRadarLayerMap(bVisiable) {
		if (krpano)
		{
			krpano.call("SetLayerMapVisible("+bVisiable+")");
		}
	 }
	 
	 
	  /************************************************************************************************
	   * 
	   * 		time: 		2018.10.08
	   * 		author: 	李长明    
	   * 		info:		雷达相关  end
	   * 
	   ************************************************************************************************/
	

	/******************************************************************************
	 * Desc: 切换场景
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _saveKrPanoAfterEdit() {
		
	 	if(krpano)
		{
			radarList.forEach(layerobj => {
				var lobj = krpano.get("layer[" + layerobj.name + "]");
				layerobj.x = lobj.x;
				layerobj.y = lobj.y;
				layerobj.rot = lobj.rot;
			}); 
		}

		var radarData = {
			
			'radarMap':radarMap,
			'radarList':radarList
		}

		var saveData = {
			
			'sceneListHost': JSON.stringify(sceneArray),
			'projectXml': curProjectPath,
			'radarData' : radarData
		}

		utils.requestFn(
			"php/krpanoOptionManager/krpanoDispatchOption.php", {
				'opCode': 'saveProjectTour',
				'saveData':saveData
			},
			function() {
				
				
				layer.msg('保存成功');
			},
			utils.resultError,
			
		);

	}

	function loadpano(xmlname) {
		if(krpano) {
			krpano.call("loadpano(" + xmlname + ", null, MERGE, BLEND(0.5));");
		}
	}

	function loadxmlstring() {
		if(krpano) {
			var xmlstring =
				'<krpano>' +
				'<preview type="grid(cube,64,64,512,0xCCCCCC,0xF6F6F6,0x999999);" />' +
				'<view hlookat="0" vlookat="0" fov="100" distortion="0.0" />' +
				'</krpano>';

			krpano.call("loadxml(" + escape(xmlstring) + ", null, MERGE, BLEND(0.5));");
		}
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

		initPanoHtml5Setting: _initPanoHtml5Setting,
		initPanoThumbList: _initPanoThumbList,
		changeScene: _changeScene,
		getHtmlSceneList: _getHtmlSceneList,
		addHotSpot: _addHotSpot,
		addTempHotSpot: _addTempHotSpot,
		saveKrPanoAfterEdit: _saveKrPanoAfterEdit,
		initEditRadar:_initEditRadar,
		createRadarLayerMap:_createRadarLayerMap,
		addRadarSpot:_addRadarSpot,
		changeRadarAngle:_changeRadarAngle,
		visiableRadarLayerMap:_visiableRadarLayerMap,
		addHotSpotLinkUrl:_addHotSpotLinkUrl,
		
	}
});