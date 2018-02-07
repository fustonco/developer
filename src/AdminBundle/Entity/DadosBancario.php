<?php

namespace AdminBundle\Entity;

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
     * @var \AdminBundle\Entity\TipoPessoa
     */
    private $idtipopessoa;

    /**
     * @var \AdminBundle\Entity\TipoConta
     */
    private $idtipoconta;

    /**
     * @var \AdminBundle\Entity\Pagamento
     */
    private $idpagamento;

    /**
     * @var \AdminBundle\Entity\Banco
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
     * @param \AdminBundle\Entity\TipoPessoa $idtipopessoa
     * @return DadosBancario
     */
    public function setIdtipopessoa(\AdminBundle\Entity\TipoPessoa $idtipopessoa = null)
    {
        $this->idtipopessoa = $idtipopessoa;

        return $this;
    }

    /**
     * Get idtipopessoa
     *
     * @return \AdminBundle\Entity\TipoPessoa 
     */
    public function getIdtipopessoa()
    {
        return $this->idtipopessoa;
    }

    /**
     * Set idtipoconta
     *
     * @param \AdminBundle\Entity\TipoConta $idtipoconta
     * @return DadosBancario
     */
    public function setIdtipoconta(\AdminBundle\Entity\TipoConta $idtipoconta = null)
    {
        $this->idtipoconta = $idtipoconta;

        return $this;
    }

    /**
     * Get idtipoconta
     *
     * @return \AdminBundle\Entity\TipoConta 
     */
    public function getIdtipoconta()
    {
        return $this->idtipoconta;
    }

    /**
     * Set idpagamento
     *
     * @param \AdminBundle\Entity\Pagamento $idpagamento
     * @return DadosBancario
     */
    public function setIdpagamento(\AdminBundle\Entity\Pagamento $idpagamento = null)
    {
        $this->idpagamento = $idpagamento;

        return $this;
    }

    /**
     * Get idpagamento
     *
     * @return \AdminBundle\Entity\Pagamento 
     */
    public function getIdpagamento()
    {
        return $this->idpagamento;
    }

    /**
     * Set idbanco
     *
     * @param \AdminBundle\Entity\Banco $idbanco
     * @return DadosBancario
     */
    public function setIdbanco(\AdminBundle\Entity\Banco $idbanco = null)
    {
        $this->idbanco = $idbanco;

        return $this;
    }

    /**
     * Get idbanco
     *
     * @return \AdminBundle\Entity\Banco 
     */
    public function getIdbanco()
    {
        return $this->idbanco;
    }
}
