<?php
	// src/device.php
	
	/**
	 * @Entity @Table(name="vehicles")
	 */
	class Device
	{
		/**
		 * @Id @Column(type="integer") @GeneratedValue
		 * @var int
		 */
		protected $id;
		
		/**
		 * @Column(type="string", options = {comment: "器材类别"})
		 * @var string
		 */
		protected $type;
		
		/**
		 * @Column(type="string", options = {comment: "器材所属子类"})
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
		 * @Column(type="string", options={comment: "器材名称"})
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
		 * @Column(type="string", options={comment: "状态"})
		 * @var string
		 */
		protected $state;
		
		/**
		 * 
		 */
		protected $note;
		
		protected $serviceLife;
			
		/**
		 * 从属于车辆
		 * 
		 */
		protected $vehicle;
	}