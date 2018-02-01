<?php

namespace FuncionarioBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FuncionarioBundle\Entity\Historico;
use FuncionarioBundle\Form\HistoricoType;

/**
 * Historico controller.
 *
 * @Route("/historico")
 */
class HistoricoController extends Controller
{

    /**
     * Lists all Historico entities.
     *
     * @Route("/", name="historico")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FuncionarioBundle:Historico')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Historico entity.
     *
     * @Route("/", name="historico_create")
     * @Method("POST")
     * @Template("FuncionarioBundle:Historico:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Historico();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('historico_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Historico entity.
     *
     * @param Historico $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Historico $entity)
    {
        $form = $this->createForm(new HistoricoType(), $entity, array(
            'action' => $this->generateUrl('historico_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Historico entity.
     *
     * @Route("/new", name="historico_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Historico();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Historico entity.
     *
     * @Route("/{id}", name="historico_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FuncionarioBundle:Historico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Historico entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Historico entity.
     *
     * @Route("/{id}/edit", name="historico_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FuncionarioBundle:Historico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Historico entity.');
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
    * Creates a form to edit a Historico entity.
    *
    * @param Historico $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Historico $entity)
    {
        $form = $this->createForm(new HistoricoType(), $entity, array(
            'action' => $this->generateUrl('historico_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Historico entity.
     *
     * @Route("/{id}", name="historico_update")
     * @Method("PUT")
     * @Template("FuncionarioBundle:Historico:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FuncionarioBundle:Historico')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Historico entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('historico_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Historico entity.
     *
     * @Route("/{id}", name="historico_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FuncionarioBundle:Historico')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Historico entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('historico'));
    }

    /**
     * Creates a form to delete a Historico entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('historico_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
