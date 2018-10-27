<?php
/**
 * 文件工具类
 */
class FileUtil {
	
	
	//获取文件后缀名函数 
	static function  fileext($filename) 
	{ 
	    return substr(strrchr($filename, '.'), 1); 
	} 
	

	//生成随机文件名函数 
	static  function random($length) 
	{
		 
	    $hash = 'CR-'; 
	    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz'; 
	    $max = strlen($chars) - 1; 
	    mt_srand((double)microtime() * 1000000); 
	    for($i = 0; $i < $length; $i++) 
	    { 
	        $hash .= $chars[mt_rand(0, $max)]; 
	    } 
	    return $hash; 
	} 


    /**
     * 建立文件夹
     *
     * @param string $aimUrl
     * @return viod
     */
    static function createDir($aimUrl) {
    	
        $aimUrl = str_replace('', '/', $aimUrl);
        $aimDir = '';
        $arr = explode('/', $aimUrl);
        $result = true;
        foreach ($arr as $str) {
            $aimDir .= $str . '/';
            if (!file_exists($aimDir)) {
               if(mkdir($aimDir)){
                 @chmod($aimDir, 0777);
                 $result = true;
               }
            }
        }
        return $result;
    }

    /**
     * 建立文件
     *
     * @param string $aimUrl 
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    static function createFile($aimUrl, $overWrite = false) {
        if (file_exists($aimUrl) && $overWrite == false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite == true) {
            FileUtil :: unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        FileUtil :: createDir($aimDir);
        touch($aimUrl);
        return true;
    }

    /**
     * 移动文件夹
     *
     * @param string $oldDir
     * @param string $aimDir
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    static function moveDir($oldDir, $aimDir, $overWrite = false) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        $oldDir = str_replace('', '/', $oldDir);
        $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
        if (!is_dir($oldDir)) {
            return false;
        }
        if (!file_exists($aimDir)) {
            FileUtil :: createDir($aimDir);
        }
        @ $dirHandle = opendir($oldDir);
        if (!$dirHandle) {
            return false;
        }
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($oldDir . $file)) {
                FileUtil :: moveFile($oldDir . $file, $aimDir . $file, $overWrite);
            } else {
                FileUtil :: moveDir($oldDir . $file, $aimDir . $file, $overWrite);
            }
        }
        closedir($dirHandle);
        return rmdir($oldDir);
    }

    /**
     * 移动文件
     *
     * @param string $fileUrl
     * @param string $aimUrl
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    static function moveFile($fileUrl, $aimUrl, $overWrite = false) {
        if (!file_exists($fileUrl)) {
            return false;
        }
        if (file_exists($aimUrl) && $overWrite = false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite = true) {
            FileUtil :: unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        FileUtil :: createDir($aimDir);
        rename($fileUrl, $aimUrl);
        return true;
    }

    /**
     * 删除文件夹
     *
     * @param string $aimDir
     * @return boolean
     */
    static function unlinkDir($aimDir) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        if (!is_dir($aimDir)) {
            return false;
        }
        $dirHandle = opendir($aimDir);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($aimDir . $file)) {
                FileUtil :: unlinkFile($aimDir . $file);
            } else {
                FileUtil :: unlinkDir($aimDir . $file);
            }
        }
        closedir($dirHandle);
        return rmdir($aimDir);
    }

    /**
     * 删除文件
     *
     * @param string $aimUrl
     * @return boolean
     */
    static function unlinkFile($aimUrl) {
        if (file_exists($aimUrl)) {
            unlink($aimUrl);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 复制文件夹
     *
     * @param string $oldDir
     * @param string $aimDir
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    static function copyDir($oldDir, $aimDir, $overWrite = false) {
        $aimDir = str_replace('', '/', $aimDir);
        $aimDir = substr($aimDir, -1) == '/' ? $aimDir : $aimDir . '/';
        $oldDir = str_replace('', '/', $oldDir);
        $oldDir = substr($oldDir, -1) == '/' ? $oldDir : $oldDir . '/';
        if (!is_dir($oldDir)) {
            return false;
        }
        if (!file_exists($aimDir)) {
            FileUtil :: createDir($aimDir);
        }
        $dirHandle = opendir($oldDir);
        while (false !== ($file = readdir($dirHandle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if (!is_dir($oldDir . $file)) {
                FileUtil :: copyFile($oldDir . $file, $aimDir . $file, $overWrite);
            } else {
                FileUtil :: copyDir($oldDir . $file, $aimDir . $file, $overWrite);
            }
        }
        return closedir($dirHandle);
    }

    /**
     * 复制文件
     *
     * @param string $fileUrl
     * @param string $aimUrl
     * @param boolean $overWrite 该参数控制是否覆盖原文件
     * @return boolean
     */
    static function copyFile($fileUrl, $aimUrl, $overWrite = false) {
        if (!file_exists($fileUrl)) {
            return false;
        }
        if (file_exists($aimUrl) && $overWrite == false) {
            return false;
        } elseif (file_exists($aimUrl) && $overWrite == true) {
            FileUtil :: unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        FileUtil :: createDir($aimDir);
        $flag = false;
        if(copy($fileUrl, $aimUrl)){
            @chmod($aimUrl, 0777);
            $flag = true;
        }
        return $flag;
    }   
	
	
	/*
	 * 检查目标文件夹是否存在，如果不存在则自动创建该目录
	 * @param       string      folder     目录路径。不能使用相对于网站根目录的URL
	 */
	 static public function make_dir($folder)
	 {   
	    $reval = false;
	    if (!file_exists($folder))
	    {
	        /* 如果目录不存在则尝试创建该目录 */
	        @umask(0);
	        /* 将目录路径拆分成数组 */
	        preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);
	        /* 如果第一个字符为/则当作物理路径处理 */
	        $base = ($atmp[0][0] == '/') ? '/' : '';
	        /* 遍历包含路径信息的数组 */
	        foreach ($atmp[1] AS $val)
	        {
	            if ('' != $val)
	            {
	                $base .= $val;
	                if ('..' == $val || '.' == $val)
	                {
	                    /* 如果目录为.或者..则直接补/继续下一个循环 */
	                    $base .= '/';
	                    continue;
	                }
	            }
	            else
	            {
	                continue;
	            }
	            $base .= '/';
	            if (!file_exists($base))
	            {
	                /* 尝试创建目录，如果创建失败则继续循环 */
	                if (@mkdir(rtrim($base, '/'), 0777))
	                {
	                    @chmod($base, 0777);
	                    $reval = true;
	                }
	            }
	        }
	    }
	    else
	    {
	        /* 路径已经存在。返回该路径是不是一个目录 */
	        $reval = is_dir($folder);
	    }
	    clearstatcache();
	    return $reval;
	 }
}

?>