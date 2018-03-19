<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mensagem
 */
class Mensagem
{
    /**
     * @var string
     */
    private $titulo;

    /**
     * @var string
     */
    private $mensagem;

    /**
     * @var string
     */
    private $lido;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Mensagem
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set mensagem
     *
     * @param string $mensagem
     * @return Mensagem
     */
    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;

        return $this;
    }

    /**
     * Get mensagem
     *
     * @return string 
     */
    public function getMensagem()
    {
        return $this->mensagem;
    }

    /**
     * Set lido
     *
     * @param string $lido
     * @return Mensagem
     */
    public function setLido($lido)
    {
        $this->lido = $lido;

        return $this;
    }

    /**
     * Get lido
     *
     * @return string 
     */
    public function getLido()
    {
        return $this->lido;
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
