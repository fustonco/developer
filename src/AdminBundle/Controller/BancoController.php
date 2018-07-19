<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\Banco;
use AdminBundle\Form\BancoType;

/**
 * Banco controller.
 *
 * @Route("/banco")
 */
class BancoController extends Controller
{
    
    /**
     * Lists all Banco entities.
     *
     * @Route("/", name="banco")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:Banco')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Banco entity.
     *
     * @Route("/", name="banco_create")
     * @Method("POST")
     * @Template("AdminBundle:Banco:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Banco();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('banco_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Banco entity.
     *
     * @param Banco $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Banco $entity)
    {
        $form = $this->createForm(new BancoType(), $entity, array(
            'action' => $this->generateUrl('banco_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Banco entity.
     *
     * @Route("/new", name="banco_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Banco();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Banco entity.
     *
     * @Route("/{id}", name="banco_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Banco')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banco entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Banco entity.
     *
     * @Route("/{id}/edit", name="banco_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Banco')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banco entity.');
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
    * Creates a form to edit a Banco entity.
    *
    * @param Banco $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Banco $entity)
    {
        $form = $this->createForm(new BancoType(), $entity, array(
            'action' => $this->generateUrl('banco_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Banco entity.
     *
     * @Route("/{id}", name="banco_update")
     * @Method("PUT")
     * @Template("AdminBundle:Banco:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Banco')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banco entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('banco_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Banco entity.
     *
     * @Route("/{id}", name="banco_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:Banco')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Banco entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('banco'));
    }

    /**
     * Creates a form to delete a Banco entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('banco_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
