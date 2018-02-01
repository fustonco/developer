<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\TipoHistorico;
use AdminBundle\Form\TipoHistoricoType;
use Symfony\Component\HttpFoundation\Response;

/**
 * TipoHistorico controller.
 *
 * @Route("/tipohistorico")
 */
class TipoHistoricoController extends Controller
{

    /**
     * Lists all TipoHistorico entities.
     *
     * @Route("/", name="tipohistorico")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:TipoHistorico')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TipoHistorico entity.
     *
     * @Route("/", name="tipohistorico_create")
     * @Method("POST")
     * @Template("AdminBundle:TipoHistorico:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TipoHistorico();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipohistorico_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a TipoHistorico entity.
     *
     * @param TipoHistorico $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoHistorico $entity)
    {
        $form = $this->createForm(new TipoHistoricoType(), $entity, array(
            'action' => $this->generateUrl('tipohistorico_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TipoHistorico entity.
     *
     * @Route("/new", name="tipohistorico_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TipoHistorico();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a TipoHistorico entity.
     *
     * @Route("/{id}", name="tipohistorico_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoHistorico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoHistorico entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TipoHistorico entity.
     *
     * @Route("/{id}/edit", name="tipohistorico_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoHistorico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoHistorico entity.');
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
    * Creates a form to edit a TipoHistorico entity.
    *
    * @param TipoHistorico $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TipoHistorico $entity)
    {
        $form = $this->createForm(new TipoHistoricoType(), $entity, array(
            'action' => $this->generateUrl('tipohistorico_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TipoHistorico entity.
     *
     * @Route("/{id}", name="tipohistorico_update")
     * @Method("PUT")
     * @Template("AdminBundle:TipoHistorico:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoHistorico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoHistorico entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tipohistorico_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TipoHistorico entity.
     *
     * @Route("/{id}", name="tipohistorico_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:TipoHistorico')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoHistorico entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tipohistorico'));
    }

    /**
     * Creates a form to delete a TipoHistorico entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipohistorico_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    /**
     * @Route("/editar/")
     */
    public function adminEditarTipoHistoricoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}

            $tipohistorico = $em->getRepository('AdminBundle:TipoHistorico')->findOneById($request->get('id'));
            if(!$tipohistorico) {throw new \Exception('error_tipohistorico');}

            $tipohistorico->setNome($request->get('nome'));
            $tipohistorico->setAtivo('S');
            $em->persist($tipohistorico);
            $em->flush();
            return new Response(json_encode([
                "description" => "Tipo de Historico Editado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_tipopedido':
                    return new Response(json_encode([
                        "description" => "Tipo de Historico não pode encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Editar Tipo de Historico!"
            ]), 500);
        }
    }

    /**
     * @Route("/excluir/")
     */
    public function adminExcluirTipoHistoricoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $tipohistorico = $em->getRepository('AdminBundle:TipoHistorico')->findOneById($request->get('id'));
            if(!$tipohistorico) {throw new \Exception('error_tipohistorico');}
            $em->remove($tipohistorico);
            $em->flush();

            return new Response(json_encode([
                "description" => "Tipo de Historico Excluido com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            if(strpos($e->getMessage(), 'FOREIGN')) {
                return new Response(json_encode([
                    "description" => "Não foi possível excluir esse Tipo de Historico, pois existe registrados nele, por favor altere-os para que seja possível a remoção segura."
                ]), 500);
            }
            switch($e->getMessage()){
                case 'error_tipohistorico':
                    return new Response(json_encode([
                        "description" => "Tipo de Historico não encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Excluir Tipo de Historico!"
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/")
     */
    public function adminCadastrarTipoHistoricoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}

            $tipohistorico = new TipoHistorico();
            $tipohistorico->setNome($request->get('nome'));
            $tipohistorico->setAtivo('S');
            $em->persist($tipohistorico);
            $em->flush();
            return new Response(json_encode([
                "description" => "Tipo de Historico Cadastrado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Tipo de Historico!"
            ]), 500);
        }
    }
}
