<?php

namespace FuncionarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/funcionario")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="funcionario")
     */
    public function FuncionarioAction()
    {
        return $this->render("AdminBundle:Default:index.html.twig");
    }
}