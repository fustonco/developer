<?php

namespace FinanceiroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use ApiBundle\Controller\DefaultController as ApiDefault;

use MasterBundle\Entity\Historico;
use MasterBundle\Entity\Mensagem;

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
     * @Route("/")
     */
    public function indexAction()
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $pedidos = $em->getConnection()->prepare("
        SELECT f.nome funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.data_pedido, p.descricao, sp.nome status, pc.valor, tp.nome tipo_pagamento
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = :para
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN pagamento pg ON pg.idPedido = p.id
        INNER JOIN tipo_pagamento tp ON pg.idTipo = tp.id
        INNER JOIN parcelas pc ON pc.idPagamento = pg.id
        WHERE p.status != 3
        AND pc.data_vencimento <= CURDATE()
        ORDER BY p.id DESC;
        ");
        $pedidos->bindValue("para", $this->getUser()->getId());
        $pedidos->execute();
        $pedidos = $pedidos->fetchAll();
        
        return $this->render("FinanceiroBundle:Default:index.html.twig", [
            'pedidos'  => $pedidos
        ]);
    }

    /**
     * @Route("/ver/")
     */
    public function funcionarioVerPedidoAction(Request $request)
    {
        $id = $request->get('id');
        try {
            $em = $this->getDoctrine()->getManager();

            $pedido = $em->getConnection()->prepare("
            SELECT p.id, p.codigo, f.nome para, fo.nome fornecedor, tu.nome tipo_para, tp.nome tipo, p.idFornecedor, p.data_pedido, p.valor, p.descricao, p.ativo, sp.nome status
            FROM pedido p 
            INNER JOIN tipo_pedido tp ON tp.id = p.idTipo
            INNER JOIN status_pedido sp ON sp.id = p.status
            LEFT JOIN historico h ON h.idPedido = p.id
            INNER JOIN funcionario f ON f.id = h.idPara
            INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
            LEFT JOIN fornecedor fo ON fo.id = p.idFornecedor
            WHERE p.id = ".$id."
            ORDER BY h.id DESC
            ");
            $pedido->execute();
            $pedido = $pedido->fetchAll();

            $historico = $em->getRepository('MasterBundle:Historico')->findByIdpedido($id);
            $historico = $this->serializeJSON($historico);

            return new Response(json_encode([
                'pedido' => $pedido,
                'historico' => json_decode($historico)
            ]), 200);
        } catch(\Exception $e) {
            return new Response(json_encode([
                "description" => "Erro ao Ver Pedido!"
            ]), 500);
        }
    }

    /**
     * @Route("/get/affecteds/")
     */
    public function getAffectedsPedidoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            if(!$request->get('id')) {throw new \Exception('error_id');}

            $afetados = $em->getConnection()->prepare("
            SELECT DISTINCT f.nome nomeFuncionario, f.id idFuncionario
            FROM pedido p
            INNER JOIN historico h ON h.idPedido = p.id
            INNER JOIN funcionario f ON f.id = h.idDe
            WHERE p.id = :pedido
            AND h.idDe != :eumesmo
            ");
            $afetados->bindValue("pedido", $request->get('id'));
            $afetados->bindValue("eumesmo", $this->getUser()->getId());
            $afetados->execute();
            $afetados = $afetados->fetchAll();
            
            return new Response(json_encode($afetados), 200);
        } catch(\Exception $e) {
            return new Response(json_encode([
                'description' => 'Erro ao pegar afetados'
            ]), 500);
        }
    }

    /**
     * @Route("/contestar/")
     */
    public function contestarPedidoAction(Request $request)
    {
        $hoje = date_create();
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            if(!$request->get('id')) {throw new \Exception('error_id');}
            if(!$request->get('mensagem')) {throw new \Exception('error_mensagem');}
            if(!$request->get('para')) {throw new \Exception('error_para');}

            $old_historico = $em->getRepository('MasterBundle:Historico')->findOneBy([
                'idpedido' => $request->get('id')
            ],[
                'id' => 'DESC'
            ]);

            $mensagem = new Mensagem();
            $mensagem->setMensagem($request->get('mensagem'));
            $em->persist($mensagem);
            $em->flush();

            $historico = new Historico();
            $historico->setCodigo($old_historico->getCodigo());
            $historico->setIdpedido($old_historico->getIdpedido());
            $de = $em->getRepository('MasterBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdde($de);
            $para = $em->getRepository('MasterBundle:Funcionario')->findOneById($request->get('para'));
            $historico->setIdpara($para);
            $historico->setDataPassagem($hoje);
            $historico->setIdmensagem($mensagem);
            $tipohistorico = $em->getRepository('MasterBundle:TipoHistorico')->findOneById(2);
            $historico->setTipoHistorico($tipohistorico);
            $em->persist($historico);
            $em->flush();

            $em->getConnection()->commit();

            $apibundle = new ApiDefault;
            $apibundle->sendPush([$para->getTokenApp()], 'Pedido Contestado', 'Seu pedido foi contestado');

            return new Response(json_encode([
                'description' => 'Pedido contestado com sucesso!'
            ]), 200);
        } catch(\Exception $e) {
            $em->getConnection()->rollBack();
            switch($e->getMessage()) {
                case 'error_id':
                    return new Response(json_encode([
                        'description' => 'ID incorreto'
                    ]), 500);
                break;
                case 'error_mensagem':
                    return new Response(json_encode([
                        'description' => 'É necessario ter uma mensagem!'
                    ]), 500);
                break;
                case 'error_para':
                    return new Response(json_encode([
                        'description' => 'Precisa escolher para quem enviar!'
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                'description' => 'Não foi possivel contestar o Pedido!'
            ]), 500);
        }
    }
}
