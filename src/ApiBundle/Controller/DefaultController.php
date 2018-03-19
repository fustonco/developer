<?php

namespace ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultController extends Controller
{
    public function getContent($request)
    {
        $data = json_decode($request->getContent(), true);
        return !empty($data) ? $data : array();
    }

    public function setResponse($response)
    {
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    public function serializeJSON($entity) {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($entity, 'json');
        return $jsonContent;
    }

    /**
     * @Route("/v1/login")
     * @Method("POST")
     */
    public function v1LoginAction(Request $request)
    {
        $request->request->replace($this->getContent($request));
        try {
            $em = $this->getDoctrine()->getManager();

            $username = trim($request->get('username'));
            $password = md5(trim($request->get('password')));
            if(!$username || !$password) {throw new \Exception("error_login");}
            
            $usuario = $em->getRepository('ApiBundle:Funcionario')->findOneBy([
                'email' => $username,
                'senha' => $password
            ]);
            if(!$usuario) {throw new \Exception("error_login");}

            return $this->setResponse(new Response($this->serializeJSON($usuario), 200));
        } catch(\Exception $e) {
            switch($e->getMessage()){
                case 'error_login':
                    return $this->setResponse(new Response(json_encode([
                        'error'         =>  true,
                        'description'   =>  'Usuário ou Senha incorretos'
                    ]), 418));
                break;
            }
            return $this->setResponse(new Response(json_encode([
                'error'         =>  true,
                'description'   =>  'Não foi possível efetuar login'
            ]), 418));
        }
    }
}
