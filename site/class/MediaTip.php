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
    /** @Column(type="string") **/
    protected $naziv;
    /** OneToMany(targetEntity="Medium", mappedBy="tip") **/
    protected $media;

    public function __construct() {
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