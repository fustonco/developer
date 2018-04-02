<?php

namespace MasterBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MasterBundle\Entity\Pedido;
use MasterBundle\Entity\Historico;
use MasterBundle\Entity\Pagamento;
use MasterBundle\Entity\Parcelas;
use MasterBundle\Entity\Mensagem;
use MasterBundle\Form\PedidoType;
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
     * @Route("/", name="master_pedido")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getConnection()->prepare("
        SELECT sp.id id_status_pedido, f.nome funcionario, f.id idFuncionario, tu.id id_tipo_funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.idTipo, p.idFornecedor, p.data_pedido dataPedido, p.valor, p.descricao, p.ativo, sp.nome status
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        WHERE p.criado_por = :criado_por
        ORDER BY p.id DESC
        ");
        $entities->bindValue("criado_por", $this->getUser()->getId());
        $entities->execute();
        $entities = $entities->fetchAll();

        return array(
            'entities' => $entities
        );
    }


    /**
     * Lists all Pedido entities.
     *
     * @Route("/recebido", name="master_pedido_recebido")
     * @Method("GET")
     * @Template()
     */
    public function indexRecebidoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getConnection()->prepare("
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
        $entities->bindValue("para", $this->getUser()->getId());
        $entities->execute();
        $entities = $entities->fetchAll();

        return array(
            'entities' => $entities
        );
    }

    /**
     * Lists all Pedido entities.
     *
     * @Route("/recusado", name="master_pedido_recusado")
     * @Method("GET")
     * @Template()
     */
    public function indexRecusadoAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getConnection()->prepare("
        SELECT f.nome funcionario, tu.id id_tipo_funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.idTipo, p.idFornecedor, p.data_pedido dataPedido, p.valor, p.descricao, p.ativo, sp.nome status
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id AND h.idPara = :para
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        WHERE p.status = 3
        ORDER BY p.id DESC;
        ");
        $entities->bindValue("para", $this->getUser()->getId());
        $entities->execute();
        $entities = $entities->fetchAll();

        return array(
            'entities' => $entities
        );
    }
    
    /**
     * @Route("/ver/")
     */
    public function funcionarioVerPedidoAction(Request $request)
    {
        $default = new DefaultController;
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
            $historico = $default->serializeJSON($historico);

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
     * @Route("/excluir/")
     */
    public function funcionarioExcluirPedidoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            $pagamento = $em->getConnection()->prepare("SELECT * FROM pagamento WHERE idPedido = ".$request->get('id'));
            $pagamento->execute();
            $pagamento = $pagamento->fetchAll();
            if(!$pagamento){throw new \Exception("error_pagamento");}
            for($i = 0; $i < count($pagamento); $i++) {
                $entities = $em->getConnection()->prepare("DELETE FROM parcelas WHERE idPagamento = ".$pagamento[$i]["id"]);
                $entities->execute();
            }
            $entities = $em->getConnection()->prepare("DELETE FROM pagamento WHERE idPedido = ".$request->get('id'));
            $entities->execute();

            $historico = $em->getConnection()->prepare("SELECT * FROM historico WHERE idPedido = ".$request->get('id'));
            $historico->execute();
            $historico = $historico->fetchAll();
            if(!$historico){throw new \Exception("error_historico");}
            $entities = $em->getConnection()->prepare("DELETE FROM historico WHERE idPedido = ".$request->get('id'));
            $entities->execute();

            for($i = 0; $i < count($historico); $i++) {
                if($historico[$i]["idMensagem"]) {
                    $entities = $em->getConnection()->prepare("DELETE FROM mensagem WHERE id = ".$historico[$i]["idMensagem"]);
                    $entities->execute();
                }
            }

            $entities = $em->getConnection()->prepare("DELETE FROM pedido WHERE id = ".$request->get('id'));
            $entities->execute();
            
            $em->getConnection()->commit();
            return new Response(json_encode([
                "description" => "Pedido Excluido com Sucesso!"
            ]), 200);
        } catch (Exception $e) {
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
     * @Route("/", name="master_pedido_create")
     * @Method("POST")
     * @Template("MasterBundle:Pedido:new.html.twig")
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

            return $this->redirect($this->generateUrl('master_pedido_show', array('id' => $entity->getId())));
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
        //     'action' => $this->generateUrl('master_pedido_create'),
        //     'method' => 'POST',
        // ));

        // $form->add('submit', 'submit', array('label' => 'Create'));

        // return $form;
    }

    /**
     * Displays a form to create a new Pedido entity.
     *
     * @Route("/new", name="master_pedido_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Pedido();
        // $form   = $this->createCreateForm($entity);

        $em = $this->getDoctrine()->getManager();
        $tipopedido = $em->getRepository('MasterBundle:TipoPedido')->findBy([], [
            'nome' => 'ASC'
        ]);
        $tipopagamento = $em->getRepository('MasterBundle:TipoPagamento')->findBy([], [
            'nome' => 'ASC'
        ]);
        $fornecedores = $em->getRepository('MasterBundle:Fornecedor')->findBy([], [
            'nome' => 'ASC'
        ]);
        $para = $em->getRepository('MasterBundle:Funcionario')->findBy([
            'idtipo' => 3
        ], [
            'nome' => 'ASC'
        ]);

        return array(
            'entity' => $entity,
            'tipopedido' => $tipopedido,
            'tipopagamento' => $tipopagamento,
            'fornecedores' => $fornecedores,
            'para' => $para
            // 'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Pedido entity.
     *
     * @Route("/{id}", name="master_pedido_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MasterBundle:Pedido')->find($id);

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
     * @Route("/{id}/edit", name="master_pedido_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        // $entity = $em->getRepository('MasterBundle:Pedido')->findOneById($id);
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
        //     'action' => $this->generateUrl('master_pedido_update', array('id' => $entity->getId())),
        //     'method' => 'PUT',
        // ));

        // $form->add('submit', 'submit', array('label' => 'Update'));

        // return $form;
    }
    /**
     * Edits an existing Pedido entity.
     *
     * @Route("/{id}", name="master_pedido_update")
     * @Method("PUT")
     * @Template("MasterBundle:Pedido:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MasterBundle:Pedido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedido entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('master_pedido_edit', array('id' => $id)));
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
     * @Route("/{id}", name="master_pedido_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MasterBundle:Pedido')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pedido entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('master_pedido'));
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
        //     ->setAction($this->generateUrl('master_pedido_delete', array('id' => $id)))
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
            
            $fornecedores = $em->getRepository("MasterBundle:Fornecedor")->createQueryBuilder('f')
            ->where("f.ativo = 'S'")
            ->andWhere('f.cnpj LIKE :cnpj')
            ->setParameter('cnpj', '%'.$request->get('cnpj_fornecedor_input').'%')
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
            if(!$request->get('mensagem')) {throw new \Exception('error_mensagem');}
            if(!$request->get('para')) {throw new \Exception('error_para');}

            $old_historico = $em->getRepository('MasterBundle:Historico')->findOneBy([
                'idpedido' => $request->get('id')
            ],[
                'id' => 'DESC'
            ]);
            
            $pedido = $em->getRepository('MasterBundle:Pedido')->findOneById($request->get('id'));
            $codigo = strtoupper(substr(str_shuffle(MD5(microtime())), 0, 5));
            $old_historico->setCodigo($codigo);
            $pedido->setCodigo($codigo);

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
            $tipohistorico = $em->getRepository('MasterBundle:TipoHistorico')->findOneById(3);
            $historico->setTipoHistorico($tipohistorico);
            $em->persist($historico);
            $em->flush();

            $em->getConnection()->commit();

            $apibundle = new ApiDefault;
            $apibundle->sendPush([$para->getTokenApp()], 'Pedido Pendente', 'Existe um pedido pendente de sua aprovação');
            
            return new Response(json_encode([
                'description' => 'Pedido confirmado com sucesso!'
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
            if(!$request->get('mensagem')) {throw new \Exception('error_mensagem');}

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
            $para = $em->getRepository('MasterBundle:Funcionario')->findOneById($old_historico->getIdde());
            $historico->setIdpara($para);
            $historico->setDataPassagem($hoje);
            $historico->setIdmensagem($mensagem);
            $tipohistorico = $em->getRepository('MasterBundle:TipoHistorico')->findOneById(1);
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

            $old_historico = $em->getRepository('MasterBundle:Historico')->findOneBy([
                'idpedido' => $request->get('id')
            ],[
                'id' => 'DESC'
            ]);

            $mensagem = new Mensagem();
            $mensagem->setMensagem($request->get('mensagem'));
            $em->persist($mensagem);
            $em->flush();

            $pedido = $em->getRepository('MasterBundle:Pedido')->findOneById($old_historico->getIdpedido());
            $status_pedido = $em->getRepository('MasterBundle:StatusPedido')->findOneById(3);
            $pedido->setStatus($status_pedido);
            $em->persist($pedido);
            $em->flush();

            $historico = new Historico();
            $historico->setCodigo($old_historico->getCodigo());
            $historico->setIdpedido($old_historico->getIdpedido());
            $de = $em->getRepository('MasterBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdde($de);
            $para = $em->getRepository('MasterBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdpara($para);
            $historico->setDataPassagem($hoje);
            $historico->setIdmensagem($mensagem);
            $tipohistorico = $em->getRepository('MasterBundle:TipoHistorico')->findOneById(4);
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
        $hoje = date_create();
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            if(!$request->get('tipopedido')) {throw new \Exception('error_tipopedido');}
            if(!$request->get('valor')) {throw new \Exception('error_valor');}
            if(!$request->get('descricao')) {throw new \Exception('error_descricao');}
            if(!$request->get('para')) {throw new \Exception('error_para');}

            $pagamentos = $request->get('pagamentos');
            if(count($pagamentos) == 0) {throw new \Exception('error_pagamentos');}

            $pedido = new Pedido();
            $pedido->setCodigo(null);
            $tipo = $em->getRepository('MasterBundle:TipoPedido')->findOneById($request->get('tipopedido'));
            $pedido->setIdtipo($tipo);
            if($request->get('forn')) {
                $fornecedor = $em->getRepository('MasterBundle:Fornecedor')->findOneById($request->get('forn'));
                $pedido->setIdfornecedor($fornecedor);
            }
            $pedido->setDataPedido($hoje);
            $pedido->setValor($request->get('valor'));
            $pedido->setDescricao($request->get('descricao'));
            $pedido->setAtivo('S');
            $status_pedido = $em->getRepository('MasterBundle:StatusPedido')->findOneById(1);
            $pedido->setStatus($status_pedido);
            $criador = $em->getRepository('MasterBundle:Funcionario')->findOneById($this->getUser()->getId());
            $pedido->setCriadoPor($criador);
            $em->persist($pedido);
            $em->flush();

            for ($i = 0; $i < count($pagamentos); $i++) {
                $pagamento = new Pagamento();
                $pagamento->setIdPedido($pedido);
                $tipo_pagamento = $em->getRepository('MasterBundle:TipoPagamento')->findOneById($pagamentos[$i]['tipopagamento']);
                $pagamento->setIdTipo($tipo_pagamento);
                $pagamento->setValorIntegral($pagamentos[$i]['valor_integral']);
                $status_pagamento = $em->getRepository('MasterBundle:StatusPagamento')->findOneById(1);
                $pagamento->setIdStatus($status_pagamento);
                $em->persist($pagamento);
                $em->flush();
    
                $parcelas = $pagamentos[$i]['parcelas'];
                for ($j=0; $j < count($parcelas); $j++) {     
                    $parcela = new Parcelas();
                    $parcela->setIdPagamento($pagamento);
                    $parcela->setNumParcela($j + 1);
                    $parcela->setValor($parcelas[$j]['valor']);
                    $parcela->setValorPago(null);
                    $parcela->setValorDesconto(null);
                    $parcela->setValorAcrecimo(null);
                    $parcela->setValorPendente($parcelas[$j]['valor']);
                    $parcela->setDataVencimento(date_create_from_format('Y-m-d', $parcelas[$j]['vencimento']));
                    $parcela->setMensagem(null);
                    $status_parcela = $em->getRepository('MasterBundle:StatusParcela')->findOneById(1);
                    $parcela->setStatus($status_parcela);
                    $em->persist($parcela);
                    $em->flush();
                }
                
            }

            $historico = new Historico();
            $historico->setCodigo(null);
            $historico->setIdpedido($pedido);
            $de = $em->getRepository('MasterBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdde($de);
            $para = $em->getRepository('MasterBundle:Funcionario')->findOneById($request->get('para'));
            $historico->setIdpara($para);
            $historico->setDataPassagem($hoje);
            $historico->setIdmensagem(null);
            $tipohistorico = $em->getRepository('MasterBundle:TipoHistorico')->findOneById(1);
            $historico->setTipoHistorico($tipohistorico);
            $em->persist($historico);
            $em->flush();

            $apibundle = new ApiDefault;
            $apibundle->sendPush([$para->getTokenApp()], 'Novo Pedido', 'Você tem um novo pedido');

            $object = (object) [];
            $apibundle->sendSocketFromPHP("sendTo", [$para->getSocket(), "atualizarRecebidos", $object]);
            
            $em->getConnection()->commit();
            return new Response(json_encode([
                'description' => 'Pedido cadastrado com sucesso!'
            ]), 200);
        } catch(\Exception $e) {
            $em->getConnection()->rollBack();
            switch($e->getMessage()) {
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
            }
            return new Response(json_encode([
                'description' => 'Não foi possivel cadastrar o Pedido!'
            ]), 500);
        }
    }
}
