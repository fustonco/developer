<?php

namespace FuncionarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FuncionarioBundle\Entity\Pedido;
use FuncionarioBundle\Entity\Historico;
use FuncionarioBundle\Entity\Pagamento;
use FuncionarioBundle\Entity\Parcelas;
use FuncionarioBundle\Form\PedidoType;

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
     * @Route("/", name="func_pedido")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FuncionarioBundle:Pedido')->findAll();

        return array(
            'entities' => $entities
        );
    }
    /**
     * Creates a new Pedido entity.
     *
     * @Route("/", name="func_pedido_create")
     * @Method("POST")
     * @Template("FuncionarioBundle:Pedido:new.html.twig")
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

            return $this->redirect($this->generateUrl('func_pedido_show', array('id' => $entity->getId())));
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
        //     'action' => $this->generateUrl('func_pedido_create'),
        //     'method' => 'POST',
        // ));

        // $form->add('submit', 'submit', array('label' => 'Create'));

        // return $form;
    }

    /**
     * Displays a form to create a new Pedido entity.
     *
     * @Route("/new", name="func_pedido_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Pedido();
        // $form   = $this->createCreateForm($entity);

        $em = $this->getDoctrine()->getManager();
        $tipopedido = $em->getRepository('FuncionarioBundle:TipoPedido')->findBy([], [
            'nome' => 'ASC'
        ]);
        $tipopagamento = $em->getRepository('FuncionarioBundle:TipoPagamento')->findBy([], [
            'nome' => 'ASC'
        ]);
        $fornecedores = $em->getRepository('FuncionarioBundle:Fornecedor')->findBy([], [
            'nome' => 'ASC'
        ]);
        $para = $em->getRepository('FuncionarioBundle:Funcionario')->findBy([
            'idtipo' => 4
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
     * @Route("/{id}", name="func_pedido_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FuncionarioBundle:Pedido')->find($id);

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
     * @Route("/{id}/edit", name="func_pedido_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FuncionarioBundle:Pedido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedido entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            // 'edit_form'   => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
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
        //     'action' => $this->generateUrl('func_pedido_update', array('id' => $entity->getId())),
        //     'method' => 'PUT',
        // ));

        // $form->add('submit', 'submit', array('label' => 'Update'));

        // return $form;
    }
    /**
     * Edits an existing Pedido entity.
     *
     * @Route("/{id}", name="func_pedido_update")
     * @Method("PUT")
     * @Template("FuncionarioBundle:Pedido:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FuncionarioBundle:Pedido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pedido entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('func_pedido_edit', array('id' => $id)));
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
     * @Route("/{id}", name="func_pedido_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FuncionarioBundle:Pedido')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pedido entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('func_pedido'));
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
        //     ->setAction($this->generateUrl('func_pedido_delete', array('id' => $id)))
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
            
            $fornecedores = $em->getRepository("FuncionarioBundle:Fornecedor")->createQueryBuilder('f')
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
        } catch (Exception $e) {
            return new Response(json_encode([
                'description' => 'Não foi possivel encontrar um fornecedor com esse CNPJ!'
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/")
     */
    public function cadastrarPedidoAction(Request $request)
    {
        $hoje = new \DateTime();
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
            $tipo = $em->getRepository('FuncionarioBundle:TipoPedido')->findOneById($request->get('tipopedido'));
            $pedido->setIdtipo($tipo);
            if($request->get('forn')) {
                $fornecedor = $em->getRepository('FuncionarioBundle:Fornecedor')->findOneById($request->get('forn'));
                $pedido->setIdfornecedor($fornecedor);
            }
            $pedido->setDataPedido($hoje);
            $pedido->setValor($request->get('valor'));
            $pedido->setDescricao($request->get('descricao'));
            $pedido->setAtivo('S');
            $status_pedido = $em->getRepository('FuncionarioBundle:StatusPedido')->findOneById(1);
            $pedido->setStatus($status_pedido);
            $em->persist($pedido);
            $em->flush();

            for ($i = 0; $i < count($pagamentos); $i++) {
                $pagamento = new Pagamento();
                $pagamento->setIdPedido($pedido);
                $tipo_pagamento = $em->getRepository('FuncionarioBundle:TipoPagamento')->findOneById($pagamentos[$i]['tipopagamento']);
                $pagamento->setIdTipo($tipo_pagamento);
                $pagamento->setValorIntegral($pagamentos[$i]['valor_integral']);
                $status_pagamento = $em->getRepository('FuncionarioBundle:StatusPagamento')->findOneById(1);
                $pagamento->setIdStatus($status_pagamento);
                // $pagamento->setParcelado('S');
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
                    $status_parcela = $em->getRepository('FuncionarioBundle:StatusParcela')->findOneById(1);
                    $parcela->setStatus($status_parcela);
                    $em->persist($parcela);
                    $em->flush();
                }
                
            }

            $historico = new Historico();
            $historico->setCodigo(null);
            $historico->setIdpedido($pedido);
            $de = $em->getRepository('FuncionarioBundle:Funcionario')->findOneById($this->getUser()->getId());
            $historico->setIdde($de);
            $para = $em->getRepository('FuncionarioBundle:Funcionario')->findOneById($request->get('para'));
            $historico->setIdpara($para);
            $historico->setDataPassagem($hoje);
            $historico->setIdmensagem(null);
            $tipohistorico = $em->getRepository('FuncionarioBundle:TipoHistorico')->findOneById(1);
            $historico->setTipoHistorico($tipohistorico);
            $em->persist($historico);
            $em->flush();

            $em->getConnection()->commit();
            return new Response(json_encode([
                'description' => 'Pedido cadastrado com sucesso!'
            ]), 200);
        } catch(Exception $e) {
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
