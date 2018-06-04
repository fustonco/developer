<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\Funcionario;
use AdminBundle\Entity\FuncionarioEmpresa;
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
     * @Route("/", name="admin_funcionario")
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
     * @Route("/", name="admin_funcionario_create")
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
     * @Route("/new", name="admin_funcionario_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Funcionario();

        $departamento = $em->getRepository('AdminBundle:Departamento')->findAll();
        $tipo = $em->getRepository('AdminBundle:TipoUsuario')->findAll();
        $empresas = $em->getRepository('AdminBundle:Empresa')->findAll();

        return array(
            'entity' => $entity,
            'departamentos' => $departamento,
            'tipos' => $tipo,
            'empresas' => $empresas
        );
    }

    /**
     * Finds and displays a Funcionario entity.
     *
     * @Route("/{id}", name="admin_funcionario_show")
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
     * @Route("/{id}/edit", name="admin_funcionario_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminBundle:Funcionario')->find($id);
        if (!$entity) {throw $this->createNotFoundException('Unable to find Funcionario entity.');}

        $departamento = $em->getRepository('AdminBundle:Departamento')->findAll();
        $tipo = $em->getRepository('AdminBundle:TipoUsuario')->findAll();
        $empresa = $em->getRepository('AdminBundle:Empresa')->findAll();
        $funcEmpresa = $em->getRepository('AdminBundle:FuncionarioEmpresa')->findByIdfuncionario($entity->getId());

        for($i = 0; $i < count($empresa); $i++) {
            for($j = 0; $j < count($funcEmpresa); $j++) {
                if($empresa[$i]->getId() == $funcEmpresa[$j]->getIdempresa()->getId()) {
                    $empresa[$i]->check = true;
                    break;
                } else {
                    $empresa[$i]->check = false;
                }
            }
        }

        return array(
            'entity'        => $entity,
            'departamentos' => $departamento,
            'tipos'         => $tipo,
            'empresas'      => $empresa,
            'funcEmpresa'   => $funcEmpresa
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
     * @Route("/{id}", name="admin_funcionario_update")
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

            return $this->redirect($this->generateUrl('admin_funcionario_edit', array('id' => $id)));
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
     * @Route("/{id}", name="admin_funcionario_delete")
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

        return $this->redirect($this->generateUrl('admin_funcionario'));
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
            ->setAction($this->generateUrl('admin_funcionario_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    /**
     * @Route("/editar/")
     */
    public function adminEditarFuncionarioAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('email') || $request->get('email') == '') {throw new \Exception('error_email');}

            $departamento = $em->getRepository('AdminBundle:Departamento')->findOneById($request->get('departamento'));
            if(!$departamento || $departamento == '') {throw new \Exception('error_departamento');}

            $tipo = $em->getRepository('AdminBundle:TipoUsuario')->findOneById($request->get('tipo'));
            if(!$tipo || $tipo == '') {throw new \Exception('error_tipo');}

            $funcionario = $em->getRepository("AdminBundle:Funcionario")->findOneById($request->get('id'));
            $funcionario->setNome($request->get('nome'));
            $funcionario->setEmail($request->get('email'));
            $funcionario->setLimiteAprovacao($request->get('limite'));
            $funcionario->setTelefone($request->get('telefone'));
            $funcionario->setCelular($request->get('celular'));
            $funcionario->setAtivo('S');
            $funcionario->setIddepartamento($departamento);
            $funcionario->setIdtipo($tipo);
            $em->persist($funcionario);
            $em->flush();

            $empresas = $em->getRepository('AdminBundle:FuncionarioEmpresa')->findByIdfuncionario($funcionario);
            for($k = 0; $k < count($empresas); $k++) {
                $em->remove($empresas[$k]);
                $em->flush();
            }

            $empresas = $request->get('empresa');
            for($i = 0; $i < count($empresas); $i++) {
                $funcionario_empresa = new FuncionarioEmpresa();
                $funcionario_empresa->setIdfuncionario($funcionario);
                $empresa = $em->getRepository('AdminBundle:Empresa')->findOneById($empresas[$i]);
                $funcionario_empresa->setIdempresa($empresa);
                $funcionario_empresa->setAtivo('S');
                $em->persist($funcionario_empresa);
                $em->flush();
            }

            $em->getConnection()->commit();
            return new Response(json_encode([
                "description" => "Funcionario Cadastrado com Sucesso!"
            ]), 200);
        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_email':
                    return new Response(json_encode([
                        "description" => "E-Mail não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_limite':
                    return new Response(json_encode([
                        "description" => "Limite não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_tipo':
                    return new Response(json_encode([
                        "description" => "Tipo não encontrado!"
                    ]), 500);
                break;
                case 'error_departamento':
                    return new Response(json_encode([
                        "description" => "Departamento não encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Funcionario!"
            ]), 500);
        }
    }

    /**
     * @Route("/excluir/")
     */
    public function adminExcluirFuncionarioAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $funcionario = $em->getRepository("AdminBundle:Funcionario")->findOneById($request->get('id'));
            $em->remove($funcionario);
            $em->flush();
            return new Response(json_encode([
                "description" => "Funcionario Excluido com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            if(strpos($e->getMessage(), 'FOREIGN')) {
                return new Response(json_encode([
                    "description" => "Não foi possível excluir esse Funcionario, pois existe registros nele, por favor altere-os para que seja possível a remoção segura."
                ]), 500);
            }
            return new Response(json_encode([
                "description" => "Erro ao Excluir Empresa!"
            ]), 500);
        }
    }

    /**
     * @Route("/cadastrar/")
     */
    public function adminCadastrarFuncionarioAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

            if(!$request->get('nome') || $request->get('nome') == '') {throw new \Exception('error_nome');}
            if(!$request->get('email') || $request->get('email') == '') {throw new \Exception('error_email');}

            $departamento = $em->getRepository('AdminBundle:Departamento')->findOneById($request->get('departamento'));
            if(!$departamento || $departamento == '') {throw new \Exception('error_departamento');}

            $tipo = $em->getRepository('AdminBundle:TipoUsuario')->findOneById($request->get('tipo'));
            if(!$tipo || $tipo == '') {throw new \Exception('error_tipo');}

            $funcionario = new Funcionario();
            $funcionario->setNome($request->get('nome'));
            $funcionario->setEmail($request->get('email'));
            $funcionario->setTelefone($request->get('telefone'));
            $funcionario->setCelular($request->get('celular'));
            $funcionario->setAtivo('S');
            $funcionario->setIddepartamento($departamento);
            $funcionario->setIdtipo($tipo);
            $funcionario->setSenha(md5('987654321'));
            $em->persist($funcionario);
            $em->flush();

            $empresas = $request->get('empresa');
            for($i = 0; $i < count($empresas); $i++) {
                $funcionario_empresa = new FuncionarioEmpresa();
                $funcionario_empresa->setIdfuncionario($funcionario);
                $empresa = $em->getRepository('AdminBundle:Empresa')->findOneById($empresas[$i]);
                $funcionario_empresa->setIdempresa($empresa);
                $funcionario_empresa->setAtivo('S');
                $em->persist($funcionario_empresa);
                $em->flush();
            }

            $em->getConnection()->commit();
            return new Response(json_encode([
                "description" => "Funcionario Cadastrado com Sucesso!"
            ]), 200);
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();
            switch($e->getMessage()){
                case 'error_nome':
                    return new Response(json_encode([
                        "description" => "Nome não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_email':
                    return new Response(json_encode([
                        "description" => "E-Mail não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_limite':
                    return new Response(json_encode([
                        "description" => "Limite não pode ser vazio!"
                    ]), 500);
                break;
                case 'error_tipo':
                    return new Response(json_encode([
                        "description" => "Tipo não encontrado!"
                    ]), 500);
                break;
                case 'error_departamento':
                    return new Response(json_encode([
                        "description" => "Departamento não encontrado!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Erro ao Cadastrar Funcionario!"
            ]), 500);
        }
    }
    
}
