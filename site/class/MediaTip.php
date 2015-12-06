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
}