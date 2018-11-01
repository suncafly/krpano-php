<?php
	/**
 	 * create By :lichangming
	 * time : 20180925
     * src/ProjectType.php
     */
	use Doctrine\Common\Collections\ArrayCollection;
		
	/**
	 * @Entity(repositoryClass="")
	 * @Table(name="ProjectType")
	 */
	class ProjectType
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
		 * @OneToMany(targetEntity="Project", mappedBy="projectTypeInfo",cascade={"persist","refresh","remove"})
		 */
		protected $projectTypeList;
		
    	
   
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projectTypeList = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ProjectType
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
     * Add projectTypeList
     *
     * @param \Project $projectTypeList
     *
     * @return ProjectType
     */
    public function addProjectTypeList(\Project $projectTypeList)
    {
        $this->projectTypeList[] = $projectTypeList;

        return $this;
    }

    /**
     * Remove projectTypeList
     *
     * @param \Project $projectTypeList
     */
    public function removeProjectTypeList(\Project $projectTypeList)
    {
        $this->projectTypeList->removeElement($projectTypeList);
    }

    /**
     * Get projectTypeList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjectTypeList()
    {
        return $this->projectTypeList;
    }
}
