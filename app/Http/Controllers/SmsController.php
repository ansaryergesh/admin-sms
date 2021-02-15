<?php

namespace App\Http\Controllers;
use DB;
use App\User;
use Carbon\Carbon;

use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index(Request $request) {
        $token = $request->input('token');
        $user = User::where('remember_token', $token)->first();

        $sms = DB::table('sms')
            ->join('sms_statuses', 'sms.status', '=', 'sms_statuses.status')
            ->join('sms_types', 'sms.type', '=', 'sms_types.id')
            ->select('sms.text', 'sms_types.name as type', 'sms.phone', 'sms_statuses.name as status', 'sms.status as status_id', 'sms.created_at')->orderBy('created_at', 'desc')->paginate(15);
        if($token && $user) {
            return response()->json($sms);
        }
        $result['success'] = false;
        $result['message'] = 'Не передан токен или пользователь не найден';
        return response()->json($result);
    }

    public function getSmsTypes(Request $request) {
        $sms_types = DB::table('sms_types')->get();
        return response()->json($sms_types);
    }

    public function getSmsStatuses(Request $request) {
        $sms_types = DB::table('sms_statuses')->get();
        return response()->json($sms_types);
    }

    public function indexFile(Request $request){
        $token = $request->input('token');
        $user = User::where('remember_token', $token)->first();
        $phone = $request->input('phone');
        $sms_type = $request->input('sms_type');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        

        $sms = DB::table('sms')->orderBy('updated_at', 'desc');
        if($phone != '') {
            $sms = $sms->where('phone', $phone);
        }
        if($sms_type != '')  {
            $sms = $sms->where('type', $sms_type);
        }
        if($date_from != '' && $date_to == '') {
            $sms= $sms->whereBetween('created_at', [$date_from, Carbon::now()]);
        }
        if($date_from == '' && $date_to != '') {
            $sms= $sms->whereBetween('created_at', [$date_from === '2000-01-01', $date_to]);
        }
        if($date_from != '' && $date_to != '') {
            $sms = $sms->whereBetween('created_at', [$date_from, $date_to]);
        }
        $sms = $sms->get();

        $types = DB::table('sms_types')->get();
        $statuses = DB::table('sms_statuses')->get();
        $smsStatuses = [];
        foreach($statuses as $status) {
            $smsStatuses[$status->status] = $status->name;
        }
        $typesData = [];
        foreach($types as $type) {
            $typesData[$type->id] = $type->name;
        };
        $data = [];
        $index = 0;
        foreach($sms as $s){
            $data[$index] = [
                'Номер' => $s->phone,
                'Тип сообщений' => $typesData[$s->type],
                'Текст' => $s->text,
                'Статус сообщений' => $smsStatuses[$s->status],
                'Изменено' => date('d.m.Y H:i:s', strtotime($s->updated_at)),
            ];
            $index++;
        }

        if($token && $user) {
            return response()->json($data);
        }

        $result['success'] = false;
        $result['message'] = 'Не передан токен или пользователь не найден';
        
        return response()->json($result);

    }

    public function filter(Request $request) {
        $token = $request->input('token');
        $phone = $request->input('phone');
        $user = User::where('remember_token', $token)->first();
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $sms_type=$request->input('sms_type');

        $sms = DB::table('sms')
            ->join('sms_statuses', 'sms.status', '=', 'sms_statuses.status')
            ->join('sms_types', 'sms.type', '=', 'sms_types.id')
            ->select('sms.text', 'sms_types.name as type', 'sms.type as sms_type', 'sms.phone', 'sms_statuses.name as status', 'sms.status as status_id', 'sms.created_at')->orderBy('created_at', 'desc');
        if($token && $user) {
            if($phone != '') {
                $sms = $sms->where('sms.phone', $phone);
            }
            if($sms_type != '')  {
                $sms = $sms->where('sms.type', $sms_type);
            }
            if($date_from != '' && $date_to == '') {
                $sms= $sms->whereBetween('sms.created_at', [$date_from, Carbon::now()]);
            }
            if($date_from == '' && $date_to != '') {
                $sms= $sms->whereBetween('sms.created_at', [$date_from === '2000-01-01', $date_to]);
            }
            if($date_from != '' && $date_to != '') {
                $sms = $sms->whereBetween('sms.created_at', [$date_from, $date_to]);
            }

            $sms = $sms->paginate(15)->appends($request->all());
            return response()->json($sms);
        }



        $result['success'] = false;
        $result['message'] = 'Не передан токен телефон или пользователь не найден';
        return response()->json($result);
    }
}
