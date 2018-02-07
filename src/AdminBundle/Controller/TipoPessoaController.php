<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\TipoPessoa;
use AdminBundle\Form\TipoPessoaType;

/**
 * TipoPessoa controller.
 *
 * @Route("/tipopessoa")
 */
class TipoPessoaController extends Controller
{

    /**
     * Lists all TipoPessoa entities.
     *
     * @Route("/", name="tipopessoa")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:TipoPessoa')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TipoPessoa entity.
     *
     * @Route("/", name="tipopessoa_create")
     * @Method("POST")
     * @Template("AdminBundle:TipoPessoa:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TipoPessoa();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tipopessoa_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a TipoPessoa entity.
     *
     * @param TipoPessoa $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TipoPessoa $entity)
    {
        $form = $this->createForm(new TipoPessoaType(), $entity, array(
            'action' => $this->generateUrl('tipopessoa_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new TipoPessoa entity.
     *
     * @Route("/new", name="tipopessoa_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TipoPessoa();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a TipoPessoa entity.
     *
     * @Route("/{id}", name="tipopessoa_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoPessoa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoPessoa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TipoPessoa entity.
     *
     * @Route("/{id}/edit", name="tipopessoa_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoPessoa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoPessoa entity.');
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
    * Creates a form to edit a TipoPessoa entity.
    *
    * @param TipoPessoa $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TipoPessoa $entity)
    {
        $form = $this->createForm(new TipoPessoaType(), $entity, array(
            'action' => $this->generateUrl('tipopessoa_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TipoPessoa entity.
     *
     * @Route("/{id}", name="tipopessoa_update")
     * @Method("PUT")
     * @Template("AdminBundle:TipoPessoa:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:TipoPessoa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TipoPessoa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('tipopessoa_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TipoPessoa entity.
     *
     * @Route("/{id}", name="tipopessoa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:TipoPessoa')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TipoPessoa entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tipopessoa'));
    }

    /**
     * Creates a form to delete a TipoPessoa entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tipopessoa_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
