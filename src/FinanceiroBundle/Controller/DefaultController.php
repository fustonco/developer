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

use FinanceiroBundle\Entity\Historico;
use FinanceiroBundle\Entity\Mensagem;
use FinanceiroBundle\Entity\DataParcial;

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
    public function indexAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();

        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = " AND p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        $pedidosAprovados = $em->getConnection()->prepare("SELECT COUNT(id) total FROM historico WHERE idPara = ? AND tipo_historico_id = 3 AND DATE(data_passagem) = CURDATE()");
        $pedidosAprovados->execute(array($this->getUser()->getId()));
        $pedidosAprovados = $pedidosAprovados->fetch();
        $pedidosAprovados = $pedidosAprovados["total"];

        $pedidosRecusados = $em->getConnection()->prepare("SELECT COUNT(id) total FROM historico WHERE idDe = ? AND tipo_historico_id = 2 AND DATE(data_passagem) = CURDATE()");
        $pedidosRecusados->execute(array($this->getUser()->getId()));
        $pedidosRecusados = $pedidosRecusados->fetch();
        $pedidosRecusados = $pedidosRecusados["total"];

        $pedidosPagos = $em->getConnection()->prepare("SELECT COUNT(p.id) total FROM pedido p
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = ?
        INNER JOIN pagamento pg ON pg.idPedido = p.id
        INNER JOIN parcelas pc ON pc.idPagamento = pg.id
        WHERE p.status = 2 AND pc.data_pagamento = CURDATE()");
        $pedidosPagos->execute(array($this->getUser()->getId()));
        $pedidosPagos = $pedidosPagos->fetch();
        $pedidosPagos = $pedidosPagos["total"];

        $pedidosPendentes = $em->getConnection()->prepare("SELECT COUNT(p.id) total FROM pedido p
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = ?
        INNER JOIN pagamento pg ON pg.idPedido = p.id
        INNER JOIN parcelas pc ON pc.idPagamento = pg.id
        WHERE p.status = 4 AND pc.data_vencimento = CURDATE()");
        $pedidosPendentes->execute(array($this->getUser()->getId()));
        $pedidosPendentes = $pedidosPendentes->fetch();
        $pedidosPendentes = $pedidosPendentes["total"];

        $pedidos = $em->getConnection()->prepare("
        SELECT fo.nome fornecedor, f.nome funcionario, p.id, pc.id parcela, p.codigo, pc.data_vencimento, sp.nome status, pc.valor, tp.nome tipo_pagamento
        FROM pedido p
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = :para
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN pagamento pg ON pg.idPedido = p.id
        INNER JOIN tipo_pagamento tp ON pg.idTipo = tp.id
        INNER JOIN parcelas pc ON pc.idPagamento = pg.id
        WHERE p.status = 4 AND pc.status = 1
        AND pc.data_vencimento <= CURDATE()
        ".$str."
        ORDER BY p.id DESC");
        $pedidos->bindValue("para", $this->getUser()->getId());
        $pedidos->execute();
        $pedidos = $pedidos->fetchAll();

        $api = new ApiDefault;
        $api->clearNotificacoesPedidos($em, $pedidos, $this->getUser()->getId(), "para");
        
        return $this->render("FinanceiroBundle:Default:index.html.twig", [
            'pedidos'  => $pedidos,
            'pedidosAprovados' => $pedidosAprovados,
            'pedidosRecusados' => $pedidosRecusados,
            'pedidosPagos' => $pedidosPagos,
            'pedidosPendentes' => $pedidosPendentes,
            'de' => $de,
            'ate' => $ate
        ]);
    }
    
    /**
     * @Route("/pagamentos-aprovados/")
     */
    public function pagamentosAprovadosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = " AND p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        // SELECT f.nome funcionario, p.id, pc.id parcela, p.codigo, pc.data_vencimento, sp.nome status, pc.valor, tp.nome tipo_pagamento, h.data_passagem data_aprovacao
        $pedidos = $em->getConnection()->prepare("
        SELECT fo.nome fornecedor, f.nome funcionario, p.id, pc.id parcela, p.codigo, pc.data_vencimento, sp.nome status, pc.valor, tp.nome tipo_pagamento, h.data_passagem data_aprovacao
        FROM pedido p
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = :para
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN pagamento pg ON pg.idPedido = p.id
        INNER JOIN tipo_pagamento tp ON pg.idTipo = tp.id
        INNER JOIN parcelas pc ON pc.idPagamento = pg.id
        WHERE p.status = 4 AND pc.status = 1
        ".$str."
        ORDER BY p.id DESC");
        $pedidos->bindValue("para", $this->getUser()->getId());
        $pedidos->execute();
        $pedidos = $pedidos->fetchAll();
        
        return $this->render("FinanceiroBundle:Default:pagamentos-aprovados.html.twig", [
            'pedidos'  => $pedidos,
            'de' => $de,
            'ate' => $ate
        ]);
    }

    /**
     * @Route("/pagamentos-efetuados/")
     */
    public function pagamentosEfetuadosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = " AND p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        // SELECT f.nome funcionario, p.id, pc.id parcela, p.codigo, pc.data_pagamento, sp.nome status, pc.valor, tp.nome tipo_pagamento
        $pagamentos = $em->getConnection()->prepare("
        SELECT fo.nome fornecedor, f.nome funcionario, p.id, pc.id parcela, p.codigo, pc.data_pagamento, pc.data_vencimento, sp.nome status, pc.valor, tp.nome tipo_pagamento, h.data_passagem data_aprovacao
        FROM pedido p
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = :para
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN pagamento pg ON pg.idPedido = p.id
        INNER JOIN tipo_pagamento tp ON pg.idTipo = tp.id
        INNER JOIN parcelas pc ON pc.idPagamento = pg.id
        WHERE pc.status = 2
        ".$str."
        ORDER BY p.id DESC");
        $pagamentos->bindValue("para", $this->getUser()->getId());
        $pagamentos->execute();
        $pagamentos = $pagamentos->fetchAll();

        return $this->render("FinanceiroBundle:Default:pagamentos-efetuados.html.twig", array(
            "pagamentos" => $pagamentos,
            'de' => $de,
            'ate' => $ate
        ));
    }

    /**
     * @Route("/proximos-pagamentos/")
     */
    public function proximosPagamentosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = " AND p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        $pedidos = $em->getConnection()->prepare("
        SELECT fo.nome fornecedor, f.nome funcionario, p.id, pc.id parcela, p.codigo, pc.data_vencimento, sp.nome status, pc.valor, tp.nome tipo_pagamento, h.data_passagem data_aprovacao
        FROM pedido p
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = :para
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN pagamento pg ON pg.idPedido = p.id
        INNER JOIN tipo_pagamento tp ON pg.idTipo = tp.id
        INNER JOIN parcelas pc ON pc.idPagamento = pg.id
        WHERE p.status = 4 AND pc.status = 1
        ".$str."
        AND pc.data_vencimento > CURDATE()
        AND pc.data_vencimento <= DATE_ADD(CURDATE(), INTERVAL 5 DAY)
        ORDER BY p.id DESC");
        $pedidos->bindValue("para", $this->getUser()->getId());
        $pedidos->execute();
        $pedidos = $pedidos->fetchAll();

        $api = new ApiDefault;
        $api->clearNotificacoesPedidos($em, $pedidos, $this->getUser()->getId(), "para");
        
        return $this->render("FinanceiroBundle:Default:proximos-pagamentos.html.twig", [
            'pedidos'  => $pedidos,
            'de' => $de,
            'ate' => $ate
        ]);
    }

    /**
     * @Route("/ver/")
     */
    public function funcionarioVerPedidoAction(Request $request)
    {
        $default = new DefaultController;
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        try {

            $pedido = $em->getConnection()->prepare("
            SELECT p.id, p.codigo, f.nome para, fo.nome fornecedorNome, fo.cnpj fornecedorCnpj, fo.cpf fornecedorCpf, fo.telefone fornecedorTelefone, fo.endereco fornecedorEndereco, tu.nome tipo_para, tp.nome tipo, p.idFornecedor, p.data_pedido, p.valor, p.descricao, p.ativo, sp.nome status, m.mensagem, e.nome empresa
            FROM pedido p 
            INNER JOIN tipo_pedido tp ON tp.id = p.idTipo
            INNER JOIN status_pedido sp ON sp.id = p.status
            INNER JOIN empresa e ON p.idEmpresa = e.id
            LEFT JOIN historico h ON h.idPedido = p.id
            LEFT JOIN mensagem m ON h.idMensagem = m.id
            INNER JOIN funcionario f ON f.id = h.idPara
            INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
            INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
            WHERE p.id = ".$id."
            ORDER BY h.id DESC
            ");
            $pedido->execute();
            $pedido = $pedido->fetchAll();

            $pagamentos = $em->getConnection()->prepare("SELECT pg.id, t.id tipo_id, t.nome tipo, pg.valor_integral valor, pc.parcelas parcelas_total, IF(pcp.parcelas, pcp.parcelas, 0) parcelas_pagas, c.titulo, c.numero, c.bandeira, DATE_FORMAT(c.vencimento, '%d/%m/%Y') vencimento, DATE_FORMAT(c.melhor_data, '%d/%m/%Y') melhor_data, c.cvc, co.banco, co.agencia, co.conta, co.cpf, co.cnpj, ct.nome conta_tipo FROM pagamento pg
            INNER JOIN tipo_pagamento t ON pg.idTipo = t.id
            INNER JOIN pedido p ON idPedido = p.id
            LEFT JOIN (SELECT idPagamento, count(id) parcelas FROM parcelas GROUP BY idPagamento) pc ON pc.idPagamento = pg.id
            LEFT JOIN (SELECT idPagamento, count(id) parcelas FROM parcelas WHERE status = 2 GROUP BY idPagamento) pcp ON pcp.idPagamento = pg.id
            LEFT JOIN cartao c ON pg.cartao = c.id
            LEFT JOIN conta co ON pg.conta = co.id
            LEFT JOIN conta_tipo ct ON co.tipo = ct.id
            WHERE idPedido = ?");
            $pagamentos->execute(array($id));
            $pagamentos = $pagamentos->fetchAll();

            $historico = $em->getRepository('ChefeBundle:Historico')->findByIdpedido($id);
            $historico = $default->serializeJSON($historico);

            for($i = 0; $i < count($pagamentos); $i = $i + 1){
                $parcelas = $em->getConnection()->prepare("SELECT p.id, num_parcela, valor, valor_pago, valor_pendente, valor_desconto, valor_acrecimo, DATE_FORMAT(data_vencimento, '%d/%m/%Y') data_vencimento, s.nome status, s.id status_id FROM parcelas p
                INNER JOIN status_parcela s ON p.status = s.id
                WHERE idPagamento = ? AND p.data_vencimento <= CURDATE()");
                $parcelas->execute(array($pagamentos[$i]["id"]));
                $pagamentos[$i]["parcelas"] = $parcelas->fetchAll();
            }

            $anexos = $em->getConnection()->prepare("SELECT a.caminho
            FROM pedido_anexo pa
            INNER JOIN anexo a ON a.id = pa.idAnexo
            WHERE pa.idPedido = ?");
            $anexos->execute(array($id));
            $anexos = $anexos->fetchAll();

            return new Response(json_encode([
                'pedido' => $pedido,
                'historico' => json_decode($historico),
                'pagamentos' => $pagamentos,
                'anexos' => $anexos
            ]), 200);
        } catch(Exception $e) {
            return new Response(json_encode([
                "description" => "Erro ao Ver Pedido!"
            ]), 500);
        }
    }

    /**
     * @Route("/ver-proximos/")
     */
    public function funcionarioVerProximosPedidoAction(Request $request)
    {
        $default = new DefaultController;
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        try {

            // SELECT p.id, p.codigo, f.nome para, fo.nome fornecedor, tu.nome tipo_para, tp.nome tipo, p.idFornecedor, p.data_pedido, p.valor, p.descricao, p.ativo, sp.nome status, m.mensagem, e.nome empresa
            $pedido = $em->getConnection()->prepare("
            SELECT p.id, p.codigo, f.nome para, fo.nome fornecedorNome, fo.cnpj fornecedorCnpj, fo.cpf fornecedorCpf, fo.telefone fornecedorTelefone, fo.endereco fornecedorEndereco, tu.nome tipo_para, tp.nome tipo, p.idFornecedor, p.data_pedido, p.valor, p.descricao, p.ativo, sp.nome status, m.mensagem, e.nome empresa
            FROM pedido p 
            INNER JOIN tipo_pedido tp ON tp.id = p.idTipo
            INNER JOIN status_pedido sp ON sp.id = p.status
            INNER JOIN empresa e ON p.idEmpresa = e.id
            LEFT JOIN historico h ON h.idPedido = p.id
            LEFT JOIN mensagem m ON h.idMensagem = m.id
            INNER JOIN funcionario f ON f.id = h.idPara
            INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
            LEFT JOIN fornecedor fo ON fo.id = p.idFornecedor
            WHERE p.id = ".$id."
            ORDER BY h.id DESC
            ");
            $pedido->execute();
            $pedido = $pedido->fetchAll();

            $pagamentos = $em->getConnection()->prepare("SELECT pg.id, t.nome tipo, pg.valor_integral valor, pc.parcelas parcelas_total, IF(pcp.parcelas, pcp.parcelas, 0) parcelas_pagas, c.titulo, c.numero, c.bandeira, DATE_FORMAT(c.vencimento, '%d/%m/%Y') vencimento, DATE_FORMAT(c.melhor_data, '%d/%m/%Y') melhor_data, c.cvc, co.banco, co.agencia, co.conta, co.cpf, co.cnpj, ct.nome conta_tipo FROM pagamento pg
            INNER JOIN tipo_pagamento t ON pg.idTipo = t.id
            INNER JOIN pedido p ON idPedido = p.id
            LEFT JOIN (SELECT idPagamento, count(id) parcelas FROM parcelas GROUP BY idPagamento) pc ON pc.idPagamento = pg.id
            LEFT JOIN (SELECT idPagamento, count(id) parcelas FROM parcelas WHERE status = 2 GROUP BY idPagamento) pcp ON pcp.idPagamento = pg.id
            LEFT JOIN cartao c ON pg.cartao = c.id
            LEFT JOIN conta co ON pg.conta = co.id
            LEFT JOIN conta_tipo ct ON co.tipo = ct.id
            WHERE idPedido = ?");
            $pagamentos->execute(array($id));
            $pagamentos = $pagamentos->fetchAll();

            for($i = 0; $i < count($pagamentos); $i = $i + 1){
                $parcelas = $em->getConnection()->prepare("SELECT p.id, num_parcela, valor, valor_pago, valor_pendente, valor_desconto, valor_acrecimo, DATE_FORMAT(data_vencimento, '%d/%m/%Y') data_vencimento, s.nome status, s.id status_id FROM parcelas p
                INNER JOIN status_parcela s ON p.status = s.id
                WHERE idPagamento = ? AND p.data_vencimento > CURDATE() AND p.data_vencimento <= DATE_ADD(CURDATE(), INTERVAL 5 DAY)");
                $parcelas->execute(array($pagamentos[$i]["id"]));
                $pagamentos[$i]["parcelas"] = $parcelas->fetchAll();
            }

            $historico = $em->getRepository('FinanceiroBundle:Historico')->findByIdpedido($id);
            $historico = $default->serializeJSON($historico);

            $anexos = $em->getConnection()->prepare("SELECT a.caminho
            FROM pedido_anexo pa
            INNER JOIN anexo a ON a.id = pa.idAnexo
            WHERE pa.idPedido = ?");
            $anexos->execute(array($id));
            $anexos = $anexos->fetchAll();

            return new Response(json_encode([
                'pedido' => $pedido,
                'pagamentos' => $pagamentos,
                'historico' => json_decode($historico),
                'anexos' => $anexos
            ]), 200);
        } catch(Exception $e) {
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

            $old_historico = $em->getRepository('FinanceiroBundle:Historico')->findOneBy([
                'idpedido' => $request->get('id')
            ],[
                'id' => 'DESC'
            ]);

            $pagamento = $em->getRepository("FinanceiroBundle:Pagamento")->findOneByIdpedido($request->get('id'));
            $parcelas = $em->getRepository("FinanceiroBundle:Parcelas")->findBy(array(
                "idpagamento" => $pagamento,
                "status" => 2
            ));

            if(count($parcelas) > 0){
                throw new \Exception("error_parcel");
            }

            $mensagem = new Mensagem();
            $mensagem->setMensagem($request->get('mensagem'));
            $em->persist($mensagem);
            $em->flush();

            $historico = new Historico();
            $historico->setCodigo($old_historico->getCodigo());
            $historico->setIdpedido($old_historico->getIdpedido());
            $de = $em->getRepository('FinanceiroBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdde($de);
            $para = $em->getRepository('FinanceiroBundle:Funcionario')->findOneById($request->get('para'));
            $historico->setIdpara($para);
            $historico->setDataPassagem($hoje);
            $historico->setIdmensagem($mensagem);
            $historico->setVisto(0);
            $tipohistorico = $em->getRepository('FinanceiroBundle:TipoHistorico')->findOneById(2);
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
                case 'error_parcel':
                    return new Response(json_encode([
                        'description' => 'Não é possível contestar um pedido com parcelas já pagas'
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                'description' => 'Não foi possivel contestar o Pedido!'
            ]), 500);
        }
    }

    /**
     * @Route("/confirm/")
     */
    public function confirmPedidoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();

        $parcel = $request->request->get("parcel");
        $variationValue = $request->request->get("variation_value");
        $variationType = $request->request->get("variation_type");
        $value = $request->request->get("value");
        $description = $request->request->get("description");

        try{
            if(empty($parcel) || empty($value)){
                throw new \Exception("Dados incompletos", 500);
            }

            $parcel = $em->getRepository("FinanceiroBundle:Parcelas")->findOneById($parcel);

            if(empty($parcel) || $parcel->getStatus()->getId() != 1){
                throw new \Exception("Parcela não encontrada", 404);
            }

            $valueParcel = str_replace(array(".", ","), array("", "."), $parcel->getValor() ? $parcel->getValor() : 0);
            $valueFormatted = str_replace(array(".", ","), array("", "."), $value);
            $valuePayed = str_replace(array(".", ","), array("", "."), $parcel->getValorPago() ? $parcel->getValorPago() : 0);
            $valuePending = str_replace(array(".", ","), array("", "."), $parcel->getValorPendente() ? $parcel->getValorPendente() : 0);

            if($valueFormatted > $valueParcel){
                throw new \Exception("O valor pago deve ser menor ou igual o valor da parcela", 500);
            }

            $valuePayed = $valuePayed + $valueFormatted;
            $valuePending = $valuePending - $valueFormatted;

            if($valuePayed > $valueParcel){
                throw new \Exception("O valor pago não deve ultrapassar o montante de R$ " . number_format($valuePending + $valueFormatted, 2, ",", "."), 500);
            }

            if($valuePending > 0){
                $dataParcial = new DataParcial;
                $dataParcial->setValor($value);
                $dataParcial->setDataPagamento(new \DateTime);
                $dataParcial->setIdparcela($parcel);

                $em->persist($dataParcial);
            }
            else{
                $parcel->setDataPagamento(new \DateTime);
                $parcel->setStatus($em->getRepository("FinanceiroBundle:StatusParcela")->findOneById(2));
            }

            $parcel->setValorPago(number_format($valuePayed, 2, ",", "."));
            $parcel->setValorPendente(number_format($valuePending, 2, ",", "."));

            if(!empty($variationValue)){
                if(empty($description)){
                    throw new \Exception("É necessário uma mensagem", 500);
                }

                $parcel->setMensagem($description);

                if($variationType == 1){
                    $parcel->setValorAcrecimo($variationValue);
                }
                else if($variationType == 0){
                    $parcel->setValorDesconto($variationValue);
                }
                else{
                    throw new \Exception("É necessário informar o tipo de variação", 500);
                }
            }

            $em->flush();

            $checkPagamento = $em->getRepository("FinanceiroBundle:Parcelas")->findBy(array(
                "idpagamento" => $parcel->getIdpagamento(),
                "status" => 1
            ));

            if(count($checkPagamento) == 0){
                $parcel->getIdpagamento()->setStatus($em->getRepository("FinanceiroBundle:StatusPagamento")->findOneById(2));

                $em->flush();
            }

            $checkPedido = $em->getRepository("FinanceiroBundle:Pagamento")->findBy(array(
                "idpedido" => $parcel->getIdpagamento()->getIdpedido(),
                "idstatus" => 1
            ));

            if(count($checkPedido) == 0){
                $parcel->getIdpagamento()->getIdpedido()->setStatus($em->getRepository("FinanceiroBundle:StatusPedido")->findOneById(2));

                $em->flush();
            }

            $em->getConnection()->commit();
            return new Response("success");
        }
        catch(\Exception $e){
            $message = !empty($e->getMessage()) ? $e->getMessage() : "Ocorreu um erro";
            $status = !empty($e->getCode()) ? $e->getCode() : 500;

            $em->getConnection()->rollBack();
            return new Response($message, $status);
        }
    }
}
