<?php

namespace FinanceiroBundle\Entity;

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
     * @var \FinanceiroBundle\Entity\Funcionario
     */
    private $idde;

    /**
     * @var \FinanceiroBundle\Entity\Mensagem
     */
    private $idmensagem;

    /**
     * @var \FinanceiroBundle\Entity\TipoHistorico
     */
    private $tipoHistorico;

    /**
     * @var \FinanceiroBundle\Entity\Funcionario
     */
    private $idpara;

    /**
     * @var \FinanceiroBundle\Entity\Pedido
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
     * @param \FinanceiroBundle\Entity\Funcionario $idde
     * @return Historico
     */
    public function setIdde(\FinanceiroBundle\Entity\Funcionario $idde = null)
    {
        $this->idde = $idde;

        return $this;
    }

    /**
     * Get idde
     *
     * @return \FinanceiroBundle\Entity\Funcionario 
     */
    public function getIdde()
    {
        return $this->idde;
    }

    /**
     * Set idmensagem
     *
     * @param \FinanceiroBundle\Entity\Mensagem $idmensagem
     * @return Historico
     */
    public function setIdmensagem(\FinanceiroBundle\Entity\Mensagem $idmensagem = null)
    {
        $this->idmensagem = $idmensagem;

        return $this;
    }

    /**
     * Get idmensagem
     *
     * @return \FinanceiroBundle\Entity\Mensagem 
     */
    public function getIdmensagem()
    {
        return $this->idmensagem;
    }

    /**
     * Set tipoHistorico
     *
     * @param \FinanceiroBundle\Entity\TipoHistorico $tipoHistorico
     * @return Historico
     */
    public function setTipoHistorico(\FinanceiroBundle\Entity\TipoHistorico $tipoHistorico = null)
    {
        $this->tipoHistorico = $tipoHistorico;

        return $this;
    }

    /**
     * Get tipoHistorico
     *
     * @return \FinanceiroBundle\Entity\TipoHistorico 
     */
    public function getTipoHistorico()
    {
        return $this->tipoHistorico;
    }

    /**
     * Set idpara
     *
     * @param \FinanceiroBundle\Entity\Funcionario $idpara
     * @return Historico
     */
    public function setIdpara(\FinanceiroBundle\Entity\Funcionario $idpara = null)
    {
        $this->idpara = $idpara;

        return $this;
    }

    /**
     * Get idpara
     *
     * @return \FinanceiroBundle\Entity\Funcionario 
     */
    public function getIdpara()
    {
        return $this->idpara;
    }

    /**
     * Set idpedido
     *
     * @param \FinanceiroBundle\Entity\Pedido $idpedido
     * @return Historico
     */
    public function setIdpedido(\FinanceiroBundle\Entity\Pedido $idpedido = null)
    {
        $this->idpedido = $idpedido;

        return $this;
    }

    /**
     * Get idpedido
     *
     * @return \FinanceiroBundle\Entity\Pedido 
     */
    public function getIdpedido()
    {
        return $this->idpedido;
    }
}
