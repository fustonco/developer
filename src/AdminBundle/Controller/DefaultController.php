<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use AdminBundle\Entity\Empresa;
use AdminBundle\Entity\Departamento;
use AdminBundle\Entity\Grupo;
use AdminBundle\Entity\Funcionario;
use AdminBundle\Entity\FuncionarioEmpresa;
use AdminBundle\Entity\Fornecedor;

class DefaultController extends Controller
{
    public function serializeJSON($entity) {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($entity, 'json');
        return $jsonContent;
    }

    /**
     * @Route("/")
     */
    public function AdminAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $grupo = $em->getRepository('AdminBundle:Grupo')->findAll();
        $empresa = $em->getRepository('AdminBundle:Empresa')->findAll();
        $departamento = $em->getRepository('AdminBundle:Departamento')->findAll();
        $funcionario = $em->getRepository('AdminBundle:Funcionario')->findAll();
        $fornecedor = $em->getRepository('AdminBundle:Fornecedor')->findAll();

        return $this->render("AdminBundle:Default:index.html.twig", [
            'countGrupo'  => count($grupo),
            'countEmpresa'  => count($empresa),
            'countDepartamento'  => count($departamento),
            'countFuncionario'  => count($funcionario),
            'countFornecedor'  => count($fornecedor),
        ]);
    }

    /**
     * @Route("/pedidos/")
     */
    public function pedidosAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $de = $request->get('de') ? $request->get('de') : '';
        $ate = $request->get('ate') ? $request->get('ate') : '';
        $str = "";
        if(($de || $de != "") && ($ate || $ate != "")) {$str = "WHERE p.data_pedido BETWEEN '".$de." 00:00:01' AND '".$ate." 23:59:59' ";}

        $entities = $em->getConnection()->prepare("
        SELECT sp.id id_status_pedido, th.nome status, e.nome empresa, f.nome funcionario, f.id idFuncionario, fo.nome fornecedor, tu.id id_tipo_funcionario, tu.nome tipo_funcionario, p.id, p.codigo, p.idTipo, p.idFornecedor, p.data_pedido dataPedido, p.valor, p.descricao, p.ativo, sp.nome hStatus
        FROM pedido p
        INNER JOIN status_pedido sp ON sp.id = p.status
        INNER JOIN (SELECT MAX(id) id, idPedido FROM historico GROUP BY idPedido) ht ON ht.idPedido = p.id
        INNER JOIN historico h ON ht.id = h.id
        INNER JOIN tipo_historico th ON th.id = h.tipo_historico_id
        INNER JOIN funcionario f ON f.id = h.idPara
        INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
        INNER JOIN empresa e ON e.id = p.idEmpresa
        INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
        ".$str."
        ORDER BY p.id DESC
        ");
        $entities->execute();
        $entities = $entities->fetchAll();

        return $this->render("AdminBundle:Default:pedidos.html.twig", [
            'entities' => $entities,
            'de' => $de,
            'ate' => $ate
        ]);
    }

    /**
     * @Route("/ver/")
     */
    public function funcionarioVerPedidoAction(Request $request)
    {
        $default = new DefaultController;
        $id = $request->get('id');
        try {
            $em = $this->getDoctrine()->getManager();

            $pedido = $em->getConnection()->prepare("
            SELECT p.id, p.codigo, f.nome para, fo.nome fornecedorNome, fo.cnpj fornecedorCnpj, fo.cpf fornecedorCpf, fo.telefone fornecedorTelefone, fo.endereco fornecedorEndereco, tu.nome tipo_para, tp.nome tipo, p.idFornecedor, p.data_pedido, p.valor, p.descricao, p.ativo, sp.nome status, m.mensagem, e.nome empresa
            FROM pedido p 
            INNER JOIN tipo_pedido tp ON tp.id = p.idTipo
            INNER JOIN status_pedido sp ON sp.id = p.status
            INNER JOIN empresa e ON p.idEmpresa = e.id
            LEFT JOIN historico h ON h.idPedido = p.id
            LEFT JOIN mensagem m ON h.idMensagem = m.id
            INNER JOIN funcionario f ON f.id = h.idPara
            INNER JOIN tipo_usuario tu ON tu.id = f.idTipo
            INNER JOIN fornecedor fo ON fo.id = p.idFornecedor
            WHERE p.id = ".$id."
            ORDER BY h.id DESC
            ");
            $pedido->execute();
            $pedido = $pedido->fetchAll();

            $historico = $em->getRepository('FuncionarioBundle:Historico')->findByIdpedido($id);
            $historico = $default->serializeJSON($historico);

            $pagamentos = $em->getConnection()->prepare("SELECT pg.id, t.id tipo_id, t.nome tipo, pg.valor_integral valor, pc.parcelas parcelas_total, IF(pcp.parcelas, pcp.parcelas, 0) parcelas_pagas, c.titulo, c.numero, c.bandeira, DATE_FORMAT(c.vencimento, '%d/%m/%Y') vencimento, DATE_FORMAT(c.melhor_data, '%d/%m/%Y') melhor_data, c.cvc, co.banco, co.agencia, co.conta, co.cpf, co.cnpj, ct.nome conta_tipo FROM pagamento pg
            INNER JOIN tipo_pagamento t ON pg.idTipo = t.id
            INNER JOIN pedido p ON idPedido = p.id
            LEFT JOIN (SELECT idPagamento, count(id) parcelas FROM parcelas GROUP BY idPagamento) pc ON pc.idPagamento = pg.id
            LEFT JOIN (SELECT idPagamento, count(id) parcelas FROM parcelas WHERE status = 2 GROUP BY idPagamento) pcp ON pcp.idPagamento = pg.id
            LEFT JOIN cartao c ON pg.cartao = c.id
            LEFT JOIN conta co ON pg.conta = co.id
            LEFT JOIN conta_tipo ct ON co.tipo = ct.id
            WHERE idPedido = ?");
            $pagamentos->execute(array($id));
            $pagamentos = $pagamentos->fetchAll();

            for($i = 0; $i < count($pagamentos); $i = $i + 1){
                $parcelas = $em->getConnection()->prepare("SELECT p.id, num_parcela, valor, valor_pago, valor_pendente, valor_desconto, valor_acrecimo, DATE_FORMAT(data_vencimento, '%d/%m/%Y') data_vencimento, s.nome status, s.id status_id FROM parcelas p
                INNER JOIN status_parcela s ON p.status = s.id
                WHERE idPagamento = ?");
                $parcelas->execute(array($pagamentos[$i]["id"]));
                $pagamentos[$i]["parcelas"] = $parcelas->fetchAll();
            }

            $anexos = $em->getConnection()->prepare("SELECT a.caminho
            FROM pedido_anexo pa
            INNER JOIN anexo a ON a.id = pa.idAnexo
            WHERE pa.idPedido = ?");
            $anexos->execute(array($id));
            $anexos = $anexos->fetchAll();

            return new Response(json_encode([
                'pedido' => $pedido,
                'historico' => json_decode($historico),
                'pagamentos' => $pagamentos,
                'anexos' => $anexos
            ]), 200);
        } catch(\Exception $e) {
            return new Response(json_encode([
                "description" => "Erro ao Ver Pedido!"
            ]), 500);
        }
    }
    
    /**
     * @Route("/find/empresas/" , name="findEmpresas")
     */
    public function FindEmpresasAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $empresas = $em->getRepository('AdminBundle:FuncionarioEmpresa')->findByIdfuncionario($request->get('id'));

            return new Response(json_encode([
                "description" => json_decode($this->serializeJSON($empresas))
            ]), 200);
        } catch(\Exception $e) {
            return new Response(json_encode([
                "description" => "Empresas nao encontradas"
            ]), 500);
        }
    }

    /**
     * @Route("/remove/empresa/" , name="removeEmpresa")
     */
    public function RemoveEmpresaAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $empresafuncionario = $em->getRepository('AdminBundle:FuncionarioEmpresa')->findOneBy([
                'idfuncionario' => $request->get('idFuncionario'),
                'idempresa' => $request->get('idEmpresa')
            ]);
            if($empresafuncionario) {
                $em->remove($empresafuncionario);
                $em->flush();
            } else {
                throw new \Exception('error_funcionarioempresa');
            }

            return new Response(json_encode([
                "description" => "Empresa removida com sucesso!"
            ]), 200);
        } catch(\Exception $e) {
            switch($e->getMessage()){
                case 'error_funcionarioempresa':
                    return new Response(json_encode([
                        "description" => "Não associados!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Não foi possível remover empresa desse funcionario!"
            ]), 500);
        }
    }

    /**
     * @Route("/add/empresa/" , name="addEmpresa")
     */
    public function AddEmpresaAction(Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $empresafuncionario = $em->getRepository('AdminBundle:FuncionarioEmpresa')->findOneBy([
                'idfuncionario' => $request->get('idFuncionario'),
                'idempresa' => $request->get('idEmpresa')
            ]);
            if(!$empresafuncionario) {
                $func = $em->getRepository('AdminBundle:Funcionario')->findOneById($request->get('idFuncionario'));
                if(!$func) {throw new \Exception('error_func');}
                $emp = $em->getRepository('AdminBundle:Empresa')->findOneById($request->get('idEmpresa'));
                if(!$emp) {throw new \Exception('error_emp');}
                $empfunc = new FuncionarioEmpresa();
                $empfunc->setIdfuncionario($func);
                $empfunc->setIdempresa($emp);
                $empfunc->setAtivo('S');
                $em->persist($empfunc);
                $em->flush();
            } else {
                throw new \Exception('error_funcionarioempresa');
            }

            return new Response(json_encode([
                "description" => "Empresa associada com sucesso!"
            ]), 200);
        } catch(\Exception $e) {
            switch($e->getMessage()){
                case 'error_funcionarioempresa':
                    return new Response(json_encode([
                        "description" => "Já associados!"
                    ]), 500);
                break;
                case 'error_func':
                    return new Response(json_encode([
                        "description" => "Funcionario não encontrado!"
                    ]), 500);
                break;
                case 'error_emp':
                    return new Response(json_encode([
                        "description" => "Empresa não encontrada!"
                    ]), 500);
                break;
            }
            return new Response(json_encode([
                "description" => "Não foi possível adicionar empresa para esse funcionario!"
            ]), 500);
        }
    }
    
}