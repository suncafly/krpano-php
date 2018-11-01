<?php
	class MessageTypes {
		const MT_ADD_USER                            = 1;
		
		const MT_DISASTER_NEW                        = 1000;
		const MT_DISASTER_DATA                       = 1001;
		const MT_ALARM_NOTIFICATION_WAIT_ACK         = 3000;
		const MT_DELETE_MARKER                       = 4000;
		const MT_NORMAL_DATA                         = 5000;
		const MT_ADD_MARKER                          = 6000;
		const MT_HISTORY_RECORDS                     = 7000;
		const MT_ACCIDENT_INFO                       = 8000;
		const MT_RECONNECT                           = 9000;
		const MT_ALARM_NOTIFICATION_ACK              = 10000;
		const MT_RESCUE_STATE                        = 11000;
		
		private static $instance;
		
		private function __construct() {
			
		}
		
		public static function getSingleton() {
			if ( !self::$instance ) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		public function getList() {
			return ['MT_DISASTER_NEW'               => self::MT_DISASTER_NEW,
			        'MT_DISASTER_DATA'              => self::MT_DISASTER_DATA,
					'MT_ALARM_NOTIFICATION_WAIT_ACK'=> self::MT_ALARM_NOTIFICATION_WAIT_ACK,
					'MT_DELETE_MARKER'              => self::MT_DELETE_MARKER,
					'MT_NORMAL_DATA'                => self::MT_NORMAL_DATA,
					'MT_ADD_MARKER'                 => self::MT_ADD_MARKER,
					'MT_ACCIDENT_INFO'              => self::MT_ACCIDENT_INFO,
					'MT_RECONNECT'                  => self::MT_RECONNECT,
					'MT_ALARM_NOTIFICATION_ACK'     => self::MT_ALARM_NOTIFICATION_ACK,
					'MT_RESCUE_STATE'               => self::MT_RESCUE_STATE];
		}
		
		public function inList( $type ) {
			return in_array( $type, [ 
								self::MT_DISASTER_NEW,
								self::MT_DISASTER_DATA,
								self::MT_ALARM_NOTIFICATION_WAIT_ACK, 
								self::MT_DELETE_MARKER, 
								self::MT_NORMAL_DATA, 
								self::MT_ADD_MARKER, 
								self::MT_ACCIDENT_INFO,
								self::MT_RECONNECT,
								self::MT_ALARM_NOTIFICATION_ACK,
								self::MT_RESCUE_STATE] );
		}
	}
?>