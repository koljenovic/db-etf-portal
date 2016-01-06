<?php

use \Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="media")
 */
class Medium
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="string", nullable=true) **/
    protected $naslov;
    /** @Column(type="text", nullable=true) **/
    protected $tekst;
    /** @Column(type="boolean") **/
    protected $izdvojeno;
    /** @ManyToOne(targetEntity="MediaTip", inversedBy="media") **/
    protected $tip;
    /** @ManyToOne(targetEntity="Sesija", inversedBy="media") **/
    protected $korisnik;
    /** @Column(type="string", nullable=true) **/
    protected $filename;
    /** @ManyToOne(targetEntity="Kategorija") **/
    protected $kategorija;
    /** @OneToMany(targetEntity="Medium", mappedBy="parent") **/
    protected $children;
    /** @ManyToOne(targetEntity="Medium", inversedBy="children") **/
    protected $parent;
    /** @Column(type="datetimetz") **/
    protected $dt;

    public function __construct() {
        $this->children = new ArrayCollection();
        if(is_null($this->getId())) {
            $this->setIzdvojeno(false);
            $this->setDt(new DateTime("now"));
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
    public function getNaslov()
    {
        return $this->naslov;
    }

    /**
     * @param mixed $naslov
     */
    public function setNaslov($naslov)
    {
        $this->naslov = $naslov;
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

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getDt()
    {
        return $this->dt;
    }

    /**
     * @param mixed $dt
     */
    public function setDt($dt)
    {
        $this->dt = $dt;
    }

    public function getSerial() {
        return array(
            'id' => $this->getId(),
            'naslov' => $this->getNaslov(),
            'tekst' => $this->getTekst(),
            'kategorija' => $this->getKategorija(),
            'korisnik' => $this->getKorisnik(),
            'dt' => $this->getDt()->format('d/m/Y H:i'),
        );
    }
}