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
    /** @Column(type="integer") */
    protected $prioritet;
    /** @OneToMany(targetEntity="Kategorija", mappedBy="parent") **/
    protected $children;
    /** @ManyToOne(targetEntity="Kategorija", inversedBy="children") **/
    protected $parent;
//    /** OneToMany(targetEntity="Medium", mappedBy="kategorija") **/
//    protected $media;

    public function __construct() {
        $this->children = new ArrayCollection();
        $this->media = new ArrayCollection();
        if(is_null($this->getId())) {
            $this->prioritet = 500;
        }
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

    /**
     * @return mixed
     */
    public function getPrioritet()
    {
        return $this->prioritet;
    }

    /**
     * @param mixed $prioritet
     */
    public function setPrioritet($prioritet)
    {
        $this->prioritet = $prioritet;
    }

    public function getSimpleSerial()
    {
        $r = array(
            'id' => $this->getId(),
            'naziv' => $this->getNaziv(),
            'prioritet' => $this->getPrioritet(),
        );
        return $r;
    }

    public function getSerial($dubina = 2, $sa_media = false) {
        $r = array(
            'id' => $this->getId(),
            'naziv' => $this->getNaziv(),
            'children' => array(),
            'parent' => $this->getParent() ? $this->getParent()->getSerial(1) : null,
            'media' => array(),
            'prioritet' => $this->getPrioritet(),
        );
        if($dubina > 0) {
            foreach ($this->getChildren() as $child) {
//                $r['children'][] = $child->getNaziv();
                $r['children'][] = $child->getSimpleSerial();
                usort($r['children'], function ($l, $r) {
                    return $l['prioritet'] <= $r['prioritet'] ? -1 : 1;
                });
            }
        }
        if($sa_media) {
            foreach ($this->getMedia() as $medium) {
                $r['media'] = $medium->getSerial();
                usort($r['media'], function ($l, $r) {
                    return $l->getDt() <= $r->getPrioritet() ? -1 : 1;
                });
            }
        }
        return $r;
    }
}