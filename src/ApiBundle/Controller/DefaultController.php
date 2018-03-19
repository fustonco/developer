<?php

namespace ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    public function getContent($request)
    {
        $data = json_decode($request->getContent(), true);
        return !empty($data) ? $data : array();
    }

    /**
     * @Route("/v1/login")
     * @Method("POST")
     */
    public function v1LoginAction(Request $request)
    {
        $request->request->replace($this->getContent($request));

        try {
            return new Response(json_encode([
                'user' => $request->get('username'),
                'pass' => $request->get('password')
            ]), 200);
        } catch(\Exception $e) {
            return new Response(json_encode('errorroror'), 500);
        }
    }
}
