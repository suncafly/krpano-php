<?php
	/**
 	 * create By :lichangming
	 * time : 20180925
     * src/editMenuType.php
     */
	use Doctrine\Common\Collections\ArrayCollection;
		
	/**
	 * @Entity(repositoryClass="")
	 * @Table(name="editMenuType")
	 */
	class editMenuType
	{
		/**
		 * @Id @Column(type="integer") @GeneratedValue
		 * @var int
		 */
		protected $id;
		
		/**
		 * 资源类型描述
		 * @Column(type="string",options={"comment":"资源类型描述"})
		 * @var string
		 */
		protected $typeDesc;
		
		/**
		 * @Column(type="string",options={"comment":"资源类型实体名称"})
		 * @var string
		 */
		protected $entityClassName;
	
		/**
		 * @OneToMany(targetEntity="PanoImgLayer", mappedBy="resourceType",cascade={"all"})
		 */
		protected $panoImgLayerList;
		
		/**
		 * @OneToMany(targetEntity="BasicMaterialLayer", mappedBy="resourceType",cascade={"all"})
		 */
		protected $basicMaterialLayerList;
		
		/**
		 * @OneToMany(targetEntity="VoiceLayer", mappedBy="resourceType",cascade={"all"})
		 */
		protected $voiceLayerList;

		/**
		 * @OneToMany(targetEntity="VideoLayer", mappedBy="resourceType",cascade={"all"})
		 */
		protected $videoLayerList;
	

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->panoImgLayerList = new \Doctrine\Common\Collections\ArrayCollection();
        $this->basicMaterialLayerList = new \Doctrine\Common\Collections\ArrayCollection();
        $this->voiceLayerList = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set typeDesc
     *
     * @param string $typeDesc
     *
     * @return PanoramaResourceType
     */
    public function setTypeDesc($typeDesc)
    {
        $this->typeDesc = $typeDesc;

        return $this;
    }

    /**
     * Get typeDesc
     *
     * @return string
     */
    public function getTypeDesc()
    {
        return $this->typeDesc;
    }

    /**
     * Set entityClassName
     *
     * @param string $entityClassName
     *
     * @return PanoramaResourceType
     */
    public function setEntityClassName($entityClassName)
    {
        $this->entityClassName = $entityClassName;

        return $this;
    }

    /**
     * Get entityClassName
     *
     * @return string
     */
    public function getEntityClassName()
    {
        return $this->entityClassName;
    }

    /**
     * Add panoImgLayerList
     *
     * @param \PanoImgLayer $panoImgLayerList
     *
     * @return PanoramaResourceType
     */
    public function addPanoImgLayerList(\PanoImgLayer $panoImgLayerList)
    {
        $this->panoImgLayerList[] = $panoImgLayerList;

        return $this;
    }

    /**
     * Remove panoImgLayerList
     *
     * @param \PanoImgLayer $panoImgLayerList
     */
    public function removePanoImgLayerList(\PanoImgLayer $panoImgLayerList)
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

    /**
     * Add basicMaterialLayerList
     *
     * @param \BasicMaterialLayer $basicMaterialLayerList
     *
     * @return PanoramaResourceType
     */
    public function addBasicMaterialLayerList(\BasicMaterialLayer $basicMaterialLayerList)
    {
        $this->basicMaterialLayerList[] = $basicMaterialLayerList;

        return $this;
    }

    /**
     * Remove basicMaterialLayerList
     *
     * @param \BasicMaterialLayer $basicMaterialLayerList
     */
    public function removeBasicMaterialLayerList(\BasicMaterialLayer $basicMaterialLayerList)
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

    /**
     * Add voiceLayerList
     *
     * @param \VoiceLayer $voiceLayerList
     *
     * @return PanoramaResourceType
     */
    public function addVoiceLayerList(\VoiceLayer $voiceLayerList)
    {
        $this->voiceLayerList[] = $voiceLayerList;

        return $this;
    }

    /**
     * Remove voiceLayerList
     *
     * @param \VoiceLayer $voiceLayerList
     */
    public function removeVoiceLayerList(\VoiceLayer $voiceLayerList)
    {
        $this->voiceLayerList->removeElement($voiceLayerList);
    }

    /**
     * Get voiceLayerList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVoiceLayerList()
    {
        return $this->voiceLayerList;
    }

    /**
     * Add videoLayerList
     *
     * @param \VideoLayer $videoLayerList
     *
     * @return PanoramaResourceType
     */
    public function addVideoLayerList(\VideoLayer $videoLayerList)
    {
        $this->videoLayerList[] = $videoLayerList;

        return $this;
    }

    /**
     * Remove videoLayerList
     *
     * @param \VideoLayer $videoLayerList
     */
    public function removeVideoLayerList(\VideoLayer $videoLayerList)
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
