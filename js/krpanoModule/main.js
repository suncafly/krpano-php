define(
	[
		'jquery', 
		'../krpanoModule/modules/Base/Base',
		'../krpanoModule/modules/Global/Global',
		'../krpanoModule/modules/Utils/Utils',
		'../krpanoModule/modules/HomeManager/homeManager',
		'../krpanoModule/modules/resourceManager/resourceManager',
		'../krpanoModule/modules/editDisplayManager/editDisplayManager',
		'../krpanoModule/modules/krpanoOnlineEditManager/krpanoOnlineEditManager',
		'../krpanoModule/modules/html5InterfaceToKrpano/html5InterfaceToKrpano',
		'../krpanoModule/modules/html5InterfaceToKrpano/tour',
		'../krpanoModule/modules/openSourceTypeManager/openSourceTypeManager',

		
	], function($, base, global, utils,homeManager,resourceManager,editDisplayManager,krpanoOnlineEditManager,html5InterfaceToKrpano,tour,openSourceTypeManager){
		
		
		
		var baseModule = base;
		
		// 全局依赖项
		baseModule.Global = global;

		// 实用工具集
		baseModule.Utils = utils;	
		
		
		//初始化主页面
		baseModule.HomeManager = homeManager;
		global.addInitManager(homeManager.init);
		global.addResizeManager(homeManager.resize);
		homeManager.buildHomeHtml();
		
		
		//初始化资源管理平台
		baseModule.ResourceManager = resourceManager;
		global.addInitManager(resourceManager.init);
		global.addResizeManager(resourceManager.resize);
		resourceManager.buildPageHtml();
		
		
		//初始化预览编辑发布平台
		baseModule.EditDisplayManager = editDisplayManager;
		global.addInitManager(editDisplayManager.init);
		global.addResizeManager(editDisplayManager.resize);
		editDisplayManager.buildPageHtml();
		
		//初始化全景在线制作平台
		baseModule.KrpanoOnlineEditManager = krpanoOnlineEditManager
		global.addInitManager(krpanoOnlineEditManager.init);
		global.addResizeManager(krpanoOnlineEditManager.resize);
		//krpanoOnlineEditManager.buildPageHtml();
		
		
		
		return baseModule;
		
	}
);