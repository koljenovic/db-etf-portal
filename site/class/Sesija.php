<?php
use \Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="sesije")
 */
class Sesija
{

    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="string") **/
    protected $kljuc;
    /** @Column(type="string") **/
    protected $korisnik;
    /** @Column(type="string") **/
    protected $lozinka;
    /** @Column(type="string") **/
    protected $ime;
    /** @Column(type="string") **/
    protected $prezime;
    /** @Column(type="string") **/
    protected $rola;
    /** @Column(type="datetimetz") **/
    protected $start_dt;
    /** @Column(type="datetimetz") **/
    protected $kraj_dt;
    /** @Column(type="boolean") **/
    protected $validna;
    /** OneToMany(targetEntity="Medium", mappedBy="korisnik") **/
    protected $media;

    public function __construct()
    {
        $this->media = new ArrayCollection();
        if(is_null($this->getId())) {
            $this->setStartDt(new DateTime("now"));
            $this->setKrajDt($this->getStartDt());
            $this->setValidna(true);
        }
    }

    /**
     * @return mixed
     */
    public function getStartDt()
    {
        return $this->start_dt;
    }

    /**
     * @param mixed $start_dt
     */
    public function setStartDt($start_dt)
    {
        $this->start_dt = $start_dt;
    }

    /**
     * @return mixed
     */
    public function getKrajDt()
    {
        return $this->kraj_dt;
    }

    /**
     * @param mixed $kraj_dt
     */
    public function setKrajDt($kraj_dt)
    {
        $this->kraj_dt = $kraj_dt;
    }

    /**
     * @return mixed
     */
    public function getValidna()
    {
        return $this->validna;
    }

    /**
     * @param mixed $validna
     */
    public function setValidna($validna)
    {
        $this->validna = $validna;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getKljuc()
    {
        return $this->kljuc;
    }

    /**
     * @param mixed $kljuc
     */
    public function setKljuc($kljuc)
    {
        $this->kljuc = $kljuc;
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
    public function getLozinka()
    {
        return $this->lozinka;
    }

    /**
     * @param mixed $lozinka
     */
    public function setLozinka($lozinka)
    {
        $this->lozinka = $lozinka;
    }

    /**
     * @return mixed
     */
    public function getIme()
    {
        return $this->ime;
    }

    /**
     * @param mixed $ime
     */
    public function setIme($ime)
    {
        $this->ime = $ime;
    }

    /**
     * @return mixed
     */
    public function getPrezime()
    {
        return $this->prezime;
    }

    /**
     * @param mixed $prezime
     */
    public function setPrezime($prezime)
    {
        $this->prezime = $prezime;
    }

    /**
     * @return mixed
     */
    public function getRola()
    {
        return $this->rola;
    }

    /**
     * @param mixed $rola
     */
    public function setRola($rola)
    {
        $this->rola = $rola;
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