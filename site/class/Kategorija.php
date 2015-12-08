<?php

use \Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="kategorije")
 */
class Kategorija
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="string") **/
    protected $naziv;
    /** @OneToMany(targetEntity="Kategorija", mappedBy="parent") **/
    protected $parent;
    /** @ManyToOne(targetEntity="Kategorija", inversedBy="children") **/
    protected $children;

    /** OneToMany(targetEntity="Medium", mappedBy="kategorija") **/
    protected $media;

    public function __construct() {
        $this->children = new ArrayCollection();
        $this->media = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNaziv()
    {
        return $this->naziv;
    }

    /**
     * @param mixed $naziv
     */
    public function setNaziv($naziv)
    {
        $this->naziv = $naziv;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }
}