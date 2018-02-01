<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\TipoPedido;
use AdminBundle\Form\TipoPedidoType;
use Symfony\Component\HttpFoundation\Response;

/**
 * TipoPedido controller.
 *
 * @Route("/tipopedido")
 */
class TipoPedidoController extends Controller
{

    /**
     * Lists all TipoPedido entities.
     *
     * @Route("/", name="tipopedido")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:TipoPedido')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TipoPedido entity.
     *
     * @Route("/", name="tipopedido_create")
     * @Method("POST")
     * @Template("AdminBundle:TipoPedido:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TipoPedido();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipopedido_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a TipoPedido entity.
     *
     * @param TipoPedido $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoPedido $entity)
    {
        $form = $this->createForm(new TipoPedidoType(), $entity, array(
            'action' => $this->generateUrl('tipopedido_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TipoPedido entity.
     *
     * @Route("/new", name="tipopedido_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TipoPedido();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a TipoPedido entity.
     *
     * @Route("/{id}", name="tipopedido_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoPedido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoPedido entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TipoPedido entity.
     *
     * @Route("/{id}/edit", name="tipopedido_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoPedido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoPedido entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a TipoPedido entity.
    *
    * @param TipoPedido $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TipoPedido $entity)
    {
        $form = $this->createForm(new TipoPedidoType(), $entity, array(
            'action' => $this->generateUrl('tipopedido_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TipoPedido entity.
     *
     * @Route("/{id}", name="tipopedido_update")
     * @Method("PUT")
     * @Template("AdminBundle:TipoPedido:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoPedido')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoPedido entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tipopedido_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TipoPedido entity.
     *
     * @Route("/{id}", name="tipopedido_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:TipoPedido')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoPedido entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tipopedido'));
    }

    /**
     * Creates a form to delete a TipoPedido entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipopedido_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    /**
     * @Route("/editar/")
     */
    public function adminEditarTipoPedidoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('descricao') || $request->get('descricao') == '') {throw new \Exception('error_descricao');}

            $tipopedido = $em->getRepository('AdminBundle:TipoPedido')->findOneById($request->get('id'));
            if(!$tipopedido) {throw new \Exception('error_tipopedido');}

            $tipopedido->setNome($request->get('nome'));
            $tipopedido->setDescricao($request->get('descricao'));
            $tipopedido->setAtivo('S');
            $em->persist($tipopedido);
            $em->flush();
            return new Response(json_encode([
                "description" => "Tipo de Pedido Editado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_descricao':
                    return new Response(json_encode([
                        "description" => "Descrição não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_tipopedido':
                    return new Response(json_encode([
                        "description" => "Tipo de Pedido não pode encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Editar Tipo de Pedido!"
            ]), 500);
        }
    }

    /**
     * @Route("/excluir/")
     */
    public function adminExcluirTipoPedidoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $tipopedido = $em->getRepository('AdminBundle:TipoPedido')->findOneById($request->get('id'));
            if(!$tipopedido) {throw new \Exception('error_tipopedido');}
            $em->remove($tipopedido);
            $em->flush();

            return new Response(json_encode([
                "description" => "Tipo de Pedido Excluido com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            if(strpos($e->getMessage(), 'FOREIGN')) {
                return new Response(json_encode([
                    "description" => "Não foi possível excluir esse Tipo de Pedido, pois existe registrados nele, por favor altere-os para que seja possível a remoção segura."
                ]), 500);
            }
            switch($e->getMessage()){
                case 'error_tipopedido':
                    return new Response(json_encode([
                        "description" => "Tipo de pedido não encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Excluir Tipo de Pedido!"
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/")
     */
    public function adminCadastrarTipoPedidoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('descricao') || $request->get('descricao') == '') {throw new \Exception('error_descricao');}

            $tipopedido = new TipoPedido();
            $tipopedido->setNome($request->get('nome'));
            $tipopedido->setDescricao($request->get('descricao'));
            $tipopedido->setAtivo('S');
            $em->persist($tipopedido);
            $em->flush();
            return new Response(json_encode([
                "description" => "Tipo de Pedido Cadastrado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_descricao':
                    return new Response(json_encode([
                        "description" => "Descrição não pode ser vazio!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Tipo de Pedido!"
            ]), 500);
        }
    }
}
