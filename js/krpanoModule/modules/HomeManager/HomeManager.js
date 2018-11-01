define(function(){
	
	var _pageId = "homeHeader";
	var _title = '标题栏';
	
	function _buildHomeHtml()
	{
		
	
	
		//======================================动态创建页面内容====================================
		
		//创建 头部内容
		function buildHeader() {
			
			$('#homeHeader').empty();
			
			
			var html  = '';
			
			html += '<div class="col top-nav" >';
			html += '	<img class="nav_logo" src="img/nav-title/Panorama.png" />';
			html += '	<p class="top_title">全景制作管理平台</p>';
			html += '	<ul class="nav nav-tabs leftNav"  role="tablist">';
			html += '		<li class="">';
			html += '			<a href="#system-resourceManager" data-toggle="tab" id=""><img src="img/nav-title/source.png"/>   资源管理平台</a>';
			html += '		</li>';
			html += '		<li class="active">';
			html += '			<a href="#system-editPushManager" data-toggle="tab" id=""><img src="img/nav-title/edit.png"/>    预览编辑发布平台</a>';
			html += '		</li>';
			html += '	</ul>';
			html += '</div>';
			
			
			$('#homeHeader').append(html);
		}
		
		//初始化页面内容
		function buildContent() {
					
			$('#homeContent').empty();
			
			var html = '';
			
			html += '<div class="tab-content container-fluid">';
			html += '	<div id="system-resourceManager" class="tab-pane active">';
			html += '	</div>';
			html += '	<div id="system-editPushManager" class="tab-pane fade"></div>';
			html += '</div>';
			
			$('#homeContent').append(html);
			
		}
		
		//初始化底部菜单
		function buildFooter() {
			
			var html = '';
			html += '<div class="bottom"></div>';
			$('.footer').append(html);
		}
		
		
		//=======================================函数调用==============================================
		
		//初始化标题栏
		buildHeader();
		
		//初始化页面内容
		buildContent();
		
		//初始化底部菜单
		buildFooter();
		
		
	}
	
	function _initHandlers()
	{
		
	}	
	
	function _initActiveFlag() {
		
		
	}

	function _init()
	{
		_initActiveFlag();
		_initHandlers();
	}
	
	function _resize()
	{

		
	}
	
	
	function _getTitle() {
		return _title;
	}
	
	
	return {
		init: _init,
		resize: _resize,
		getTitle: _getTitle,
		buildHomeHtml: _buildHomeHtml
	}
});
