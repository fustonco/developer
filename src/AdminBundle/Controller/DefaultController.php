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
    
}