<?php

namespace FinanceiroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $pedidos = $em->getConnection()->prepare("
        SELECT f.nome funcionario, tu.id id_tipo_funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.idTipo, p.idFornecedor, p.data_pedido dataPedido, p.valor, p.descricao, p.ativo, sp.nome status
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = :para
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        WHERE p.status != 3
        ORDER BY p.id DESC;
        ");
        $pedidos->bindValue("para", $this->getUser()->getId());
        $pedidos->execute();
        $pedidos = $pedidos->fetchAll();
        
        return $this->render("FinanceiroBundle:Default:index.html.twig", [
            'pedidos'  => $pedidos
        ]);
    }
}
