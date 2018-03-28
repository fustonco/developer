<?php

namespace LoginBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Funcionario
 */
class Funcionario implements UserInterface, \Serializable
{
    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $senha;

    /**
     * @var float
     */
    private $limiteAprovacao;

    /**
     * @var string
     */
    private $telefone;

    /**
     * @var string
     */
    private $celular;

    /**
     * @var string
     */
    private $ativo;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \LoginBundle\Entity\TipoUsuario
     */
    private $idtipo;

    /**
     * @var \LoginBundle\Entity\Departamento
     */
    private $iddepartamento;


    /**
     * Set nome
     *
     * @param string $nome
     * @return Funcionario
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
     * Set email
     *
     * @param string $email
     * @return Funcionario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set senha
     *
     * @param string $senha
     * @return Funcionario
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;

        return $this;
    }

    /**
     * Get senha
     *
     * @return string 
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set limiteAprovacao
     *
     * @param float $limiteAprovacao
     * @return Funcionario
     */
    public function setLimiteAprovacao($limiteAprovacao)
    {
        $this->limiteAprovacao = $limiteAprovacao;

        return $this;
    }

    /**
     * Get limiteAprovacao
     *
     * @return float 
     */
    public function getLimiteAprovacao()
    {
        return $this->limiteAprovacao;
    }

    /**
     * Set telefone
     *
     * @param string $telefone
     * @return Funcionario
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
     * Set celular
     *
     * @param string $celular
     * @return Funcionario
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;

        return $this;
    }

    /**
     * Get celular
     *
     * @return string 
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set ativo
     *
     * @param string $ativo
     * @return Funcionario
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
     * Set idtipo
     *
     * @param \LoginBundle\Entity\TipoUsuario $idtipo
     * @return Funcionario
     */
    public function setIdtipo(\LoginBundle\Entity\TipoUsuario $idtipo = null)
    {
        $this->idtipo = $idtipo;

        return $this;
    }

    /**
     * Get idtipo
     *
     * @return \LoginBundle\Entity\TipoUsuario 
     */
    public function getIdtipo()
    {
        return $this->idtipo;
    }

    /**
     * Set iddepartamento
     *
     * @param \LoginBundle\Entity\Departamento $iddepartamento
     * @return Funcionario
     */
    public function setIddepartamento(\LoginBundle\Entity\Departamento $iddepartamento = null)
    {
        $this->iddepartamento = $iddepartamento;

        return $this;
    }

    /**
     * Get iddepartamento
     *
     * @return \LoginBundle\Entity\Departamento 
     */
    public function getIddepartamento()
    {
        return $this->iddepartamento;
    }
    
    
    public function eraseCredentials() {
        
    }

    public function getPassword() {
        return $this->senha;
    }

    public function getRoles() {
        switch ($this->getIdTipo()->getId()){
            case 1:
                 return array("ROLE_ADMIN");
                 break;
            case 2:
                return array("ROLE_FUNCIONARIO");
                break;
            case 3:
                return array("ROLE_FINANCEIRO");
                break;
            case 4:
                return array("ROLE_FUNCIONARIO_CHEFE");
                break;
            case 5:
                return array("ROLE_FUNCIONARIO_MASTER");
                break;
        }
    }


    public function getSalt()
    {
        return null;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->senha,
        ));
    }

    public function unserialize($serialized)
    {
        list (
        $this->id,
        $this->email,
        $this->senha,
        ) = unserialize($serialized);
    }
    
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @var string
     */
    private $tokenApp;


    /**
     * Set tokenApp
     *
     * @param string $tokenApp
     * @return Funcionario
     */
    public function setTokenApp($tokenApp)
    {
        $this->tokenApp = $tokenApp;

        return $this;
    }

    /**
     * Get tokenApp
     *
     * @return string 
     */
    public function getTokenApp()
    {
        return $this->tokenApp;
    }
}
