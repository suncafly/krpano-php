<?php
	/**
 	 * create By :lichangming
	 * time : 20180914
     * src/VideoLayer.php
     */
	use Doctrine\Common\Collections\ArrayCollection;
		
	/**
	 * @Entity(repositoryClass="")
	 * @Table(name="VideoLayer")
	 */
	class VideoLayer
	{
		/**
		 * @Id @Column(type="integer") @GeneratedValue
		 * @var int
		 */
		protected $id;
		
		/**
		 * 音乐语音图层分类类名
		 *
		 * @Column(type="string",options={"comment":"音乐语音图层分类类名"})
		 * @var string
		 */
		protected $name;
			

		/**
		 * @ManyToOne(targetEntity="PanoramaResourceType", inversedBy="videoLayerList",cascade={"persist","refresh"})
		 * @JoinColumn(name="resourceTypeId",referencedColumnName="id" )
		 */
		protected  $resourceType;

		/**
		 * @OneToMany(targetEntity="VideoResource", mappedBy="videoLayerInfo",cascade={"persist","refresh","remove"})
		 */
		protected $videoLayerList;
		
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->videoLayerList = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return VideoLayer
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
     * Set resourceType
     *
     * @param \PanoramaResourceType $resourceType
     *
     * @return VideoLayer
     */
    public function setResourceType(\PanoramaResourceType $resourceType = null)
    {
        $this->resourceType = $resourceType;

        return $this;
    }

    /**
     * Get resourceType
     *
     * @return \PanoramaResourceType
     */
    public function getResourceType()
    {
        return $this->resourceType;
    }

    /**
     * Add videoLayerList
     *
     * @param \VideoResource $videoLayerList
     *
     * @return VideoLayer
     */
    public function addVideoLayerList(\VideoResource $videoLayerList)
    {
        $this->videoLayerList[] = $videoLayerList;

        return $this;
    }

    /**
     * Remove videoLayerList
     *
     * @param \VideoResource $videoLayerList
     */
    public function removeVideoLayerList(\VideoResource $videoLayerList)
    {
        $this->videoLayerList->removeElement($videoLayerList);
    }

    /**
     * Get videoLayerList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideoLayerList()
    {
        return $this->videoLayerList;
    }
}
