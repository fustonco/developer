<?php

namespace FinanceiroBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FinanceiroBundle extends Bundle
{
    
    /**
    * @Route("/")
    */
    public function indexAction()
    {
        return $this->render("FinanceiroBundle:Default:index.html.twig");
    }
    
}
