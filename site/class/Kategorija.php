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
}