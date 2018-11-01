<?php
	// src/vehicle.php
	
	/**
	 * @Entity @Table(name="vehicles")
	 */
	class Vehicle
	{
		/**
		 * @Id @Column(type="integer") @GeneratedValue
		 * @var int
		 */
		protected $id;
		
		/**
		 * @Column(type="string", options = {comment: "车辆类别"})
		 * @var string
		 */
		protected $type;
		
		/**
		 * @Column(type="string", options = {comment: "车辆所属子类"})
		 * @var string
		 */
		protected $subType;
		
		/**
		 * @Column(type="integer", options={comment: "机构Id"})
		 * @var int
		 */
		protected $departmentId;
		
		/**
		 * @Column(type="string", options = {comment: "机构名称"})
		 * @var string
		 */
		protected $department;
		
		/**
		 * @Column(type="string", options={comment: "车辆名称"})
		 * @var string
		 */
		protected $name;
		
		/**
		 * @Column(type="string", options={comment: "生产厂家"})
		 * @var string
		 */
		protected $vendor;
		
		/**
		 * @Column(type="datetime", options={comment: "生产日期"})
		 * @var datetime
		 */
		protected $productionDate;
		
		/**
		 * @Column(type="datetime", options={comment: "购买日期"})
		 * @var datetime
		 */
		protected $purchasingDate;
		
		/**
		 * @Column(type="string", options={comment: "车牌号"})
		 * @var string
		 */
		protected $licensePlateNumber;
		
		/**
		 * @Column(type="string", options={comment: "状态"})
		 * @var string
		 */
		protected $state;

		/**
		 * @Column(type="integer", options={comment: "乘客数量"})
		 * @var int
		 */
		protected $passengersAmount;
		
		protected $serviceLife;
		
		protected $city;		
		/**
		 * 车载器材
		 * 
		 */
		protected $devices;
		
		public function __construct()
		{
			
		}
		
		public function getId()
		{
			return $this->id;
		}
		
		public function setType($type)
		{
			$this->type = $type;
		}
		
		public function getType()
		{
			return $this->type;
		}
		
		public function setSubType($type)
		{
			$this->subType = $type;
		}
		
		public function getSubType()
		{
			return $this->subType;
		}
		
		public function setName($name)
		{
			$this->name = $name;
		}
		
		public function getName()
		{
			return $this->name;
		}
		
		public function setVendor($vendor)
		{
			$this->vendor = $vender;
		}
		
		public function getVendor()
		{
			return $this->vendor;
		}
		
		public function setDepartment($department)
		{
			$this->department = $department;
		}
		
		public function getDepartment($department)
		{
			return $this->department;
		}
		
		public function setDepartmentId($departmentId)
		{
			return $this->departmentId;
		}
		
		public function getDepartmentId()
		{
			return $this->departmentId;
		}
		
		public function setProductionDate($date)
		{
			$this->productionDate = $date;
		}
		
		public function getProductionDate()
		{
			return $this->productionDate;
		}
		
		public function setPurchasingDate($date)
		{
			$this->purchasingDate = $date;
		}
		
		public function getPurchasingDate()
		{
			return $this->purchasingDate;
		}
		
		public function setState($state)
		{
			$this->state = $state;
		}
		
		public function getState()
		{
			return $this->state;
		}
		
		public function setCity($city)
		{
			$this->city = $city;
		}
		
		public function getCity()
		{
			return $this->city;
		}	
		
		public function setPassengersAmount($amount)
		{
			$this->passengersAmount = $amount;
		}
		
		public function getPassengersAmount()
		{
			return $this->passengersAmount;
		}
		
		public function setServiceLife($time)
		{
			$this->serviceLife = $time;
		}
		
		public function getServiceLife()
		{
			return $this->serviceLife;
		}
		
		public function setLicensePlateNumber($number)
		{
			$this->licensePlateNumber = $number;
		}
		
		public function getLicensePlateNumber()
		{
			return $this->licensePlateNumber;
		}
		
		public function addDevice($device)
		{
			$this->devices[] = $device;
		}
		
		public function getDevices()
		{
			return $this->devices;
		}
	}