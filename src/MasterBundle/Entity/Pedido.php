<?php

namespace MasterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pedido
 */
class Pedido
{
    /**
     * @var string
     */
    private $codigo;

    /**
     * @var \DateTime
     */
    private $dataVencimento;

    /**
     * @var \DateTime
     */
    private $dataPedido;

    /**
     * @var string
     */
    private $valor;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var string
     */
    private $ativo;

    /**
     * @var string
     */
    private $status;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \MasterBundle\Entity\TipoPedido
     */
    private $idtipo;

    /**
     * @var \MasterBundle\Entity\Fornecedor
     */
    private $idfornecedor;


    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Pedido
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
     * Set dataVencimento
     *
     * @param \DateTime $dataVencimento
     * @return Pedido
     */
    public function setDataVencimento($dataVencimento)
    {
        $this->dataVencimento = $dataVencimento;

        return $this;
    }

    /**
     * Get dataVencimento
     *
     * @return \DateTime 
     */
    public function getDataVencimento()
    {
        return $this->dataVencimento;
    }

    /**
     * Set dataPedido
     *
     * @param \DateTime $dataPedido
     * @return Pedido
     */
    public function setDataPedido($dataPedido)
    {
        $this->dataPedido = $dataPedido;

        return $this;
    }

    /**
     * Get dataPedido
     *
     * @return \DateTime 
     */
    public function getDataPedido()
    {
        return $this->dataPedido;
    }

    /**
     * Set valor
     *
     * @param string $valor
     * @return Pedido
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return string 
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set descricao
     *
     * @param string $descricao
     * @return Pedido
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get descricao
     *
     * @return string 
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set ativo
     *
     * @param string $ativo
     * @return Pedido
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo
     *
     * @return string 
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Pedido
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

    /**
     * Set idtipo
     *
     * @param \MasterBundle\Entity\TipoPedido $idtipo
     * @return Pedido
     */
    public function setIdtipo(\MasterBundle\Entity\TipoPedido $idtipo = null)
    {
        $this->idtipo = $idtipo;

        return $this;
    }

    /**
     * Get idtipo
     *
     * @return \MasterBundle\Entity\TipoPedido 
     */
    public function getIdtipo()
    {
        return $this->idtipo;
    }

    /**
     * Set idfornecedor
     *
     * @param \MasterBundle\Entity\Fornecedor $idfornecedor
     * @return Pedido
     */
    public function setIdfornecedor(\MasterBundle\Entity\Fornecedor $idfornecedor = null)
    {
        $this->idfornecedor = $idfornecedor;

        return $this;
    }

    /**
     * Get idfornecedor
     *
     * @return \MasterBundle\Entity\Fornecedor 
     */
    public function getIdfornecedor()
    {
        return $this->idfornecedor;
    }
}
