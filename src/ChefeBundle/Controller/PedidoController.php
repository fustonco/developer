<?php

namespace ChefeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ChefeBundle\Entity\Pedido;
use ChefeBundle\Entity\Historico;
use ChefeBundle\Entity\Pagamento;
use ChefeBundle\Entity\Parcelas;
use ChefeBundle\Entity\Mensagem;
use ChefeBundle\Entity\PedidoAnexo;
use ChefeBundle\Entity\Anexo;
use ChefeBundle\Entity\Conta;
use ChefeBundle\Form\PedidoType;
use ApiBundle\Controller\DefaultController as ApiDefault;

/**
 * Pedido controller.
 *
 * @Route("/pedido")
 */
class PedidoController extends Controller
{

    /**
     * Lists all Pedido entities.
     *
     * @Route("/", name="chefe_pedido")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = " AND p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        $entities = $em->getConnection()->prepare("
        SELECT sp.id id_status_pedido, th.nome status, e.nome empresa, f.nome funcionario, f.id idFuncionario, fo.nome fornecedor, tu.id id_tipo_funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.idTipo, p.idFornecedor, p.data_pedido dataPedido, p.valor, p.descricao, p.ativo, sp.nome hStatus
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id
        INNER JOIN tipo_historico th ON th.id = h.tipo_historico_id
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN empresa e ON e.id = p.idEmpresa
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        WHERE p.criado_por = :criado_por
        ".$str."
        ORDER BY p.id DESC
        ");
        $entities->bindValue("criado_por", $this->getUser()->getId());
        $entities->execute();
        $entities = $entities->fetchAll();

        return array(
            'entities' => $entities,
            'de' => $de,
            'ate' => $ate
        );
    }

    /**
     * Lists all Pedido entities.
     *
     * @Route("/recebido", name="chefe_pedido_recebido")
     * @Template()
     */
    public function indexRecebidoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = " AND p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        $entities = $em->getConnection()->prepare("
        SELECT sp.id id_status_pedido, th.nome status, e.nome empresa, f.nome funcionario, f.id idFuncionario, fo.nome fornecedor, tu.id id_tipo_funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.idTipo, p.idFornecedor, p.data_pedido dataPedido, p.valor, p.descricao, p.ativo, sp.nome hStatus
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = :para
        INNER JOIN tipo_historico th ON th.id = h.tipo_historico_id
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN empresa e ON p.idEmpresa = e.id
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        WHERE p.status != 3
        ".$str."
        ORDER BY p.id DESC;
        ");
        $entities->bindValue("para", $this->getUser()->getId());
        $entities->execute();
        $entities = $entities->fetchAll();

        $api = new ApiDefault;
        $api->clearNotificacoesPedidos($em, $entities, $this->getUser()->getId(), "para");

        return array(
            'entities' => $entities,
            'de' => $de,
            'ate' => $ate
        );
    }

    /**
     * Lists all Pedido entities.
     *
     * @Route("/recusados", name="chefe_pedido_recusados")
     * @Template()
     */
    public function indexRecusadosAction(Request $request)
    { 
        $em = $this->getDoctrine()->getManager();

        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = " AND p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        $entities = $em->getConnection()->prepare("
        SELECT sp.id id_status_pedido, th.nome status, e.nome empresa, f.nome funcionario, f.id idFuncionario, fo.nome fornecedor, tu.id id_tipo_funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.idTipo, p.idFornecedor, p.data_pedido dataPedido, p.valor, p.descricao, p.ativo, sp.nome hStatus
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idDe = :de
        INNER JOIN tipo_historico th ON th.id = h.tipo_historico_id
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN empresa e ON p.idEmpresa = e.id
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        WHERE p.status = 3
        ".$str."
        ORDER BY p.id DESC;
        ");
        $entities->bindValue("de", $this->getUser()->getId());
        $entities->execute();
        $entities = $entities->fetchAll();

        return array(
            'entities' => $entities,
            'de' => $de,
            'ate' => $ate
        );
    }

    /**
     * Lists all Pedido entities.
     *
     * @Route("/recusado", name="chefe_pedido_recusado")
     * @Template()
     */
    public function indexRecusadoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = " AND p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        $entities = $em->getConnection()->prepare("
        SELECT sp.id id_status_pedido, th.nome status, e.nome empresa, f.nome funcionario, f.id idFuncionario, fo.nome fornecedor, tu.id id_tipo_funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.idTipo, p.idFornecedor, p.data_pedido dataPedido, p.valor, p.descricao, p.ativo, sp.nome hStatus
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = :para
        INNER JOIN tipo_historico th ON th.id = h.tipo_historico_id
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN empresa e ON p.idEmpresa = e.id
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        WHERE p.status = 3
        ".$str."
        ORDER BY p.id DESC;
        ");
        $entities->bindValue("para", $this->getUser()->getId());
        $entities->execute();
        $entities = $entities->fetchAll();

        $api = new ApiDefault;
        $api->clearNotificacoesPedidos($em, $entities, $this->getUser()->getId(), "para");

        return array(
            'entities' => $entities,
            'de' => $de,
            'ate' => $ate
        );
    }

    /**
     * Lists all Pedido entities.
     *
     * @Route("/contestado", name="chefe_pedido_contestado")
     * @Template()
     */
    public function indexContestadoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = " AND p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        $entities = $em->getConnection()->prepare("
        SELECT sp.id id_status_pedido, th.nome status, e.nome empresa, f.nome funcionario, f.id idFuncionario, fo.nome fornecedor, tu.id id_tipo_funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.idTipo, p.idFornecedor, p.data_pedido dataPedido, p.valor, p.descricao, p.ativo, sp.nome hStatus
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = :para
        INNER JOIN tipo_historico th ON th.id = h.tipo_historico_id
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN empresa e ON e.id = p.idEmpresa
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        WHERE h.tipo_historico_id = 2
        ".$str."
        ORDER BY p.id DESC
        ");
        $entities->bindValue("para", $this->getUser()->getId());
        $entities->execute();
        $entities = $entities->fetchAll();

        $api = new ApiDefault;
        $api->clearNotificacoesPedidos($em, $entities, $this->getUser()->getId(), "para");

        return array(
            'entities' => $entities,
            'de' => $de,
            'ate' => $ate
        );
    }

    /**
     * Lists all Pedido entities.
     *
     * @Route("/aprovados", name="chefe_pedido_aprovados")
     * @Template()
     */
    public function indexAprovadosAction(Request $request)
    { 
        $em = $this->getDoctrine()->getManager();

        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = " AND p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        $entities = $em->getConnection()->prepare("
        SELECT sp.id id_status_pedido, th.nome status, e.nome empresa, f.nome funcionario, f.id idFuncionario, fo.nome fornecedor, tu.id id_tipo_funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.idTipo, p.idFornecedor, p.data_pedido dataPedido, p.valor, p.descricao, p.ativo, sp.nome hStatus
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id
        INNER JOIN tipo_historico th ON th.id = h.tipo_historico_id
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN empresa e ON p.idEmpresa = e.id
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        WHERE p.status = 4 OR p.status = 2
        ".$str."
        ORDER BY p.id DESC;
        ");
        $entities->execute();
        $entities = $entities->fetchAll();

        return array(
            'entities' => $entities,
            'de' => $de,
            'ate' => $ate
        );
    }

    /**
     * Lists all Pedido entities.
     *
     * @Route("/aprovado", name="chefe_pedido_aprovado")
     * @Template()
     */
    public function indexAprovadoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = " AND p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        $entities = $em->getConnection()->prepare("
        SELECT sp.id id_status_pedido, th.nome status, e.nome empresa, f.nome funcionario, f.id idFuncionario, fo.nome fornecedor, tu.id id_tipo_funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.idTipo, p.idFornecedor, p.data_pedido dataPedido, p.valor, p.descricao, p.ativo, sp.nome hStatus
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id
        INNER JOIN tipo_historico th ON th.id = h.tipo_historico_id
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN empresa e ON p.idEmpresa = e.id
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        WHERE p.status = 4
        ".$str."
        AND p.criado_por = :criado_por
        ORDER BY p.id DESC;
        ");
        $entities->bindValue("criado_por", $this->getUser()->getId());
        $entities->execute();
        $entities = $entities->fetchAll();

        $api = new ApiDefault;
        $api->clearNotificacoesPedidos($em, $entities, $this->getUser()->getId(), "de");

        return array(
            'entities' => $entities,
            'de' => $de,
            'ate' => $ate
        );
    }

    /**
     * @Route("/ver/")
     */
    public function funcionarioVerPedidoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $default = new DefaultController;
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

            $historico = $em->getRepository('ChefeBundle:Historico')->findByIdpedido($id);
            $historico = $default->serializeJSON($historico);

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

            for($i = 0; $i < count($pagamentos); $i = $i + 1){
                $parcelas = $em->getConnection()->prepare("SELECT p.id, num_parcela, valor, valor_pago, valor_pendente, valor_desconto, valor_acrecimo, DATE_FORMAT(data_vencimento, '%d/%m/%Y') data_vencimento, s.nome status, s.id status_id FROM parcelas p
                INNER JOIN status_parcela s ON p.status = s.id
                WHERE idPagamento = ?");
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
        } catch(\Exception $e) {
            return new Response(json_encode([
                "description" => "Erro ao Ver Pedido!"
            ]), 500);
        }
    }

    /**
     * @Route("/excluir/")
     */
    public function funcionarioExcluirPedidoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            $pedido = $em->getRepository('ChefeBundle:Pedido')->findOneById($request->get('id'));
            if($pedido && $pedido->getCodigo()) { throw new \Exception("error_pedido"); }

            $pagamento = $em->getConnection()->prepare("SELECT * FROM pagamento WHERE idPedido = ".$request->get('id'));
            $pagamento->execute();
            $pagamento = $pagamento->fetchAll();
            for($i = 0; $i < count($pagamento); $i++) {
                $entities = $em->getConnection()->prepare("DELETE FROM parcelas WHERE idPagamento = ".$pagamento[$i]["id"]);
                $entities->execute();
            }
            $pagamento = $em->getConnection()->prepare("DELETE FROM pagamento WHERE idPedido = ".$request->get('id'));
            $pagamento->execute();

            $historico = $em->getConnection()->prepare("SELECT * FROM historico WHERE idPedido = ".$request->get('id'));
            $historico->execute();
            $historico = $historico->fetchAll();
            $entities = $em->getConnection()->prepare("DELETE FROM historico WHERE idPedido = ".$request->get('id'));
            $entities->execute();
            for($i = 0; $i < count($historico); $i++) {
                if($historico[$i]["idMensagem"]) {
                    $entities = $em->getConnection()->prepare("DELETE FROM mensagem WHERE id = ".$historico[$i]["idMensagem"]);
                    $entities->execute();
                }
            }

            $pedido_anexo = $em->getConnection()->prepare("SELECT * FROM pedido_anexo WHERE idPedido = ".$request->get('id'));
            $pedido_anexo->execute();
            $pedido_anexo = $pedido_anexo->fetchAll();
            $entities = $em->getConnection()->prepare("DELETE FROM pedido_anexo WHERE idPedido = ".$request->get('id'));
            $entities->execute();
            for($i = 0; $i < count($pedido_anexo); $i++) {
                if($pedido_anexo[$i]["idAnexo"]) {
                    $entities = $em->getConnection()->prepare("DELETE FROM anexo WHERE id = ".$pedido_anexo[$i]["idAnexo"]);
                    $entities->execute();
                }
            }

            $entities = $em->getConnection()->prepare("DELETE FROM pedido WHERE id = ".$request->get('id'));
            $entities->execute();
            $em->getConnection()->commit();
            return new Response(json_encode([
                "description" => "Pedido Excluido com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();
            if(strpos($e->getMessage(), 'FOREIGN')) {
                return new Response(json_encode([
                    "description" => "Não foi possível excluir esse Pedido, pois existe registros nele, por favor altere-os para que seja possível a remoção segura."
                ]), 500);
            }
            return new Response(json_encode([
                "description" => "Erro ao Excluir Pedido!"
            ]), 500);
        }
    }

    /**
     * Creates a new Pedido entity.
     *
     * @Route("/", name="chefe_pedido_create")
     * @Method("POST")
     * @Template("ChefeBundle:Pedido:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Pedido();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('chefe_pedido_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity
            // 'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Pedido entity.
     *
     * @param Pedido $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Pedido $entity)
    {
        // $form = $this->createForm(new PedidoType(), $entity, array(
        //     'action' => $this->generateUrl('chefe_pedido_create'),
        //     'method' => 'POST',
        // ));

        // $form->add('submit', 'submit', array('label' => 'Create'));

        // return $form;
    }

    /**
     * Displays a form to create a new Pedido entity.
     *
     * @Route("/new", name="chefe_pedido_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Pedido();
        // $form   = $this->createCreateForm($entity);

        $em = $this->getDoctrine()->getManager();
        $tipopedido = $em->getRepository('ChefeBundle:TipoPedido')->findBy([], [
            'nome' => 'ASC'
        ]);
        $empresa = $em->getRepository('FuncionarioBundle:Empresa')->findBy([], [
            'nome' => 'ASC'
        ]);
        $tipopagamento = $em->getRepository('ChefeBundle:TipoPagamento')->findBy([], [
            'nome' => 'ASC'
        ]);
        $fornecedores = $em->getRepository('ChefeBundle:Fornecedor')->findBy([], [
            'nome' => 'ASC'
        ]);
        $para = $em->getRepository('ChefeBundle:Funcionario')->findBy([
            'idtipo' => 5
        ], [
            'nome' => 'ASC'
        ]);

        $contaTipo = $em->getRepository('ChefeBundle:ContaTipo')->findAll();
        $cartao = $em->getRepository('ChefeBundle:Cartao')->findBy(['active' => true], [
            'titulo' => 'ASC'
        ]);

        return array(
            'entity' => $entity,
            'empresa' => $empresa,
            'tipopedido' => $tipopedido,
            'tipopagamento' => $tipopagamento,
            'fornecedores' => $fornecedores,
            'para' => $para,
            'contaTipo' => $contaTipo,
            'cartao' => $cartao
        );
    }

    /**
     * Finds and displays a Pedido entity.
     *
     * @Route("/{id}", name="chefe_pedido_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChefeBundle:Pedido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedido entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            // 'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Pedido entity.
     *
     * @Route("/{id}/edit", name="chefe_pedido_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        // $entity = $em->getRepository('ChefeBundle:Pedido')->findOneById($id);
        $entity = $em->getConnection()->prepare("SELECT id, codigo, idTipo, idFornecedor, data_pedido dataPedido, valor, descricao, ativo, status FROM pedido WHERE id = ".$id);
        $entity->execute();
        $entity = $entity->fetchAll();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedido entity.');
        }

        // $editForm = $this->createEditForm($entity);
        // $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
        );
    }

    /**
    * Creates a form to edit a Pedido entity.
    *
    * @param Pedido $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Pedido $entity)
    {
        // $form = $this->createForm(new PedidoType(), $entity, array(
        //     'action' => $this->generateUrl('chefe_pedido_update', array('id' => $entity->getId())),
        //     'method' => 'PUT',
        // ));

        // $form->add('submit', 'submit', array('label' => 'Update'));

        // return $form;
    }
    /**
     * Edits an existing Pedido entity.
     *
     * @Route("/{id}", name="chefe_pedido_update")
     * @Method("PUT")
     * @Template("ChefeBundle:Pedido:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChefeBundle:Pedido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedido entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chefe_pedido_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            // 'edit_form'   => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Pedido entity.
     *
     * @Route("/{id}", name="chefe_pedido_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ChefeBundle:Pedido')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pedido entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('chefe_pedido'));
    }

    /**
     * Creates a form to delete a Pedido entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        // return $this->createFormBuilder()
        //     ->setAction($this->generateUrl('chefe_pedido_delete', array('id' => $id)))
        //     ->setMethod('DELETE')
        //     ->add('submit', 'submit', array('label' => 'Delete'))
        //     ->getForm()
        // ;
    }

    /**
     * @Route("/get/fornecedores")
     */
    public function getFornecedoresAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            
            $fornecedores = $em->getRepository("ChefeBundle:Fornecedor")->createQueryBuilder('f')
            ->where("f.ativo = 'S'")
            ->andWhere('f.nome LIKE :nome')
            ->setParameter('nome', '%'.$request->get('cnpj_fornecedor_input').'%')
            ->getQuery()
            ->getResult();
            if(!$fornecedores) {throw new \Exception('error_fornecedores');}

            $default = new DefaultController();
            $fornecedores = $default->serializeJSON($fornecedores);
            
            return new Response(json_encode([
                'description' => json_decode($fornecedores)
            ]), 200);
        } catch (\Exception $e) {
            return new Response(json_encode([
                'description' => 'Não foi possivel encontrar um fornecedor com esse CNPJ!'
            ]), 500);
        }
    }

    /**
     * @Route("/get/masters/")
     */
    public function getMastersAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            
            $financeiros = $em->getConnection()->prepare("SELECT id idFuncionario, nome nomeFuncionario FROM funcionario WHERE idTipo = 5");
            $financeiros->execute();
            $financeiros = $financeiros->fetchAll();
            
            return new Response(json_encode($financeiros), 200);
        } catch (\Exception $e) {
            return new Response(json_encode([
                'description' => 'Não foi possivel encontrar masters!'
            ]), 500);
        }
    }

    /**
     * @Route("/get/financeiros/")
     */
    public function getFinanceirosAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            
            $financeiros = $em->getConnection()->prepare("SELECT id idFuncionario, nome nomeFuncionario FROM funcionario WHERE idTipo = 3");
            $financeiros->execute();
            $financeiros = $financeiros->fetchAll();
            
            return new Response(json_encode($financeiros), 200);
        } catch (\Exception $e) {
            return new Response(json_encode([
                'description' => 'Não foi possivel encontrar financeiros!'
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

            $old_historico = $em->getRepository('ChefeBundle:Historico')->findOneBy([
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
            $de = $em->getRepository('ChefeBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdde($de);
            $para = $em->getRepository('ChefeBundle:Funcionario')->findOneById($request->get('para'));
            $historico->setIdpara($para);
            $historico->setDataPassagem($hoje);
            $historico->setIdmensagem($mensagem);
            $historico->setVisto(0);
            $tipohistorico = $em->getRepository('ChefeBundle:TipoHistorico')->findOneById(2);
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

    /**
     * @Route("/confirmar/")
     */
    public function confirmarPedidoAction(Request $request)
    {
        $hoje = date_create();
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            if(!$request->get('id')) {throw new \Exception('error_id');}
            if(!$request->get('para')) {throw new \Exception('error_para');}

            $old_historico = $em->getRepository('ChefeBundle:Historico')->findOneBy([
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
            $de = $em->getRepository('ChefeBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdde($de);
            $para = $em->getRepository('ChefeBundle:Funcionario')->findOneById($request->get('para'));
            $historico->setIdpara($para);
            $historico->setDataPassagem($hoje);
            $historico->setIdmensagem($mensagem);
            $historico->setVisto(0);
            $tipohistorico = $em->getRepository('ChefeBundle:TipoHistorico')->findOneById(1);
            $historico->setTipoHistorico($tipohistorico);
            $em->persist($historico);
            $em->flush();

            $em->getConnection()->commit();

            $apibundle = new ApiDefault;
            $apibundle->sendPush([$para->getTokenApp()], 'Pedido Pendente', 'Existe um pedido pendente de sua aprovação');
            
            return new Response(json_encode([
                'description' => 'Pedido confirmado com sucesso!'
            ]), 200);
        } catch(Exception $e) {
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
                'description' => 'Não foi possivel confirmar o Pedido!'
            ]), 500);
        }
    }

    /**
     * @Route("/encaminhar/")
     */
    public function encaminharPedidoAction(Request $request)
    {
        $hoje = date_create();
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            if(!$request->get('id')) {throw new \Exception('error_id');}

            $old_historico = $em->getRepository('ChefeBundle:Historico')->findOneBy([
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
            $de = $em->getRepository('ChefeBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdde($de);
            $para = $em->getRepository('ChefeBundle:Funcionario')->findOneById($old_historico->getIdde());
            $historico->setIdpara($para);
            $historico->setDataPassagem($hoje);
            $historico->setIdmensagem($mensagem);
            $historico->setVisto(0);
            $tipohistorico = $em->getRepository('ChefeBundle:TipoHistorico')->findOneById(1);
            $historico->setTipoHistorico($tipohistorico);
            $em->persist($historico);
            $em->flush();

            $em->getConnection()->commit();

            $apibundle = new ApiDefault;
            $apibundle->sendPush([$para->getTokenApp()], 'Pedido Pendente', 'Existe um pedido pendente de sua aprovação');
            
            return new Response(json_encode([
                'description' => 'Pedido encaminhado com sucesso!'
            ]), 200);
        } catch(\Exception $e) {
            $em->getConnection()->rollBack();
            switch($e->getMessage()) {
                case 'error_id':
                    return new Response(json_encode([
                        'description' => 'Id incorreto'
                    ]), 500);
                break;
                case 'error_mensagem':
                    return new Response(json_encode([
                        'description' => 'Mensagem incorreta!'
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                'description' => 'Não foi possivel encaminhado o Pedido!'
            ]), 500);
        }
    }

    /**
     * @Route("/recusar/")
     */
    public function recusarPedidoAction(Request $request)
    {
        $hoje = date_create();
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            if(!$request->get('id')) {throw new \Exception('error_id');}
            if(!$request->get('mensagem')) {throw new \Exception('error_mensagem');}

            $old_historico = $em->getRepository('ChefeBundle:Historico')->findOneBy([
                'idpedido' => $request->get('id')
            ],[
                'id' => 'DESC'
            ]);

            $mensagem = new Mensagem();
            $mensagem->setMensagem($request->get('mensagem'));
            $em->persist($mensagem);
            $em->flush();

            $pedido = $em->getRepository('ChefeBundle:Pedido')->findOneById($old_historico->getIdpedido());
            $status_pedido = $em->getRepository('ChefeBundle:StatusPedido')->findOneById(3);
            $pedido->setStatus($status_pedido);
            $em->persist($pedido);
            $em->flush();

            $historico = new Historico();
            $historico->setCodigo($old_historico->getCodigo());
            $historico->setIdpedido($old_historico->getIdpedido());
            $de = $em->getRepository('ChefeBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdde($de);
            $para = $em->getRepository('ChefeBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdpara($para);
            $historico->setDataPassagem($hoje);
            $historico->setIdmensagem($mensagem);
            $historico->setVisto(0);
            $tipohistorico = $em->getRepository('ChefeBundle:TipoHistorico')->findOneById(4);
            $historico->setTipoHistorico($tipohistorico);
            $em->persist($historico);
            $em->flush();

            $em->getConnection()->commit();

            $apibundle = new ApiDefault;
            $apibundle->sendPush([$old_historico->getIdpara()->getTokenApp()], 'Pedido Recusado', 'Seu pedido foi recusado');
            
            return new Response(json_encode([
                'description' => 'Pedido recusado com sucesso!'
            ]), 200);
        } catch(Exception $e) {
            $em->getConnection()->rollBack();
            switch($e->getMessage()) {
                case 'error_id':
                    return new Response(json_encode([
                        'description' => 'Id incorreto'
                    ]), 500);
                break;
                case 'error_mensagem':
                    return new Response(json_encode([
                        'description' => 'Mensagem incorreta!'
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                'description' => 'Não foi possivel recusar o Pedido!'
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/")
     */
    public function cadastrarPedidoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $request = json_decode($request->get('data'));
        $default = new DefaultController();
        $hoje = date_create();
        try {
            $em->getConnection()->beginTransaction();

            if(!$request->tipopedido) {throw new \Exception('error_tipopedido');}
            if(!$request->valor) {throw new \Exception('error_valor');}
            if(!$request->descricao) {throw new \Exception('error_descricao');}
            if(!$request->para) {throw new \Exception('error_para');}
            if(!$request->empresa) {throw new \Exception('error_empresa');}
            if(!$request->forn) {throw new \Exception('error_forn');}
            
            $pagamentos = $request->pagamentos;            
            if(count($pagamentos) == 0) {throw new \Exception('error_pagamentos');}
            
            $empresa = $em->getRepository('ChefeBundle:Empresa')->findOneById($request->empresa);
            
            $pedido = new Pedido();
            $pedido->setIdempresa($empresa);
            $pedido->setCodigo(null);
            $tipo = $em->getRepository('ChefeBundle:TipoPedido')->findOneById($request->tipopedido);
            $pedido->setIdtipo($tipo);
            $fornecedor = $em->getRepository('ChefeBundle:Fornecedor')->findOneById($request->forn);
            $pedido->setIdfornecedor($fornecedor);
            $pedido->setDataPedido($hoje);
            $pedido->setValor($request->valor);
            $pedido->setDescricao($request->descricao);
            $pedido->setAtivo('S');
            $status_pedido = $em->getRepository('ChefeBundle:StatusPedido')->findOneById(1);
            $pedido->setStatus($status_pedido);
            $criador = $em->getRepository('ChefeBundle:Funcionario')->findOneById($this->getUser()->getId());
            $pedido->setCriadoPor($criador);
            $em->persist($pedido);
            $em->flush();

            for ($i = 0; $i < count($pagamentos); $i++) {
                $pagamento = new Pagamento();
                $pagamento->setIdPedido($pedido);
                $tipo_pagamento = $em->getRepository('ChefeBundle:TipoPagamento')->findOneById($pagamentos[$i]->tipopagamento);
                $pagamento->setIdTipo($tipo_pagamento);
                $pagamento->setValorIntegral($pagamentos[$i]->valor_integral);
                $status_pagamento = $em->getRepository('ChefeBundle:StatusPagamento')->findOneById(1);
                $pagamento->setIdStatus($status_pagamento);

                switch($tipo_pagamento->getId()){
                    case 2:
                        $cartao = $em->getRepository('ChefeBundle:Cartao')->findOneBy(array(
                            'id' => $pagamentos[$i]->cartao,
                            'active' => true
                        ));

                        if(empty($cartao)){
                            throw new \Exception('error_cartao');
                        }

                        $pagamento->setCartao($cartao);

                        break;
                    case 6:
                        $banco = $pagamentos[$i]->conta->banco;
                        $agencia = $pagamentos[$i]->conta->agencia;
                        $contaId = $pagamentos[$i]->conta->conta;
                        $cpf = $pagamentos[$i]->conta->cpf;
                        $cnpj = $pagamentos[$i]->conta->cnpj;

                        if(empty($banco)){throw new \Exception('error_conta_banco');}
                        if(empty($agencia)){throw new \Exception('error_conta_agencia');}
                        if(empty($contaId)){throw new \Exception('error_conta_conta');}
                        if(empty($cpf) && empty($cnpj)){throw new \Exception('error_conta_cpf_cnpj');}

                        $contaTipo = $em->getRepository('ChefeBundle:ContaTipo')->findOneById($pagamentos[$i]->conta->tipo);
                        
                        if(empty($contaTipo)){
                            throw new \Exception('error_conta_tipo');
                        }

                        $conta = new Conta;
                        $conta->setBanco($banco);
                        $conta->setAgencia($agencia);
                        $conta->setConta($contaId);
                        $conta->setCpf($cpf);
                        $conta->setCnpj($cnpj);
                        $conta->setTipo($contaTipo);

                        $em->persist($conta);
                        $em->flush();

                        $pagamento->setConta($conta);
                }

                $em->persist($pagamento);
                $em->flush();
    
                $parcelas = $pagamentos[$i]->parcelas;
                for ($j=0; $j < count($parcelas); $j++) {
                    $parcela = new Parcelas();
                    $parcela->setIdPagamento($pagamento);
                    $parcela->setNumParcela($j + 1);
                    $parcela->setValor($parcelas[$j]->valor);
                    $parcela->setValorPago(null);
                    $parcela->setValorDesconto(null);
                    $parcela->setValorAcrecimo(null);
                    $parcela->setValorPendente($parcelas[$j]->valor);
                    $parcela->setDataVencimento(date_create_from_format('Y-m-d', $parcelas[$j]->vencimento));
                    $parcela->setMensagem(null);
                    $status_parcela = $em->getRepository('ChefeBundle:StatusParcela')->findOneById(1);
                    $parcela->setStatus($status_parcela);
                    $em->persist($parcela);
                    $em->flush();
                }
                
            }

            $historico = new Historico();
            $historico->setCodigo(null);
            $historico->setIdpedido($pedido);
            $de = $em->getRepository('ChefeBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdde($de);
            $para = $em->getRepository('ChefeBundle:Funcionario')->findOneById($request->para);
            $historico->setIdpara($para);
            $historico->setDataPassagem($hoje);
            $historico->setIdmensagem(null);
            $historico->setVisto(0);
            $tipohistorico = $em->getRepository('ChefeBundle:TipoHistorico')->findOneById(1);
            $historico->setTipoHistorico($tipohistorico);
            $em->persist($historico);
            $em->flush();

            $apibundle = new ApiDefault;
            $apibundle->sendPush([$para->getTokenApp()], 'Novo Pedido', 'Você tem um novo pedido');

            if($para->getSocket()){
                // $object = (object) [];
                // $apibundle->sendSocketFromPHP("sendTo", [$para->getSocket(), "atualizarRecebidos", $object]);
            }
            
            foreach ($_FILES as $value):
                $nome = substr(str_shuffle(MD5(microtime())), 0, 20);
                $path = $value['name'];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $nome_file = $nome . "." . $ext;
                if(move_uploaded_file($value['tmp_name'], './uploads/' . $nome_file)){
                    $anexo = new Anexo();
                    $anexo->setCaminho($nome_file);
                    $anexo->setAtivo("S");
                    $em->persist($anexo);
                    $em->flush();
        
                    $pedidoAnexo = new PedidoAnexo();
                    $pedidoAnexo->setIdanexo($anexo);
                    $pedidoAnexo->setIdpedido($pedido);
                    $pedidoAnexo->setAtivo("S");
                    $em->persist($pedidoAnexo);
                    $em->flush();
                } else {
                    throw new \Exception('error_foto_upload');
                }
            endforeach;

            $em->getConnection()->commit();
            return new Response(json_encode([
                'description' => 'Pedido cadastrado com sucesso!'
            ]), 200);
        } catch(\Exception $e) {
            $em->getConnection()->rollBack();
            switch($e->getMessage()) {
                case 'error_foto_upload':
                    return new Response(json_encode([
                        'description' => 'Arquivo acima de 2MB, por favor reduza o arquivo.'
                    ]), 500);
                break;
                case 'error_empresa':
                    return new Response(json_encode([
                        'description' => 'Empresa não pode ser vazio!'
                    ]), 500);
                break;
                case 'error_tipopedido':
                    return new Response(json_encode([
                        'description' => 'Tipo de Pedido não pode ser vazio!'
                    ]), 500);
                break;
                case 'error_valor':
                    return new Response(json_encode([
                        'description' => 'Valor não pode ser vazio!'
                    ]), 500);
                break;
                case 'error_descricao':
                    return new Response(json_encode([
                        'description' => 'Descrição não pode ser vazio!'
                    ]), 500);
                break;
                case 'error_para':
                    return new Response(json_encode([
                        'description' => 'Para quem não pode ser vazio!'
                    ]), 500);
                break;
                case 'error_pagamentos':
                    return new Response(json_encode([
                        'description' => 'Pedido deve ter pelo menos uma forma de pagamento'
                    ]), 500);
                break;
                case 'error_cartao':
                    return new Response(json_encode([
                        'description' => 'É necessário informar um cartão'
                    ]), 500);
                break;
                case 'error_conta_banco':
                    return new Response(json_encode([
                        'description' => 'É necessário informar um banco'
                    ]), 500);
                break;
                case 'error_conta_agencia':
                    return new Response(json_encode([
                        'description' => 'É necessário informar uma agência'
                    ]), 500);
                break;
                case 'error_conta_conta':
                    return new Response(json_encode([
                        'description' => 'É necessário informar uma conta'
                    ]), 500);
                break;
                case 'error_conta_cpf_cnpj':
                    return new Response(json_encode([
                        'description' => 'É necessário informar CPF ou CNPJ'
                    ]), 500);
                break;
                case 'error_conta_tipo':
                    return new Response(json_encode([
                        'description' => 'É necessário informar o tipo de conta'
                    ]), 500);
                break;
                case 'error_forn':
                    return new Response(json_encode([
                        'description' => 'É necessário informar o favorecido'
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                'description' => 'Não foi possivel cadastrar o Pedido!'
            ]), 500);
        }
    }
}
