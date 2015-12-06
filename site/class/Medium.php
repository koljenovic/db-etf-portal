<?php
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="media")
 */
class Medium
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="string") **/
    protected $tekst;
    /** @Column(type="boolean") **/
    protected $izdvojeno;
    /** @ManyToOne(targetEntity="MediaTip", inversedBy="media") **/
    protected $tip;
    /** @ManyToOne(targetEntity="Sesija", inversedBy="media") **/
    protected $korisnik;
    /** @OneToOne(targetEntity="Podaci") **/
    protected $podaci;
    /** @ManyToOne(targetEntity="Kategorija", inversedBy="media") **/
    protected $kategorija;
    /** @OneToMany(targetEntity="Medium", mappedBy="parent") **/
    protected $parent;
    /** @ManyToOne(targetEntity="Medium", inversedBy="children") **/
    protected $children;

    public function __construct() {
        $this->children = new ArrayCollection();
    }
}