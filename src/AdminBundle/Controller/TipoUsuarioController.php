<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\TipoUsuario;
use AdminBundle\Form\TipoUsuarioType;
use Symfony\Component\HttpFoundation\Response;

/**
 * TipoUsuario controller.
 *
 * @Route("/tipousuario")
 */
class TipoUsuarioController extends Controller
{

    /**
     * Lists all TipoUsuario entities.
     *
     * @Route("/", name="tipousuario")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:TipoUsuario')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TipoUsuario entity.
     *
     * @Route("/", name="tipousuario_create")
     * @Method("POST")
     * @Template("AdminBundle:TipoUsuario:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TipoUsuario();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipousuario_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a TipoUsuario entity.
     *
     * @param TipoUsuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoUsuario $entity)
    {
        $form = $this->createForm(new TipoUsuarioType(), $entity, array(
            'action' => $this->generateUrl('tipousuario_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TipoUsuario entity.
     *
     * @Route("/new", name="tipousuario_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TipoUsuario();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a TipoUsuario entity.
     *
     * @Route("/{id}", name="tipousuario_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoUsuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoUsuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TipoUsuario entity.
     *
     * @Route("/{id}/edit", name="tipousuario_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoUsuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoUsuario entity.');
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
    * Creates a form to edit a TipoUsuario entity.
    *
    * @param TipoUsuario $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TipoUsuario $entity)
    {
        $form = $this->createForm(new TipoUsuarioType(), $entity, array(
            'action' => $this->generateUrl('tipousuario_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TipoUsuario entity.
     *
     * @Route("/{id}", name="tipousuario_update")
     * @Method("PUT")
     * @Template("AdminBundle:TipoUsuario:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoUsuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoUsuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tipousuario_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TipoUsuario entity.
     *
     * @Route("/{id}", name="tipousuario_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:TipoUsuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoUsuario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tipousuario'));
    }

    /**
     * Creates a form to delete a TipoUsuario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipousuario_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
