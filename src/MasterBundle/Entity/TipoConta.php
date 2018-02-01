<?php

namespace MasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoConta
 */
class TipoConta
{
    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set nome
     *
     * @param string $nome
     * @return TipoConta
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return TipoConta
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
