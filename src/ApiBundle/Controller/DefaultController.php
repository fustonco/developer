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
     * @Route("/v1/login/")
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
            $usuario->setTokenApp(trim($request->get('idonesignal')));
            $em->persist($usuario);
            $em->flush();

            return $this->setResponse(new Response($this->serializeJSON($usuario), 200));
        } catch(\Exception $e) {
            switch($e->getMessage()){
                case 'error_login':
                    return $this->setResponse(new Response($this->serializeJSON([
                        'error'         =>  true,
                        'description'   =>  'Usuário ou Senha incorretos'
                    ]), 418));
                break;
            }
            return $this->setResponse(new Response($this->serializeJSON([
                'error'         =>  true,
                'description'   =>  'Não foi possível efetuar login'
            ]), 418));
        }
    }

    /**
     * @Route("/teste-socket/")
     */
    public function sendSocketTesteAction()
    {
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_connect($socket, '127.0.0.1', 5020);
        $response = socket_send($socket, 'attIdSocket', 1, 0);
        socket_close($socket);
        return new Response($response, 200);
    }

    /**
     * @Route("/teste-push/")
     */
    public function sendPushTesteAction()
    {
        try{
            return new Response($this->sendPush(['ca08ab24-1b66-4a58-bf69-508a540a4865'], 'TESTE DA API', 'TESTANDO DA API'), 200);
        } catch(\Exception $e){
            return new Response($e, 500);
        }
    }

    public function sendPush($pushToken, $headings, $content){
        $content = ["en" => $content];
        $headings = ["en" => $headings];

        $fields = [
            'app_id' => "10d5d1a3-ab11-4b39-97e4-801924e0f330",
            'include_player_ids' => $pushToken,
            'contents' => $content,
            'headings' => $headings,
            'small_icon' => "ic_stat_onesignal_default",
            'android_accent_color' => "2196F3",
            'android_group' => 'Pdall',
        ];
        $fields = json_encode($fields);

        $auth = "NmViNWUyMWUtMTU5OS00ZjIzLTg5ZWYtYWNiZjk4MjU5MTc2";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic '.$auth));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $return["allresponses"] = $response;
        $return = json_encode($return);
        return $return;
    }
}
