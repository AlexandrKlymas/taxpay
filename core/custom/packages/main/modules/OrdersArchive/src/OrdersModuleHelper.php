<?php
namespace EvolutionCMS\Main\Modules\OrdersArchive;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrdersModuleHelper
{
    public function insertFilterRulesToQuery(Request $request, Builder $q){

        $filters = [];

        if($request->get('filter') && is_array($request->get('filter'))){
            $filters = $request->get('filter');
        }
        else if($request->get('filter')){
            $filters = json_decode($request->get('filter'),true);
        }

        $filterConfig = [
            'id'=>'=',
            'service_id'=>'=',
            'full_name'=>'like',
            'status'=>'=',
            'phone'=>'like',
            'email'=>'like',
            'recipient_name'=>'like',
            'total'=>'like',
            'sum'=>'like',
            'liqpay_transaction_id'=>'like',
            'liqpay_real_commission'=>'like',
            'bank_commission'=>'like',

            'profit'=>'like',
        ];

        foreach ($filterConfig as $field => $type) {
            if(in_array($field,['recipient_name'])){
                $fieldWithPrefix = 'payment_recipients.'.$field;
            }
            else{
                $fieldWithPrefix = 'service_orders.'.$field;
            }

            if(!empty($filters[$field]) && $type === '='){
                $q->where($fieldWithPrefix,$type,$filters[$field]);
            }

            if(!empty($filters[$field]) && $type === 'like'){
                $q->where($fieldWithPrefix,$type,'%'.$filters[$field].'%');
            }
        }

        if(!empty($filters['liqpay_payment_date'])){
            $q->where('liqpay_payment_date','>=',date('Y-m-d H:i:s',strtotime($filters['liqpay_payment_date'])));
        }

        if(!empty($filters['payment_data_from'])){
            $q->where('liqpay_payment_date','>=',date('Y-m-d 00:00:00',strtotime($filters['payment_data_from'])));
        }

        if(!empty($filters['payment_data_to'])){
            $q->where('liqpay_payment_date','<=',date('Y-m-d 23:59:59',strtotime($filters['payment_data_to'])));
        }

        return $q;
    }

    public function insertSortRulesToQuery(Request $request, Builder $q){
        $sort = [];

        if($request->get('sort')){
            if(is_array($request->get('sort'))){
                $sort = [
                    'id'=> key($request->get('sort')),
                    'dir'=>$request->get('sort')[key($request->get('sort'))]
                ];
            }
            else{
                $sort = json_decode($request->get('sort'),true);
            }
        }



        if($sort){
            $q->orderBy($sort['id'],$sort['dir']);
        }
        return $q;
    }
}