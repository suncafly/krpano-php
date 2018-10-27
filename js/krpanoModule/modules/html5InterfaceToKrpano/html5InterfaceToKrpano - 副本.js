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

	var dataHotSpotList = {
		list: []
	};
	
	//var linkSpot = [
		
	//];

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
	function _initPanoHtml5Setting(projectPath, projectId,projectLayerId, elem) {

		if(typeof projectPath == "undefined" || typeof elem == "undefined" || projectPath == "" || elem == "") {
			return;
		}

		var baseUrl = _getRootPath();
		
		var curProjectPath = projectPath.substring(0, projectPath.lastIndexOf('/'))
		

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
		
		var swf = baseUrl + "/data/krpano/1/33/tour.swf";
		var xmlName = baseUrl + "/data/krpano/1/33/tour.xml";

		//var swf = baseUrl + "/" + curProjectPath + "/tour.swf";
		//var xmlName = baseUrl + "/" + curProjectPath + "/tour.xml";
		

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
	 function _updateHotSpotData() {
	 	
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
	 	
	 	// krpano.get("hotspot[" + spotName + "]").onclick = function() {};
	 	
	 	
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
	 function _addHotSpot(targetSceneName,curSceneIndex, curSceneName,skin_hotspot_style) {
	 
	 
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
	 	_updateHotSpotData();
	 }
	 
	 
	 <hotspot name="iframelayer"
         url="black.png"
         ath="0" atv="0"
         distorted="true"
         renderer="css3d"
         onloaded="delayedcall(0,add_iframe('https://www.youtube.com/embed/p4j18C0CEEg', 640, 360));"
         />
	 //超链接
	 function _hotspotLinkUrl(title,webUrl,openStyle,style) {
		 
	   // _addHotSpot(" ",webUrl,openStyle,style);
	 }
	 
	 //图片热点
	function  _hotspotImgLink(title,photo,openStyle,style){
		 
	 }
	 //视频热点
	 function _hotspotVideoLink(title,videoaddress,openStyle,style){
		 
	 }
	 
	 //文本热点
	 function _hotspotTextLink(title,content,openStyle,style){
		 
	 }
	 
	 //音频热点
	 function _hotspotVoiceLink(title,audiofile,openStyle,style){
		 
	 }
	 
	 //其他热点
	 function _defaultSpotTypeChange(title,photo,openStyle,style){
	
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
		hotspotLinkUrl:_hotspotLinkUrl,
		hotspotImgLink:_hotspotImgLink,
		hotspotVideoLink:_hotspotVideoLink,
		hotspotTextLink:_hotspotTextLink,
		hotspotVoiceLink:_hotspotVoiceLink,
		defaultSpotTypeChange:_defaultSpotTypeChange
		
	}
});