<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use AdminBundle\Entity\Empresa;
use AdminBundle\Entity\Departamento;
use AdminBundle\Entity\Grupo;
use AdminBundle\Entity\Funcionario;
use AdminBundle\Entity\FuncionarioEmpresa;
use AdminBundle\Entity\Fornecedor;

class DefaultController extends Controller
{
    public function serializeJSON($entity) {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($entity, 'json');
        return $jsonContent;
    }

    /**
     * @Route("/find/empresas/" , name="findEmpresas")
     */
    public function FindEmpresasAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $empresas = $em->getRepository('AdminBundle:FuncionarioEmpresa')->findByIdfuncionario($request->get('id'));

            return new Response(json_encode([
                "description" => json_decode($this->serializeJSON($empresas))
            ]), 200);
        } catch(\Exception $e) {
            return new Response(json_encode([
                "description" => "Empresas nao encontradas"
            ]), 500);
        }
    }

    /**
     * @Route("/remove/empresa/" , name="removeEmpresa")
     */
    public function RemoveEmpresaAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $empresafuncionario = $em->getRepository('AdminBundle:FuncionarioEmpresa')->findOneBy([
                'idfuncionario' => $request->get('idFuncionario'),
                'idempresa' => $request->get('idEmpresa')
            ]);
            if($empresafuncionario) {
                $em->remove($empresafuncionario);
                $em->flush();
            } else {
                throw new \Exception('error_funcionarioempresa');
            }

            return new Response(json_encode([
                "description" => "Empresa removida com sucesso!"
            ]), 200);
        } catch(\Exception $e) {
            switch($e->getMessage()){
                case 'error_funcionarioempresa':
                    return new Response(json_encode([
                        "description" => "Não associados!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Não foi possível remover empresa desse funcionario!"
            ]), 500);
        }
    }

    /**
     * @Route("/add/empresa/" , name="addEmpresa")
     */
    public function AddEmpresaAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $empresafuncionario = $em->getRepository('AdminBundle:FuncionarioEmpresa')->findOneBy([
                'idfuncionario' => $request->get('idFuncionario'),
                'idempresa' => $request->get('idEmpresa')
            ]);
            if(!$empresafuncionario) {
                $func = $em->getRepository('AdminBundle:Funcionario')->findOneById($request->get('idFuncionario'));
                if(!$func) {throw new \Exception('error_func');}
                $emp = $em->getRepository('AdminBundle:Empresa')->findOneById($request->get('idEmpresa'));
                if(!$emp) {throw new \Exception('error_emp');}
                $empfunc = new FuncionarioEmpresa();
                $empfunc->setIdfuncionario($func);
                $empfunc->setIdempresa($emp);
                $empfunc->setAtivo('S');
                $em->persist($empfunc);
                $em->flush();
            } else {
                throw new \Exception('error_funcionarioempresa');
            }

            return new Response(json_encode([
                "description" => "Empresa associada com sucesso!"
            ]), 200);
        } catch(\Exception $e) {
            switch($e->getMessage()){
                case 'error_funcionarioempresa':
                    return new Response(json_encode([
                        "description" => "Já associados!"
                    ]), 500);
                break;
                case 'error_func':
                    return new Response(json_encode([
                        "description" => "Funcionario não encontrado!"
                    ]), 500);
                break;
                case 'error_emp':
                    return new Response(json_encode([
                        "description" => "Empresa não encontrada!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Não foi possível adicionar empresa para esse funcionario!"
            ]), 500);
        }
    }

    /**
     * @Route("/" , name="admin")
     */
    public function AdminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $statement = $connection->prepare("SELECT count(id) count FROM grupo");
        $statement->execute();
        $countGrupo = $statement->fetch();

        $statement = $connection->prepare("SELECT count(id) count FROM empresa");
        $statement->execute();
        $countEmpresa = $statement->fetch();

        $statement = $connection->prepare("SELECT count(id) count FROM departamento");
        $statement->execute();
        $countDepartamento = $statement->fetch();

        $statement = $connection->prepare("SELECT count(id) count FROM funcionario");
        $statement->execute();
        $countFuncionario = $statement->fetch();

        $statement = $connection->prepare("SELECT count(id) count FROM fornecedor");
        $statement->execute();
        $countFornecedor = $statement->fetch();

        return $this->render("AdminBundle:Default:index.html.twig", [
            'countGrupo'  => $countGrupo,
            'countEmpresa'  => $countEmpresa,
            'countDepartamento'  => $countDepartamento,
            'countFuncionario'  => $countFuncionario,
            'countFornecedor'  => $countFornecedor,
        ]);
    }

    /**
     * @Route("/editar/departamento/")
     */
    public function adminEditarDepartamentoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('descricao') || $request->get('descricao') == '') {throw new \Exception('error_descricao');}

            $departamento = $em->getRepository("AdminBundle:Departamento")->findOneById($request->get('id'));
            $departamento->setNome($request->get('nome'));
            $departamento->setDescricao($request->get('descricao'));
            $em->persist($departamento);
            $em->flush();
            return new Response(json_encode([
                "description" => "Departamento Editado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_descricao':
                    return new Response(json_encode([
                        "description" => "Descrição não pode ser vazio!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Editar Departamento!"
            ]), 500);
        }
    }

    /**
     * @Route("/excluir/departamento/")
     */
    public function adminExcluirDepartamentoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $departamento = $em->getRepository("AdminBundle:Departamento")->findOneById($request->get('id'));
            $em->remove($departamento);
            $em->flush();
            return new Response(json_encode([
                "description" => "Departamento Excluido com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            if(strpos($e->getMessage(), 'FOREIGN')) {
                return new Response(json_encode([
                    "description" => "Não foi possível excluir esse Departamento, pois existe funcionários registrados nele, por favor altere-os para que seja possível a remoção segura."
                ]), 500);
            }
            return new Response(json_encode([
                "description" => "Erro ao Excluir Departamento!"
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/departamento/")
     */
    public function adminCadastrarDepartamentoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('descricao') || $request->get('descricao') == '') {throw new \Exception('error_descricao');}

            $departamento = new Departamento();
            $departamento->setNome($request->get('nome'));
            $departamento->setDescricao($request->get('descricao'));
            $departamento->setAtivo('S');
            $em->persist($departamento);
            $em->flush();
            return new Response(json_encode([
                "description" => "Departamento Cadastrado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_descricao':
                    return new Response(json_encode([
                        "description" => "Descrição não pode ser vazio!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Departamento!"
            ]), 500);
        }
    }

    /**
     * @Route("/editar/empresa/")
     */
    public function adminEditarEmpresaAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('cnpj') || $request->get('cnpj') == '') {throw new \Exception('error_cnpj');}

            $grupo = $em->getRepository('AdminBundle:Grupo')->findOneById($request->get('grupo'));
            if(!$grupo || $grupo == '') {throw new \Exception('error_grupo');}

            $empresa = $em->getRepository("AdminBundle:Empresa")->findOneById($request->get('id'));
            $empresa->setNome($request->get('nome'));
            $empresa->setCnpj($request->get('cnpj'));
            $empresa->setIdgrupo($grupo);
            $em->persist($empresa);
            $em->flush();
            return new Response(json_encode([
                "description" => "Empresa Editado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_descricao':
                    return new Response(json_encode([
                        "description" => "Descrição não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_grupo':
                    return new Response(json_encode([
                        "description" => "Grupo não encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Editar Empresa!"
            ]), 500);
        }
    }

    /**
     * @Route("/excluir/empresa/")
     */
    public function adminExcluirEmpresaAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $empresa = $em->getRepository("AdminBundle:Empresa")->findOneById($request->get('id'));
            $em->remove($empresa);
            $em->flush();
            return new Response(json_encode([
                "description" => "Empresa Excluido com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            if(strpos($e->getMessage(), 'FOREIGN')) {
                return new Response(json_encode([
                    "description" => "Não foi possível excluir essa Empresa, pois existe funcionários registrados nele, por favor altere-os para que seja possível a remoção segura."
                ]), 500);
            }
            return new Response(json_encode([
                "description" => "Erro ao Excluir Empresa!"
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/empresa/")
     */
    public function adminCadastrarEmpresaAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('cnpj') || $request->get('cnpj') == '') {throw new \Exception('error_cnpj');}

            $grupo = $em->getRepository('AdminBundle:Grupo')->findOneById($request->get('grupo'));
            if(!$grupo || $grupo == '') {throw new \Exception('error_grupo');}

            $empresa = new Empresa();
            $empresa->setNome($request->get('nome'));
            $empresa->setCnpj($request->get('cnpj'));
            $empresa->setAtivo('S');
            $empresa->setIdgrupo($grupo);
            $em->persist($empresa);
            $em->flush();
            return new Response(json_encode([
                "description" => "Empresa Cadastrado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_cnpj':
                    return new Response(json_encode([
                        "description" => "CNPJ não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_grupo':
                    return new Response(json_encode([
                        "description" => "Grupo não encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Empresa!"
            ]), 500);
        }
    }



    /**
     * @Route("/editar/funcionario/")
     */
    public function adminEditarFuncionarioAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('email') || $request->get('email') == '') {throw new \Exception('error_email');}
            if(!$request->get('limite') || $request->get('limite') == '') {throw new \Exception('error_limite');}

            $departamento = $em->getRepository('AdminBundle:Departamento')->findOneById($request->get('departamento'));
            if(!$departamento || $departamento == '') {throw new \Exception('error_departamento');}

            $tipo = $em->getRepository('AdminBundle:TipoUsuario')->findOneById($request->get('tipo'));
            if(!$tipo || $tipo == '') {throw new \Exception('error_tipo');}

            $funcionario = $em->getRepository("AdminBundle:Funcionario")->findOneById($request->get('id'));
            $funcionario->setNome($request->get('nome'));
            $funcionario->setEmail($request->get('email'));
            $funcionario->setLimiteAprovacao($request->get('limite'));
            $funcionario->setTelefone($request->get('telefone'));
            $funcionario->setCelular($request->get('celular'));
            $funcionario->setAtivo('S');
            $funcionario->setIddepartamento($departamento);
            $funcionario->setIdtipo($tipo);
            // $funcionario->setSenha('func123');
            $em->persist($funcionario);
            $em->flush();

            $em->getConnection()->commit();
            return new Response(json_encode([
                "description" => "Funcionario Cadastrado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_email':
                    return new Response(json_encode([
                        "description" => "E-Mail não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_limite':
                    return new Response(json_encode([
                        "description" => "Limite não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_tipo':
                    return new Response(json_encode([
                        "description" => "Tipo não encontrado!"
                    ]), 500);
                break;
                case 'error_departamento':
                    return new Response(json_encode([
                        "description" => "Departamento não encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Funcionario!"
            ]), 500);
        }
    }

    /**
     * @Route("/excluir/funcionario/")
     */
    public function adminExcluirFuncionarioAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $funcionario = $em->getRepository("AdminBundle:Funcionario")->findOneById($request->get('id'));
            $em->remove($funcionario);
            $em->flush();
            return new Response(json_encode([
                "description" => "Funcionario Excluido com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            if(strpos($e->getMessage(), 'FOREIGN')) {
                return new Response(json_encode([
                    "description" => "Não foi possível excluir esse Funcionario, pois existe registros nele, por favor altere-os para que seja possível a remoção segura."
                ]), 500);
            }
            return new Response(json_encode([
                "description" => "Erro ao Excluir Empresa!"
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/funcionario/")
     */
    public function adminCadastrarFuncionarioAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('email') || $request->get('email') == '') {throw new \Exception('error_email');}
            if(!$request->get('limite') || $request->get('limite') == '') {throw new \Exception('error_limite');}

            $departamento = $em->getRepository('AdminBundle:Departamento')->findOneById($request->get('departamento'));
            if(!$departamento || $departamento == '') {throw new \Exception('error_departamento');}

            $tipo = $em->getRepository('AdminBundle:TipoUsuario')->findOneById($request->get('tipo'));
            if(!$tipo || $tipo == '') {throw new \Exception('error_tipo');}

            $funcionario = new Funcionario();
            $funcionario->setNome($request->get('nome'));
            $funcionario->setEmail($request->get('email'));
            $funcionario->setLimiteAprovacao($request->get('limite'));
            $funcionario->setTelefone($request->get('telefone'));
            $funcionario->setCelular($request->get('celular'));
            $funcionario->setAtivo('S');
            $funcionario->setIddepartamento($departamento);
            $funcionario->setIdtipo($tipo);
            $funcionario->setSenha('func123');
            $em->persist($funcionario);
            $em->flush();

            $em->getConnection()->commit();
            return new Response(json_encode([
                "description" => "Funcionario Cadastrado com Sucesso!"
            ]), 200);
        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_email':
                    return new Response(json_encode([
                        "description" => "E-Mail não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_limite':
                    return new Response(json_encode([
                        "description" => "Limite não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_tipo':
                    return new Response(json_encode([
                        "description" => "Tipo não encontrado!"
                    ]), 500);
                break;
                case 'error_departamento':
                    return new Response(json_encode([
                        "description" => "Departamento não encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Funcionario!"
            ]), 500);
        }
    }





    /**
     * @Route("/editar/fornecedor/")
     */
    public function adminEditarFornecedorAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}

            $fornecedor = $em->getRepository("AdminBundle:Fornecedor")->findOneById($request->get('id'));
            $fornecedor->setNome($request->get('nome'));
            $fornecedor->setCnpj($request->get('cnpj'));
            $fornecedor->setCpf($request->get('cpf'));
            $fornecedor->setTelefone($request->get('telefone'));
            $fornecedor->setEndereco($request->get('endereco'));
            $fornecedor->setAtivo('S');
            $em->persist($fornecedor);
            $em->flush();
            return new Response(json_encode([
                "description" => "Fornecedor Cadastrado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Fornecedor!"
            ]), 500);
        }
    }

    /**
     * @Route("/excluir/fornecedor/")
     */
    public function adminExcluirFornecedorAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $fornecedor = $em->getRepository("AdminBundle:Fornecedor")->findOneById($request->get('id'));
            $em->remove($fornecedor);
            $em->flush();
            return new Response(json_encode([
                "description" => "Fornecedor Excluido com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            if(strpos($e->getMessage(), 'FOREIGN')) {
                return new Response(json_encode([
                    "description" => "Não foi possível excluir esse Fornecedor, pois existe registros nele, por favor altere-os para que seja possível a remoção segura."
                ]), 500);
            }
            return new Response(json_encode([
                "description" => "Erro ao Excluir Empresa!"
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/fornecedor/")
     */
    public function adminCadastrarFornecedorAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}

            $fornecedor = new Fornecedor();
            $fornecedor->setNome($request->get('nome'));
            $fornecedor->setCnpj($request->get('cnpj'));
            $fornecedor->setCpf($request->get('cpf'));
            $fornecedor->setTelefone($request->get('telefone'));
            $fornecedor->setEndereco($request->get('endereco'));
            $fornecedor->setAtivo('S');
            $em->persist($fornecedor);
            $em->flush();
            return new Response(json_encode([
                "description" => "Fornecedor Cadastrado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Fornecedor!"
            ]), 500);
        }
    }


    /**
     * @Route("/editar/tipopedido/")
     */
    public function adminEditarTipoPedidoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('descricao') || $request->get('descricao') == '') {throw new \Exception('error_descricao');}

            $tipopedido = $em->getRepository('AdminBundle:TipoPedido')->findOneById($request->get('id'));
            if(!$tipopedido) {throw new \Exception('error_tipopedido');}

            $tipopedido->setNome($request->get('nome'));
            $tipopedido->setDescricao($request->get('descricao'));
            $tipopedido->setAtivo('S');
            $em->persist($tipopedido);
            $em->flush();
            return new Response(json_encode([
                "description" => "Tipo de Pedido Editado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_descricao':
                    return new Response(json_encode([
                        "description" => "Descrição não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_tipopedido':
                    return new Response(json_encode([
                        "description" => "Tipo de Pedido não pode encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Editar Tipo de Pedido!"
            ]), 500);
        }
    }

    /**
     * @Route("/excluir/tipopedido/")
     */
    public function adminExcluirTipoPedidoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $tipopedido = $em->getRepository('AdminBundle:TipoPedido')->findOneById($request->ger('id'));
            if(!$tipopedido) {throw new \Exception('error_tipopedido');}
            $em->remove($tipopedido);
            $em->flush();

            return new Response(json_encode([
                "description" => "Tipo de Pedido Excluido com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            if(strpos($e->getMessage(), 'FOREIGN')) {
                return new Response(json_encode([
                    "description" => "Não foi possível excluir esse Tipo de Pedido, pois existe registrados nele, por favor altere-os para que seja possível a remoção segura."
                ]), 500);
            }
            switch($e->getMessage()){
                case 'error_tipopedido':
                    return new Response(json_encode([
                        "description" => "Tipo de pedido não encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Excluir Tipo de Pedido!"
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/tipopedido/")
     */
    public function adminCadastrarTipoPedidoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('descricao') || $request->get('descricao') == '') {throw new \Exception('error_descricao');}

            $tipopedido = new TipoPedido();
            $tipopedido->setNome($request->get('nome'));
            $tipopedido->setDescricao($request->get('descricao'));
            $tipopedido->setAtivo('S');
            $em->persist($tipopedido);
            $em->flush();
            return new Response(json_encode([
                "description" => "Tipo de Pedido Cadastrado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_descricao':
                    return new Response(json_encode([
                        "description" => "Descrição não pode ser vazio!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Tipo de Pedido!"
            ]), 500);
        }
    }
}