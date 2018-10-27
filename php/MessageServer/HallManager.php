<?php
	// HallManager.php
	
	/**************************************************************************
	 *
	 * 说明：大厅内的一个房间，管理与该房间相关的用户信息。
	 *
	 *************************************************************************/
	class Room 
	{
		private $roomName; // 大厅名称
		
		private $users = array(); // 用户信息
		
		public function __construct($roomId)
		{
			$this->roomName = $roomId;
		}
		
		public function __destruct() 
		{
		}
		
		public function getRoomId() {
			return $this->roomName;
		}
		/*
		 * 说明：添加用户信息
		 *
		 */
		public function addUser($userName, $user, $socketId)
		{
			$this->users[$userName] = array( 'user' => $user, 'socketId' => $socketId);
		}
		
		/*
		 * 说明：移除用户
		 *
		 */
		public function removeUser($userName) 
		{
			if (!array_key_exists($userName, $this->users)) {
				return;
			}
			
			unset($this->users[$userName]);
		}
		
		/*
		 * 说明：获取用户信息
		 *
		 */
		public function getUser($userName) 
		{
			return array_key_exists($userName, $this->users ) ? $this->users[$userName] : null;
		}
		
		/*
		 * 说明：获取某单位所有在线注册用户信息
		 *
		 */
		public function getUsersByDepartmentId($departmentId) 
		{
			$tmps = array();
			foreach ($this->users as $userName => $item) {
				if ($item['user']->getDepartmentId() == $departmentId) {
					$tmps[] = $item;
				}
			}
			
			//return count($tmps) > 0 ? $tmps : null;
			
			return $tmps;
		}
		
		/*
		 * 说明：获取所欲用户信息
		 *
		 */
		public function getAllUserInfos() 
		{
			return $this->users;
		}
		
		public function setSocketId($userName, $id)
		{
			if (array_key_exists($userName, $this->users)) {
				$this->users[$userName]['socketId'] = $id;
			} 
		}
	}
	
	/**************************************************************************
	 *
	 * 说明：大厅管理器，管理各个房间。
	 *
	 *************************************************************************/
	class HallManager 
	{
		static private $instance;
		
		private $rooms = array();
		
		protected function __construct() {}
		
		protected function __clone() {}
		
		static public function getSingleton() 
		{
			if (!(self::$instance instanceof self)) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		/**
		 * 说明：获取对应房间
		 * @params
		 *         int $roomId
		 * @return 
		 *         对应房间信息
		 */
		public function getRoom($roomId)
		{
			if (!array_key_exists($roomId, $this->rooms )) {
				$this->rooms[$roomId] = new Room( $roomId );
			}
			
			return $this->rooms[$roomId];
		}
	}