<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\Funcionario;
use AdminBundle\Form\FuncionarioType;
use AdminBundle\Form\TipoUsuario;
use Symfony\Component\HttpFoundation\Response;

/**
 * Funcionario controller.
 *
 * @Route("/funcionario")
 */
class FuncionarioController extends Controller
{

    /**
     * Lists all Funcionario entities.
     *
     * @Route("/", name="funcionario")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $statement = $connection->prepare("SELECT f.id, f.nome, f.email, f.limite_aprovacao limiteAprovacao, f.telefone, f.celular, d.nome nomeDepartamento, tu.nome tipo FROM funcionario f
        LEFT JOIN departamento d ON d.id = f.iddepartamento
        LEFT JOIN funcionario_empresa fe ON fe.idfuncionario = f.id
        LEFT JOIN tipo_usuario tu ON tu.id = f.idtipo
        GROUP BY f.id");
        $statement->execute();
        $entities = $statement->fetchAll();

        $empresas = $em->getRepository('AdminBundle:Empresa')->findAll();

        return array(
            'entities' => $entities,
            'empresas' => $empresas
        );
    }
    /**
     * Creates a new Funcionario entity.
     *
     * @Route("/", name="funcionario_create")
     * @Method("POST")
     * @Template("AdminBundle:Funcionario:new.html.twig")
     */
    public function createAction(Request $request)
    {
        // $entity = new Funcionario();
        // $form = $this->createCreateForm($entity);
        // $form->handleRequest($request);

        // if ($form->isValid()) {
        //     $em = $this->getDoctrine()->getManager();
        //     $em->persist($entity);
        //     $em->flush();

        //     return $this->redirect($this->generateUrl('funcionario_show', array('id' => $entity->getId())));
        // }

        // return array(
        //     'entity' => $entity,
        //     'form'   => $form->createView(),
        // );
    }

    /**
     * Creates a form to create a Funcionario entity.
     *
     * @param Funcionario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Funcionario $entity)
    {
        // $form = $this->createForm(new FuncionarioType(), $entity, array(
        //     'action' => $this->generateUrl('funcionario_create'),
        //     'method' => 'POST',
        // ));

        // $form->add('submit', 'submit', array('label' => 'Create'));

        // return $form;
    }

    /**
     * Displays a form to create a new Funcionario entity.
     *
     * @Route("/new", name="funcionario_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Funcionario();
        // $form   = $this->createCreateForm($entity);

        $departamento = $em->getRepository('AdminBundle:Departamento')->findAll();
        $tipo = $em->getRepository('AdminBundle:TipoUsuario')->findAll();
        $empresa = $em->getRepository('AdminBundle:Empresa')->findAll();

        return array(
            'entity' => $entity,
            // 'form'   => $form->createView(),
            'departamentos' => $departamento,
            'tipos' => $tipo,
            'empresas' => $empresa
        );
    }

    /**
     * Finds and displays a Funcionario entity.
     *
     * @Route("/{id}", name="funcionario_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Funcionario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Funcionario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Funcionario entity.
     *
     * @Route("/{id}/edit", name="funcionario_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Funcionario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Funcionario entity.');
        }

        // $editForm = $this->createEditForm($entity);
        // $deleteForm = $this->createDeleteForm($id);

        $departamento = $em->getRepository('AdminBundle:Departamento')->findAll();
        $tipo = $em->getRepository('AdminBundle:TipoUsuario')->findAll();
        $empresa = $em->getRepository('AdminBundle:Empresa')->findAll();

        $funcEmpresa = $em->getRepository('AdminBundle:FuncionarioEmpresa')->findOneByIdfuncionario($entity->getId());
        if($funcEmpresa) $funcEmpresa = $funcEmpresa->getIdempresa()->getId();

        return array(
            'entity'      => $entity,
            // 'edit_form'   => $editForm->createView(),
            // 'delete_form' => $deleteForm->createView(),
            'departamentos' => $departamento,
            'tipos' => $tipo,
            'empresas' => $empresa,
            'funcEmpresa' => $funcEmpresa
        );
    }

    /**
    * Creates a form to edit a Funcionario entity.
    *
    * @param Funcionario $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Funcionario $entity)
    {
        $form = $this->createForm(new FuncionarioType(), $entity, array(
            'action' => $this->generateUrl('funcionario_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Funcionario entity.
     *
     * @Route("/{id}", name="funcionario_update")
     * @Method("PUT")
     * @Template("AdminBundle:Funcionario:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Funcionario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Funcionario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('funcionario_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Funcionario entity.
     *
     * @Route("/{id}", name="funcionario_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:Funcionario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Funcionario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('funcionario'));
    }

    /**
     * Creates a form to delete a Funcionario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('funcionario_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
