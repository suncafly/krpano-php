<?php
	/**
 	 * create By :lichangming
	 * time : 20180925
     * src/ProjectLayer.php
     */
	use Doctrine\Common\Collections\ArrayCollection;
		
	/**
	 * @Entity(repositoryClass="")
	 * @Table(name="ProjectLayer")
	 */
	class ProjectLayer
	{
		/**
		 * @Id @Column(type="integer") @GeneratedValue
		 * @var int
		 */
		protected $id;
		
		/**
		 * 项目图层图层列表名
		 *
		 * @Column(type="string",options={"comment":"全景图图层列表名"})
		 * @var string
		 */
		protected $name;
			
			
		/**
		 * @OneToMany(targetEntity="Project", mappedBy="projectLayerInfo",cascade={"persist","refresh","remove"})
		 */
		protected $projectLayerList;
		
    	
   
   
      /**
     * Constructor
     */
    public function __construct()
    {
        $this->projectLayerList = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ProjectLayer
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
     * Add projectLayerList
     *
     * @param \Project $projectLayerList
     *
     * @return ProjectLayer
     */
    public function addProjectLayerList(\Project $projectLayerList)
    {
        $this->projectLayerList[] = $projectLayerList;

        return $this;
    }

    /**
     * Remove projectLayerList
     *
     * @param \Project $projectLayerList
     */
    public function removeProjectLayerList(\Project $projectLayerList)
    {
        $this->projectLayerList->removeElement($projectLayerList);
    }

    /**
     * Get projectLayerList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjectLayerList()
    {
        return $this->projectLayerList;
    }
}
