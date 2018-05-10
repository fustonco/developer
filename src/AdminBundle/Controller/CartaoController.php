<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\Cartao;
use AdminBundle\Form\CartaoType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cartão controller.
 *
 * @Route("/cartao")
 */
class CartaoController extends Controller
{

    /**
     * Lists all Cartao entities.
     *
     * @Route("/", name="cartoes")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:Cartao')->findByActive(true);

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Cartao entity.
     *
     * @Route("/", name="cartao_create")
     * @Method("POST")
     * @Template("AdminBundle:Cartao:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Cartao();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('cartao_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Cartao entity.
     *
     * @param Cartao $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Cartao $entity)
    {
        $form = $this->createForm(new CartaoType(), $entity, array(
            'action' => $this->generateUrl('cartao_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Criar'));

        return $form;
    }

    /**
     * Displays a form to create a new Cartao entity.
     *
     * @Route("/new", name="cartao_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Cartao();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Cartao entity.
     *
     * @Route("/{id}", name="cartao_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Cartao')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cartao entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
    
        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Cartao entity.
     *
     * @Route("/{id}/edit", name="cartao_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Cartao')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cartao entity.');
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
    * Creates a form to edit a Cartao entity.
    *
    * @param Cartao $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Cartao $entity)
    {
        $form = $this->createForm(new CartaoType(), $entity, array(
            'action' => $this->generateUrl('cartao_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Atualizar'));

        return $form;
    }
    /**
     * Edits an existing Cartao entity.
     *
     * @Route("/{id}", name="cartao_update")
     * @Method("PUT")
     * @Template("AdminBundle:Cartao:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Cartao')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Cartao entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('cartao_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Cartao entity.
     *
     * @Route("/{id}", name="cartao_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:Cartao')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Cartao entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cartao'));
    }

    /**
     * Creates a form to delete a Cartao entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cartao_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @Route("/editar/")
     */
    public function adminEditarCartaoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('titulo') || $request->get('titulo') == '') {throw new \Exception('error_titulo');}
            if(!$request->get('numero') || $request->get('numero') == '') {throw new \Exception('error_numero');}
            if(!$request->get('bandeira') || $request->get('bandeira') == '') {throw new \Exception('error_bandeira');}
            if(!$request->get('vencimento') || $request->get('vencimento') == '') {throw new \Exception('error_vencimento');}

            $cartao = $em->getRepository("AdminBundle:Cartao")->findOneById($request->get('id'));
            $cartao->setTitulo($request->get('titulo'));
            $cartao->setNumero($request->get('numero'));
            $cartao->setBandeira($request->get('bandeira'));
            $cartao->setVencimento(new \DateTime($request->get('vencimento')));
            $cartao->setCvc($request->get('cvc'));
            $cartao->setMelhorData(!empty($request->get('melhor_data')) ? new \DateTime($request->get('melhor_data')) : NULL);
            $em->persist($cartao);
            $em->flush();
            return new Response(json_encode([
                "description" => "Cartão Editado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_titulo':
                    return new Response(json_encode([
                        "description" => "Titulo não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_numero':
                    return new Response(json_encode([
                        "description" => "Número não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_bandeira':
                    return new Response(json_encode([
                        "description" => "Bandeira não pode ser vazia!"
                    ]), 500);
                break;
                case 'error_vencimento':
                    return new Response(json_encode([
                        "description" => "Data de vencimento não pode ser vazia!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Editar Cartão!"
            ]), 500);
        }
    }

    /**
     * @Route("/excluir/")
     */
    public function adminExcluirCartaoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $cartao = $em->getRepository("AdminBundle:Cartao")->findOneById($request->get('id'));
            $cartao->setActive(false);
            $em->flush();
            return new Response(json_encode([
                "description" => "Cartão Excluido com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            return new Response(json_encode([
                "description" => "Erro ao Excluir Cartão!"
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/")
     */
    public function adminCadastrarCartaoAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            if(!$request->get('titulo') || $request->get('titulo') == '') {throw new \Exception('error_titulo');}
            if(!$request->get('numero') || $request->get('numero') == '') {throw new \Exception('error_numero');}
            if(!$request->get('bandeira') || $request->get('bandeira') == '') {throw new \Exception('error_bandeira');}
            if(!$request->get('vencimento') || $request->get('vencimento') == '') {throw new \Exception('error_vencimento');}

            $cartao = new Cartao();
            $cartao->setTitulo($request->get('titulo'));
            $cartao->setNumero($request->get('numero'));
            $cartao->setBandeira($request->get('bandeira'));
            $cartao->setVencimento(new \DateTime($request->get('vencimento')));
            $cartao->setCvc($request->get('cvc'));
            $cartao->setMelhorData(!empty($request->get('melhor_data')) ? new \DateTime($request->get('melhor_data')) : NULL);
            $cartao->setActive(true);
            $em->persist($cartao);
            $em->flush();
            return new Response(json_encode([
                "description" => "Cartão Cadastrado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            switch($e->getMessage()){
                case 'error_titulo':
                    return new Response(json_encode([
                        "description" => "Titulo não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_numero':
                    return new Response(json_encode([
                        "description" => "Número não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_bandeira':
                    return new Response(json_encode([
                        "description" => "Bandeira não pode ser vazia!"
                    ]), 500);
                break;
                case 'error_vencimento':
                    return new Response(json_encode([
                        "description" => "Data de vencimento não pode ser vazia!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Cartão!"
            ]), 500);
        }
    }
}
