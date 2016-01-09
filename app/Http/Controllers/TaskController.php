<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;

class TaskController extends BaseController
{

    public function indexAction(Request $request)
    {
        // Load grid
        if ($request->getMethod() == 'GET') {
            return view('tasks.index');
        }

        $tasks = Tasks::all();

        return JsonResponse::create([
            'rows' => $tasks,
            'total' => $tasks->count()
        ]);




//            $daoCadastros = App_Model_DAO_Cadastros::getInstance();
//
//            $retorno = array('total' => 0, 'rows' => array());
//
//            $select = $daoCadastros->getAdapter()->select()
//                ->from($daoCadastros->info('name'))
//                ->limit($this->getRequest()->getParam('limit', 30), $this->getRequest()->getParam('offset', 0))
//                ->order("{$this->getRequest()->getParam('sort', 'cad_dataCadastro')} {$this->getRequest()->getParam('order', 'DESC')}");
//
//            // Filtro
//            if (($search = $this->getRequest()->getParam('search', false)) != false) {
//                $select->where('cad_nome LIKE ? OR cad_email LIKE ?', "%{$search}%");
//            }
//
//            $rsCadastros = $daoCadastros->createRowset(
//                $daoCadastros->getAdapter()->fetchAll($select)
//            );
//
//            $base = Zend_Registry::get('config')->paths->admin->base;
//
//            foreach ($rsCadastros as $cadastro) {
//                $retorno['rows'][] = array(
//                    'cad_idCadastro' => $cadastro->getCodigo(),
//                    'cad_dataCadastro' => App_Funcoes_Date::conversion($cadastro->getDataCadastro()),
//                    'cad_nome' => $cadastro->getNome(),
//                    'cad_email' => $cadastro->getEmail(),
//                    'cad_status' => $cadastro->getStatus(),
//                    'status_label' => App_Funcoes_Rotulos::$status[$cadastro->getStatus()]['label'],
//                    'status_class' => App_Funcoes_Rotulos::$status[$cadastro->getStatus()]['class'],
//                    'edit' => $base .'/cadastros/edit/id/'. md5($cadastro->getCodigo()),
//                    'delete' => $base .'/cadastros/delete/id/'. md5($cadastro->getCodigo())
//                );
//            }
//
//            // Total
//            $selectCount = clone $select;
//            $selectCount->reset(Zend_Db_Select::LIMIT_COUNT)->reset(Zend_Db_Select::LIMIT_OFFSET);
//            $selectCount = $daoCadastros->getAdapter()->select()->from($selectCount, 'COUNT(1)');
//            $retorno['total'] = $daoCadastros->getAdapter()->fetchOne($selectCount);
//
//            echo Zend_Json::encode($retorno);
//            die();
//
//        } else {
//
//        }



    }
}
