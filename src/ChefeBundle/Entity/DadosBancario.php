<?php

namespace ChefeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DadosBancario
 */
class DadosBancario
{
    /**
     * @var string
     */
    private $agencia;

    /**
     * @var string
     */
    private $numero;

    /**
     * @var string
     */
    private $favorecido;

    /**
     * @var string
     */
    private $documento;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \ChefeBundle\Entity\TipoPessoa
     */
    private $idtipopessoa;

    /**
     * @var \ChefeBundle\Entity\TipoConta
     */
    private $idtipoconta;

    /**
     * @var \ChefeBundle\Entity\Pagamento
     */
    private $idpagamento;

    /**
     * @var \ChefeBundle\Entity\Banco
     */
    private $idbanco;


    /**
     * Set agencia
     *
     * @param string $agencia
     * @return DadosBancario
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;

        return $this;
    }

    /**
     * Get agencia
     *
     * @return string 
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return DadosBancario
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set favorecido
     *
     * @param string $favorecido
     * @return DadosBancario
     */
    public function setFavorecido($favorecido)
    {
        $this->favorecido = $favorecido;

        return $this;
    }

    /**
     * Get favorecido
     *
     * @return string 
     */
    public function getFavorecido()
    {
        return $this->favorecido;
    }

    /**
     * Set documento
     *
     * @param string $documento
     * @return DadosBancario
     */
    public function setDocumento($documento)
    {
        $this->documento = $documento;

        return $this;
    }

    /**
     * Get documento
     *
     * @return string 
     */
    public function getDocumento()
    {
        return $this->documento;
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
     * Set idtipopessoa
     *
     * @param \ChefeBundle\Entity\TipoPessoa $idtipopessoa
     * @return DadosBancario
     */
    public function setIdtipopessoa(\ChefeBundle\Entity\TipoPessoa $idtipopessoa = null)
    {
        $this->idtipopessoa = $idtipopessoa;

        return $this;
    }

    /**
     * Get idtipopessoa
     *
     * @return \ChefeBundle\Entity\TipoPessoa 
     */
    public function getIdtipopessoa()
    {
        return $this->idtipopessoa;
    }

    /**
     * Set idtipoconta
     *
     * @param \ChefeBundle\Entity\TipoConta $idtipoconta
     * @return DadosBancario
     */
    public function setIdtipoconta(\ChefeBundle\Entity\TipoConta $idtipoconta = null)
    {
        $this->idtipoconta = $idtipoconta;

        return $this;
    }

    /**
     * Get idtipoconta
     *
     * @return \ChefeBundle\Entity\TipoConta 
     */
    public function getIdtipoconta()
    {
        return $this->idtipoconta;
    }

    /**
     * Set idpagamento
     *
     * @param \ChefeBundle\Entity\Pagamento $idpagamento
     * @return DadosBancario
     */
    public function setIdpagamento(\ChefeBundle\Entity\Pagamento $idpagamento = null)
    {
        $this->idpagamento = $idpagamento;

        return $this;
    }

    /**
     * Get idpagamento
     *
     * @return \ChefeBundle\Entity\Pagamento 
     */
    public function getIdpagamento()
    {
        return $this->idpagamento;
    }

    /**
     * Set idbanco
     *
     * @param \ChefeBundle\Entity\Banco $idbanco
     * @return DadosBancario
     */
    public function setIdbanco(\ChefeBundle\Entity\Banco $idbanco = null)
    {
        $this->idbanco = $idbanco;

        return $this;
    }

    /**
     * Get idbanco
     *
     * @return \ChefeBundle\Entity\Banco 
     */
    public function getIdbanco()
    {
        return $this->idbanco;
    }
}
