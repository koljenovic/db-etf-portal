<?php

/**
 * @Entity @Table(name="sesije")
 */
class Sesija
{


    // @TODO: dodati datum


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

}