<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\TipoConta;
use AdminBundle\Form\TipoContaType;

/**
 * TipoConta controller.
 *
 * @Route("/tipoconta")
 */
class TipoContaController extends Controller
{

    /**
     * Lists all TipoConta entities.
     *
     * @Route("/", name="tipoconta")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:TipoConta')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TipoConta entity.
     *
     * @Route("/", name="tipoconta_create")
     * @Method("POST")
     * @Template("AdminBundle:TipoConta:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TipoConta();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipoconta_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a TipoConta entity.
     *
     * @param TipoConta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoConta $entity)
    {
        $form = $this->createForm(new TipoContaType(), $entity, array(
            'action' => $this->generateUrl('tipoconta_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TipoConta entity.
     *
     * @Route("/new", name="tipoconta_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TipoConta();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a TipoConta entity.
     *
     * @Route("/{id}", name="tipoconta_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoConta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoConta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TipoConta entity.
     *
     * @Route("/{id}/edit", name="tipoconta_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoConta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoConta entity.');
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
    * Creates a form to edit a TipoConta entity.
    *
    * @param TipoConta $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TipoConta $entity)
    {
        $form = $this->createForm(new TipoContaType(), $entity, array(
            'action' => $this->generateUrl('tipoconta_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TipoConta entity.
     *
     * @Route("/{id}", name="tipoconta_update")
     * @Method("PUT")
     * @Template("AdminBundle:TipoConta:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoConta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoConta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tipoconta_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TipoConta entity.
     *
     * @Route("/{id}", name="tipoconta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:TipoConta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoConta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tipoconta'));
    }

    /**
     * Creates a form to delete a TipoConta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipoconta_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
