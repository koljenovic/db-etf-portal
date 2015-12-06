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
}