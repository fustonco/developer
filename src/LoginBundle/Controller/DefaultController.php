<?php

namespace LoginBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


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
            "error" => $error,
        ));
    }

    /**
     * @Route("/logar/{_username}/{_password}" , name="loginReact")
     */
    public function indexLogarAction($_username, $_password)
    {
        $em = $this->getDoctrine()->getManager();
        $userEntity = $em->getRepository('LoginBundle:Funcionario')->findOneBy([
            'email' => $_username,
            'senha' => md5($_password)
        ]);
        $token = new UsernamePasswordToken($userEntity, null, 'login_secured_area', $userEntity->getRoles());
        $this->get('security.context')->setToken($token);
        return $this->redirect("/redirectlogin");
    }
    
     /**
     * @Route("/redirectlogin" , name="redirectlogin")
     */
    public function redirectLoginAction()
    {
        switch ($this->getUser()->getIdTipo()->getId()){
            case 1:
                return $this->redirect("/admin");
                break;
            case 2:
                return $this->redirect("/funcionario/pedido/");
                break;
            case 3:
                return $this->redirect("/financeiro");
                break; 
            case 4:
                return $this->redirect("/chefe");
                break;
            case 5:
                return $this->redirect("/master");
                break;
        }
    }
}
