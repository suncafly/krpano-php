<?php
/**
 * create By :lichangming
 * time : 20180421
 *
 */
require_once "../doctrine2/bootstrap.php";

date_default_timezone_set("Asia/Shanghai");

// 开启session 
//session_start();


/**
 * 上传三维预案资源系统管理操作类
 */
class uploaderThirdPlansManager
{

    private static $instance;
    private $curCity;
    private $curTargetType;
    private $curId;
    private $detailType;
    private $resourceType;
    private $targetDir;
    private $uploadDir;
    private $cleanupTargetDir;
    private $maxFileAge;
    private $fileName;
    private $oldName;
    private $filePath;
    private $chunk;
    private $chunks;
    private $uploadPath;
    private $oldUploadDir;


    private function __construct()
    {
        $this->cleanupTargetDir = true;
        $this->maxFileAge = 5 * 3600;
    }

    public function  getCleanUpFlag()
    {
        return $this->cleanupTargetDir;
    }

    static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 系统参数校验
     * @param  void
     * @return bool
     */
    public function  sysParamCheck()
    {
        $ret = false;

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            return $ret; // finish preflight CORS requests here
        }

        if (!empty($_REQUEST['debug'])) {
            $random = rand(0, intval($_REQUEST['debug']));
            if ($random === 0) {
                header("HTTP/1.0 500 Internal Server Error");
                return $ret;
            }
        }
        return true;
    }

    /**
     * 自定义参数校验
     * @param  void
     * @return bool
     */
    public function customParamCheck()
    {
        if (!isset($_POST['curCity']) || !isset($_POST['type']) || !isset($_POST['id']) || !isset($_POST['detailType'])) {
            return false;
        }

        if (isset($_REQUEST["name"])) {
            $this->fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $this->fileName = $_FILES["file"]["name"];
        } else {
            $this->fileName = uniqid("file_");
        }

        $this->chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $this->chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

        $this->oldName = iconv('utf-8', 'gbk',$this->fileName);

        $this->curCity = $_POST['curCity'];
        $this->curTargetType = $_POST['curType'];
        $this->curId = $_POST['curId'];
        $this->detailType = $_POST['detailType'];

        return true;
    }

    /**
     * 根据当前目标属性类型 创建文件夹
     * @param  void
     *
     */
    public function createFileDirByTargetProperty()
    {

        $this->targetDir = iconv('utf-8', 'gbk', dirname(__FILE__) . "/../../uploadfiles/file_material_tmp");
        $this->oldUploadDir = "resource/$this->curCity/$this->curTargetType/$this->curId/$this->detailType";
        $this->uploadDir = iconv('utf-8', 'gbk', dirname(__FILE__) . "/../../resource/$this->curCity/$this->curTargetType/$this->curId/$this->detailType");

        // Create target dir
        if (!file_exists($this->targetDir)) {
            @mkdir($this->targetDir);
        }
        // Create target dir
        if ($this->uploadDir) {
            @mkdir($this->uploadDir, 0777, true);
        }
        
        $this->filePath = iconv('utf-8', 'gbk', $this->targetDir . DIRECTORY_SEPARATOR . $this->fileName);
    }

    /**
     * 清除临时文件夹
     * @param  void
     *
     */
    public function cleanTempFiles()
    {
        if (!is_dir($this->targetDir) || !$dir = opendir($this->targetDir)) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
        }
        while (($file = readdir($dir)) !== false) {
            $tmpfilePath = $this->targetDir . DIRECTORY_SEPARATOR . $file;
            // If temp file is current file proceed to the next
            if ($tmpfilePath == "{$this->filePath}_{$this->chunk}.part" || $tmpfilePath == "{$this->filePath}_{$this->chunk}.parttmp") {
                continue;
            }
            // Remove temp file if it is older than the max age and is not the current file
            if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $this->maxFileAge)) {
                @unlink($tmpfilePath);
            }
        }
        closedir($dir);
    }

    /**
     * 开始对上传的文件进行读写操作
     * @param  void
     * 
     */
    public function optionUploaderFileWirteAndRead()
    {
        // Open temp file
        if (!$out = @fopen("{$this->filePath}_{$this->chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$this->filePath}_{$this->chunk}.parttmp", "{$this->filePath}_{$this->chunk}.part");
        $index = 0;
        $done = true;
        for ($index = 0; $index < $this->chunks; $index++) {
            if (!file_exists("{$this->filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }

        if ($done) {

            $pathInfo = pathinfo($this->fileName);
            //$hashStr = substr(md5($pathInfo['basename']),8,16);
            //$hashName = time() . $hashStr . '.' .$pathInfo['extension'];

            //$hashName = iconv('utf-8', 'gbk', $this->fileName) . '.' . $pathInfo['extension'];
            //$backgroundName = $layerName.'.'.$pathInfo['extension'];
            $hashName =  iconv('utf-8', 'gbk',$this->fileName);
            
            $this->uploadPath = $this->uploadDir . DIRECTORY_SEPARATOR . $hashName;

            if (!$out = @fopen($this->uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $this->chunks; $index++) {
                    if (!$in = @fopen("{$this->filePath}_{$index}.part", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$this->filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);
            $fileSize = filesize($this->uploadPath);
            $response = [
                'success'      => true,
                'fileName'     => $this->fileName,
                'filePath'     => $this->oldUploadDir . '/' . $this->fileName,
                'fileSize'     => $fileSize,
                'fileSuffixes' => $pathInfo['extension'],
                'curId'        => $this->curId,

            ];
            $this->retDataToDisplay($response);

        }

    }

    /**
     * 返回数据到前端显示
     * @param $ret
     *
     */
    public function retDataToDisplay($ret)
    {
        if ($ret) {
            echo json_encode($ret);
			exit();
        }
        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }
}


/**
 * 上传单位视频楼层信息资源管理操作类
 */
class uploaderBuildLevelManager
{
    
    private static $instance;

    private $curCity;
    private $curTargetType;
    private $curId;
    private $detailType;
    private $resourceType;
    private $targetDir;
    private $uploadDir;
    private $cleanupTargetDir;
    private $maxFileAge;
    private $fileName;
    private $oldName;
    private $filePath;
    private $chunk;
    private $chunks;
    private $uploadPath;
    private $oldUploadDir;
    private $buildLevelName;


    private function __construct()
    {
        $this->cleanupTargetDir = true;
        $this->maxFileAge = 5 * 3600;
    }

    public function  getCleanUpFlag()
    {
        return $this->cleanupTargetDir;
    }

    static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 系统参数校验
     * @param  void  
     * @return bool
     */
    public function  sysParamCheck()
    {
        $ret = false;
        
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            return $ret; // finish preflight CORS requests here
        }
        
        if (!empty($_REQUEST['debug'])) {
            $random = rand(0, intval($_REQUEST['debug']));
            if ($random === 0) {
                header("HTTP/1.0 500 Internal Server Error");
                return $ret;
            }
        }
        return true;
    }

    /**
     * 自定义参数校验
     * @param  void 
     * @return bool
     */
    public function customParamCheck()
    {
        if (!isset($_POST['curCity']) || !isset($_POST['type']) || !isset($_POST['id']) || !isset($_POST['detailType']) || !isset($_POST['buildLevelName'])) {
            return false;
        }

        if (isset($_REQUEST["name"])) {
            $this->fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $this->fileName = $_FILES["file"]["name"];
        } else {
            $this->fileName = uniqid("file_");
        }

        $this->chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $this->chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

        $this->oldName = iconv('utf-8', 'gbk', $this->fileName);

        $this->curCity = $_POST['curCity'];
        $this->curTargetType = $_POST['curType'];
        $this->curId = $_POST['curId'];
        $this->detailType = $_POST['detailType'];
        $this->buildLevelName = $_POST['buildLevelName'];

        return true;
    }

    /**
     * 根据当前目标属性类型 创建文件夹
     * @param  void
     *
     */
    public function createFileDirByTargetProperty()
    {

        $this->targetDir = iconv('utf-8', 'gbk', dirname(__FILE__) . "/../../uploadfiles/file_material_tmp");
        $this->oldUploadDir = "resource/$this->curCity/$this->curTargetType/$this->curId/$this->detailType/$this->buildLevelName";
        $this->uploadDir = iconv('utf-8', 'gbk', dirname(__FILE__) . "/../../resource/$this->curCity/$this->curTargetType/$this->curId/$this->detailType/$this->buildLevelName");

        // Create target dir
        if (!file_exists($this->targetDir)) {
            @mkdir($this->targetDir);
        }
        // Create target dir
        if ($this->uploadDir) {
            @mkdir($this->uploadDir, 0777, true);

        }

        $this->filePath = iconv('utf-8', 'gbk', $this->targetDir . DIRECTORY_SEPARATOR . $this->fileName);
    }

    /**
     * 清除临时文件夹
     * @param  void
     *
     */
    public function cleanTempFiles()
    {
        if (!is_dir($this->targetDir) || !$dir = opendir($this->targetDir)) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
        }
        while (($file = readdir($dir)) !== false) {
            $tmpfilePath = $this->targetDir . DIRECTORY_SEPARATOR . $file;
            // If temp file is current file proceed to the next
            if ($tmpfilePath == "{$this->filePath}_{$this->chunk}.part" || $tmpfilePath == "{$this->filePath}_{$this->chunk}.parttmp") {
                continue;
            }
            // Remove temp file if it is older than the max age and is not the current file
            if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $this->maxFileAge)) {
                @unlink($tmpfilePath);
            }
        }
        closedir($dir);
    }

    /**
     * 开始对上传的文件进行读写操作
     * @param  void
     *
     */
    public function optionUploaderFileWirteAndRead()
    {
        // Open temp file
        if (!$out = @fopen("{$this->filePath}_{$this->chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$this->filePath}_{$this->chunk}.parttmp", "{$this->filePath}_{$this->chunk}.part");
        $index = 0;
        $done = true;
        for ($index = 0; $index < $this->chunks; $index++) {
            if (!file_exists("{$this->filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }

        if ($done) {

            $pathInfo = pathinfo($this->fileName);
            //$hashStr = substr(md5($pathInfo['basename']),8,16);
            //$hashName = time() . $hashStr . '.' .$pathInfo['extension'];

            //$hashName = iconv('utf-8', 'gbk', $this->fileName) . '.' . $pathInfo['extension'];
            //$backgroundName = $layerName.'.'.$pathInfo['extension'];
            $hashName = iconv('utf-8', 'gbk', $this->fileName);

            $this->uploadPath = $this->uploadDir . DIRECTORY_SEPARATOR . $hashName;

            if (!$out = @fopen($this->uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $this->chunks; $index++) {
                    if (!$in = @fopen("{$this->filePath}_{$index}.part", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$this->filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);
            $fileSize = filesize($this->uploadPath);
            $response = [
                'success'      => true,
                'fileName'     => $this->fileName,
                'filePath'     => $this->oldUploadDir . '/' . $this->fileName,
                'fileSize'     => $fileSize,
                'fileSuffixes' => $pathInfo['extension'],
                'curId'        => $this->curId,

            ];
            $this->retDataToDisplay($response);

        }

    }

    /**
     * 返回数据到前端显示
     * @param $ret
     *
     */
    public function retDataToDisplay($ret)
    {
        if ($ret) {
            echo json_encode($ret);
            exit();
        }
        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }
}
/**
 * 上传二维预案资源管理操作类
 */
class uploaderSecondPlansManager
{

    private static $instance;
	
    private $curCity;
    private $curTargetType;
    private $curId;
    private $detailType;
    private $resourceType;
    private $targetDir;
    private $uploadDir;
    private $cleanupTargetDir;
    private $maxFileAge;
    private $fileName;
    private $oldName;
    private $filePath;
    private $chunk;
    private $chunks;
    private $uploadPath;
    private $oldUploadDir;
	  private $secondListName;


    private function __construct()
    {
        $this->cleanupTargetDir = true;
        $this->maxFileAge = 5 * 3600;
    }

    public function  getCleanUpFlag()
    {
        return $this->cleanupTargetDir;
    }

    static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 系统参数校验
     * @param  void
     * @return bool
     */
    public function  sysParamCheck()
    {
        $ret = false;
        
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            return $ret; // finish preflight CORS requests here
        }

        if (!empty($_REQUEST['debug'])) {
            $random = rand(0, intval($_REQUEST['debug']));
            if ($random === 0) {
                header("HTTP/1.0 500 Internal Server Error");
                return $ret;
            }
        }
        return true;
    }

    /**
     * 自定义参数校验
     * @param  void
     * @return bool
     */
    public function customParamCheck()
    {
        if (!isset($_POST['curCity']) || !isset($_POST['type']) || !isset($_POST['id']) || !isset($_POST['detailType']) || !isset($_POST['secondListName'])) {
            return false;
        }

        if (isset($_REQUEST["name"])) {
            $this->fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $this->fileName = $_FILES["file"]["name"];
        } else {
            $this->fileName = uniqid("file_");
        }

        $this->chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $this->chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

        $this->oldName = iconv('utf-8', 'gbk',$this->fileName);

        $this->curCity = $_POST['curCity'];
        $this->curTargetType = $_POST['curType'];
        $this->curId = $_POST['curId'];
        $this->detailType = $_POST['detailType'];
		    $this->secondListName = $_POST['secondListName'];

        return true;
    }

    /**
     * 根据当前目标属性类型 创建文件夹
     * @param  void
     *
     */
    public function createFileDirByTargetProperty()
    {

        $this->targetDir = iconv('utf-8', 'gbk', dirname(__FILE__) . "/../../uploadfiles/file_material_tmp");
        $this->oldUploadDir = "resource/$this->curCity/$this->curTargetType/$this->curId/$this->detailType/$this->secondListName";
        $this->uploadDir = iconv('utf-8', 'gbk', dirname(__FILE__) . "/../../resource/$this->curCity/$this->curTargetType/$this->curId/$this->detailType/$this->secondListName");

        // Create target dir
        if (!file_exists($this->targetDir)) {
            @mkdir($this->targetDir);
        }
        // Create target dir
        if ($this->uploadDir) {
            @mkdir($this->uploadDir, 0777, true);

        }

        $this->filePath = iconv('utf-8', 'gbk', $this->targetDir . DIRECTORY_SEPARATOR . $this->fileName);
    }

    /**
     * 清除临时文件夹
     * @param  void
     *
     */
    public function cleanTempFiles()
    {

        if (!is_dir($this->targetDir) || !$dir = opendir($this->targetDir)) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
        }
        while (($file = readdir($dir)) !== false) {
            $tmpfilePath = $this->targetDir . DIRECTORY_SEPARATOR . $file;
            // If temp file is current file proceed to the next
            if ($tmpfilePath == "{$this->filePath}_{$this->chunk}.part" || $tmpfilePath == "{$this->filePath}_{$this->chunk}.parttmp") {
                continue;
            }
            // Remove temp file if it is older than the max age and is not the current file
            if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $this->maxFileAge)) {
                @unlink($tmpfilePath);
            }
        }
        closedir($dir);
    }

    /**
     * 开始对上传的文件进行读写操作
     * @param  void
     *
     */
    public function optionUploaderFileWirteAndRead()
    {
        // Open temp file
        if (!$out = @fopen("{$this->filePath}_{$this->chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$this->filePath}_{$this->chunk}.parttmp", "{$this->filePath}_{$this->chunk}.part");
        $index = 0;
        $done = true;
        for ($index = 0; $index < $this->chunks; $index++) {
            if (!file_exists("{$this->filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }

        if ($done) {


            $pathInfo = pathinfo($this->fileName);
            //$hashStr = substr(md5($pathInfo['basename']),8,16);
            //$hashName = time() . $hashStr . '.' .$pathInfo['extension'];
            
            //$hashName = iconv('utf-8', 'gbk', $this->fileName) . '.' . $pathInfo['extension'];
            //$backgroundName = $layerName.'.'.$pathInfo['extension'];
             $hashName =  iconv('utf-8', 'gbk',$this->fileName);
 
            $this->uploadPath = $this->uploadDir . DIRECTORY_SEPARATOR . $hashName;

            if (!$out = @fopen($this->uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $this->chunks; $index++) {
                    if (!$in = @fopen("{$this->filePath}_{$index}.part", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$this->filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);
            $fileSize = filesize($this->uploadPath);
            $response = [
                'success'      => true,
                'fileName'     => $this->fileName,
                'filePath'     => $this->oldUploadDir . '/' . $this->fileName,
                'fileSize'     => $fileSize,
                'fileSuffixes' => $pathInfo['extension'],
                'curId'        => $this->curId,

            ];
            $this->retDataToDisplay($response);

        }

    }

    /**
     * 返回数据到前端显示
     * @param $ret
     *
     */
    public function retDataToDisplay($ret)
    {
        if ($ret) {
            echo json_encode($ret);
		  	exit();
        }
        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }

//  /**
//   * 插入三维预案基本信息
//   * @param $ret
//   *
//   */
//  public function insertThirdPlanInfo($entityManager) {
//     
//
//  }
//  /**
//   * 插入上传信息到数据库中
//   * @param $ret
//   *
//   */
//  public function insertBaseInfo($entityManager)
//  {
//
//      if (!$entityManager) {
//          return;
//      }
//      if (!isset($_POST['optionType'])) {
//          return;
//      }
//      $curOption = $_POST['optionType'];
//      switch ($curOption) {
//          case "三维预案录入":
//              $this->insertThirdPlanInfo($entityManager);
//              break;
//
//          default:
//              break;
//      }
//
//
//
//  }
}
//qxf_add_20180809
/**
 * 上传信息互动资源管理操作类
 */
 class uploaderChatMessagesManager
{
    private static $instance;
	
    private $curCity;
    private $curTargetType;
    private $disasterId;       //ID
    private $resourceType;
    private $targetDir;
    private $uploadDir;
    private $cleanupTargetDir;
    private $maxFileAge;
    private $fileName;
    private $oldName;
    private $filePath;
    private $chunk;
    private $chunks;
    private $uploadPath;
    private $oldUploadDir;
	  private $userName;      //用户名称

    private function __construct()
    {
        $this->cleanupTargetDir = true;
        $this->maxFileAge = 5 * 3600;
    }

    public function  getCleanUpFlag()
    {
        return $this->cleanupTargetDir;
    }

    static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 系统参数校验
     * @param  void
     * @return bool
     */
    public function  sysParamCheck()
    {
        $ret = false;

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            return $ret; // finish preflight CORS requests here
        }

        if (!empty($_REQUEST['debug'])) {
            $random = rand(0, intval($_REQUEST['debug']));
            if ($random === 0) {
                header("HTTP/1.0 500 Internal Server Error");
                return $ret;
            }
        }
        return true;
    }

    /**
     * 自定义参数校验
     * @param  void
     * @return bool
     */
    public function customParamCheck()
    { 
        if (!isset($_POST['curCity']) || !isset($_POST['type']) || !isset($_POST['id'])  || !isset($_POST['userName'])) {
            return false;
        }
        
        if (isset($_REQUEST["name"])) {
            $this->fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $this->fileName = $_FILES["file"]["name"];
        } else {
            $this->fileName = uniqid("file_");
        }
        //qxf_change_20180814
        //检测文件上传类型
        //允许的文件扩展名 
        $allowed_types = array('gif','jpg', 'jpeg','bmp','png','mp3','wav','ogg','mp4'); 
        //$filename = $_FILES['filename']['name']; 
        //正则表达式匹配出上传文件的扩展名 
        preg_match('|\.(\w+)$|', $this->fileName, $ext); 
        //print_r($ext); 
        //转化成小写 
        $ext = strtolower($ext[1]); 
        //判断是否在被允许的扩展名里 
        if(!in_array($ext, $allowed_types))
        { 
          return false; 
        } 
        
        $this->chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $this->chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

        $this->oldName = iconv('utf-8', 'gbk',$this->fileName);

        $this->curCity = $_POST['curCity'];
        $this->curTargetType = $_POST['curType'];
        $this->disasterId = $_POST['disasterId'];
        $this->userName = $_POST['userName'];
        return true;
    }

    /**
     * 根据当前目标属性类型 创建文件夹
     * @param  void
     *
     */
    public function createFileDirByTargetProperty()
    {

        $this->targetDir = iconv('utf-8', 'gbk', dirname(__FILE__) . "/../../uploadfiles/file_material_tmp");
        $this->oldUploadDir = "resource/$this->curCity/$this->curTargetType/$this->disasterId/$this->userName";
        $this->uploadDir = iconv('utf-8', 'gbk', dirname(__FILE__) . "/../../resource/$this->curCity/$this->curTargetType/$this->disasterId/$this->userName");

        // Create target dir
        if (!file_exists($this->targetDir)) {
            @mkdir($this->targetDir);
        }
        // Create target dir
        if ($this->uploadDir) {
            @mkdir($this->uploadDir, 0777, true);

        }

        $this->filePath = iconv('utf-8', 'gbk', $this->targetDir . DIRECTORY_SEPARATOR . $this->fileName);
    }

    /**
     * 清除临时文件夹
     * @param  void
     *
     */
    public function cleanTempFiles()
    {

        if (!is_dir($this->targetDir) || !$dir = opendir($this->targetDir)) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
        }
        while (($file = readdir($dir)) !== false) {
            $tmpfilePath = $this->targetDir . DIRECTORY_SEPARATOR . $file;
            // If temp file is current file proceed to the next
            if ($tmpfilePath == "{$this->filePath}_{$this->chunk}.part" || $tmpfilePath == "{$this->filePath}_{$this->chunk}.parttmp") {
                continue;
            }
            // Remove temp file if it is older than the max age and is not the current file
            if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $this->maxFileAge)) {
                @unlink($tmpfilePath);
            }
        }
        closedir($dir);
    }

    /**
     * 开始对上传的文件进行读写操作
     * @param  void
     *
     */
    public function optionUploaderFileWirteAndRead()
    {
        // Open temp file
        if (!$out = @fopen("{$this->filePath}_{$this->chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$this->filePath}_{$this->chunk}.parttmp", "{$this->filePath}_{$this->chunk}.part");
        $index = 0;
        $done = true;
        for ($index = 0; $index < $this->chunks; $index++) {
            if (!file_exists("{$this->filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }

        if ($done) {

            $pathInfo = pathinfo($this->fileName);
            //$hashStr = substr(md5($pathInfo['basename']),8,16);
            //$hashName = time() . $hashStr . '.' .$pathInfo['extension'];
            
            //$hashName = iconv('utf-8', 'gbk', $this->fileName) . '.' . $pathInfo['extension'];
            //$backgroundName = $layerName.'.'.$pathInfo['extension'];
            $hashName =  iconv('utf-8', 'gbk',$this->fileName);
            
            $this->uploadPath = $this->uploadDir . DIRECTORY_SEPARATOR . $hashName;

            if (!$out = @fopen($this->uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $this->chunks; $index++) {
                    if (!$in = @fopen("{$this->filePath}_{$index}.part", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$this->filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);
            //qxf_change_20180813
            require '../../plugins/vendor/autoload.php';
            $index = strrpos($this->fileName,'.');      
            $extention = strtolower(substr($this->fileName,$index+1));  
            $videoextention = array("ogv", "mp4", "webm");
            $audioextention = array("mp3", "ogg", "wav");
            $ret = 0;
            if(in_array($extention,$audioextention)||in_array($extention,$videoextention))
            {
               //视频时生成缩略图
               $ffmpeg = FFMpeg\FFMpeg::create();
               $filePath = '../../'.$this->oldUploadDir . '/' . $this->fileName; 
               $ret = $ffmpeg->getFFProbe()      
                    ->format($filePath)    // extracts file informations
                      ->get('duration');   // returns the duration property
               if(in_array($extention,$videoextention))
               {
                  //$imgName = substr($this->fileName,-1*strlen(strrchr($this->fileName, '.'))); 
                   $imgName = '../../'.$this->oldUploadDir . '/'.$this->fileName.'.jpg';
                   $video = $ffmpeg->open($filePath);
                   $video 
                     ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(0))
                       ->save($imgName);
               }
              }
                     
            $fileSize = filesize($this->uploadPath);
            $response = [
                'success'      => true,
                'fileName'     => $this->fileName,
                'filePath'     => $this->oldUploadDir . '/' . $this->fileName,
                'fileSize'     => $fileSize,
                'fileSuffixes' => $pathInfo['extension'],
                'fileLength'   => $ret
            ];
            $this->retDataToDisplay($response);

        }

    }

    /**
     * 返回数据到前端显示
     * @param $ret
     *
     */
    public function retDataToDisplay($ret)
    {
        if ($ret) {
            echo json_encode($ret);
		  	exit();
        }
        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }
}

/**
 * //qxf_change_20180827
 * 上传装备管理资源管理操作类
 */
class uploaderEquipmentManager
{             
    private static $instance;
	  
    private $curCity;
    private $curTargetType;
    private $classType;
    private $Id;
    
    private $resourceType;
    private $targetDir;
    private $uploadDir;
    private $cleanupTargetDir;
    private $maxFileAge;
    private $fileName;
    private $oldName;
    private $filePath;
    private $chunk;
    private $chunks;
    private $uploadPath;
    private $oldUploadDir;


    private function __construct()
    {
        $this->cleanupTargetDir = true;
        $this->maxFileAge = 5 * 3600;
    }

    public function  getCleanUpFlag()
    {
        return $this->cleanupTargetDir;
    }

    static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 系统参数校验
     * @param  void
     * @return bool
     */
    public function  sysParamCheck()
    {
        $ret = false;
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            return $ret; // finish preflight CORS requests here
        }

        if (!empty($_REQUEST['debug'])) {
            $random = rand(0, intval($_REQUEST['debug']));
            if ($random === 0) {
                header("HTTP/1.0 500 Internal Server Error");
                return $ret;
            }
        }
        return true;
    }

    /**
     * 自定义参数校验
     * @param  void
     * @return bool
     */
    public function customParamCheck()
    {
        if (!isset($_POST['curCity']) || !isset($_POST['curType'])|| !isset($_POST['classType']) ||  !isset($_POST['Id'])) {
            return false;
        }

        if (isset($_REQUEST["name"])) {
            $this->fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $this->fileName = $_FILES["file"]["name"];
        } else {
            $this->fileName = uniqid("file_");
        }

        $this->chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $this->chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;

        $this->oldName = iconv('utf-8', 'gbk',$this->fileName);

        $this->curCity = $_POST['curCity'];
        $this->curTargetType = $_POST['curType'];
        $this->classType = $_POST['classType'];
        $this->Id=$_POST['Id'];
        return true;
    }

    /**
     * 根据当前目标属性类型 创建文件夹
     * @param  void
     *
     */
    public function createFileDirByTargetProperty()
    {
        $this->targetDir = iconv('utf-8', 'gbk', dirname(__FILE__) . "/../../uploadfiles/file_material_tmp");
        $this->oldUploadDir = "resource/$this->curCity/$this->curTargetType/$this->classType/$this->Id";
        $this->uploadDir = iconv('utf-8', 'gbk', dirname(__FILE__) . "/../../resource/$this->curCity/$this->curTargetType/$this->classType/$this->Id");

        // Create target dir
        if (!file_exists($this->targetDir)) {
            @mkdir($this->targetDir);
        }
        // Create target dir
        if ($this->uploadDir) {
            @mkdir($this->uploadDir, 0777, true);

        }

        $this->filePath = iconv('utf-8', 'gbk', $this->targetDir . DIRECTORY_SEPARATOR . $this->fileName);
    }

    /**
     * 清除临时文件夹
     * @param  void
     *
     */
    public function cleanTempFiles()
    {

        if (!is_dir($this->targetDir) || !$dir = opendir($this->targetDir)) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
        }
        while (($file = readdir($dir)) !== false) {
            $tmpfilePath = $this->targetDir . DIRECTORY_SEPARATOR . $file;
            // If temp file is current file proceed to the next
            if ($tmpfilePath == "{$this->filePath}_{$this->chunk}.part" || $tmpfilePath == "{$this->filePath}_{$this->chunk}.parttmp") {
                continue;
            }
            // Remove temp file if it is older than the max age and is not the current file
            if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $this->maxFileAge)) {
                @unlink($tmpfilePath);
            }
        }
        closedir($dir);
    }

    /**
     * 开始对上传的文件进行读写操作
     * @param  void
     *
     */
    public function optionUploaderFileWirteAndRead()
    {
        // Open temp file
        if (!$out = @fopen("{$this->filePath}_{$this->chunk}.parttmp", "wb")) {
            die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }
        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
            }
            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
            }
        }
        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }
        @fclose($out);
        @fclose($in);
        rename("{$this->filePath}_{$this->chunk}.parttmp", "{$this->filePath}_{$this->chunk}.part");
        $index = 0;
        $done = true;
        for ($index = 0; $index < $this->chunks; $index++) {
            if (!file_exists("{$this->filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }

        if ($done) {


            $pathInfo = pathinfo($this->fileName);
            //$hashStr = substr(md5($pathInfo['basename']),8,16);
            //$hashName = time() . $hashStr . '.' .$pathInfo['extension'];
            
            //$hashName = iconv('utf-8', 'gbk', $this->fileName) . '.' . $pathInfo['extension'];
            //$backgroundName = $layerName.'.'.$pathInfo['extension'];
             $hashName =  iconv('utf-8', 'gbk',$this->fileName);
 
            $this->uploadPath = $this->uploadDir . DIRECTORY_SEPARATOR . $hashName;

            if (!$out = @fopen($this->uploadPath, "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }
            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $this->chunks; $index++) {
                    if (!$in = @fopen("{$this->filePath}_{$index}.part", "rb")) {
                        break;
                    }
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$this->filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);
            $fileSize = filesize($this->uploadPath);
            $response = [
                'success'      => true,
                'fileName'     => $this->fileName,
                'filePath'     => $this->oldUploadDir . '/' . $this->fileName,
                'fileSize'     => $fileSize,
                'fileSuffixes' => $pathInfo['extension'],
                'classType'    => $this->classType,
                'Id'           => $this->Id,
            ];
            $this->retDataToDisplay($response);

        }

    }

    /**
     * 返回数据到前端显示
     * @param $ret
     *
     */
    public function retDataToDisplay($ret)
    {
        if ($ret) {
            echo json_encode($ret);
		  	exit();
        }
        // Return Success JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }
}

?>
