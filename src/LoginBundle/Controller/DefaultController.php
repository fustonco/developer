<?php

namespace LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/" , name="login")
     */
    public function indexAction()
    {
        $authUtils = $this->get("security.authentication_utils");
        
        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();
    
        return $this->render("LoginBundle:Default:index.html.twig", array(
            "last_username" => $lastUsername,
            "error" => $error
        ));
    }
    
     /**
     * @Route("/redirectlogin" , name="redirectlogin")
     */
    public function redirectLoginAction()
    {
        switch ($this->getUser()->getIdTipo()->getId()){
            case 1:
                return $this->redirect("admin/departamento/");
                break;
            case 2:
                return $this->redirect("/funcionario");
                break;
            case 3:
                return $this->redirect("/financeiro");
                break;
        }
    }
}
