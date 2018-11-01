<?php
	/**
 	 * create By :lichangming
	 * time : 20180914
     * src/BasicMaterialLayer.php
     */
	use Doctrine\Common\Collections\ArrayCollection;
		
	/**
	 * @Entity(repositoryClass="")
	 * @Table(name="BasicMaterialLayer")
	 */
	class BasicMaterialLayer
	{
		/**
		 * @Id @Column(type="integer") @GeneratedValue
		 * @var int
		 */
		protected $id;
		
		/**
		 * 素材图片图层分类类名
		 *
		 * @Column(type="string",options={"comment":"素材图片图层分类类名"})
		 * @var string
		 */
		protected $name;
			

		/**
		 * @ManyToOne(targetEntity="PanoramaResourceType", inversedBy="basicMaterialLayerList",cascade={"persist","refresh"})
		 * @JoinColumn(name="resourceTypeId",referencedColumnName="id" )
		 */
		protected  $resourceType;

		/**
		 * @OneToMany(targetEntity="BasicMaterialResource", mappedBy="basicMaterialLayerInfo",cascade={"persist","refresh","remove"})
		 */
		protected $basicMaterialLayerList;
		
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->basicMaterialLayerList = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return BasicMaterialLayer
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
     * @return BasicMaterialLayer
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
     * Add basicMaterialLayerList
     *
     * @param \BasicMaterialResource $basicMaterialLayerList
     *
     * @return BasicMaterialLayer
     */
    public function addBasicMaterialLayerList(\BasicMaterialResource $basicMaterialLayerList)
    {
        $this->basicMaterialLayerList[] = $basicMaterialLayerList;

        return $this;
    }

    /**
     * Remove basicMaterialLayerList
     *
     * @param \BasicMaterialResource $basicMaterialLayerList
     */
    public function removeBasicMaterialLayerList(\BasicMaterialResource $basicMaterialLayerList)
    {
        $this->basicMaterialLayerList->removeElement($basicMaterialLayerList);
    }

    /**
     * Get basicMaterialLayerList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBasicMaterialLayerList()
    {
        return $this->basicMaterialLayerList;
    }
}
