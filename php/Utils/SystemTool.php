<?php

class SystemTool 
{
	const STATE_ERROR = 'error';
	const STATE_SUCCESS = 'success';	
	protected $result = array("state" => "error", "data" => "调用有误!");

	protected function setResult($state, $data)
	{
		if ($state !== self::STATE_ERROR && $state !== self::STATE_SUCCESS) {
			return;
		}
			
		$this->result = array('state' => $state, 'msg' => $data);
	}
		
	/**
	 * 说明：设置响应结果
	 */
	protected function setSuccessResult($data) 
	{
		$this->setResult(self::STATE_SUCCESS, $data);
	}
		
	protected function setErrorResult($data) 
	{
		$this->setResult(self::STATE_ERROR, $data);
	}		
	
	public function __construct()
	{
			
	}
		
	public function parse($params) 
	{
		if (!array_key_exists('opCode', $params)) {
			return false;
		}
		
		$opCode = $params['opCode'];
		if (method_exists($this, $opCode)) {
			if (call_user_func_array(array($this, $opCode), array($params))) {
				return true;
			}
		}
		
		return true;
	}
	
	public function getYestodayDate()
	{
		$this->setSuccessResult((new DateTime('-1 days'))->format('Y-m-d'));
		
		return true;
	}
		
	public function response()
	{
		echo json_encode($this->result, JSON_UNESCAPED_UNICODE);	
	}
}

$st = new SystemTool();
$st->parse($_POST);
$st->response();