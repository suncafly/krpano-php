<?php
	/**
 	 * create By :lichangming
	 * time : 20180915
     * src/VideoResource.php
     */
	use Doctrine\Common\Collections\ArrayCollection;
		
	/**
	 * @Entity(repositoryClass="")
	 * @Table(name="VideoResource")
	 */
	class VideoResource
	{
		/**
		 * @Id @Column(type="integer") @GeneratedValue
		 * @var int
		 */
		protected $id;
		
		/**
		 * 资源类型
		 * @Column(type="string",options={"comment":"资源类型"})
		 * @var string
		 */
		protected $resType;
		
		/**
		 * 缩率图资源路径
		 * @Column(type="string",options={"comment":"缩率图资源路径"})
		 * @var string
		 */
		protected $resThumbFilePathInServer;

		/**
		 * 资源路径
		 * @Column(type="string",options={"comment":"资源路径"})
		 * @var string
		 */
		protected $resFilePathInServer;
		
		/**
		 * 上传人
		 * @Column(type="string",options={"comment":"上传人"})
		 * @var string
		 */
		protected $resUploadPerson;


		/**
		 * 上传时间
		 * @Column(type="datetime",options={"comment":"上传时间"})
		 * @var datetime
		 */
		protected $resUploaderTime;
		
        /**
         * 上传文件名
         * @Column(type="string",options={"comment":"上传文件名"})
         * @var string
         */
        protected $resFileServerName;


		/**
		 * @ManyToOne(targetEntity="VideoLayer", inversedBy="videoLayerList",cascade={"persist","refresh"})
		 * @JoinColumn(name="layerId",referencedColumnName="id" )
		 */
		protected  $videoLayerInfo;

   
   
   
   
   

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
     * Set resType
     *
     * @param string $resType
     *
     * @return VideoResource
     */
    public function setResType($resType)
    {
        $this->resType = $resType;

        return $this;
    }

    /**
     * Get resType
     *
     * @return string
     */
    public function getResType()
    {
        return $this->resType;
    }

    /**
     * Set resThumbFilePathInServer
     *
     * @param string $resThumbFilePathInServer
     *
     * @return VideoResource
     */
    public function setResThumbFilePathInServer($resThumbFilePathInServer)
    {
        $this->resThumbFilePathInServer = $resThumbFilePathInServer;

        return $this;
    }

    /**
     * Get resThumbFilePathInServer
     *
     * @return string
     */
    public function getResThumbFilePathInServer()
    {
        return $this->resThumbFilePathInServer;
    }

    /**
     * Set resFilePathInServer
     *
     * @param string $resFilePathInServer
     *
     * @return VideoResource
     */
    public function setResFilePathInServer($resFilePathInServer)
    {
        $this->resFilePathInServer = $resFilePathInServer;

        return $this;
    }

    /**
     * Get resFilePathInServer
     *
     * @return string
     */
    public function getResFilePathInServer()
    {
        return $this->resFilePathInServer;
    }

    /**
     * Set resUploadPerson
     *
     * @param string $resUploadPerson
     *
     * @return VideoResource
     */
    public function setResUploadPerson($resUploadPerson)
    {
        $this->resUploadPerson = $resUploadPerson;

        return $this;
    }

    /**
     * Get resUploadPerson
     *
     * @return string
     */
    public function getResUploadPerson()
    {
        return $this->resUploadPerson;
    }

    /**
     * Set resUploaderTime
     *
     * @param \DateTime $resUploaderTime
     *
     * @return VideoResource
     */
    public function setResUploaderTime($resUploaderTime)
    {
        $this->resUploaderTime = $resUploaderTime;

        return $this;
    }

    /**
     * Get resUploaderTime
     *
     * @return \DateTime
     */
    public function getResUploaderTime()
    {
        return $this->resUploaderTime;
    }

    /**
     * Set resFileServerName
     *
     * @param string $resFileServerName
     *
     * @return VideoResource
     */
    public function setResFileServerName($resFileServerName)
    {
        $this->resFileServerName = $resFileServerName;

        return $this;
    }

    /**
     * Get resFileServerName
     *
     * @return string
     */
    public function getResFileServerName()
    {
        return $this->resFileServerName;
    }

    /**
     * Set videoLayerInfo
     *
     * @param \VideoLayer $videoLayerInfo
     *
     * @return VideoResource
     */
    public function setVideoLayerInfo(\VideoLayer $videoLayerInfo = null)
    {
        $this->videoLayerInfo = $videoLayerInfo;

        return $this;
    }

    /**
     * Get videoLayerInfo
     *
     * @return \VideoLayer
     */
    public function getVideoLayerInfo()
    {
        return $this->videoLayerInfo;
    }
}
