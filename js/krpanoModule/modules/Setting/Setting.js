define(['jquery', './../Global/Global','jquerymobile', '../Me/Me','layer','../MessageManager/MessageManager','../androidInterface/androidInterface','../uploadOption/uploadOption'], function($, global,jqm, me,layer,messageManager,androidInterface,uploadResource) {
	
	var _pageId = 'page-Settings';
	var _title = '我的';
	var _icon = 'img/locationActive.png';
	var _class = 'me';
	
	var _user = {};
	
	
	var _initManagers = [];
	
	/******************************************************************************
	 * Desc: 初始化页面
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _buildHtml() {
		
		
		
		
		var html = '';
		
		
		var html = '';
		html += "<div data-role='page' id='page-Settings'>";
		html += "    <div data-role='header'>";
		html += "        <a href='#' class='' data-rel='back'><</a>";
		html += "        <h1>我的</h1>";
		html += "    </div>";
	
		html += "    <div data-role='main' class='aboutMe'>";
		html += "       <a href='#page-settings-managerInfo' class='userManager' data-name='个人信息' data-targetManger='userManager'>";
		html += '			<table id="page-me-detail-info">';
		html += '				<tbody>';
		html += '					<tr>';
		html += '						<td class="touxiang"><img src="img/touxiang.png"  class=""></td>';
		html += '						<td class="">';
		html += '							<table>';
		html += '								<tbody>';
		html += '									<tr>';
		html += '										<td>';
		html += '											<p class="userName">李二虎</p>';
		html += '										</td>';
		html += '									</tr>';
		html += '									<tr>';
		html += '										<td>';
		html += '											<p class="detailInfo">中队接警员/天鹅湖/15646132</p>';
		html += '										</td>';
		html += '									</tr>';
		html += '								</tbody>';
		html += '							</table>';
		html += '						</td>';
		html += '						<td class="enterMeInfo"><img src="img/enterNext.png"  class=""></td>';
		html += '					</tr>';
		html += '				</tbody>';
		html += '			</table>';
		html += "		</a>";

		html += "       <ul id='page-Settings-list' data-role='listview' data-inset='true'>";
		html += "			<li><a href='#page-settings-managerInfo' data-name='系统信息' data-targetManger='sysManager'>系统信息</a></li>";
		html += "			<li><a href='#page-settings-managerInfo' data-name='操作指南' data-targetManger='optManager'>操作指南</a></li>";
		html += "			<li><a href='#page-settings-managerInfo' data-name='意见反馈' data-targetManger='infoBackManager'>意见反馈</a></li>";
		html += "			<li><a href='#page-settings-managerInfo' data-name='关于我们' data-targetManger='UsManager'>关于我们</a></li>";
		html += "			<li><a href='#page-settings-managerInfo' data-name='版本信息' data-targetManger='versionManager'>版本信息</a></li>";
		html += "			<li><a href='#page-settings-managerInfo' data-name='上传测试' data-targetManger='uploaderTest'>上传测试</a></li>";
		html += "		</ul>";
	
		html += "      	<div class='userExit'>";
		html += "			<a href='#' class='ui-btn ui-icon-power '>退出登录</a>";
		html += "      	</div>";
		html += "    </div>";
		
		html += "</div>";
	
		//用户管理
		html += "<div data-role='page' id='page-settings-managerInfo'>";
		html += "    <div data-role='header' data-position='fixed'>";
		html += "        <a href='#' class='' data-rel='back'><</a>";
		html += "        <h1 id='page-settings-managerInfo-title'></h1>";
		html += "    </div>";
		
		html += "    <div data-role='main' class='' >";
		html += "       <div id='page-settings-managerInfo-list'></div>";
		html += "    </div>";
		
		html += "</div>";
	
		return html;
	}
	
	/******************************************************************************
	 * Desc: 用户管理
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initUserManager() {
	
		if(typeof me.user == "undefined") {
			return;
		}
		
		_user = me.user;
		
		//单位 组织机构
		var department = _user['department'];
		
		//用户名
		var userName = _user['userName'];
		
		//城市编码
		var cityCode = _user['cityCode'];
		
		//名称
		var name = _user['name'];
		
		//职务
		var post = _user['post'];
		
		var html = "";
		
		html += "       	<div class='userInfo'>";
//		html += "       		<div class='baseInfo'>";
//		html += "      				<div class='logoDisplay'></div>";
//		html += "      				<div class='nameDisplay'>";
//		html += "						<p class='userName '><strong>"+userName+"</strong></p>";
//		html += "						<p class='power '><strong>"+post+"</strong></p>";
//		html += "      				</div>";
//		html += "      			</div>";
		html += "       		<div class='otherInfo'>";
		html += "					<div class='info'>头像<img src='img/touxiang.png'  class=''></div>";
		html += "					<div class='info'>用户名<span>"+userName+"</span></div>";
		html += "					<div class='info'>姓名<span>"+name+"</span></div>";
		html += "					<div class='info'>职务<span>"+post+"</span></div>";
		html += "					<div class='info'>单位<span>"+department+"</span></div>";
		html += "					<div class='info'>联系方式<span>18710713192</span></div>";
		html += "      			</div>";
		html += "      		</div>";
		
		$('#page-settings-managerInfo-list').append(html);
		
		console.log(me.user);
	}
	
	/******************************************************************************
	 * Desc: 系统信息
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initSysManager() {
		
		var html = "";
		
		html += "       	<div class='sysSetting'>";
		html += "      			<div class='setIP'>";
		html += "                   <form>";
		html += "						<label for='setIpName'>设置IP:</label>";
		html += "						<input type='text' name='setIpName' id='setIpName' placeholder='设置iP'>";
		html += "                   </form>";
		html += "      			</div>";
		html += "      			<div class='setIpBtn'>";
		html += "					<a href='#' class='ui-btn'>设置IP</a>";
		html += "      			</div>";
		html += "      		</div>";
		
		$('#page-settings-managerInfo-list').append(html);
	}
	
	/******************************************************************************
	 * Desc: 操作指南
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initOptManager() {
		
		var html = "";
		
		html += "       	<div class='optManager'>";
		html += "       		<div class=''>";
		html += "       			<h3 class=''>操作指南</h3>";
		html += "      			</div>";
		html += "      		</div>";
		
		$('#page-settings-managerInfo-list').append(html);
	}
	
	/******************************************************************************
	 * Desc: 意见反馈
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initInfoBackManager() {
		
		var html = "";
		
		html += "       	<div class='adivceManager'>";
		html += "       		<div class='adviceInfo'>";
		html += "                   <form>";
		html += "						<div class='ui-field-contain'>";
		html += "							<textarea  class='addinfo' name='addinfo' id='info' placeholder='请写下您宝贵的意见或遇到的问题'></textarea>";
		html += "      					</div>";
		html += "                   <form>";
		html += "      			</div>";
		html += "				<span>*我们需要与您沟通解决问题,联系方式仅客服可见</span>";
		html += "				<div class='companyInfo'>";
		html += "					<input placeholder='您的邮箱或手机号'></input>";
		html += "					<input placeholder='您的称呼'></input>";
		html += "					<p>或通过以下方式联系我们:</p>";
		html += "					<p>微信公众号： 众智软件:</p>";
		html += "					<p>联系邮箱： zhongzhiruanjian@163.com</p>";
		html += "					<p>联系电话: 0379-56974236</p>";
		html += "				</div>";
		html += "      		</div>";
		
		$('#page-settings-managerInfo-list').append(html);
	}
	
	/******************************************************************************
	 * Desc: 关于我们
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initUsManager() {
	
		var html = "";
		
		html += "       	<div class='aboutUsManager'>";
		html += "				<div class='logo' style='text-align:center'><img src='img/aboutUs.png'><p>智慧消防</p></div>";
		html += "       		<div class='footer'>";
		html += "					<p>洛阳众智软件科技股份有限公司</p>";
		html += "					<p>&copy2018 ZhongZhiRuanJian</p>";	
		html += "      			</div>";
		html += "      		</div>";
		
		$('#page-settings-managerInfo-list').append(html);
	}
	
	/******************************************************************************
	 * Desc: 版本信息
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initVersionManagers() {
		
		
				
		window.ZZSoftMobileApp.sendMessage(androidInterface.html5ToAppMessageList.HTML5_TO_ANDROID_MESSAGE_GETAPPVERSION, ['theVersion', ""], function(data) {
			
		
			var obj = eval('(' + data + ')');
			
			var versionInfo;
			var versionKey;
			var info = [];
			var infoHtml = "";
			
			if("appNewFunciton" in obj) {
				
				versionInfo = obj['appNewFunciton'];
				
				info = versionInfo.split('#');
				for(var i= 0; i < info.length; i++) {
					infoHtml += "<li>"+info[i]+"</li>";
				}

			}
			
			if("appVersion" in obj) {
				versionKey = obj['appVersion'];
			}
			
			var html = "";
			
			html += "       	<div class='VersionManagers'>";
			html += "       		<div class=''>";
			html += "       			<h3 class=''>版本号：</h3>";
			html += "       			<h4 class='versionKey'>"+versionKey+"</h4>";
			html += "       			<h3 class=''>版本内容：</h3>";
			html += "					<ul>";
			html += 						infoHtml;
			html += "					</ul>";
			html += "      			</div>";
			html += "      		</div>";
			
			$('#page-settings-managerInfo-list').append(html);
		
			
		});
		
		
	}
	
	/******************************************************************************
	 * Desc: 上传插件 测试
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _uploaderTest() {
		
		var html = "";
		
		html += "       	<div class='uploaderTest'>";
		html += "       		<div class='uploaderAdd'>";
		html += "					<img src='img/addImageNormal.png'/>";
		html += "      			</div>";
		html += "       		<div class=''>";
		html += "                 	<ul class='' id='android-diplay-img' >";
		html += "                 	</ul>";
		html += "      			</div>";
		
		html += "      		</div>";
		
		$('#page-settings-managerInfo-list').append(html);
	}
	
	/******************************************************************************
	 * Desc: 通用处理逻辑
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _opManagers(typeName) {
	
		if(typeof typeName == "undefined") {
	
			return;
		}
	
		for(var type in _initManagers) {
	
			if(type == typeName) {
	
				if(typeof _initManagers[typeName] !== 'function') {
					return;
				}
	
				_initManagers[typeName]();
	
			}
		}
	
	}
	
	function _initUpload() {
	
		
		var camera = {
	
			auto: true,
			swf: 'js/lib/webuploader-0.1.5/Uploader.swf',
			server: global.getServerBaseUrl() + 'php/upload/uploadOld.php',
			pick: {id : '.camera',
                    //只能选择一个文件上传
                    multiple: false},
                    
			error: function(err) {},
			//				formData: {
			//					curType: type,
			//					curId: companyId,
			//					curCity: "0551",
			//					secondListName: name,
			//					detailType: "secondPlan",
			//					optionType: "二维预案录入",
			//			
			//				},
			success: function(response) {
	
				var fileName = response.fileName;
				var path = global.getServerBaseUrl() + 'uploadfiles/' + fileName;
				$('#android-diplay-img').append('<div class="androidDispalyImg"><li><img class="" src="' + path + '"></li></div>')
				layer.closeAll();
				
				//camera, photoList , fileList, voice video
			},
			error: function(err) {},
			buttonText: '',
	
			accept: {
				title: 'Image',
				extensions: 'gif,jpg,jpeg,bmp,png,camera',
				mimeTypes: 'image/jpg,image/jpeg,image/png,image/camera'
			},
			thumb: {
				width: 120,
				height: 90,
				quality: 100,
				allowMagnify: true,
				crop: true,
				type: "image/jpeg"
			}
		}
		
		var photoList = {
	
			auto: true,
			swf: 'js/lib/webuploader-0.1.5/Uploader.swf',
			server: global.getServerBaseUrl() + 'php/upload/uploadOld.php',
			pick: {id : '.photoList',
                    //只能选择一个文件上传
                    multiple: false},
                    
			error: function(err) {},
		
			success: function(response) {
	
				var fileName = response.fileName;
				var path = global.getServerBaseUrl() + 'uploadfiles/' + fileName;
				$('#android-diplay-img').append('<div class="androidDispalyImg"><li><img class="" src="' + path + '"></li></div>')
				layer.closeAll();
				
				//camera, photoList , fileList, voice video
			},
			error: function(err) {},
			buttonText: '',
	
			accept: {
				title: 'Image',
				extensions: 'gif,jpg,jpeg,bmp,png,photoList',
				mimeTypes: 'image/jpg,image/jpeg,image/png,image/photoList'
			},
			thumb: {
				width: 120,
				height: 90,
				quality: 100,
				allowMagnify: true,
				crop: true,
				type: "image/jpeg"
			}
		}
		
		var fileList = {
	
			auto: true,
			swf: 'js/lib/webuploader-0.1.5/Uploader.swf',
			server: global.getServerBaseUrl() + 'php/upload/uploadOld.php',
			pick: {id : '.fileList',
                    //只能选择一个文件上传
                    multiple: false},
                    
			error: function(err) {},
			
			success: function(response) {
	
				var fileName = response.fileName;
				var path = global.getServerBaseUrl() + 'uploadfiles/' + fileName;
				$('#android-diplay-img').append('<div class="androidDispalyImg"><li><a class="" href="' + path + '" download ="">'+fileName+'</a></li></div>');
				layer.closeAll();
				
				//camera, photoList , fileList, voice video
			},
			error: function(err) {},
			buttonText: '',
	
			accept: {
				title: 'Image',
				extensions: 'gif,jpg,jpeg,bmp,png,exe,fileList',
				mimeTypes: 'image/jpg,image/jpeg,image/png,image/fileList'
			},
			
		}
		
		var voice = {
	
			auto: true,
			swf: 'js/lib/webuploader-0.1.5/Uploader.swf',
			server: global.getServerBaseUrl() + 'php/upload/uploadOld.php',
			pick: {id : '.voice',
                    //只能选择一个文件上传
                    multiple: false},
                    
			error: function(err) {},
			//				formData: {
			//					curType: type,
			//					curId: companyId,
			//					curCity: "0551",
			//					secondListName: name,
			//					detailType: "secondPlan",
			//					optionType: "二维预案录入",
			//			
			//				},
			success: function(response) {
	
				var fileName = response.fileName;
				var path = global.getServerBaseUrl() + 'uploadfiles/' + fileName;
				$('#android-diplay-img').append('<div class="androidDispalyImg"><li><a class="" href="' + path + '" download =' + fileName + '>'+fileName+'</a></li></div>')
				layer.closeAll();
				
				//camera, photoList , fileList, voice video
			},
			error: function(err) {},
			buttonText: '',
	
			accept: {
				title: 'Image',
				extensions: 'mp3,voice',
				mimeTypes: 'image/mp3,image/voice'
			},
		
		}
		
		var video = {
	
			auto: true,
			swf: 'js/lib/webuploader-0.1.5/Uploader.swf',
			server: global.getServerBaseUrl() + 'php/upload/uploadOld.php',
			pick: {id : '.video',
                    //只能选择一个文件上传
                    multiple: false},
                    
			error: function(err) {},
			//				formData: {
			//					curType: type,
			//					curId: companyId,
			//					curCity: "0551",
			//					secondListName: name,
			//					detailType: "secondPlan",
			//					optionType: "二维预案录入",
			//			
			//				},
			success: function(response) {
	
				var fileName = response.fileName;
				var path = global.getServerBaseUrl() + 'uploadfiles/' + fileName;
				$('#android-diplay-img').append('<div class="androidDispalyImg"><li><a class="" href="' + path + '" download =' + fileName + '>'+fileName+'</a></li></div>')
				layer.closeAll();
				
				//camera, photoList , fileList, voice video
			},
			error: function(err) {},
			buttonText: '',
	
			accept: {
				title: 'Image',
				extensions: 'mp4,video',
				mimeTypes: 'image/mp4,image/video'
			},
			
		}
	
		
		uploadResource.initUpload(camera, $('.camera'));
		uploadResource.initUpload(photoList, $('.photoList'));
		uploadResource.initUpload(fileList, $('.fileList'));
		uploadResource.initUpload(voice, $('.voice'));
		uploadResource.initUpload(video, $('.video'));
		
	}
	
	/******************************************************************************
	 * Desc: 相关事件监听
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _initHandlers() {
		
		$('#page-Settings').on('pageshow', function(evt) {
		
			if(typeof me.user == "undefined") {
				return;
			}
			
			_user = me.user;
			
			//单位 组织机构
			var department = _user['department'];
			
			//用户名
			var userName = _user['userName'];
			
			//城市编码
			var cityCode = _user['cityCode'];
			
			//名称
			var name = _user['name'];
			
			//职务
			var post = _user['post'];
			
			var detailInfo = post + '/' + department + '/' + '18710713192';
			$('#page-me-detail-info td .userName').text(userName);
			$('#page-me-detail-info td .detailInfo').text(detailInfo);
			
		});
		
		
		
		$(document).on('click','#page-Settings-list  li  a , .aboutMe .userManager',function(evt) {
//		$('#page-Settings-list ').on('tap', 'li a', function(evt) {
//		$(document).on('click','#page-Settings-list li a', function(evt) {
			
			var name = $(this).attr('data-name');
			var dataTarget = $(this).attr('data-targetManger');
			
			//标题
			function initTitle() {
			
				
				$('#page-settings-managerInfo-title').text(name);
			}
			
			//清除数据
			function clearData() {
			
				$('#page-settings-managerInfo-list').empty();
			}
			
			//路由页面
			function distachPage() {
			
				
				_opManagers(dataTarget);
				
				$("#page-settings-managerInfo-list").trigger("create");
			}
			
			//标题
			initTitle();
			
			//清除数据
			clearData();
			
			//路由页面
			distachPage();
		})
		
		$(document).on('click','#page-Settings .userExit',function(evt) {
			
			
			//底部对话框
			layer.open({
				content: '是否确定退出程序',
				btn: ['确认', '取消'],
				skin: 'footer',
				yes: function(index) {
					
					
					var index = layer.open({
						type: 2,
						content: '退出中',
						time: 10
					});
					window.sessionStorage.clear();
					window.localStorage.clear();
					
					window.ZZSoftMobileApp.sendMessage(androidInterface.html5ToAppMessageList.HTML5_TO_ANDROID_MESSAGE_CLEARAPPDATA, [],function(data) {
						if(typeof data != 'undefined') {
							if(data) {
								layer.close(index);
							}
						}
					});
					
					messageManager.sendMessage(messageManager.serverTypes.MSG_TYPE_FORCEEXIT, {});
					
					
					window.location.href = 'login.html';
				},
			});
		})
		
		$(document).on('click', '#page-settings-managerInfo-list .sysSetting .setIpBtn', function(evt) {
		
			var newIpName = $('#page-settings-managerInfo-list .sysSetting .setIpName');
			if(typeof newIpName == "undefined" || newIpName == "") {
				
				//提示对话框
				layer.open({
					content: "请填写需要设置的内容",
				});
				
				return;
			}
			else {
				
				
				window.ZZSoftMobileApp.sendMessage(androidInterface.html5ToAppMessageList.HTML5_TO_ANDROID_MESSAGE_SETAPPIP, ['newIpName', newIpName],function(data){
					var ret = data;
					if(ret) {
						window.location.href = 'login.html';
					}
				});
			}
			
		})
		
		$(document).on('click','.uploaderAdd',function(evt) {
			//底部提示
			var html = "";
			html += "       	<div id='uploader-list'>";
			html += "       		<div class='uploaderTest'>";
			html += "					<ul>";
			html += "						<li><div id='camera' class='camera uploader'></div><div>拍摄</div></li>";	
			html += "						<li><div id='photoList' class='photoList uploader'></div><div>相册</div></li>";	
			html += "						<li><div id='fileList' class='fileList uploader'></div><div>文件</div></li>";	
			html += "						<li><div id='voice' class='voice uploader'></div><div>语音</div></li>";	
			html += "						<li><div id='video' class='video uploader'></div><div>视频</div></li>";	
			html += "					</ul>";
			html += "      			</div>";		
			html += "      		</div>";
			
			layer.open({
				content: html,
				skin: 'footer'
			});
			
			_initUpload();
		})
		
		$(document).on('click', '.uploaderTest li ', function(evt) {
		
//			_initUpload();
		
		})
	}
	
	
	
	/******************************************************************************
	 * Desc: 注册处理函数
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _registerManager() {
		
		_initManagers['userManager'] = _initUserManager;
		_initManagers['sysManager'] = _initSysManager;
		_initManagers['optManager'] = _initOptManager;
		_initManagers['infoBackManager'] = _initInfoBackManager;
		_initManagers['UsManager'] = _initUsManager;
		_initManagers['versionManager'] = _initVersionManagers,
		_initManagers['uploaderTest'] = _uploaderTest

	}
	

	
	/******************************************************************************
	 * Desc: 唯一入口
	 * 
	 * @param 
	 *
	 * @return 
	 *		void
	 */
	function _init() {
		
		$(document.body).append(_buildHtml());
		
		//注册处理函数
		_registerManager();
	
		//初始化监听
		_initHandlers();
	}
	
	function _resize() {
	
	}
	
	function _getTitle() {
		return _title;
	}
	
	function _getUrl() {
		return '#' + _pageId;
	}
	
	function _getIcon() {
	
		return _icon;
	}
	function _getClass() {
		return _class;
	}
	
	return {
		resize: _resize,
		init: _init,
		getTitle: _getTitle,
		getUrl: _getUrl,
		getIcon: _getIcon,
		getClass:_getClass
	}
});