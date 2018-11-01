define(['jquery', 'bootstrap','fileInput','explorerfa','filelocalZh','themeFa','layuiModule'], function($, bootstrap,fileInput,explorerfa,filelocalZh,themeFa,layuiModule) {



	var uploaderTarget = null;

	/******************************************************************************
	 * Desc: 上传插件fileinput 的默认配置
	 * 
	 *
	 * @return 
	 *		void
	 */
	function _getOption() {
		/*
		 *	配置文件同webUploader一致,这里只给出默认配置.
		 *	具体参照:http://fex.baidu.com/webuploader/doc/index.html
		 */
		return {
			
			//主题模板
			theme: 'fa',
			
			//语言
			language: 'zh',
			
			//是否显示上传按钮
			showUpload: false,
			
			//是否显示移除上传按钮
			showRemove: false,
			
			//是否显示取消按钮
			showCancel: false,
			
			//是否显示标题
			showCaption: false,
			
			//是否显示上传缩略图
			showUploadedThumbs: false,
			
			//上传文件的个数限制
			maxFileCount: 20000,
			
			//上传文件的size最大显示
			maxFileSize: 6000000,
			
			//文件默认目录
			previewFileType: "image",
			
			//接收的文件后缀
			allowedFileExtensions: ["jpg", "jpeg", "tif", "tiff", "JPG"],
			
			//非法类型提示
			msgInvalidFileExtension: '不支持文件类型"{name}"。只支持扩展名为"{extensions}"的文件。',
			
			//按钮样式
			browseClass: "btn btn-primary",
			
			//按钮说明
			browseLabel: "选择本地全景图片",
			
			//按钮icon
			browseIcon: "<i class=\"icon icon-picture\"></i> ",
			
			//移除按钮样式
			removeClass: "btn btn-danger",
			
			//移除按钮说明
			removeLabel: "删除",
			
			//移除按钮icon
			removeIcon: "<i class=\"icon icon-trash\"></i> ",
			
			//上传地址
			uploadUrl: '../php/ImageUpload.php',
			
			//是否异步上传
			uploadAsync: true,
			
			fileActionSettings: {},
			
			//拖拽显示区
			dropZoneTitle: "拖拽一组/单幅图片或点击下面按钮上传",
			
			//编码方式
			textEncoding: "UTF-8",
			
		};
	}
	
	
	/******************************************************************************
	 * Desc: 实例化上传插件
	 * 
	 * @param 上传方法 opt为参数配置   curEle 为需要初始化的上传元素
	 *
	 * @return 
	 *		void
	 */
	function _getUploader(opt,uploaderElem) {
	
		return $("#" + uploaderElem).fileinput(opt);
	}





	/******************************************************************************
	 * Desc: 通用的上传插件配置
	 * 
	 * @param 上传方法 opt为参数配置   curEle 为需要初始化的上传元素
	 *
	 * @return 
	 *		void
	 */
	function _initUpload(opt, uploaderElem) {
		
		
		//判断参数配置opt
		if(typeof opt != 'object') {
			layer.msg('参数错误');
			return;
		}
		
		
		//判断当前元素
		if(typeof uploaderElem == "undefined") {
			return;
		}
		
		//组装参数
		if(opt.uploadUrl) {
			opt.uploadUrl = opt.uploadUrl;
		}
		
		if(opt.success) {
			var successCallBack = opt.success;
		
		}
		
		if(opt.fileloaded) {
			var fileloadedCallBack = opt.fileloaded;
		}
		
		
		if(opt.error) {
			var errorCallBack = opt.error;
		}
		
		
		//迭代获取默认参数配置
		
		$.each(_getOption(), function(key, value) {
			opt[key] = opt[key] || value
		})
		
		
		//创建fileinput 上传插件
		uploaderTarget = _getUploader(opt,uploaderElem);
		
		//没有创建成功则直接返回
		if(!uploaderTarget) {
			return;
		}
		
		uploaderTarget.on('fileloaded', function(event, file, previewId, index, reader) {
		
			if(fileloadedCallBack) {
				fileloadedCallBack(event, file, previewId, index, reader);
			}
		
		});
		
		//异步上传成功结果处理
		uploaderTarget.on("fileuploaded", function(event, data, index) {
			
			var response = data.response;
			if(response != null && response.status == 'success') {
				if(successCallBack) {
					successCallBack(response);
				}
			} else {
				if(errorCallBack) {
					errorCallBack(response);
				}
			}
		});
		
		uploaderTarget.on('fileuploaderror',function(){
//			uploaderTarget.fileinput('reset');
		})
		
		
		
		
		
		return uploaderTarget;
		
	}

	
	return {
		
		initUpload:_initUpload
	}
});