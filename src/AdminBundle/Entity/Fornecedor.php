<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fornecedor
 */
class Fornecedor
{
    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $cnpj;

    /**
     * @var string
     */
    private $cpf;

    /**
     * @var string
     */
    private $telefone;

    /**
     * @var string
     */
    private $endereco;

    /**
     * @var string
     */
    private $ativo;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set nome
     *
     * @param string $nome
     * @return Fornecedor
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
     * Set cnpj
     *
     * @param string $cnpj
     * @return Fornecedor
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * Get cnpj
     *
     * @return string 
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * Set cpf
     *
     * @param string $cpf
     * @return Fornecedor
     */
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get cpf
     *
     * @return string 
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set telefone
     *
     * @param string $telefone
     * @return Fornecedor
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Get telefone
     *
     * @return string 
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set endereco
     *
     * @param string $endereco
     * @return Fornecedor
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * Get endereco
     *
     * @return string 
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Set ativo
     *
     * @param string $ativo
     * @return Fornecedor
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
     * @var string
     */
    private $fantasia;


    /**
     * Set fantasia
     *
     * @param string $fantasia
     * @return Fornecedor
     */
    public function setFantasia($fantasia)
    {
        $this->fantasia = $fantasia;

        return $this;
    }

    /**
     * Get fantasia
     *
     * @return string 
     */
    public function getFantasia()
    {
        return $this->fantasia;
    }
}
