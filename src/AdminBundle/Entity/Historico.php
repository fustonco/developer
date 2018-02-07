<?php

namespace AdminBundle\Entity;

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
     * @var \AdminBundle\Entity\Pedido
     */
    private $idpedido;

    /**
     * @var \AdminBundle\Entity\Funcionario
     */
    private $idpara;

    /**
     * @var \AdminBundle\Entity\TipoHistorico
     */
    private $tipoHistorico;

    /**
     * @var \AdminBundle\Entity\Mensagem
     */
    private $idmensagem;

    /**
     * @var \AdminBundle\Entity\Funcionario
     */
    private $idde;


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
     * Set idpedido
     *
     * @param \AdminBundle\Entity\Pedido $idpedido
     * @return Historico
     */
    public function setIdpedido(\AdminBundle\Entity\Pedido $idpedido = null)
    {
        $this->idpedido = $idpedido;

        return $this;
    }

    /**
     * Get idpedido
     *
     * @return \AdminBundle\Entity\Pedido 
     */
    public function getIdpedido()
    {
        return $this->idpedido;
    }

    /**
     * Set idpara
     *
     * @param \AdminBundle\Entity\Funcionario $idpara
     * @return Historico
     */
    public function setIdpara(\AdminBundle\Entity\Funcionario $idpara = null)
    {
        $this->idpara = $idpara;

        return $this;
    }

    /**
     * Get idpara
     *
     * @return \AdminBundle\Entity\Funcionario 
     */
    public function getIdpara()
    {
        return $this->idpara;
    }

    /**
     * Set tipoHistorico
     *
     * @param \AdminBundle\Entity\TipoHistorico $tipoHistorico
     * @return Historico
     */
    public function setTipoHistorico(\AdminBundle\Entity\TipoHistorico $tipoHistorico = null)
    {
        $this->tipoHistorico = $tipoHistorico;

        return $this;
    }

    /**
     * Get tipoHistorico
     *
     * @return \AdminBundle\Entity\TipoHistorico 
     */
    public function getTipoHistorico()
    {
        return $this->tipoHistorico;
    }

    /**
     * Set idmensagem
     *
     * @param \AdminBundle\Entity\Mensagem $idmensagem
     * @return Historico
     */
    public function setIdmensagem(\AdminBundle\Entity\Mensagem $idmensagem = null)
    {
        $this->idmensagem = $idmensagem;

        return $this;
    }

    /**
     * Get idmensagem
     *
     * @return \AdminBundle\Entity\Mensagem 
     */
    public function getIdmensagem()
    {
        return $this->idmensagem;
    }

    /**
     * Set idde
     *
     * @param \AdminBundle\Entity\Funcionario $idde
     * @return Historico
     */
    public function setIdde(\AdminBundle\Entity\Funcionario $idde = null)
    {
        $this->idde = $idde;

        return $this;
    }

    /**
     * Get idde
     *
     * @return \AdminBundle\Entity\Funcionario 
     */
    public function getIdde()
    {
        return $this->idde;
    }
}
