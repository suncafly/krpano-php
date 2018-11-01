<?php
	/**
 	 * create By :lichangming
	 * time : 20180915
     * src/PanoImgResource.php
     */
	use Doctrine\Common\Collections\ArrayCollection;
		
	/**
	 * @Entity(repositoryClass="")
	 * @Table(name="PanoImgResource")
	 */
	class PanoImgResource
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
		 * 资源路径
		 * @Column(type="string",options={"comment":"资源路径"})
		 * @var string
		 */
		protected $resFilePathInServer;
		
		/**
		 * 缩率图资源路径
		 * @Column(type="string",options={"comment":"缩率图资源路径"})
		 * @var string
		 */
		protected $resThumbFilePathInServer;
		
		/**
		 * 全景图切图资源路径
		 * @Column(type="string",options={"comment":"全景图切图资源路径"})
		 * @var string
		 */
		protected $resPanoPathInServer;
		
		/**
		 * 资源全景场景描述
		 * @Column( type="text",options={"comment":"资源全景场景描述"})
		 * @var text
		 */
		protected $resSceneString;
		
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
		 * @ManyToOne(targetEntity="PanoImgLayer", inversedBy="panoImgLayerList",cascade={"persist","refresh"})
		 * @JoinColumn(name="layerId",referencedColumnName="id" )
		 */
		protected  $panoImgLayerInfo;

   
   
   
   
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
     * @return PanoImgResource
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
     * Set resFilePathInServer
     *
     * @param string $resFilePathInServer
     *
     * @return PanoImgResource
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
     * Set resThumbFilePathInServer
     *
     * @param string $resThumbFilePathInServer
     *
     * @return PanoImgResource
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
     * Set resUploadPerson
     *
     * @param string $resUploadPerson
     *
     * @return PanoImgResource
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
     * @return PanoImgResource
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
     * @return PanoImgResource
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
     * Set panoImgLayerInfo
     *
     * @param \PanoImgLayer $panoImgLayerInfo
     *
     * @return PanoImgResource
     */
    public function setPanoImgLayerInfo(\PanoImgLayer $panoImgLayerInfo = null)
    {
        $this->panoImgLayerInfo = $panoImgLayerInfo;

        return $this;
    }

    /**
     * Get panoImgLayerInfo
     *
     * @return \PanoImgLayer
     */
    public function getPanoImgLayerInfo()
    {
        return $this->panoImgLayerInfo;
    }

    /**
     * Set resPanoPathInServer
     *
     * @param string $resPanoPathInServer
     *
     * @return PanoImgResource
     */
    public function setResPanoPathInServer($resPanoPathInServer)
    {
        $this->resPanoPathInServer = $resPanoPathInServer;

        return $this;
    }

    /**
     * Get resPanoPathInServer
     *
     * @return string
     */
    public function getResPanoPathInServer()
    {
        return $this->resPanoPathInServer;
    }

    /**
     * Set resSceneString
     *
     * @param string $resSceneString
     *
     * @return PanoImgResource
     */
    public function setResSceneString($resSceneString)
    {
        $this->resSceneString = $resSceneString;

        return $this;
    }

    /**
     * Get resSceneString
     *
     * @return string
     */
    public function getResSceneString()
    {
        return $this->resSceneString;
    }
}
