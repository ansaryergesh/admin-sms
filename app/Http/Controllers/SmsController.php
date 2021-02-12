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
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        
        if(!$phone && !$date_from && !$date_to)  {
          $sms  = DB::table('sms')->orderBy('updated_at', 'desc')->get();
        }
        if($phone && !$date_from && !$date_to) {
            $sms  = DB::table('sms')->orderBy('updated_at', 'desc')->where('phone', $phone)->get(); 
        }
        if($date_from) {
            if(!$phone){
                if(!$date_to) {
                    $date_to = Carbon::now();
                }
                $start = date($date_from);
                $end = date($date_to);
                $sms = DB::table('sms')->select('text', 'status', 'type', 'phone', 'created_at')->whereBetween('created_at', [$start, $end])->orderBy('created_at', 'desc')->get();
            }
        }

        if($date_to) {
            if(!$phone){
                if(!$date_from) {
                    $date_from = '2000-01-01';
                }
                $start = date($date_from);
                $end = date($date_to);
                $sms = DB::table('sms')->whereBetween('created_at', [$start, $end])->orderBy('created_at', 'desc')->get();
            }
        }
        if($phone && $date_from) {
            if(!$date_to) {
                $date_to = Carbon::now();
            }
            $start = date($date_from);
            $end = date($date_to);
            $sms = DB::table('sms')->whereBetween('created_at', [$start, $end])->where('phone', $phone)->get();
  
        }

        if($phone && $date_to) {
            if(!$date_from) {
                $date_from = '2000-01-01';
            }
            $start = date($date_from);
            $end = date($date_to);
            $sms = DB::table('sms')->whereBetween('created_at', [$start, $end])->where('phone', $phone)->get();
  
        }

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

        $sms = DB::table('sms')
            ->join('sms_statuses', 'sms.status', '=', 'sms_statuses.status')
            ->join('sms_types', 'sms.type', '=', 'sms_types.id')
            ->select('sms.text', 'sms_types.name as type', 'sms.phone', 'sms_statuses.name as status', 'sms.status as status_id', 'sms.created_at')->orderBy('created_at', 'desc');

        if($phone && $token && $user) {
            if(!$date_from) {
                $sms = $sms->where('phone', $phone)->paginate(15);
              
                return response()->json($sms);
            }
        }

        if($date_from && $token && $user) {
            if(!$phone){
                if(!$date_to) {
                    $date_to = Carbon::now();
                }
                $start = date($date_from);
                $end = date($date_to);
                $sms = $sms->whereBetween('created_at', [$start, $end])->paginate(15);
                return response()->json($sms);  
            }
        }

        if($date_to && $token && $user) {
            if(!$phone){
                if(!$date_from) {
                    $date_from = '2000-01-01';
                }
                $start = date($date_from);
                $end = date($date_to);
                $sms = $sms->whereBetween('created_at', [$start, $end])->paginate(15);
            }
        }
        if($phone && $token && $user && $date_from) {
            if(!$date_to) {
                $date_to = Carbon::now();
            }
            $start = date($date_from);
            $end = date($date_to);
            $sms = $sms->whereBetween('created_at', [$start, $end])->where('phone', $phone)->paginate(15);
           
            return response()->json($sms);   
        }

        if($phone && $token && $user && $date_to) {
            if(!$date_from) {
                $date_from = '2000-01-01';
            }
            $start = date($date_from);
            $end = date($date_to);
            $sms = $sms->whereBetween('created_at', [$start, $end])->where('phone', $phone)->paginate(15);
            return response()->json($sms);   
        }
        $result['success'] = false;
        $result['message'] = 'Не передан токен телефон или пользователь не найден';
        return response()->json($result);
    }
}
