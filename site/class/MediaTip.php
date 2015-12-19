<?php
use \Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity @Table(name="media_tipovi")
 */
class MediaTip
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="boolean") **/
    protected $binarni;
    // @TODO indksirati po
    /** @Column(type="string", unique=true) **/
    protected $naziv;
    // @TODO indksirati po
    /** @Column(type="string", unique=true) **/
    protected $ekstenzija;
    /** @Column(type="string") **/
    protected $opis;
    /** @Column(type="string", nullable=true) **/
    protected $url;
    /** @Column(type="boolean") **/
    protected $dozvoljen;
    /** OneToMany(targetEntity="Medium", mappedBy="tip") **/
    protected $media;

    public function __construct() {
        $this->media = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getEkstenzija()
    {
        return $this->ekstenzija;
    }

    /**
     * @param mixed $ekstenzija
     */
    public function setEkstenzija($ekstenzija)
    {
        $this->ekstenzija = $ekstenzija;
    }

    /**
     * @return mixed
     */
    public function getOpis()
    {
        return $this->opis;
    }

    /**
     * @param mixed $opis
     */
    public function setOpis($opis)
    {
        $this->opis = $opis;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getDozvoljen()
    {
        return $this->dozvoljen;
    }

    /**
     * @param mixed $dozvoljen
     */
    public function setDozvoljen($dozvoljen)
    {
        $this->dozvoljen = $dozvoljen;
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
    public function getBinarni()
    {
        return $this->binarni;
    }

    /**
     * @param mixed $binarni
     */
    public function setBinarni($binarni)
    {
        $this->binarni = $binarni;
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