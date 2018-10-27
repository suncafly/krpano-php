requirejs.config({
	
	waitSeconds: 0,
	baseUrl:'js/plugins', // 默认模块加载路径
	map:{
		'*':{
			'css':'../require/css'
		}
	},
	paths:{
		
		jquery: 'https://code.jquery.com/jquery-3.2.1.min',	
		
		popper: 'bootstrap-4.0.0-dist/js/popper.min',
		
		bootstrap: 'bootstrap-4.0.0-dist/js/bootstrap',
		
		layuiModule: 'layui/layui.all',
		
		explorerfa:'bootstrap-fileinput-v4.5.1/themes/explorer-fa/theme',
		
		fileInput:'bootstrap-fileinput-v4.5.1/js/fileinput.min',
		
		filelocalZh:'bootstrap-fileinput-v4.5.1/js/fileinput_locale_zh',
		
		themeFa:'bootstrap-fileinput-v4.5.1/themes/fa/theme',	
		
		mainModule:'../krpanoModule/main',
		
		rightKeyMenuModule:'rightKeyMenu/contextMenu',
		
		dragFlexModule: 'dargFlex/jquery.dad.min',
		
		
		
	},
	shim: {
		
		
		bootstrap : {
			deps: ['css!bootstrap-4.0.0-dist/css/bootstrap.min', 'jquery','popper'],
			exports: "bootstrap"
		},
		
		layuiModule : {
			deps :['css!layui/css/layui']
		},
		
		explorerfa : {
			deps: ['css!bootstrap-fileinput-v4.5.1/themes/explorer-fa/theme','fileInput'],
			exports: "explorerfa"
		},
		
		filelocalZh: {
			deps: ['fileInput'],
			exports: "filelocalZh"
		},
		
		themeFa: {
			deps: ['fileInput'],
		},
		
		fileInput: {
			deps: ['jquery','css!bootstrap-fileinput-v4.5.1/css/fileinput.min','css!https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min'],
			exports:"fileInput"
			
		},
		
		rightKeyMenuModule: {
			deps: ['jquery','css!rightKeyMenu/contextMenu'],
			exports: "rightKeyMenuModule"
		},
		
		dragFlexModule: {
			deps: ['jquery','css!dargFlex/jquery.dad'],
		}

		
		
	}
});

define(['jquery','bootstrap','layuiModule','fileInput','explorerfa','filelocalZh','themeFa','rightKeyMenuModule','mainModule'], function($,bootstrap, layuiModule, fileInput, explorerfa, filelocalZh, themeFa, rightKeyMenuModule,mainModule,dragFlexModule) {
	
	
	
	
	console.log(1);
	mainModule.Global.init();
	mainModule.Global.resize();
	
	
	
	console.log(mainModule);
	
});