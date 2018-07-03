<?php

namespace ChefeBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ChefeBundle\Entity\Fornecedor;
use ChefeBundle\Form\FornecedorType;

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
     * @Route("/", name="chefe_fornecedor")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ChefeBundle:Fornecedor')->findAll();

        return array(
            'entities' => $entities
        );
    }
    /**
     * Creates a new Fornecedor entity.
     *
     * @Route("/", name="chefe_fornecedor_create")
     * @Method("POST")
     * @Template("ChefeBundle:Fornecedor:new.html.twig")
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

            return $this->redirect($this->generateUrl('chefe_fornecedor_show', array('id' => $entity->getId())));
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
            'action' => $this->generateUrl('chefe_fornecedor_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Fornecedor entity.
     *
     * @Route("/new", name="chefe_fornecedor_new")
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
     * @Route("/{id}", name="chefe_fornecedor_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChefeBundle:Fornecedor')->find($id);

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
     * @Route("/{id}/edit", name="chefe_fornecedor_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChefeBundle:Fornecedor')->find($id);

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
            'action' => $this->generateUrl('chefe_fornecedor_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Fornecedor entity.
     *
     * @Route("/{id}", name="chefe_fornecedor_update")
     * @Method("PUT")
     * @Template("ChefeBundle:Fornecedor:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ChefeBundle:Fornecedor')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fornecedor entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chefe_fornecedor_edit', array('id' => $id)));
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
     * @Route("/{id}", name="chefe_fornecedor_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ChefeBundle:Fornecedor')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Fornecedor entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('chefe_fornecedor'));
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
            ->setAction($this->generateUrl('chefe_fornecedor_delete', array('id' => $id)))
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
            
            $nome = trim($request->get('nome'));
            $fantasia = trim($request->get('fantasia'));
            $cpf = trim($request->get('cpf'));
            $cnpj = trim($request->get('cnpj'));
            if($nome == '') {throw new \Exception('error_nome');}
            if($cpf == '' && $cnpj == '') {throw new \Exception('error_cpf_cnpj');}
            if($cpf != ''){
                $has_cpf = $em->getRepository('ChefeBundle:Fornecedor')->findOneByCpf($cpf);
                if($has_cpf && $has_cpf->getId() != $request->get('id')) throw new \Exception('error_cpf');
            }
            if($cnpj != ''){
                $has_cnpj = $em->getRepository('ChefeBundle:Fornecedor')->findOneByCnpj($cnpj);
                if($has_cnpj && $has_cnpj->getId() != $request->get('id')) throw new \Exception('error_cnpj');
            }

            $fornecedor = $em->getRepository("ChefeBundle:Fornecedor")->findOneById($request->get('id'));
            $fornecedor->setNome($nome);
            $fornecedor->setFantasia($fantasia);
            $fornecedor->setCnpj($cnpj);
            $fornecedor->setCpf($cpf);
            $fornecedor->setTelefone($request->get('telefone'));
            $fornecedor->setCelular($request->get('celular'));
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
                case 'error_cpf_cnpj':
                    return new Response(json_encode([
                        "description" => "Precisa ter um CNPJ ou um CPF!"
                    ]), 500);
                break;
                case 'error_cpf':
                    return new Response(json_encode([
                        "description" => "Já existe esse CPF em nossa base de dados!"
                    ]), 500);
                break;
                case 'error_cnpj':
                    return new Response(json_encode([
                        "description" => "Já existe esse CNPJ em nossa base de dados!"
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
            $fornecedor = $em->getRepository("ChefeBundle:Fornecedor")->findOneById($request->get('id'));
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

            $nome = trim($request->get('nome'));
            $fantasia = trim($request->get('fantasia'));
            $cpf = trim($request->get('cpf'));
            $cnpj = trim($request->get('cnpj'));
            if($nome == '') {throw new \Exception('error_nome');}
            if($cpf == '' && $cnpj == '') {throw new \Exception('error_cpf_cnpj');}
            if($cpf != ''){
                $has_cpf = $em->getRepository('ChefeBundle:Fornecedor')->findOneByCpf($cpf);
                if($has_cpf) throw new \Exception('error_cpf');
            }
            if($cnpj != ''){
                $has_cnpj = $em->getRepository('ChefeBundle:Fornecedor')->findOneByCnpj($cnpj);
                if($has_cnpj) throw new \Exception('error_cnpj');
            }

            $fornecedor = new Fornecedor();
            $fornecedor->setNome($nome);
            $fornecedor->setFantasia($fantasia);
            $fornecedor->setCnpj($cnpj);
            $fornecedor->setCpf($cpf);
            $fornecedor->setTelefone($request->get('telefone'));
            $fornecedor->setCelular($request->get('celular'));
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
                case 'error_cpf_cnpj':
                    return new Response(json_encode([
                        "description" => "Precisa ter um CNPJ ou um CPF!"
                    ]), 500);
                break;
                case 'error_cpf':
                    return new Response(json_encode([
                        "description" => "Já existe esse CPF em nossa base de dados!"
                    ]), 500);
                break;
                case 'error_cnpj':
                    return new Response(json_encode([
                        "description" => "Já existe esse CNPJ em nossa base de dados!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Fornecedor!"
            ]), 500);
        }
    }
}
