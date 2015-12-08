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
    public function getTekst()
    {
        return $this->tekst;
    }

    /**
     * @param mixed $tekst
     */
    public function setTekst($tekst)
    {
        $this->tekst = $tekst;
    }

    /**
     * @return mixed
     */
    public function getIzdvojeno()
    {
        return $this->izdvojeno;
    }

    /**
     * @param mixed $izdvojeno
     */
    public function setIzdvojeno($izdvojeno)
    {
        $this->izdvojeno = $izdvojeno;
    }

    /**
     * @return mixed
     */
    public function getTip()
    {
        return $this->tip;
    }

    /**
     * @param mixed $tip
     */
    public function setTip($tip)
    {
        $this->tip = $tip;
    }

    /**
     * @return mixed
     */
    public function getKorisnik()
    {
        return $this->korisnik;
    }

    /**
     * @param mixed $korisnik
     */
    public function setKorisnik($korisnik)
    {
        $this->korisnik = $korisnik;
    }

    /**
     * @return mixed
     */
    public function getPodaci()
    {
        return $this->podaci;
    }

    /**
     * @param mixed $podaci
     */
    public function setPodaci($podaci)
    {
        $this->podaci = $podaci;
    }

    /**
     * @return mixed
     */
    public function getKategorija()
    {
        return $this->kategorija;
    }

    /**
     * @param mixed $kategorija
     */
    public function setKategorija($kategorija)
    {
        $this->kategorija = $kategorija;
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
}