<?php

namespace FuncionarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="func")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        $statement = $connection->prepare("SELECT count(id) count FROM grupo");
        $statement->execute();
        $countGrupo = $statement->fetch();

        $statement = $connection->prepare("SELECT count(id) count FROM empresa");
        $statement->execute();
        $countEmpresa = $statement->fetch();

        $statement = $connection->prepare("SELECT count(id) count FROM departamento");
        $statement->execute();
        $countDepartamento = $statement->fetch();

        $statement = $connection->prepare("SELECT count(id) count FROM funcionario");
        $statement->execute();
        $countFuncionario = $statement->fetch();

        $statement = $connection->prepare("SELECT count(id) count FROM fornecedor");
        $statement->execute();
        $countFornecedor = $statement->fetch();

        return $this->render("AdminBundle:Default:index.html.twig", [
            'countGrupo'  => $countGrupo,
            'countEmpresa'  => $countEmpresa,
            'countDepartamento'  => $countDepartamento,
            'countFuncionario'  => $countFuncionario,
            'countFornecedor'  => $countFornecedor,
        ]);
    }

}