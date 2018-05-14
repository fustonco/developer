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
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

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
     * @Route("/notificacoes/pedidos/")
     * @Method("POST")
     */
    public function notificacoesPedidosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $request->request->get("user");

        $notificacoes = array(
            "recebido" => 0,
            "contestado" => 0,
            "recusado" => 0,
            "aprovado" => 0,
            "proximos_pagamentos" => 0
        );

        $pedidos = $em->getConnection()->prepare("SELECT p.id, p.status, pc.status status_parcela, h.tipo_historico_id, h.idDe, h.idPara, f.idTipo tipo_funcionario,
        IF(pc.data_vencimento <= CURDATE(), TRUE, FALSE) vencimento,
        IF(pc.data_vencimento <= DATE_ADD(CURDATE(), INTERVAL 5 DAY), TRUE, FALSE) proximo
        FROM pedido p
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id
        INNER JOIN pagamento pg ON pg.idPedido = p.id
        INNER JOIN parcelas pc ON pc.idPagamento = pg.id
        INNER JOIN funcionario f ON f.id = ?
        WHERE visto = false
        AND (h.idDe = ? OR h.idPara = ?)");
        $pedidos->execute(array($user, $user, $user));
        $pedidos = $pedidos->fetchAll();

        foreach($pedidos as $p){
            if($p["tipo_funcionario"] == 3){
                if(empty($p["vencimento"]) && !empty($p["proximo"])){
                    $notificacoes["proximos_pagamentos"] = $notificacoes["proximos_pagamentos"] + 1;
                }
                else if(!empty($p["vencimento"])){
                    $notificacoes["recebido"] = $notificacoes["recebido"] + 1;
                }
            }
            else{
                if($p["idPara"] == $user && $p["tipo_historico_id"] == 2){
                    $notificacoes["contestado"] = $notificacoes["contestado"] + 1;
                }
                else if($p["idDe"] == $user && $p["status"] == 3){
                    $notificacoes["recusado"] = $notificacoes["recusado"] + 1;
                }
                else if($p["idDe"] == $user && $p["status"] == 4){
                    $notificacoes["aprovado"] = $notificacoes["aprovado"] + 1;
                }
                else if($p["idPara"] == $user && $p["status"] != 3){
                    $notificacoes["recebido"] = $notificacoes["recebido"] + 1;
                }
            }
        }

        return new Response(json_encode($notificacoes));
    }

    public function clearNotificacoesPedidos($em, $pedidos, $user, $type)
    {    
        $vistos = array();

        $type = $type == "para" ? "AND idPara = ?" : "AND idDe = ?";
        
        foreach($pedidos as $p){
            $vistos[] = $p["id"];
        }

        $vistos = join(", ", $vistos);

        $clearNotifications = $em->getConnection()->prepare("UPDATE historico SET visto = true
        WHERE idPedido IN (" . $vistos . ") " . $type);
        $clearNotifications->execute(array($user));
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

    /**
     * @Route("/teste-socket/")
     */
    public function sendSocketTesteAction()
    {
        $object = (object) ["property" => '1', "property2" => 2];
        $this->sendSocketFromPHP("sendTo", ["tYdd_m0d97PdgEjlAAAA", "TESTE", $object]);
    }

    public function sendSocketFromPHP($string_on, $data_array)
    {
        // $client = new Client(new Version2X("http://localhost:2020"));
        $client = new Client(new Version2X("http://149.56.74.5:2020"));
        $client->initialize();
        $client->emit($string_on, $data_array);
        $client->close();
    }
}
