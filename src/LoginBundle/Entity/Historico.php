<?php

namespace LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Historico
 */
class Historico
{
    /**
     * @var string
     */
    private $codigo;

    /**
     * @var \DateTime
     */
    private $dataPassagem;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \LoginBundle\Entity\Funcionario
     */
    private $idde;

    /**
     * @var \LoginBundle\Entity\Mensagem
     */
    private $idmensagem;

    /**
     * @var \LoginBundle\Entity\TipoHistorico
     */
    private $tipoHistorico;

    /**
     * @var \LoginBundle\Entity\Funcionario
     */
    private $idpara;

    /**
     * @var \LoginBundle\Entity\Pedido
     */
    private $idpedido;


    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Historico
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set dataPassagem
     *
     * @param \DateTime $dataPassagem
     * @return Historico
     */
    public function setDataPassagem($dataPassagem)
    {
        $this->dataPassagem = $dataPassagem;

        return $this;
    }

    /**
     * Get dataPassagem
     *
     * @return \DateTime 
     */
    public function getDataPassagem()
    {
        return $this->dataPassagem;
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

    /**
     * Set idde
     *
     * @param \LoginBundle\Entity\Funcionario $idde
     * @return Historico
     */
    public function setIdde(\LoginBundle\Entity\Funcionario $idde = null)
    {
        $this->idde = $idde;

        return $this;
    }

    /**
     * Get idde
     *
     * @return \LoginBundle\Entity\Funcionario 
     */
    public function getIdde()
    {
        return $this->idde;
    }

    /**
     * Set idmensagem
     *
     * @param \LoginBundle\Entity\Mensagem $idmensagem
     * @return Historico
     */
    public function setIdmensagem(\LoginBundle\Entity\Mensagem $idmensagem = null)
    {
        $this->idmensagem = $idmensagem;

        return $this;
    }

    /**
     * Get idmensagem
     *
     * @return \LoginBundle\Entity\Mensagem 
     */
    public function getIdmensagem()
    {
        return $this->idmensagem;
    }

    /**
     * Set tipoHistorico
     *
     * @param \LoginBundle\Entity\TipoHistorico $tipoHistorico
     * @return Historico
     */
    public function setTipoHistorico(\LoginBundle\Entity\TipoHistorico $tipoHistorico = null)
    {
        $this->tipoHistorico = $tipoHistorico;

        return $this;
    }

    /**
     * Get tipoHistorico
     *
     * @return \LoginBundle\Entity\TipoHistorico 
     */
    public function getTipoHistorico()
    {
        return $this->tipoHistorico;
    }

    /**
     * Set idpara
     *
     * @param \LoginBundle\Entity\Funcionario $idpara
     * @return Historico
     */
    public function setIdpara(\LoginBundle\Entity\Funcionario $idpara = null)
    {
        $this->idpara = $idpara;

        return $this;
    }

    /**
     * Get idpara
     *
     * @return \LoginBundle\Entity\Funcionario 
     */
    public function getIdpara()
    {
        return $this->idpara;
    }

    /**
     * Set idpedido
     *
     * @param \LoginBundle\Entity\Pedido $idpedido
     * @return Historico
     */
    public function setIdpedido(\LoginBundle\Entity\Pedido $idpedido = null)
    {
        $this->idpedido = $idpedido;

        return $this;
    }

    /**
     * Get idpedido
     *
     * @return \LoginBundle\Entity\Pedido 
     */
    public function getIdpedido()
    {
        return $this->idpedido;
    }
    /**
     * @var boolean
     */
    private $visto;


    /**
     * Set visto
     *
     * @param boolean $visto
     * @return Historico
     */
    public function setVisto($visto)
    {
        $this->visto = $visto;

        return $this;
    }

    /**
     * Get visto
     *
     * @return boolean 
     */
    public function getVisto()
    {
        return $this->visto;
    }
}
