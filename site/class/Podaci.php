<?php

/**
 * @Entity @Table(name="podaci")
 */
class Podaci
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;
    /** @Column(type="blob", nullable=true) **/
    protected $sadrzaj;

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
    public function getSadrzaj()
    {
        return $this->sadrzaj;
    }

    /**
     * @param mixed $sadrzaj
     */
    public function setSadrzaj($sadrzaj)
    {
        $this->sadrzaj = $sadrzaj;
    }


}