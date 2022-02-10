<?php


namespace EvolutionCMS\Main\Modules\FineSearchHistory\Controllers;


use Carbon\Carbon;
use EvolutionCMS\Main\Services\FineSearchLog\Models\FineSearchLog;
use Illuminate\Http\Request;
use View;

class MainController extends BaseController
{
    public function index(Request $request)
    {
        return View::make('Modules.FineSearchHistory::index', array_merge($this->viewData,[]))->render();
    }


    public function getLogs(Request $request){

        $perPage = 15;
        $page = 1;

        if ($_GET['start']) {
            $page = (int)$_GET['start'] / $perPage + 1;
        }

        $q = FineSearchLog::query();

        $sort = [
            'id'=>'created_at',
            'dir'=>'desc'
        ];

        if($request->has('filter')){
            foreach ($request->get('filter') as $key => $value) {
                if(!empty($value)){
                    if(in_array($key,['id'])){
                        $q->where($key,$value);
                    }
                    else if(in_array($key,['driving_license_date_issue','created_at'])){
                        $q->where($key,'>=',Carbon::createFromDate($value)->startOfDay());
                    }
                    else{
                        $q->where($key,'like','%'.$value.'%');
                    }
                }
            }
        }

        if ($request->get('sort')) {
            $field = key($request->get('sort'));

            $sort = [
                'id'=>$field,
                'dir'=>$request->get('sort')[$field]
            ];
        }

        if($sort){
            $q->orderBy($sort['id'],$sort['dir']);
        }


        $orderPaginate = $q->paginate($perPage, $columns = ['*'], $pageName = 'page', $page);

        /** @var FineSearchLog[] $logs */
        $logs = $orderPaginate->items();

        $data = [];
        foreach ($logs as $log) {
            $item = $log->toArray();
            $item['created_at'] = $log->created_at->format('d-m-Y');

            $item['driving_license_date_issue'] = !is_null($log->driving_license_date_issue)?$log->driving_license_date_issue->format('d-m-Y'):'';
            $data[] = $item;
        }

        $response = [
            'data' => $data,
        ];

        if (empty($_GET['start'])) {
            $response['total_count'] = $orderPaginate->total();
        } else {
            $response['pos'] = $_GET['start'];
        }
        return $response;

//
//        if($sort){
//            $q->orderBy($sort['id'],$sort['dir']);
//        }
//        return $q;
//

    }
}