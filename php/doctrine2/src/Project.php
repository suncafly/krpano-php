<?php
	/**
 	 * create By :lichangming
	 * time : 20180925
     * src/Project.php
     */
	use Doctrine\Common\Collections\ArrayCollection;
		
	/**
	 * @Entity(repositoryClass="")
	 * @Table(name="Project")
	 */
	class Project
	{
		
		/**
		 * @Id @Column(type="integer") @GeneratedValue
		 * @var int
		 */
		protected $id;
		
		
		/**
		 * 项目名称
		 * @Column(type="string",options={"comment":"项目名称"})
		 * @var string
		 */
		protected $name;
		
		
		/**
		 * 项目创建人
		 * @Column(type="string",options={"comment":"项目创建人"})
		 * @var string
		 */
		protected $createPerson;
		
	
		/**
		 * 创建时间
		 * @Column(type="datetime",options={"comment":"创建时间"})
		 * @var datetime
		 */
		protected $createTime;
		
		
		/**
		 * 项目描述
		 * @Column(type="string",options={"comment": "项目描述","default":""})
		 * @var string
		 */
		protected $projectInfo;
		
		
		/**
		 * 项目分类名称
		 * @Column(type="string",options={"comment":"项目分类名称"})
		 * @var string
		 */
		protected $projectTypeName;
		
		
		/**
		 * 浏览次数
		 * @Column(type="integer",options={"comment":"浏览次数"})
		 * @var string
		 */
		protected $viewNumber;


		/**
		 * 下载次数
		 * @Column(type="integer",options={"comment":"下载次数"})
		 * @var string
		 */
		protected $uploadedNumber;
		
		
		/**
		 * 项目路径
		 * @Column(type="string",options={"comment":"项目路径"})
		 * @var string
		 */
		protected $projectPathInServer;
		
	
		/**
		 * @ManyToOne(targetEntity="ProjectLayer", inversedBy="projectLayerList",cascade={"persist","refresh"})
		 * @JoinColumn(name="projectLayerId",referencedColumnName="id" )
		 */
		protected  $projectLayerInfo;
		
		/**
		 * @ManyToOne(targetEntity="ProjectType", inversedBy="projectTypeList",cascade={"persist","refresh"})
		 * @JoinColumn(name="projectTypeId",referencedColumnName="id" )
		 */
		protected  $projectTypeInfo;

   
   

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set createPerson
     *
     * @param string $createPerson
     *
     * @return Project
     */
    public function setCreatePerson($createPerson)
    {
        $this->createPerson = $createPerson;

        return $this;
    }

    /**
     * Get createPerson
     *
     * @return string
     */
    public function getCreatePerson()
    {
        return $this->createPerson;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     *
     * @return Project
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set projectInfo
     *
     * @param string $projectInfo
     *
     * @return Project
     */
    public function setProjectInfo($projectInfo)
    {
        $this->projectInfo = $projectInfo;

        return $this;
    }

    /**
     * Get projectInfo
     *
     * @return string
     */
    public function getProjectInfo()
    {
        return $this->projectInfo;
    }

    /**
     * Set projectTypeName
     *
     * @param string $projectTypeName
     *
     * @return Project
     */
    public function setProjectTypeName($projectTypeName)
    {
        $this->projectTypeName = $projectTypeName;

        return $this;
    }

    /**
     * Get projectTypeName
     *
     * @return string
     */
    public function getProjectTypeName()
    {
        return $this->projectTypeName;
    }

    /**
     * Set viewNumber
     *
     * @param integer $viewNumber
     *
     * @return Project
     */
    public function setViewNumber($viewNumber)
    {
        $this->viewNumber = $viewNumber;

        return $this;
    }

    /**
     * Get viewNumber
     *
     * @return integer
     */
    public function getViewNumber()
    {
        return $this->viewNumber;
    }

    /**
     * Set uploadedNumber
     *
     * @param integer $uploadedNumber
     *
     * @return Project
     */
    public function setUploadedNumber($uploadedNumber)
    {
        $this->uploadedNumber = $uploadedNumber;

        return $this;
    }

    /**
     * Get uploadedNumber
     *
     * @return integer
     */
    public function getUploadedNumber()
    {
        return $this->uploadedNumber;
    }

    /**
     * Set projectPathInServer
     *
     * @param string $projectPathInServer
     *
     * @return Project
     */
    public function setProjectPathInServer($projectPathInServer)
    {
        $this->projectPathInServer = $projectPathInServer;

        return $this;
    }

    /**
     * Get projectPathInServer
     *
     * @return string
     */
    public function getProjectPathInServer()
    {
        return $this->projectPathInServer;
    }

    /**
     * Set projectLayerInfo
     *
     * @param \ProjectLayer $projectLayerInfo
     *
     * @return Project
     */
    public function setProjectLayerInfo(\ProjectLayer $projectLayerInfo = null)
    {
        $this->projectLayerInfo = $projectLayerInfo;

        return $this;
    }

    /**
     * Get projectLayerInfo
     *
     * @return \ProjectLayer
     */
    public function getProjectLayerInfo()
    {
        return $this->projectLayerInfo;
    }

    /**
     * Set projectTypeInfo
     *
     * @param \ProjectType $projectTypeInfo
     *
     * @return Project
     */
    public function setProjectTypeInfo(\ProjectType $projectTypeInfo = null)
    {
        $this->projectTypeInfo = $projectTypeInfo;

        return $this;
    }

    /**
     * Get projectTypeInfo
     *
     * @return \ProjectType
     */
    public function getProjectTypeInfo()
    {
        return $this->projectTypeInfo;
    }
}
