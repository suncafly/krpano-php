<?php

/**************************************************************************
 *
 * 说明：全景图片操作类
 * 作者：李长明
 * 时间：20180917
 *
 *************************************************************************/

require_once "krpanoConfigConstant.php";
require_once "krpanoRegister.php";	
require_once "KrpanoCommonOperation.php";

class PanoImgResourceUploader extends fileBaseOpHandler
{
	
	//设置允许上传文件的类型
	protected  $type = array("jpg","gif","bmp","jpeg","png"); 
	

	//设置文件保存目录
	protected $uploadDir = '../../resource';
	
	protected $oldUploadDir = 'resource/';
	
	public function __construct($em)
	{
		parent::__construct($em);
	}
	
	public function parse($params)
	{
		$post = $params['post'];
		if (!$this->checkProps(['opCode'], $post)) {
			return false;
		}
		
			
		//根据操作
		$opCode = $post['opCode'];
		
		if (method_exists($this, $opCode)) {
			if (call_user_func_array(array($this, $opCode), array($params))) {
				return true;
			}
		}
		
		return true;
	}
	
	protected function panoSlice($fileNamePath,$dir) {
		
		
		if(!$fileNamePath || !$dir) {
			return array('ret'=>'error',"info"=>"切图失败，没有获取到资源");
		}
		
		
		if(!FileUtil::createDir($dir)) {
			
			return array('ret'=>'error',"info"=>"创建目录失败");
	    	
		}
		
		$dst_panos_dir = $dir . "/panos/";
		
		//创建目录
		if(!FileUtil::make_dir($dst_panos_dir)) {
			return array('ret'=>'error',"info"=>"创建目录失败");
		}
		
						
		$result = KrOperation::slice($fileNamePath,$dst_panos_dir);
	
		return $result;
		
	}
	
	
	/**************************************************************************
	 *
	 * 说明：上传全景图片
	 * 作者：李长明
	 * 时间：20180917
	 */
	 
	protected function uploaderPanoImg($params)
	{
		
		$post = $params['post'];
		$files = $params['files'];
		
		$thumbPath = "";
		
		$panoSliceRet = '';
		
		if (!$this->checkProps(['curEntityClass','layerid'], $post)) {
			return array();
		}
		
		$curEntityClass = $post['curEntityClass'];
		
		$layerid = $post['layerid'];
		
		$dir = $this->uploadDir . '/' . $curEntityClass . '/' .$layerid;
		
		$retUploader = array();
		
		$retUploader = $this->uploader($dir, $files, $this->type);
		
		$fileNamePath = $retUploader['filePath'];
		
		$targetFileName = $retUploader['fileName'];
		
		$targetType = $retUploader['type'];
		
		
		$result = $this->getResult();
		
		//上传失败
		if($result['state'] == "error") {
			
			return; 
		}
		
		//上传成功 
		else {
			
			//创建缩略图
			$thumbPath = $this->createThumb($files,$dir,$targetFileName,$targetType);
			
			//执行切割  生成切割之后的全景资源 已经xml场景 TODO lizhangming 20181019
			$panoSliceRet = $this->panoSlice($fileNamePath,$dir);
			
			//执行切图失败 
			if($panoSliceRet['ret'] == "error") {
				
				$this->setErrorResult($panoSliceRet['info']);
				return true;
			}
	
		}
		
		//保存信息到数据库
		$conditions = array('id' => $layerid);
		
		$resourceEntity = $this->em->getRepository($curEntityClass)->findOneBy($conditions);

		if(!$resourceEntity) {
			
			return array();
		}
		
		
		//首先判断是否已经上传过 如果上传过则数据将不再插入库中
		$conditions = array('resFilePathInServer' => $fileNamePath);
		$resourcePano = $this->em->getRepository('PanoImgResource')->findOneBy($conditions);
		if($resourcePano) {
			
			$retArray = array(
		
				'id' => $resourcePano->getId(),
				'fileName' => $resourcePano->getResFileServerName(), 
				'filePath' => $resourcePano->getResFilePathInServer(), 
				'fileThumbPath' => $resourcePano->getResThumbFilePathInServer()
			);
			
			$this->setSuccessResult($retArray);
			return true;
		}
		
		
		$filename = $files['file']['name'];
		$scenes = $panoSliceRet['scenes'];
		$panosPath = $panoSliceRet['panosPath'];
		
		
		$dt = new DateTime('NOW');
		
		$t = $dt->format('Y-m-d H:i:s');
	
 		$panoImgResource = new PanoImgResource();
        $panoImgResource->setResType("图片");
        $panoImgResource->setResFilePathInServer($fileNamePath);
		$panoImgResource->setResThumbFilePathInServer($thumbPath);
		$panoImgResource->setResPanoPathInServer($panosPath);
		$panoImgResource->setResSceneString($scenes);
		$panoImgResource->setResUploadPerson("李长明");
		$panoImgResource->setResUploaderTime(new DateTime($t));
		$panoImgResource->setResFileServerName($filename);
		$panoImgResource->setPanoImglayerInfo($resourceEntity);


		$this->em->persist($panoImgResource);

		$this->em->flush();
		
		$retArray = array(
		
					'id' => $panoImgResource->getId(),
					'fileName' => $filename, 
					'filePath' => $fileNamePath, 
					'fileThumbPath' => $thumbPath
					);

		$this->setSuccessResult($retArray);
		
		return true;
	}
	
}
