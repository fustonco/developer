<?php

namespace LoginBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/politica" , name="politica")
     */
    public function politicaAction()
    {
        return $this->render("LoginBundle:Default:politica.html.twig");
    }
    
    /**
     * @Route("/download" , name="download")
     */
    public function downloadAction()
    {
        return $this->render("LoginBundle:Default:download.html.twig");
    }
    
    
    /**
     * @Route("/renew/" , name="renewPassNull")
     */
    public function renewPassNullAction()
    {
        return $this->redirect("/");
    }

    /**
     * @Route("/renew/{token}" , name="renewPass")
     */
    public function renewPassAction($token)
    {
        return $this->render("LoginBundle:Default:renew-pass.html.twig", [
            "token" => $token
        ]);
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

    /**
     * @Route("/change-password/")
     */
    public function changePasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $oldPassword = $request->request->get("old_password");
        $newPassword = $request->request->get("new_password");
        $confirmPassword = $request->request->get("confirm_password");

        try{
            if(empty($oldPassword) || empty($newPassword) || empty($confirmPassword)){
                throw new \Exception("Dados incompletos", 500);
            }

            $oldPassword = md5($oldPassword);
            $newPassword = md5($newPassword);
            $confirmPassword = md5($confirmPassword);

            if($newPassword != $confirmPassword){
                throw new \Exception("Nova senha e confirmar senha devem ser iguais", 500);
            }

            if($this->getUser()->getSenha() != $oldPassword){
                throw new \Exception("A senha antiga está errada", 500);
            }

            $this->getUser()->setSenha($newPassword);

            $em->flush();

            return new Response("success");
        }
        catch(\Exception $e){
            $message = !empty($e->getMessage()) ? $e->getMessage() : "Ocorreu um erro";
            $status = !empty($e->getCode()) ? $e->getCode() : 500;
    
            return new Response($message, $status);
        }
    }

    //Essa função gera um valor de String aleatório do tamanho recebendo por parametros
    function randString($size){
        //String com valor possíveis do resultado, os caracteres pode ser adicionado ou retirados conforme sua necessidade
        $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $return= "";
        for($count= 0; $size > $count; $count++){
            //Gera um caracter aleatorio
            $return .= $basic[rand(0, strlen($basic) - 1)];
        }
        return $return;
    }

    /**
     * @Route("/forget-password/", name="forgetPassword")
     */
    public function forgetPasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $email = $request->get('email');
            if(!$email) throw new \Exception("Email não pode ser vazio");

            $usuario = $em->getRepository('LoginBundle:Funcionario')->findOneByEmail($email);
            if(!$usuario) throw new \Exception("Usuário não encontrado");
            $usuario->setTokenForget($this->randString(20));
            $em->persist($usuario);
            $em->flush();

            $message = new \Swift_Message('Resetar Senha - PDALL');
            $message->setFrom('contato@pdall.com.br')
                ->setTo($email)
                ->setBody(
                    $this->renderView('LoginBundle:Default:forget-password.html.twig', [
                        'usuario' => $usuario
                    ])
                    ,'text/html');
            $mailer = $this->get('swiftmailer.mailer.spool_mailer');
            $mailer->send($message);

            return new Response(json_encode([
                "description" => "Email de recuperação enviado com sucesso! Acesse seu email para seguir os próximos passos."
            ]), 200);
        }
        catch(\Exception $e){
            $message = !empty($e->getMessage()) ? $e->getMessage() : "Ocorreu um erro";
            $status = !empty($e->getCode()) ? $e->getCode() : 500;
            return new Response($message, $status);
        }
    }
    

    /**
     * @Route("/renew-password/", name="renewPassword")
     */
    public function renewPasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $token = trim($request->get('token'));
            if(!$token) throw new \Exception("Token não pode ser vazio!");
            
            $password = trim($request->get('password'));
            if(!$password) throw new \Exception("Senha não pode ser vazia!");
            
            $cpassword = trim($request->get('cpassword'));
            if(!$cpassword) throw new \Exception("Confirmar Senha não pode ser vazia!");

            if($cpassword != $password) throw new \Exception("Senhas não são iguais!");

            $usuario = $em->getRepository('LoginBundle:Funcionario')->findOneByTokenForget($token);
            if(!$usuario) throw new \Exception("Token Inválido, por favor requisite a troca de senha novamente!");
            $usuario->setTokenForget(NULL);
            $usuario->setSenha(md5($password));
            $em->persist($usuario);
            $em->flush();
            
            return new Response(json_encode([
                "description" => "Nova senha inserida com sucesso!"
            ]), 200);
        }
        catch(\Exception $e){
            $message = !empty($e->getMessage()) ? $e->getMessage() : "Ocorreu um erro";
            $status = !empty($e->getCode()) ? $e->getCode() : 500;
            return new Response($message, $status);
        }
    }
}
