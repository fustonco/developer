<?php

namespace FinanceiroBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        
        $em = $this->getDoctrine()->getManager();
        
        $parcelas = $em->getRepository("FinanceiroBundle:Parcelas")->findOneBy(array(
            'dataVencimento' => date_create(),
            'status' => '1'
        ));

        $idPag = $parcelas->getIdPagamento();
        
        
        //findBy(array('data_vencimento' <= 'current_date', 'status' => '1'))
        
        return $this->render("FinanceiroBundle:Default:index.html.twig", [
            'parcelas'  => $parcelas,
            'teste' =>$idPag
        ]);
    }
}
