<?php
	/**
 	 * create By :lichangming
	 * time : 20180914
     * src/VoiceLayer.php
     */
	use Doctrine\Common\Collections\ArrayCollection;
		
	/**
	 * @Entity(repositoryClass="")
	 * @Table(name="VoiceLayer")
	 */
	class VoiceLayer
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
		 * @ManyToOne(targetEntity="PanoramaResourceType", inversedBy="voiceLayerList",cascade={"persist","refresh"})
		 * @JoinColumn(name="resourceTypeId",referencedColumnName="id" )
		 */
		protected  $resourceType;

		/**
		 * @OneToMany(targetEntity="VoiceResource", mappedBy="voiceLayerInfo",cascade={"persist","refresh","remove"})
		 */
		protected $voiceLayerList;
		
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->voiceLayerList = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return VoiceLayer
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
     * @return VoiceLayer
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
     * Add voiceLayerList
     *
     * @param \VoiceResource $voiceLayerList
     *
     * @return VoiceLayer
     */
    public function addVoiceLayerList(\VoiceResource $voiceLayerList)
    {
        $this->voiceLayerList[] = $voiceLayerList;

        return $this;
    }

    /**
     * Remove voiceLayerList
     *
     * @param \VoiceResource $voiceLayerList
     */
    public function removeVoiceLayerList(\VoiceResource $voiceLayerList)
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
}
