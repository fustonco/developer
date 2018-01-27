<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\FuncionarioEmpresa;
use AdminBundle\Form\FuncionarioEmpresaType;

/**
 * FuncionarioEmpresa controller.
 *
 * @Route("/funcionarioempresa")
 */
class FuncionarioEmpresaController extends Controller
{

    /**
     * Lists all FuncionarioEmpresa entities.
     *
     * @Route("/", name="funcionarioempresa")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:FuncionarioEmpresa')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new FuncionarioEmpresa entity.
     *
     * @Route("/", name="funcionarioempresa_create")
     * @Method("POST")
     * @Template("AdminBundle:FuncionarioEmpresa:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new FuncionarioEmpresa();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('funcionarioempresa_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a FuncionarioEmpresa entity.
     *
     * @param FuncionarioEmpresa $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(FuncionarioEmpresa $entity)
    {
        $form = $this->createForm(new FuncionarioEmpresaType(), $entity, array(
            'action' => $this->generateUrl('funcionarioempresa_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new FuncionarioEmpresa entity.
     *
     * @Route("/new", name="funcionarioempresa_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FuncionarioEmpresa();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a FuncionarioEmpresa entity.
     *
     * @Route("/{id}", name="funcionarioempresa_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:FuncionarioEmpresa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FuncionarioEmpresa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FuncionarioEmpresa entity.
     *
     * @Route("/{id}/edit", name="funcionarioempresa_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:FuncionarioEmpresa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FuncionarioEmpresa entity.');
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
    * Creates a form to edit a FuncionarioEmpresa entity.
    *
    * @param FuncionarioEmpresa $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(FuncionarioEmpresa $entity)
    {
        $form = $this->createForm(new FuncionarioEmpresaType(), $entity, array(
            'action' => $this->generateUrl('funcionarioempresa_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing FuncionarioEmpresa entity.
     *
     * @Route("/{id}", name="funcionarioempresa_update")
     * @Method("PUT")
     * @Template("AdminBundle:FuncionarioEmpresa:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:FuncionarioEmpresa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FuncionarioEmpresa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('funcionarioempresa_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FuncionarioEmpresa entity.
     *
     * @Route("/{id}", name="funcionarioempresa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:FuncionarioEmpresa')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FuncionarioEmpresa entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('funcionarioempresa'));
    }

    /**
     * Creates a form to delete a FuncionarioEmpresa entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('funcionarioempresa_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
