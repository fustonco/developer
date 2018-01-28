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
use AdminBundle\Entity\Departamento;

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
        return $this->render("AdminBundle:Default:index.html.twig");
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
}