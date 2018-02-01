<?php

namespace MasterBundle\Entity;

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
     * @var \MasterBundle\Entity\Pedido
     */
    private $idpedido;

    /**
     * @var \MasterBundle\Entity\Funcionario
     */
    private $idpara;

    /**
     * @var \MasterBundle\Entity\TipoHistorico
     */
    private $tipoHistorico;

    /**
     * @var \MasterBundle\Entity\Mensagem
     */
    private $idmensagem;

    /**
     * @var \MasterBundle\Entity\Funcionario
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
     * @param \MasterBundle\Entity\Pedido $idpedido
     * @return Historico
     */
    public function setIdpedido(\MasterBundle\Entity\Pedido $idpedido = null)
    {
        $this->idpedido = $idpedido;

        return $this;
    }

    /**
     * Get idpedido
     *
     * @return \MasterBundle\Entity\Pedido 
     */
    public function getIdpedido()
    {
        return $this->idpedido;
    }

    /**
     * Set idpara
     *
     * @param \MasterBundle\Entity\Funcionario $idpara
     * @return Historico
     */
    public function setIdpara(\MasterBundle\Entity\Funcionario $idpara = null)
    {
        $this->idpara = $idpara;

        return $this;
    }

    /**
     * Get idpara
     *
     * @return \MasterBundle\Entity\Funcionario 
     */
    public function getIdpara()
    {
        return $this->idpara;
    }

    /**
     * Set tipoHistorico
     *
     * @param \MasterBundle\Entity\TipoHistorico $tipoHistorico
     * @return Historico
     */
    public function setTipoHistorico(\MasterBundle\Entity\TipoHistorico $tipoHistorico = null)
    {
        $this->tipoHistorico = $tipoHistorico;

        return $this;
    }

    /**
     * Get tipoHistorico
     *
     * @return \MasterBundle\Entity\TipoHistorico 
     */
    public function getTipoHistorico()
    {
        return $this->tipoHistorico;
    }

    /**
     * Set idmensagem
     *
     * @param \MasterBundle\Entity\Mensagem $idmensagem
     * @return Historico
     */
    public function setIdmensagem(\MasterBundle\Entity\Mensagem $idmensagem = null)
    {
        $this->idmensagem = $idmensagem;

        return $this;
    }

    /**
     * Get idmensagem
     *
     * @return \MasterBundle\Entity\Mensagem 
     */
    public function getIdmensagem()
    {
        return $this->idmensagem;
    }

    /**
     * Set idde
     *
     * @param \MasterBundle\Entity\Funcionario $idde
     * @return Historico
     */
    public function setIdde(\MasterBundle\Entity\Funcionario $idde = null)
    {
        $this->idde = $idde;

        return $this;
    }

    /**
     * Get idde
     *
     * @return \MasterBundle\Entity\Funcionario 
     */
    public function getIdde()
    {
        return $this->idde;
    }
}
