<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\Empresa;
use AdminBundle\Form\EmpresaType;

/**
 * Empresa controller.
 *
 * @Route("/empresa")
 */
class EmpresaController extends Controller
{

    /**
     * Lists all Empresa entities.
     *
     * @Route("/", name="empresa")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:Empresa')->findAll();

        return array(
            'entities' => $entities
        );
    }
    /**
     * Creates a new Empresa entity.
     *
     * @Route("/", name="empresa_create")
     * @Method("POST")
     * @Template("AdminBundle:Empresa:new.html.twig")
     */
    public function createAction(Request $request)
    {
        // $entity = new Empresa();
        // $form = $this->createCreateForm($entity);
        // $form->handleRequest($request);

        // if ($form->isValid()) {
        //     $em = $this->getDoctrine()->getManager();
        //     $em->persist($entity);
        //     $em->flush();

        //     return $this->redirect($this->generateUrl('empresa_show', array('id' => $entity->getId())));
        // }

        // return array(
        //     'entity' => $entity,
        //     'form'   => $form->createView()
        // );
    }

    /**
     * Creates a form to create a Empresa entity.
     *
     * @param Empresa $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Empresa $entity)
    {
        // $form = $this->createForm(new EmpresaType(), $entity, array(
        //     'action' => $this->generateUrl('empresa_create'),
        //     'method' => 'POST',
        // ));

        // $form->add('submit', 'submit', array('label' => 'Create'));

        // return $form;
    }

    /**
     * Displays a form to create a new Empresa entity.
     *
     * @Route("/new", name="empresa_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Empresa();
        $em = $this->getDoctrine()->getManager();

        $grupo = $em->getRepository('AdminBundle:Grupo')->findAll();

        return array(
            'entity' => $entity,
            'grupos'  => $grupo
        );
    }

    /**
     * Finds and displays a Empresa entity.
     *
     * @Route("/{id}", name="empresa_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Empresa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Empresa entity.');
        }

        // $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            // 'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Empresa entity.
     *
     * @Route("/{id}/edit", name="empresa_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Empresa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Empresa entity.');
        }

        // $editForm = $this->createEditForm($entity);
        // $deleteForm = $this->createDeleteForm($id);

        $grupo = $em->getRepository("AdminBundle:Grupo")->findAll();

        return array(
            'entity'      => $entity,
            // 'edit_form'   => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
            'grupos'      => $grupo

        );
    }

    /**
    * Creates a form to edit a Empresa entity.
    *
    * @param Empresa $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Empresa $entity)
    {
        $form = $this->createForm(new EmpresaType(), $entity, array(
            'action' => $this->generateUrl('empresa_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Empresa entity.
     *
     * @Route("/{id}", name="empresa_update")
     * @Method("PUT")
     * @Template("AdminBundle:Empresa:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Empresa')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Empresa entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('empresa_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Empresa entity.
     *
     * @Route("/{id}", name="empresa_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:Empresa')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Empresa entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('empresa'));
    }

    /**
     * Creates a form to delete a Empresa entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('empresa_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
