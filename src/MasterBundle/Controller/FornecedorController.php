<?php

namespace MasterBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use MasterBundle\Entity\Fornecedor;
use MasterBundle\Form\FornecedorType;

/**
 * Fornecedor controller.
 *
 * @Route("/fornecedor")
 */
class FornecedorController extends Controller
{

    /**
     * Lists all Fornecedor entities.
     *
     * @Route("/", name="master_fornecedor")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MasterBundle:Fornecedor')->findAll();

        return array(
            'entities' => $entities
        );
    }
    /**
     * Creates a new Fornecedor entity.
     *
     * @Route("/", name="master_fornecedor_create")
     * @Method("POST")
     * @Template("MasterBundle:Fornecedor:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Fornecedor();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('master_fornecedor_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a form to create a Fornecedor entity.
     *
     * @param Fornecedor $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Fornecedor $entity)
    {
        $form = $this->createForm(new FornecedorType(), $entity, array(
            'action' => $this->generateUrl('master_fornecedor_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Fornecedor entity.
     *
     * @Route("/new", name="master_fornecedor_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Fornecedor();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Finds and displays a Fornecedor entity.
     *
     * @Route("/{id}", name="master_fornecedor_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MasterBundle:Fornecedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fornecedor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Fornecedor entity.
     *
     * @Route("/{id}/edit", name="master_fornecedor_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MasterBundle:Fornecedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fornecedor entity.');
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
    * Creates a form to edit a Fornecedor entity.
    *
    * @param Fornecedor $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Fornecedor $entity)
    {
        $form = $this->createForm(new FornecedorType(), $entity, array(
            'action' => $this->generateUrl('master_fornecedor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Fornecedor entity.
     *
     * @Route("/{id}", name="master_fornecedor_update")
     * @Method("PUT")
     * @Template("MasterBundle:Fornecedor:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MasterBundle:Fornecedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fornecedor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('master_fornecedor_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Fornecedor entity.
     *
     * @Route("/{id}", name="master_fornecedor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MasterBundle:Fornecedor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Fornecedor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('master_fornecedor'));
    }

    /**
     * Creates a form to delete a Fornecedor entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('master_fornecedor_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @Route("/editar/")
     */
    public function funcionarioEditarFornecedorAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            
            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}

            $fornecedor = $em->getRepository("MasterBundle:Fornecedor")->findOneById($request->get('id'));
            $fornecedor->setNome($request->get('nome'));
            $fornecedor->setCnpj($request->get('cnpj'));
            $fornecedor->setCpf($request->get('cpf'));
            $fornecedor->setTelefone($request->get('telefone'));
            $fornecedor->setEndereco($request->get('endereco'));
            $fornecedor->setAtivo('S');
            $em->persist($fornecedor);
            $em->flush();
            return new Response(json_encode([
                "description" => "Fornecedor Cadastrado com Sucesso!"
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
                "description" => "Erro ao Cadastrar Fornecedor!"
            ]), 500);
        }
    }

    /**
     * @Route("/excluir/")
     */
    public function funcionarioExcluirFornecedorAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $fornecedor = $em->getRepository("MasterBundle:Fornecedor")->findOneById($request->get('id'));
            $em->remove($fornecedor);
            $em->flush();
            return new Response(json_encode([
                "description" => "Fornecedor Excluido com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            if(strpos($e->getMessage(), 'FOREIGN')) {
                return new Response(json_encode([
                    "description" => "Não foi possível excluir esse Fornecedor, pois existe registros nele, por favor altere-os para que seja possível a remoção segura."
                ]), 500);
            }
            return new Response(json_encode([
                "description" => "Erro ao Excluir Fornecedor!"
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/")
     */
    public function funcionarioCadastrarFornecedorAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}

            $fornecedor = new Fornecedor();
            $fornecedor->setNome($request->get('nome'));
            $fornecedor->setCnpj($request->get('cnpj'));
            $fornecedor->setCpf($request->get('cpf'));
            $fornecedor->setTelefone($request->get('telefone'));
            $fornecedor->setEndereco($request->get('endereco'));
            $fornecedor->setAtivo('S');
            $em->persist($fornecedor);
            $em->flush();
            return new Response(json_encode([
                "description" => "Fornecedor Cadastrado com Sucesso!"
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
                "description" => "Erro ao Cadastrar Fornecedor!"
            ]), 500);
        }
    }
}