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
            $departamento = $em->getRepository("AdminBundle:Departamento")->findOneById($request->get('id'));
            $departamento->setNome($request->get('nome'));
            $departamento->setDescricao($request->get('descricao'));
            $em->persist($departamento);
            $em->flush();
            return new Response(json_encode([
                "description" => "Departamento Editado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            return new Response(json_encode([
                "description" => "Erro ao Editar Departamento!"
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
            $departamento = new Departamento();
            $departamento->setNome($request->get('nome'));
            $departamento->setDescricao($request->get('descricao'));
            $departamento->setAtivo('S');
            $em->persist($departamento);
            $em->flush();
            return new Response(json_encode([
                "description" => "Departamento Cadastrado com Sucesso!"
            ]), 200);
        } catch (Exception $e) {
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Departamento!"
            ]), 500);
        }
    }
}