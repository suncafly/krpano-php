<?php
	/**
 	 * create By :lichangming
	 * time : 20180914
     * src/PanoImgLayer.php
     */
	use Doctrine\Common\Collections\ArrayCollection;
		
	/**
	 * @Entity(repositoryClass="")
	 * @Table(name="PanoImgLayer")
	 */
	class PanoImgLayer
	{
		/**
		 * @Id @Column(type="integer") @GeneratedValue
		 * @var int
		 */
		protected $id;
		
		/**
		 * 全景图图层列表名
		 *
		 * @Column(type="string",options={"comment":"全景图图层列表名"})
		 * @var string
		 */
		protected $name;
			


		/**
		 * @ManyToOne(targetEntity="PanoramaResourceType", inversedBy="panoImgLayerList",cascade={"persist","refresh"})
		 * @JoinColumn(name="resourceTypeId",referencedColumnName="id" )
		 */
		protected  $resourceType;

		/**
		 * @OneToMany(targetEntity="PanoImgResource", mappedBy="panoImgLayerInfo",cascade={"persist","refresh","remove"})
		 */
		protected $panoImgLayerList;
		
    	
   
   
       /**
     * Constructor
     */
    public function __construct()
    {
        $this->panoImgLayerList = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PanoImgLayer
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
     * @return PanoImgLayer
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
     * Add panoImgLayerList
     *
     * @param \PanoImgResource $panoImgLayerList
     *
     * @return PanoImgLayer
     */
    public function addPanoImgLayerList(\PanoImgResource $panoImgLayerList)
    {
        $this->panoImgLayerList[] = $panoImgLayerList;

        return $this;
    }

    /**
     * Remove panoImgLayerList
     *
     * @param \PanoImgResource $panoImgLayerList
     */
    public function removePanoImgLayerList(\PanoImgResource $panoImgLayerList)
    {
        $this->panoImgLayerList->removeElement($panoImgLayerList);
    }

    /**
     * Get panoImgLayerList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPanoImgLayerList()
    {
        return $this->panoImgLayerList;
    }
}
