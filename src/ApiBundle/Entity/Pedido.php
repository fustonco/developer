<?php

namespace ApiBundle\Entity;

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
     * @var integer
     */
    private $id;

    /**
     * @var \ApiBundle\Entity\Fornecedor
     */
    private $idfornecedor;

    /**
     * @var \ApiBundle\Entity\Funcionario
     */
    private $criadoPor;

    /**
     * @var \ApiBundle\Entity\StatusPedido
     */
    private $status;

    /**
     * @var \ApiBundle\Entity\TipoPedido
     */
    private $idtipo;


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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idfornecedor
     *
     * @param \ApiBundle\Entity\Fornecedor $idfornecedor
     * @return Pedido
     */
    public function setIdfornecedor(\ApiBundle\Entity\Fornecedor $idfornecedor = null)
    {
        $this->idfornecedor = $idfornecedor;

        return $this;
    }

    /**
     * Get idfornecedor
     *
     * @return \ApiBundle\Entity\Fornecedor 
     */
    public function getIdfornecedor()
    {
        return $this->idfornecedor;
    }

    /**
     * Set criadoPor
     *
     * @param \ApiBundle\Entity\Funcionario $criadoPor
     * @return Pedido
     */
    public function setCriadoPor(\ApiBundle\Entity\Funcionario $criadoPor = null)
    {
        $this->criadoPor = $criadoPor;

        return $this;
    }

    /**
     * Get criadoPor
     *
     * @return \ApiBundle\Entity\Funcionario 
     */
    public function getCriadoPor()
    {
        return $this->criadoPor;
    }

    /**
     * Set status
     *
     * @param \ApiBundle\Entity\StatusPedido $status
     * @return Pedido
     */
    public function setStatus(\ApiBundle\Entity\StatusPedido $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \ApiBundle\Entity\StatusPedido 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set idtipo
     *
     * @param \ApiBundle\Entity\TipoPedido $idtipo
     * @return Pedido
     */
    public function setIdtipo(\ApiBundle\Entity\TipoPedido $idtipo = null)
    {
        $this->idtipo = $idtipo;

        return $this;
    }

    /**
     * Get idtipo
     *
     * @return \ApiBundle\Entity\TipoPedido 
     */
    public function getIdtipo()
    {
        return $this->idtipo;
    }
}
