define(['jquery', 'layuiModule', 'bootstrap', 'fileInput', './tour', './../Utils/Utils'], function($, layuiModule, bootstrap, fileInput, tour, utils) {

	/************************************************************************************************
	 * 
	 * 		time: 		2018.09.29
	 * 		author: 	李长明    
	 * 		info:		js统一调用krpano的方法 对外接口模块
	 * 
	 ************************************************************************************************/

	//=================================================================================================
	//全局数据
	var globalData = {

		krpano: null,

		xmlName: '',

		curProjectPath: '',

		//场景拖拽对象
		dragSceneObj: null,
		//分组拖拽对象
		dragGroupObj: null,
		//是否可以拖拽
		bCanDrag: false,

		//当前显示分组
		curGroupTitle: "",
		
		//当前选中的热点
		movingSpot: {},
		
		clearGlobalData: function() {
			
			globalData.clearData();
			globalKrpanoData.clearData();
			krpanoSaveData.clearData();
		},

		//清除数据
		clearData: function() {

			krpano = null;
			xmlName = '';
			curProjectPath = '';
			//场景拖拽对象
			dragSceneObj: null;
			//分组拖拽对象
			dragGroupObj: null;
			//是否可以拖拽
			bCanDrag: false;
			//当前显示分组
			curGroupTitle: "";
		},

		//更新数据
		updateData: function() {

		},
		
		//===============================================================
		//获取 项目xml
		getXmlName: function() {
			return this.xmlName;
		},

		//设置 项目xml
		setXmlName: function(data) {
			this.xmlName = data;
		},
		
		//===============================================================
		//获取 项目路径
		getCurProjectPath: function() {
			return this.curProjectPath;
		},

		//设置 项目路径
		setCurProjectPath: function(data) {
			this.curProjectPath = data;
		},

		//===============================================================
		//获取 krpano
		getKrpano: function() {
			return this.krpano;
		},

		//设置 krpano
		setKrpano: function(data) {
			this.krpano = data;
		},

		//================================================================
		//获得场景拖拽对象
		getDragSceneObj: function() {
			return this.dragSceneObj;
		},

		//设置场景拖拽对象
		setDragSceneObj: function(data) {
			this.dragSceneObj = data;
		},

		//================================================================
		//获得场景拖拽对象
		getDragGroupObj: function() {
			return this.dragGroupObj;
		},

		//设置场景拖拽对象
		setDragGroupObj: function(data) {
			this.dragGroupObj = data;
		},

		//================================================================
		//获取是否拖拽标志
		getBCanDrag: function() {
			return this.bCanDrag;
		},
		//设置是否可以拖拽标志
		setBCanDrag: function(data) {

			if(typeof data == "boolean") {
				this.bCanDrag = data;
			}
		},

		//================================================================
		//获取当前激活显示分组
		getCurGroupTitle: function() {
			return this.curGroupTitle;
		},
		//设置是否可以拖拽标志
		setCurGroupTitle: function(data) {

			if(typeof data != "undefined") {
				this.curGroupTitle = data;
			}
		},
		
		//================================================================
		//获取当前拖拽选中的热点
		getMovingSpot: function() {
			return this.movingSpot;
		},
		//设置当前拖拽选中的热点
		setMovingSpot: function(data) {
	
			if(typeof data != "undefined") {
				this.movingSpot = data;
			}
		},

	};

	//=================================================================================================
	//全局全景内存数据
	var globalKrpanoData = {

		clearData: function() {

			this.scenes.clearData();
			this.groups.clearData()
		},

		//场景数据
		scenes: {

			sceneList: [],
			//清除数据
			clearData: function() {

				this.sceneList.splice(0, this.sceneList.length);
			},
			//更新数据
			updateData: function(sceneObj) {

				this.sceneList.push(sceneObj);
			},
			//获取数据
			getData: function() {
				return this.sceneList;
			}
		},

		//分组数据
		groups: {

			groupData: [],

			//清除数据
			clearData: function() {
				this.groupData.splice(0, this.groupData.length);
			},

			//更新数据
			updateData: function(groupObj) {

				if(typeof(groupObj) == "object") {
					this.groupData.push(groupObj);
				}
			},

			//获取数据
			getData: function() {
				return this.groupData;
			},

			//设置数据
			setData: function(data) {
				if(typeof data != 'undefined') {
					this.groupData = data;
				}

			},
			//删除数据
			delData: function(title) {
				
				if(typeof title == "undefined" || title == "") {
					return;
				}
				//找到对应元素
				$.each(this.groupData,function(index,elem) {
					
					if(elem['title'] == title) {
						this.groupData.splice(index, 1);
					}
				});
				
			},
			//重命名分组
			renameData: function(oldTitle, newTitle) {
				
				//参数校验
				if(typeof oldTitle == "undefined" || oldTitle == "" || typeof newTitle == "undefined" || newTitle == "") {
					return;
				}
				
				$.each(this.groupData, function(index, elem) {
				
					if(elem['title'] == oldTitle) {
						elem['title'] = newTitle;
					}
				});
				
			}

		},
		hotspot : {
			
			hotSpotList: [],
			
			//清除数据
			clearData: function() {
				this.hotSpotList.splice(0, this.hotSpotList.length);
			},
		
			//更新数据
			updateData: function(hotSpotObj) {
		
				if(typeof(hotSpotObj) == "object") {
					this.hotSpotList.push(hotSpotObj);
				}
			},
		
			//获取数据
			getData: function() {
				return this.hotSpotList;
			},
		
			//设置数据
			setData: function(data) {
				if(typeof data != 'undefined') {
					this.hotSpotList = data;
				}
		
			}
		},

	}

	//=================================================================================================
	//还未保存的内存数据
	var krpanoSaveData = {

		clearData: function() {
		
			this.readySavedScenes.clearData();
			this.readySavedGroups.clearData();
			this.readySavedHotspot.clearData();
			
		},
		
		//场景数据
		readySavedScenes: {
			
			sceneAppendList: [],
			
			sceneList: [],
			
			//清除数据
			clearData: function() {
	
				this.sceneList.splice(0, this.sceneList.length);
				this.sceneAppendList.splice(0, this.sceneAppendList.length);
			},
			
			//更新场景组合数据
			updateSceneList: function(sceneObj) {
	
				this.sceneList.push(sceneObj);
			},
			
			//获取场景组合数据
			getSceneList: function() {
				return this.sceneList;
			},
			//设置场景组合数据
			setSceneList: function(data) {
				
				if(typeof data != "undefined") {
					this.sceneList = data;
				}
			},
			
			//更新编辑时候追加场景
			updateSceneAppendList: function(sceneString) {
				
				if(typeof sceneString != "undefined" && sceneString != "") {
					
					this.sceneAppendList.push(sceneString);
					
				}
				
			},
			
			//获取编辑时候追加场景
			getSceneAppendList: function() {
				
				return this.sceneAppendList;
			},
			//设置编辑时候追加场景
			setSceneAppendList: function(data) {
				
				if(typeof data != "undefined") {
					this.sceneAppendList = data;
				}
			}
		},
	
		//分组数据
		readySavedGroups: {
	
			groupData: [],
	
			//清除数据
			clearData: function() {
				this.groupData.splice(0, this.groupData.length);
			},
	
			//更新数据
			updateData: function(groupObj) {
	
				if(typeof(groupObj) == "object") {
					this.groupData.push(groupObj);
				}
			},
	
			//获取数据
			getData: function() {
				return this.groupData;
			},
	
			//设置数据
			setData: function(data) {
				if(typeof data != 'undefined') {
					this.groupData = data;
				}
	
			}
	
		},
		
		//需要保存的热点数据
		readySavedHotspot: {
		
			hotSpotList: [],
			
			//初始化函数
			init: function() {
				
			},
			
			
			//清除数据
			clearData: function() {
					
//				this.hotSpotList.splice(0, this.hotSpotList.length);
			},
		
			//更新数据
			updateData: function(hotSpotObj) {
		
				if(typeof(hotSpotObj) == "object") {
					this.hotSpotList.push(hotSpotObj);
				}
			},
		
			//获取数据
			getData: function() {
				return this.hotSpotList;
			},
		
			//设置数据
			setData: function(data) {
				if(typeof data != 'undefined') {
					this.hotSpotList = data;
				}
		
			}
		},
	}

	//=================================================================================================
	//场景对象
	var sceneManager = {

		/******************************************************************************
		 * Desc: 移除全景
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		removeKrpano: function() {

			if(globalData.getKrpano()) {
				removepano("panoSettingObject");
			}
		},

		/******************************************************************************
		 * Desc: 获得根目录
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		getRootPath: function() {

			var strFullPath = window.document.location.href;
			var strPath = window.document.location.pathname;
			var pos = strFullPath.indexOf(strPath);
			var prePath = strFullPath.substring(0, pos);
			var postPath = strPath.substring(0, strPath.substr(1).indexOf('/') + 1);
			return(prePath + postPath);
		},

		/******************************************************************************
		 * Desc: 初始化成功之后的回调函数 获取唯一ID
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		krpano_onready_callback: function(krpano_interface) {

			globalData.setKrpano(krpano_interface);
			globalData.setKrpano(document.getElementById("panoSettingObject"));

		},

		/******************************************************************************
		 * Desc: 初始化全景场景
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		initPanoHtml5Setting: function(projectPath, projectId, projectLayerId, elem) {

			if(typeof projectPath == "undefined" || typeof elem == "undefined" || projectPath == "" || elem == "") {
				return;
			}

			var baseUrl = this.getRootPath();
			
			//设置全局项目路径
			var curProjectPath = projectPath.substring(0, projectPath.lastIndexOf('/'));
			globalData.setCurProjectPath(curProjectPath);
			

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
			settings["layer[textbox].visible"] = false;

			var swf = baseUrl + "/" + curProjectPath + "/tour.swf";
			
			var xmlName = baseUrl + "/" + curProjectPath + "/tour.xml";
			globalData.setXmlName(xmlName);

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
				onready: this.krpano_onready_callback,
			});
		},

		/******************************************************************************
		 * Desc: 初始化場景列表
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		initPanoThumbList: function() {

			if(globalData.getKrpano()) {

				//隐藏热点
				globalData.getKrpano().get("hotspot").getArray().forEach(function(everySpot) {
					if(everySpot.name !== "vr_cursor" && everySpot.name !== 'webvr_prev_scene' &&
						everySpot.name !== 'webvr_next_scene' &&
						everySpot.name !== "skin_webvr_prev_scene" &&
						everySpot.name !== "skin_webvr_next_scene") {
						globalData.getKrpano().set("hotspot[" + everySpot.name + "].visible", 'false');
					}
				});

				//获取场景
				this.getKrpanoScenes();

				//根据分组信息初始化分组
				groupManger.initGroupData();
			}

		},

		/******************************************************************************
		 * Desc: 获取场景数据
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		getKrpanoScenes: function() {
			
			//清除数据
			globalKrpanoData.scenes.clearData();

			//获取当前场景信息
			globalData.getKrpano().get("scene").getArray().forEach(function(scene) {
				var sceneObj = {};
				sceneObj.index = scene.index;
				sceneObj.name = scene.name;
				sceneObj.thumburl = scene.thumburl;
				if(scene.name == globalData.getKrpano().get("startscene")) {
					sceneObj.welcomeFlag = true;
				}
				//更新全局场景数据
				globalKrpanoData.scenes.updateData(sceneObj);
			});

		},
		
		/******************************************************************************
		 * Desc: 根据场景名称获取场景
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		getKrpanoSceneByName: function(name) {
		
			//获取当前场景信息
			var isFind = false;
			globalData.getKrpano().get("scene").getArray().forEach(function(scene) {
				
				if(name == scene.name) {
					
					isFind = true;
					return true;
				}
			});
			
			return isFind;
		
		},
		
		

		/******************************************************************************
		 * Desc: 添加场景
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		addScene: function(scenes) {
			
			//参数校验
			if(typeof scenes == "undefined" || scenes.length == 0) {
				
				return;
			}
			
			var krpano = globalData.getKrpano();
			
			//解析xml字符串 获取当前增加场景的name 如果场景中已经存在了场景则不在增加到场景中
			var newAddScenesName = []
			var xmlDoc = $.parseXML(scenes);
			$(xmlDoc).find('scene').each(function(index,elem) {
				
				if(!this.getKrpanoSceneByName($(this).attr('name'))) {
					
					newAddScenesName.push($(this).attr('name'));
				}
				else {
					scenes.splice(index,1);
				}
			});

			//修改默认场景字符串中的一些参数
			if(krpano) {

				var sceneName = krpano.get("scene[get(xml.scene)].name");
				
				var xmlstring = '<krpano>';
				
				for(var scene of scenes) {

					if(typeof scene['scenes'] != "undefined" || scene['scenes'] != "") {

						var curScene = scene['scenes'];
						
						//新增的场景同步到保存数据中
						krpanoSaveData.readySavedScenes.updateSceneAppendList(curScene);
						
						curScene = curScene.replace(/panos\//g, globalData.getCurProjectPath() + '/panos/');
						curScene = curScene.replace('onstart=""', 'onstart="updateradar()"');
						xmlstring += curScene;

					}
				}

				xmlstring += '</krpano>';
				krpano.call("loadxml(" + escape(xmlstring) + ", null, MERGE, BLEND(0.5));");
				krpano.call("loadscene(" + sceneName + ", null, MERGE);");

				//更新场景
				this.getKrpanoScenes();
				
				//更新分组下面的场景
				groupManger.updateGroupData(globalData.getCurGroupTitle());

				this.updateSceneView(globalData.getCurGroupTitle());
			}
		},

		/******************************************************************************
		 * Desc: 切换场景
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		changeScene: function(sceneName, sceneindex, curLeftNavActived) {

			if(typeof sceneName == "undefined" || typeof sceneindex == "undefined" || typeof curLeftNavActived == "undefined") {
				return;
			}
			
			var krpano = globalData.getKrpano();

			//加载指定场景
			krpano.call('loadscene(' + sceneName + ', null, MERGE);');

			//不在显示缩率图
			krpano.call("skin_showthumbs(false);");

			//获取内存中的场景数据
			var sceneArray = globalKrpanoData.scenes.getData();
			
			var currentScene = sceneArray[sceneindex];

			//加载基础设置
			if(currentScene.initH) krpano.set("view.hlookat", currentScene.initH);
			if(currentScene.initV) krpano.set("view.vlookat", currentScene.initV);
			if(currentScene.fov) krpano.set("view.fov", currentScene.fov);
			if(currentScene.fovmax) krpano.set("view.fovmax", currentScene.fovmax);
			if(currentScene.fovmin) krpano.set("view.fovmin", currentScene.fovmin);
			if(currentScene.autorotate) {
				krpano.set("autorotate.enabled", currentScene.autorotate.enabled);
				krpano.set("autorotate.waittime", currentScene.autorotate.waitTime);
			}

			switch(curLeftNavActived) {

				//基础设置
				case 'baseSet':
					break;
					//视角
				case 'viewAngle':
					break;
					//热点
				case 'hotsopt':
					hotspotManger.getHotspotDataWhenChangeScene(sceneName, sceneindex);
					hotspotManger.getCurSceneHotspotList(sceneindex, sceneName);
					break;
					//雷达
				case 'radar':
					break;
					//嵌入
				case 'embed':
					break;
					//音乐
				case 'specialEffect':
					break;
					//特效
				default:

					break;

			}

		},

		/******************************************************************************
		 * Desc: 当前分组所对应的场景缩略图更新 
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		updateSceneView: function(title, bChangeScene = false) {
			
			//参数校验
			if(typeof title == "undefined" || title == "") {
				return;
			}

			//设置当前激活显示的分组
			globalData.setCurGroupTitle(title);

			var html = '';

			$('.panoListContainerPlanNeps').empty();

			var groupData = globalKrpanoData.groups.getData();

			//取出当前分组中的所有场景
			var scenes = [];
			$.each(groupData, function(index, elem) {

				//找到了当前分组 直接返回
				if(elem['title'] == title) {
					scenes = elem['scenes'];
					return true;
				}
			});

			//遍历当前场景 初始化页面信息
			$.each(scenes, function(index, elem) {

				if(index == 0) {

					html += '<div title=' + elem['name'] + ' class="list" data-sceneName=' + elem['name'] + ' data-sceneIndex=' + elem['index'] + ' data-sceneThumburl=' + elem['thumburl'] + ' draggable="false">';
					html += '	<div class="panoItemThumb active">';
					html += '		<img src=' + elem['thumburl'] + ' alt=' + elem['name'] + ' style="width: 100%; height: 100%;" draggable="false">'
					html += '		<div class="more">...</div>';
					html += '	</div>';
					html += '	<p class="">' + elem['name'] + '</p>';
					html += '</div>';

					//默认切换分组之后加载的是分组中第一个场景
					if(bChangeScene) {
						
						this.changeScene(elem['name'], elem['index'], "");
					}

				} else {
					html += '<div title=' + elem['name'] + ' class="list" data-sceneName=' + elem['name'] + ' data-sceneIndex=' + elem['index'] + ' data-sceneThumburl=' + elem['thumburl'] + ' draggable="false">';
					html += '	<div class="panoItemThumb " draggable="false">';
					html += '		<img src=' + elem['thumburl'] + ' alt=' + elem['name'] + ' style="width: 100%; height: 100%;">'
					html += '		<div class="more">...</div>';
					html += '	</div>';
					html += '	<p class="">' + elem['name'] + '</p>';
					html += '</div>';
				}

			});

			$('.panoListContainerPlanNeps').append(html);

			var dragSceneObj = $('.panoListContainerPlanNeps').initDrap({
				callback: function() {
					//获取场景排列顺序
					this.updateSceneSort();
				}
			});

			globalData.setDragSceneObj(dragSceneObj);

			groupManger.setDraggable();
		},

		/******************************************************************************
		 * Desc: 拖动场景之后重新更新场景顺序 
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		updateSceneSort: function() {

			//获取当前激活显示的分组title
			var title = globalKrpanoData.groups.getCurGroupTitle();

			//获取当前内存中的分组信息
			var groupData = globalKrpanoData.groups.getData();

			//找到当前分组下的场景信息 清除之后重新赋值拖拽之后的结果信息
			$.each(groupData, function(index, elem) {

				//找到当前分组
				if(elem['title'] == title) {

					//清除当前分组下的旧的场景数据
					elem['scenes'].splice(0, elem['scenes'].length);

					//在根据xml顺序插入
					var scenesList = $('.panoListContainerPlanNeps .dads-children');
					for(var i = 0; i < scenesList.length; i++) {

						var scene = {
							
							name: scenesList[i].getAttribute('data-sceneName'),
							title: scenesList[i].getAttribute('data-sceneName'),
							index: scenesList[i].getAttribute('data-sceneIndex'),
							thumburl: scenesList[i].getAttribute('data-sceneThumburl'),

						};
						elem['scenes'].push(scene);
					}

				}
			})

			//将修改之后的数据重新更新到内存中
			globalKrpanoData.groups.setData(groupData);
		},

	}
	
	//=================================================================================================
	//分组对象
	var groupManger = {

		/******************************************************************************
		 * Desc: 获取到分组数据之后同步内存数据
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */

		afterGetGroupInfo: function(data) {

			if(typeof data == "undefined" || data.length == 0) {
				return;
			}

			//初始化数据
			globalKrpanoData.groups.clearData();

			var groupData = data['groupData'];

			//判断是否有分组数据 如果没有则构造默认分组结构
			if(groupData.length == 0) {

				//创建默认分组
				var group = {};

				//分组标题
				group['title'] = "默认分组";

				//分组场景 默认分组的时候将场景中所有场景加入到分组中

				var scenes = [];
				var sceneList = globalKrpanoData.scenes.getData();

				if(sceneList.length > 0) {

					$.each(sceneList, function(index, elem) {

						var scene = {
							name: elem['name'],
							title: elem['name'],
							index: elem['index'],
							thumburl: elem['thumburl'],
						};

						scenes.push(scene);
					})
				}
				group['scenes'] = scenes;

				//更新全局数据
				globalKrpanoData.groups.updateData(group);
				
				//因为默认分组并没有在后台创建 所以此刻创建的数据在关闭项目或者切换节点的时候需要保存 所以将默认数据加载到需要保存的数据中
				krpanoSaveData.readySavedGroups.updateData(group);
			} else {

				//直接赋值
				globalKrpanoData.groups.setData(groupData);
			}

			//初始化场景缩略图列表
			groupManger.initGroupView();
		},

		/******************************************************************************
		 * Desc: 初始化分组信息
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		initGroupData: function() {
			//获取分组信息
			utils.requestFn(

				"php/krpanoOptionManager/krpanoDispatchOption.php", {
					'opCode': 'getGroupInfo',
					'projectXml': globalData.getCurProjectPath()
				},
				groupManger.afterGetGroupInfo,
				utils.resultError
			);
		},

		/******************************************************************************
		 * Desc: 根据分组信息初始化分组页面
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		initGroupView: function() {
			
			var html = '';

			$('.groupListContainerPlanNeps').empty();

			var groupData = globalKrpanoData.groups.getData();

			$(groupData).each(function(idx) {

				if(idx == 0) {

					html += '	<div class="groupItem groupItemActive" draggable="true">';
					html += ' 		<span class="groupItemSpan ellipsis" title=' + this.title + '>' + this.title + '</span>';
					html += ' 		<div class="groupItemMenu">•••</div>';
					html += '	</div>';

					sceneManager.updateSceneView(this.title);

				} else {
					html += '	<div class="groupItem" draggable="true">';
					html += ' 		<span class="groupItemSpan ellipsis" title=' + this.title + '>' + this.title + '</span>';
					html += ' 		<div class="groupItemMenu">•••</div>';
					html += '	</div>';
				}
			});

			$('.groupListContainerPlanNeps').append(html);

			var dragGroupObj = $('.groupListContainerPlanNeps').initDrap({

				callback: function() {
					//获取场景排列顺序
				}
			});
			
			//设置拖拽对象
			globalData.setDragGroupObj(dragGroupObj);
			
			//更新是否可以拖拽状态
			groupManger.setDraggable();
		},

		/******************************************************************************
		 * Desc: 拖动分组之后重新更新分组顺序 
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		updateGroupSort: function() {

			var groupList = $('.groupListContainerPlanNeps .dads-children span');

			var groupData = globalKrpanoData.groups.getData();

			for(var i = 0; i < groupList.length; i++) {
				var group = null;

				for(var j = (i + 1); j < groupData.length; j++) {
					if(groupData[j].title == groupList[i].getAttribute("title")) {
						group = groupData[i];
						groupData[i] = groupData[j];
						groupData[j] = group;
						group = null;
					}
				}
			}

			//将最新数据同步到内存中
			globalKrpanoData.groups.setData(groupData);

		},

		/******************************************************************************
		 * Desc: 是否可以拖拽设置 
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		setDraggable: function() {
			
			var bCanDrag = globalData.getBCanDrag();

			if(null != globalData.getDragSceneObj()) {
				if(bCanDrag) {
					globalData.getDragSceneObj().activate();
				} else {
					globalData.getDragSceneObj().deactivate();
				}
			}

			if(null != globalData.getDragGroupObj()) {
				if(bCanDrag) {
					globalData.getDragGroupObj().activate();
				} else {
					globalData.getDragGroupObj().deactivate();
				}
			}
		},

		/******************************************************************************
		 * Desc: 更新分组信息
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		updateGroupData: function(title) {
			
			//参数校验 
			if(typeof title == "undefined" || title == "") {
				return;
			}
			
			//获取场景信息
			var sceneList = globalKrpanoData.scenes.getData();
			
			//根据分组名称获取分组信息
			var groupData = globalKrpanoData.groups.getData();
			
			//查询当前场景中没有加到分组里面的场景 即为新增加场景，则加入到当前分组中
			sceneList.forEach(scene => {

				var bExit = false;
				var scenes = null;
				$(groupData).each(function(idx) {
					if(null == scenes) {
						if(this.title == title) {
							scenes = this.scenes;
						}
					}

					$(this.scenes).each(function(ndx) {
						if(this.name == scene.name) {
							bExit = true;
						}
					});
				});

				if(!bExit) {
					var sc = {
						name: scene.name,
						title: scene.name,
						index: scene.index,
						thumburl: scene.thumburl,
					};
					scenes.push(sc);
				}
			});
			
			//更新分组信息
			globalKrpanoData.groups.setData(groupData);
			
			//更新内存中分组信息
			krpanoSaveData.readySavedGroups.setData(groupData);
		},

		/******************************************************************************
		 * Desc: 添加分组
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		addGroup: function(title) {
			
			//参数校验
			if(typeof title == "undefined" || title == "") {
				return;
			}
			
			//判断当前增加的分组是否已经存在
			var groupData = globalKrpanoData.groups.getData();
			
			var bExit = false;
			
			$.each(groupData,function(index,elem) {
				
				if(title == elem['title']) {
					bExit = true;
				}
			});

			//如果不存在则添加 存在的话则直接返回
			if(!bExit) {
				
				//创建默认分组
				var group = {};
				group['title'] = title;
				//添加所有场景
				group['scenes'] = [];
				
				//添加到内存数据
				globalKrpanoData.groups.updateData(group);
				
				//添加到保存数据
				krpanoSaveData.readySavedGroups.updateData(group);
				
				//添加
				var html = '';
				html += '	<div class="groupItem" draggable="true">';
				html += ' 		<span class="groupItemSpan ellipsis" title=' + title + '>' + title + '</span>';
				html += ' 		<div class="groupItemMenu">•••</div>';
				html += '	</div>';
				$('.groupListContainerPlanNeps').append(html);

				var dragGroupObj = $('.groupListContainerPlanNeps').initDrap({
				
					callback: function() {
						//获取场景排列顺序
						groupManger.updateGroupSort();
					}
				});
				
				//设置拖拽对象
				globalKrpanoData.setDragGroupObj(dragGroupObj);
				
				//更新是否可以拖拽状态
				groupManger.setDraggable();

			}
		},

		/******************************************************************************
		 * Desc: 删除分组
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		subGroup: function(title) {
			
			//参数校验
			if(typeof title == "undefined" || title == "") {
				return;
			}
			
			//删除数据
			globalKrpanoData.groups.delData(title);
		
		},

		/******************************************************************************
		 * Desc: 重命名分组
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */

		renameGroup: function(oldTitle, newTitle) {
			
			globalKrpanoData.groups.renameData(oldTitle, newTitle);

		},

		/******************************************************************************
		 * Desc: 激活分组
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		onUpdateGroup: function(title) {

			groupManger.updateSceneView(title, true);
		},
	}

	//=================================================================================================
	//热点对象
	var hotspotManger = {

		/******************************************************************************
		 * Desc: 根据热点名称获得热点数据
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		getHotspotDataByName: function(hotspotName) {

			var hotSpot = {};
			if(typeof hotspotName == "undefined") {
				return;
			}
			
			var krpano = globalData.getKrpano();

			krpano.get("hotspot").getArray().forEach(function(everySpot) {

				if(everySpot.name !== "vr_cursor" && everySpot.name !== 'webvr_prev_scene' &&
					everySpot.name !== 'webvr_next_scene' &&
					everySpot.name !== "skin_webvr_prev_scene" &&
					everySpot.name !== "skin_webvr_next_scene") {

					if(hotspotName != everySpot.name) {
						return false;
					} else {

						hotSpot.ath = everySpot.ath.toString();
						hotSpot.atv = everySpot.atv.toString();
						hotSpot.linkedscene = everySpot.linkedscene;
						hotSpot.name = everySpot.name;
						hotSpot.style = everySpot.style;
						hotSpot.title = everySpot.title;
						hotSpot.typevalue = everySpot.typevalue;
						hotSpot.curscenename = everySpot.curscenename;
						hotSpot.hotspotlink = everySpot.hotspotlink;
						hotSpot.dive = everySpot.dive;
					}

				}

			});

			return hotSpot;

		},

		/******************************************************************************
		 * Desc: 改变热点显隐性信息
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		changeHotspotVisible: function(openFlag) {
		
			if(globalData.getKrpano()) {

				globalData.getKrpano().get("hotspot").getArray().forEach(function(everySpot) {
					if(everySpot.name !== "vr_cursor" && everySpot.name !== 'webvr_prev_scene' &&
						everySpot.name !== 'webvr_next_scene' &&
						everySpot.name !== "skin_webvr_prev_scene" &&
						everySpot.name !== "skin_webvr_next_scene") {
						globalData.getKrpano().set("hotspot[" + everySpot.name + "].visible", openFlag);
					}
				});
			}

		},

		/******************************************************************************
		 * Desc: 改变热点显隐性信息
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		getCurSceneHotspotList: function(curSceneIndex, curSceneName) {

			var krpano = globalData.getKrpano();
			
			var html = '';
			krpano.get("hotspot").getArray().forEach(function(everySpot) {

				if(everySpot.name !== "vr_cursor" && everySpot.name !== 'webvr_prev_scene' &&
					everySpot.name !== 'webvr_next_scene' &&
					everySpot.name !== "skin_webvr_prev_scene" &&
					everySpot.name !== "skin_webvr_next_scene") {
					var hotspotName = everySpot.name;
					var sceneName = krpano.get("hotspot[" + everySpot.name + "].curscenename");
					var typevalue = krpano.get("hotspot[" + everySpot.name + "].typevalue");
					if(curSceneName != sceneName) {
						return false;
					} else {

						var curStyle = krpano.get('style[' + everySpot.style + '].url');
						var hotspotObj = {};
						hotspotObj.curscenename = sceneName;
						hotspotObj.name = everySpot.name
						hotspotObj.url = curStyle;
						hotspotObj.typevalue = typevalue;
					

						var htmlHotspotTypeText = ''

						switch(typevalue) {

							case '1':
								htmlHotspotTypeText = '<div class="sidebarHotspotItemTypeName" data-typeText="全景切换">全景切换</div>';
								break;
							case '2':
								htmlHotspotTypeText = '<div class="sidebarHotspotItemTypeName" data-typeText="超链接">超链接</div>';
								break;
							case '3':
								htmlHotspotTypeText = '<div class="sidebarHotspotItemTypeName" data-typeText="图片热点">图片热点</div>';
								break;
							case '4':
								htmlHotspotTypeText = '<div class="sidebarHotspotItemTypeName" data-typeText="视频热点">视频热点</div>';
								break;
							case '5':
								htmlHotspotTypeText = '<div class="sidebarHotspotItemTypeName" data-typeText="文字热点">文字热点</div>';
								break;
							case '6':
								htmlHotspotTypeText = '<div class="sidebarHotspotItemTypeName" data-typeText="音频热点">音频热点</div>';
								break;

						}

						html += '<div id=' + hotspotName + ' class="sidebarHotspotItem" data-typeValue=' + typevalue + ' data-spotName=' + hotspotName + '>';
						html += '	<div class="sidebarHotspotItemIcon">';
						html += '		<img class=' + everySpot.style + ' src=' + curStyle + ' style="width: 100%; height: 100%;">';
						html += '	</div>';
						html += '	<div class="sidebarHotspotItemName ellipsis" data-sceneIndex=' + curSceneIndex + '>' + curSceneName + '</div>';
						html += htmlHotspotTypeText;
						html += '</div>';
					}

				}
			});

			if(typeof(curSceneName) == "undefined" || typeof curSceneIndex == "undefined") {

				return;
			}

			$('.hotspotContainerListPlan').empty();

			$('.hotspotContainerListPlan').append(html);

		},

		/******************************************************************************
		 * Desc: 切换场景的时候获取当前场景热点数据
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		getHotspotDataWhenChangeScene: function(sceneName, sceneIndex) {


			
			//获取krpano对象
			var krpano = globalData.getKrpano();

			var curSceneName = krpano.get('xml.scene');

			if(curSceneName != sceneName) {
				return;
			}
			
			
			//获取当前场景的热点数据
			var hotSpotLists = globalKrpanoData.hotspot.getData();
			
			var currenthotspot;
			
			$.each(hotSpotLists, function(index, elem) {
				
				if(elem['index'] == sceneIndex) {
					currenthotspot = elem['hotspots']
				}
			});
			

			if(currenthotspot) {

				krpano.get("hotspot").getArray().forEach(function(everySpot) {
					if(everySpot.name !== "vr_cursor" && everySpot.name !== 'webvr_prev_scene' &&
						everySpot.name !== 'webvr_next_scene' &&
						everySpot.name !== "skin_webvr_prev_scene" &&
						everySpot.name !== "skin_webvr_next_scene") {
						krpano.call("removehotspot(" + everySpot.name + ")");
					}
				});
				
				currenthotspot.forEach(function(everySpot) {

					krpano.call("addhotspot(" + everySpot.name + ");");
					krpano.set("hotspot[" + everySpot.name + "].ath", everySpot.ath);
					krpano.set("hotspot[" + everySpot.name + "].atv", everySpot.atv);
					krpano.set("hotspot[" + everySpot.name + "].title", everySpot.title);
					krpano.set("hotspot[" + everySpot.name + "].linkedscene", everySpot.linkedscene);
					krpano.set("hotspot[" + everySpot.name + "].dive", everySpot.dive);
					krpano.set("hotspot[" + everySpot.name + "].curscenename", everySpot.curscenename);
					krpano.set("hotspot[" + everySpot.name + "].linkedscene", everySpot.targetSceneName);
					krpano.set("hotspot[" + everySpot.name + "].typevalue", everySpot.typevalue);
					krpano.set("hotspot[" + everySpot.name + "].style", everySpot.style);
					krpano.get("hotspot[" + everySpot.name + "]").loadstyle(everySpot.style);

					hotspotManger.hotSpotInitEvent(everySpot.name);
				});

			}

			//覆盖原热点选中事件,添加热点点击移动事件
			krpano.get("hotspot").getArray().forEach(function(oldHotSpot) {

				if(oldHotSpot.name !== 'vr_cursor' && oldHotSpot.name !== 'webvr_prev_scene' &&
					oldHotSpot.name !== 'webvr_next_scene' &&
					oldHotSpot.name !== "skin_webvr_prev_scene" &&
					oldHotSpot.name !== "skin_webvr_next_scene") {
					hotspotManger.hotSpotInitEvent(oldHotSpot.name);

				}
			});

		},

		/******************************************************************************
		 * Desc: 修改全局热点数据
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		updateHotSpotData: function() {

			//获取krpano对象
			var krpano = globalData.getKrpano();
			
			var hotSpotData = [];

			krpano.get("hotspot").getArray().forEach(function(everySpot) {

				if(everySpot.name !== "vr_cursor" && everySpot.name !== 'webvr_prev_scene' &&
					everySpot.name !== 'webvr_next_scene' &&
					everySpot.name !== "skin_webvr_prev_scene" &&
					everySpot.name !== "skin_webvr_next_scene") {
						
					var hotSpot = {};
					hotSpot.ath = everySpot.ath.toString();
					hotSpot.atv = everySpot.atv.toString();
					hotSpot.linkedscene = everySpot.linkedscene;
					hotSpot.name = everySpot.name;
					hotSpot.style = everySpot.style;
					hotSpot.title = everySpot.title;
					hotSpot.typevalue = everySpot.typevalue;
					hotSpot.curscenename = everySpot.curscenename;
					hotSpot.hotspotlink = everySpot.hotspotlink;
					hotSpot.dive = everySpot.dive;
					hotSpotData.push(hotSpot);
				}
			});

			if(hotSpotData.length > 0) {
				
				var index = krpano.get('scene[get(xml.scene)].index');
				
				var hotspotObj = {
					'index': index,
					'hotspots':hotSpotData
				}
				
				//更新内存中全局数据
				globalKrpanoData.hotspot.updateData(hotspotObj);
				
				//将新加节点保存在热点信息中
				krpanoSaveData.readySavedHotspot.updateData(hotspotObj);
				
			}

		},

		/******************************************************************************
		 * Desc:  热点移动
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */

		autoMove: function() {

			var krpano = globalData.getKrpano();
			var isAddHotSpot = true;
			
			var movingSpot = globalData.getMovingSpot();
			krpano.call("screentosphere(mouse.x, mouse.y, mouseath, mouseatv);");
			krpano.set("hotspot[" + movingSpot.name + "].ath", krpano.get("mouseath") + movingSpot.athDis);
			krpano.set("hotspot[" + movingSpot.name + "].atv", krpano.get("mouseatv") + movingSpot.atvDis);
		},

		/******************************************************************************
		 * Desc:  获取最近的热点
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		selectHotSpot: function() {
			
			var krpano = globalData.getKrpano();

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

		},

		/******************************************************************************
		 * Desc: 注册热点事件
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		hotSpotInitEvent: function(spotName) {

			var krpano = globalData.getKrpano();
			
			//热点按下
			krpano.get("hotspot[" + spotName + "]").ondown = function() {

				globalData.setMovingSpot(hotspotManger.selectHotSpot());
				var intervalId = setInterval(hotspotManger.autoMove, 1000.0 / 30.0);
				krpano.set("autoMoveIntervalId", intervalId);

			};

			krpano.get("hotspot[" + spotName + "]").onup = function() {

				window.clearInterval(krpano.get("autoMoveIntervalId"));
				hotspotManger.updateHotSpotData();

			};

			krpano.get("hotspot[" + spotName + "]").onclick = function() {
					krpano.set("layer[textbox].visible",true);
					krpano.set("layer[text].html","我是中国人");
			};

			krpano.get("hotspot[" + spotName + "]").onover = function() {
          
			};
			krpano.get("hotspot[" + spotName + "]").onout = function() {
          
			};

		},

		/******************************************************************************
		 * Desc: 添加临时热点
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		addTempHotSpot: function(skin_hotspot_style) {

			hotspotManger.removeHotSpot('tempHotspot');

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
			hotspotManger.hotSpotInitEvent(newHotSpotName);

		},

		/******************************************************************************
		 * Desc: 删除热点
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		removeHotSpot: function(name) {

			krpano.call("removehotspot(" + name + ")");

			//保存全局数据
			hotspotManger.updateHotSpotData();
		},

		removeHotspotByName: function(newHotSpotName) {

			var krpano = globalData.getKrpano();
			//是否存在
			krpano.get("hotspot").getArray().forEach(function(everySpot) {

				if(everySpot.name !== "vr_cursor" && everySpot.name !== 'webvr_prev_scene' &&
					everySpot.name !== 'webvr_next_scene' &&
					everySpot.name !== "skin_webvr_prev_scene" &&
					everySpot.name !== "skin_webvr_next_scene") {

					if(newHotSpotName == everySpot.name) {
						krpano.call("removehotspot(" + everySpot.name + ")");
					}
				}

			});
		},

		/******************************************************************************
		 * Desc: 确定添加热点
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		addHotSpot: function(newHotSpotName, targetSceneName, curSceneIndex, curSceneName, skin_hotspot_style, hotspotTypeValue) {


			var krpano = globalData.getKrpano();
			// 计算中间位置的球面坐标

			krpano.set("halfHeight", krpano.get("stageheight") / 2);
			krpano.set("halfWidth", krpano.get("stagewidth") / 2);

			krpano.call("screentosphere(halfWidth,halfHeight,init_h,init_v);");

			var init_h = krpano.get("init_h");
			var init_v = krpano.get("init_v");

			hotspotManger.removeHotspotByName(newHotSpotName);

			var title = "spot" + '_' + curSceneIndex;

			krpano.call("addhotspot(" + newHotSpotName + ");");
			krpano.get("hotspot[" + newHotSpotName + "]").loadstyle(skin_hotspot_style);
			krpano.set("hotspot[" + newHotSpotName + "].ath", init_h);
			krpano.set("hotspot[" + newHotSpotName + "].atv", init_v);
			krpano.set("hotspot[" + newHotSpotName + "].title", title);
			krpano.set("hotspot[" + newHotSpotName + "].style", skin_hotspot_style);
			krpano.set("hotspot[" + newHotSpotName + "].curscenename", curSceneName);
			krpano.set("hotspot[" + newHotSpotName + "].typevalue", hotspotTypeValue);
			krpano.set("hotspot[" + newHotSpotName + "].linkedscene", targetSceneName);
			krpano.set("hotspot[" + newHotSpotName + "].hotspotlink", '');

			//注册热点事件
			hotspotManger.hotSpotInitEvent(newHotSpotName);

			//保存全局数据
			hotspotManger.updateHotSpotData();
      
		},

		/******************************************************************************
		 * Desc: 确定添加热点
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		addHotSpotLinkUrl: function(newHotSpotName, curSceneIndex, curSceneName, title, linkStr, linkOpenTyle, skin_hotspot_style, hotspotTypeValue) {

			var krpano = globalData.getKrpano();
			
			var param = {};


			// 计算中间位置的球面坐标

			krpano.set("halfHeight", krpano.get("stageheight") / 2);
			krpano.set("halfWidth", krpano.get("stagewidth") / 2);

			krpano.call("screentosphere(halfWidth,halfHeight,init_h,init_v);");

			var init_h = krpano.get("init_h");
			var init_v = krpano.get("init_v");

			hotspotManger.removeHotspotByName(newHotSpotName);

			var title = title;

			krpano.call("addhotspot(" + newHotSpotName + ");");

			krpano.get("hotspot[" + newHotSpotName + "]").loadstyle(skin_hotspot_style);
			krpano.set("hotspot[" + newHotSpotName + "].ath", init_h);
			krpano.set("hotspot[" + newHotSpotName + "].atv", init_v);
			krpano.set("hotspot[" + newHotSpotName + "].title", title);
			krpano.set("hotspot[" + newHotSpotName + "].style", skin_hotspot_style);
			krpano.set("hotspot[" + newHotSpotName + "].curscenename", curSceneName);
			krpano.set("hotspot[" + newHotSpotName + "].hotspotlink", linkStr);
			krpano.set("hotspot[" + newHotSpotName + "].linkedscene", '');
			krpano.set("hotspot[" + newHotSpotName + "].typevalue", hotspotTypeValue);

			krpano.get("hotspot[" + newHotSpotName + "]").onclick = function() {

				var link = krpano.get("hotspot[" + newHotSpotName + "]").hotspotlink;

				window.open(link, "_blank");

			};

			//保存全局数据
			hotspotManger.updateHotSpotData();

		},
	}

	//=================================================================================================
	//雷达对象
	var radarManger = {

		/******************************************************************************
		 * Desc: 获得雷达map
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */

		getRadarLayerMap: function() {

			if(krpano) {

				var layerMap = krpano.get('layer[map]');

				krpano.call("SetRadarLayerMap(" + radarMap.path + "," + radarMap.width + "," + radarMap.height + ")");
			}
		},

		/******************************************************************************
		 * Desc: 初始化雷达map，使所有雷达点位可拖拽
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		initEditRadar: function() {

			if(globalData.getKrpano()) {
				globalData.getKrpano().call("InitEditRadar()");
			}
		},

		/******************************************************************************
		 * Desc: 创建雷达map
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */

		createRadarLayerMap: function(sourceThumbPath) {

			if(globalData.getKrpano()) {
//				//获取宽高
//				$(function() {
//
//					getImageWidth('resource/PanoImgLayer/1/thumb/CR-AwPxv.jpg', function(w, h) {
//						console.log({
//							width: w,
//							height: h
//						});
//					});
//				});
//
//				function getImageWidth(url, callback) {
//					var img = new Image();
//					img.src = url;
//
//					// 如果图片被缓存，则直接返回缓存数据
//					if(img.complete) {
//						callback(img.width, img.height);
//					} else {
//						// 完全加载完毕的事件
//						img.onload = function() {
//							callback(img.width, img.height);
//						}
//					}
//
//				}
//
//				radarMap.path = sourceThumbPath;
//				radarMap.width = 1024;
//				radarMap.height = 768;
//				globalData.getKrpano().call("SetRadarLayerMap(" + radarMap.path + "," + radarMap.width + "," + radarMap.height + ")");
			}
		},

		/******************************************************************************
		 * Desc: 添加雷达标记点
		 * add: 1 添加  -1 删除 0 修改
		 * @param 
		 *
		 * @return 
		 *		void
		 */

		addRadarSpot: function(targetSceneName) {
			
			if(globalData.getKrpano()) {
//				var layerobj = radarList.find(function(x) {
//					return x.name == targetSceneName;
//				});
//				if(layerobj) {
//					layerobj.text = targetSceneName;
//					layerobj.sceneName = targetSceneName.toLowerCase();
//					layerobj.x = 0;
//					layerobj.y = 0;
//					layerobj.rot = 0;
//					layerobj.add = 0;
//
//					globalData.getKrpano().call("AddRadarLayer(" + layerobj.name + "," + layerobj.text + "," + layerobj.sceneName + ")");
//				} else {
//					var layerobj = {
//						name: targetSceneName,
//						text: targetSceneName,
//						sceneName: targetSceneName.toLowerCase(),
//						x: 0,
//						y: 0,
//						rot: 0,
//						add: 1
//					};
//					radarList.push(layerobj);
//					globalData.getKrpano().call("AddRadarLayer(" + layerobj.name + "," + layerobj.text + "," + layerobj.sceneName + ")");
//				}
			}
		},

		/******************************************************************************
		 * Desc: 删除雷达标记点
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */

		subRadarSpot: function(targetSceneName) {

			if(globalData.getKrpano()) {
//				var layerobj = radarList.find(function(x) {
//					return x.sceneName == targetSceneName.toLowerCase();
//				});
//				if(layerobj) {
//					radarList.remove(layerobj);
//				} else {
//					var layerobj = {
//						name: targetSceneName,
//						add: -1
//					};
//					radarList.push(layerobj);
//				}
//				globalData.getKrpano().call("SubRadarLayer(" + targetSceneName + ")");
			}
		},

		/******************************************************************************
		 * Desc: 设置雷达角度
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		changeRadarAngle: function(angle) {
			
			if(globalData.getKrpano()) {
//				var sceneName = krpano.get("scene[get(xml.scene)].name");
//				var layerobj = radarList.find(function(x) {
//					return x.sceneName == sceneName;
//				});
//				if(layerobj) {
//					layerobj.rot = angle;
//				}
//				globalData.getKrpano().call("RotRadarAngle(" + angle + ")");
			}
		},

		/******************************************************************************
		 * Desc: 显隐雷达图层
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		visiableRadarLayerMap: function(bVisiable) {
			
			if(globalData.getKrpano()) {
				globalData.getKrpano().call("SetLayerMapVisible(" + bVisiable + ")");
			}
		},
	}

	//=================================================================================================
	//保存数据对象
	var saveDataManager = {

		/******************************************************************************
		 * Desc: 保存之前根据当前保存的类型做数据准备
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */

		beforeSaveReadyData: function(curLeftNavActived) {

			var savedData = {};

			if(typeof curLeftNavActived == "undefined" || curLeftNavActived == "") {

				return savedData;
			}

			//基础参数准备
			function beforeSaveReadyBaseSetData() {
				
				
				return;
			}

			//视角准备
			function beforeSaveReadyViewAngleData() {
				return;
			}

			//热点准备
			function beforeSaveReadyHotsoptData() {
			
				savedData['sceneListHost'] = krpanoSaveData.readySavedHotspot.getData();
				return;
			}

			//分组数据 雷达数据与分组数据合并
			function beforeSaveGroupData() {

				if(krpano) {
					radarList.forEach(layerobj => {
						var lobj = krpano.get("layer[" + layerobj.name + "]");
						layerobj.x = lobj.x;
						layerobj.y = lobj.y;
						layerobj.rot = lobj.rot;
					});
				}

				var radarData = {

					'radarMap': radarMap,
					'radarList': radarList
				}

				savedData['radarData'] = radarData;

				return;
			}

			//遮罩准备
			function beforeSaveReadyEmbedData() {
				return;
			}

			//音乐准备
			function beforeSaveReadyMusicData() {
				return;
			}

			//特效准备
			function beforeSaveReadySpecialEffectData() {
				return;
			}

			switch(curLeftNavActived) {

				//保存场景的基本参数
				case 'baseSet':

					beforeSaveReadyBaseSetData();

					break;
				//保存视角数据
				case 'viewAngle':

					beforeSaveReadyViewAngleData();

					break;
				//保存热点数据
				case 'hotsopt':

					beforeSaveReadyHotsoptData();

					break;
				//保存分组信息同时雷达数据也包括在里面
				case 'groups':

					beforeSaveGroupData();

					break;
				//保存切入信息
				case 'embed':

					break;
				//保存音乐信息
				case 'music':

					beforeSaveReadyMusicData();

					break;
					
				//保存特效信息
				case 'specialEffect':

					beforeSaveReadySpecialEffectData();

					break;
					
				//追加场景信息
				case 'addScene':
				
					beforeSaveReadySpecialEffectData();
				
					break;
				default:
					break;

			}

			return savedData;

		},

		/******************************************************************************
		 * Desc: 保存编辑之后的结果
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */

		saveKrPanoAfterEdit: function(curLeftNavActived,optCode='save') {

			if(typeof curLeftNavActived == "undefined" || curLeftNavActived == "") {

				return ;
			}
			//保存之前根据当前保存的类型做数据准备
			var saveData = saveDataManager.beforeSaveReadyData(curLeftNavActived);

			if(typeof saveData != "object" || $.isEmptyObject(saveData)) {
				return;
			}
			
			saveData['projectXml'] = globalData.getCurProjectPath();
			saveData['curOptionType'] = curLeftNavActived;
			saveData['opCode'] = optCode;
			

			utils.requestFn(
				"php/krpanoSaveDataAfterEdit/KrSaveDataManagerDispatcher.php", {
					'saveData': saveData
				},
				saveDataManager.afterSaveReadyData,
				utils.resultError,

			);

			

		},
		
		/******************************************************************************
		 * Desc: 保存之后的一些操作
		 * 
		 * @param 
		 *
		 * @return 
		 *		void
		 */
		
		afterSaveReadyData: function(curLeftNavActived) {
			
			if(typeof curLeftNavActived == "undefined" || curLeftNavActived == "") {
				return;
			}
		
			
			if(typeof curLeftNavActived == "undefined" || curLeftNavActived == "") {
		
				return savedData;
			}
		
			//基础参数准备
			function afterSaveReadyBaseSetData() {
		
				
				return;
			}
		
			//视角准备
			function afterSaveReadyViewAngleData() {
				return;
			}
		
			//热点准备
			function afterSaveReadyHotsoptData() {
				krpanoSaveData.readySavedHotspot.clearData();
			}
		
			//雷达准备
			function afterSaveReadyRadarData() {
		
				return;
			}
		
			//遮罩准备
			function afterSaveReadyEmbedData() {
				return;
			}
		
			//音乐准备
			function afterSaveReadyMusicData() {
				return;
			}
		
			//特效准备
			function afterSaveReadySpecialEffectData() {
				return;
			}
		
			switch(curLeftNavActived) {
		
				case 'baseSet':
		
					afterSaveReadyBaseSetData();
		
					break;
		
				case 'viewAngle':
		
					afterSaveReadyViewAngleData();
		
					break;
		
				case 'hotsopt':
		
					afterSaveReadyHotsoptData();
		
					break;
		
				case 'radar':
		
					afterSaveReadyRadarData();
		
					break;
		
				case 'embed':
		
					break;
		
				case 'music':
		
					afterSaveReadyMusicData();
		
					break;
		
				case 'specialEffect':
		
					afterSaveReadySpecialEffectData();
		
					break;
		
				default:
					break;
		
			}
		
		
		},
	}




	function _clearGlobalData() {
		
		globalData.clearGlobalData();
	}

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.08
	 * 		author: 	李长明    
	 * 		info:		全景对象及缩率图初始化相关  场景 begin
	 * 
	 ************************************************************************************************/

	/******************************************************************************
	 * Desc: 获取场景列表
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _getHtmlSceneList() {

		return globalKrpanoData.scenes.getData();
	}

	/******************************************************************************
	 * Desc: 获取全景节点的唯一id
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _getKrpano() {

		return globalData.getKrpano();
	}

	/******************************************************************************
	 * Desc: 清除全景
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _removeKrpano() {

		sceneManager.removeKrpano();
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

		sceneManager.initPanoHtml5Setting(projectPath, projectId, projectLayerId, elem);

	}

	/******************************************************************************
	 * Desc: 切换场景
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _changeScene(sceneName, sceneindex, curLeftNavActived) {

		sceneManager.changeScene(sceneName, sceneindex, curLeftNavActived);
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

		sceneManager.initPanoThumbList();

	}

	/******************************************************************************
	 * Desc: 添加场景
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _addScene(scenes) {

		sceneManager.addScene(scenes);
	}

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.24
	 * 		author: 	李长明    
	 * 		info:		全景对象及缩率图初始化相关  场景 end
	 * 
	 ************************************************************************************************/

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.24
	 * 		author: 	李长明    
	 * 		info:		热点相关  begin
	 * 
	 ************************************************************************************************/

	/******************************************************************************
	 * Desc: 确定添加热点
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _addHotSpot(newHotSpotName, targetSceneName, curSceneIndex, curSceneName, skin_hotspot_style, hotspotTypeValue) {

		hotspotManger.addHotSpot(newHotSpotName, targetSceneName, curSceneIndex, curSceneName, skin_hotspot_style, hotspotTypeValue);
	}

	/******************************************************************************
	 * Desc: 确定添加超链接热点
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _addHotSpotLinkUrl(newHotSpotName, curSceneIndex, curSceneName, title, linkStr, linkOpenTyle, skin_hotspot_style, hotspotTypeValue) {

		hotspotManger.addHotSpotLinkUrl(newHotSpotName, targetSceneName, curSceneIndex, curSceneName, skin_hotspot_style, hotspotTypeValue);
	}

	/******************************************************************************
	 * Desc: 改变热点显隐性信息
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _changeHotspotVisible(openFlag) {

		hotspotManger.changeHotspotVisible(openFlag);
	}

	/******************************************************************************
	 * Desc: 切换场景的时候获取当前场景热点数据
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _getCurSceneHotspotList(curSceneIndex, curSceneName) {

		hotspotManger.getCurSceneHotspotList(curSceneIndex, curSceneName);
	}

	/******************************************************************************
	 * Desc: 根据热点名称获得热点数据
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _getHotspotDataByName(hotspotName) {

		return hotspotManger.getHotspotDataByName(hotspotName);
	}

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.24
	 * 		author: 	李长明    
	 * 		info:		热点相关  end
	 * 
	 ************************************************************************************************/

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.24
	 * 		author: 	李长明    
	 * 		info:		分组相关  begin
	 * 
	 ************************************************************************************************/

	/******************************************************************************
	 * Desc: 更新分组信息
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _updateGroupData(title) {

		groupManger.updateGroupData(title);
	}

	/******************************************************************************
	 * Desc: 添加分组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _addGroup(title) {

		groupManger.addGroup(title);
	}

	/******************************************************************************
	 * Desc: 删除分组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _subGroup(title) {

		groupManger.subGroup(title);
	}

	/******************************************************************************
	 * Desc: 重命名分组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _renameGroup(oldTitle, newTitle) {

		groupData.renameGroup(oldTitle, newTitle);
	}

	/******************************************************************************
	 * Desc: 激活分组
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _onUpdateGroup(title) {

		groupManger.onUpdateGroup(title);
	}
	
	/******************************************************************************
	 * Desc: 控制拖拽开关
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	
	function _setDraggable(bFlag) {
	
		globalData.setBCanDrag(bFlag);
		groupManger.setDraggable();
	}
	

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.08
	 * 		author: 	李长明    
	 * 		info:		分组相关  end
	 * 
	 ************************************************************************************************/

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.24
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
	function _initEditRadar() {

		radarManger.initEditRadar();
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

		radarManger.createRadarLayerMap(sourceThumbPath);
	}

	/******************************************************************************
	 * Desc: 添加雷达标记点
	 * @param add: 1 添加  -1 删除 0 修改
	 *
	 * @return 
	 *		void
	 */
	function _addRadarSpot(targetSceneName) {

		radarManger.addRadarSpot(targetSceneName);
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

		radarManger.subRadarSpot(targetSceneName);
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

		radarManger.changeRadarAngle(angle);
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

		radarManger.visiableRadarLayerMap(bVisiable);
	}

	/************************************************************************************************
	 * 
	 * 		time: 		2018.10.08
	 * 		author: 	李长明    
	 * 		info:		雷达相关  end
	 * 
	 ************************************************************************************************/

	/******************************************************************************
	 * Desc: 保存编辑之后的结果
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _saveKrPanoAfterEdit(curLeftNavActived) {

		saveDataManager.saveKrPanoAfterEdit(curLeftNavActived);
	}

	/******************************************************************************
	 * Desc: 模块的初始化入口
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */

	function _init() {

		_registerManager();

		_initHandlers();

		_initReadyData();

	}

	return {

		//全景对象及缩率图初始化相关
		initPanoHtml5Setting: _initPanoHtml5Setting,
		initPanoThumbList: _initPanoThumbList,
		getKrpano: _getKrpano,
		removeKrpano: _removeKrpano,
		clearGlobalData: _clearGlobalData,

		//场景相关
		addScene: _addScene,
		changeScene: _changeScene,
		getHtmlSceneList: _getHtmlSceneList,

		//热点相关
		addHotSpot: _addHotSpot,
		addHotSpotLinkUrl: _addHotSpotLinkUrl,
		changeHotspotVisible: _changeHotspotVisible,
		getCurSceneHotspotList: _getCurSceneHotspotList,
		getHotspotDataByName: _getHotspotDataByName,

		//雷达相关
		initEditRadar: _initEditRadar,
		createRadarLayerMap: _createRadarLayerMap,
		addRadarSpot: _addRadarSpot,
		changeRadarAngle: _changeRadarAngle,
		visiableRadarLayerMap: _visiableRadarLayerMap,

		//分组相关
		addGroup: _addGroup,
		onUpdateGroup: _onUpdateGroup,
		setDraggable: _setDraggable,

		//保存数据相关
		saveKrPanoAfterEdit: _saveKrPanoAfterEdit,

	}
});