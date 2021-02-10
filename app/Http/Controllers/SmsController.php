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
            ->select('sms.text','sms.status', 'sms.type','sms.phone', 'sms.created_at')->orderBy('created_at', 'desc')->paginate(15);

        if($token && $user) {
            return response()->json($sms);
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

        if($phone && $token && $user) {
            if(!$date_from) {
                $sms = DB::table('sms')
                    ->select('text', 'status', 'type', 'phone', 'created_at')->where('phone', $phone)->orderBy('created_at', 'desc')->paginate(15);
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
                $sms = DB::table('sms')
                    ->select('text', 'status', 'type', 'phone', 'created_at')->whereBetween('created_at', [$start, $end])->orderBy('created_at', 'desc')->paginate(15);
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
                $sms = DB::table('sms')
                    ->select('text', 'status', 'type', 'phone', 'created_at')->whereBetween('created_at', [$start, $end])->orderBy('created_at', 'desc')->paginate(15);
                    return response()->json($sms);  
            }
        }
        if($phone && $token && $user && $date_from) {
            if(!$date_to) {
                $date_to = Carbon::now();
            }
            $start = date($date_from);
            $end = date($date_to);
            $sms = DB::table('sms')
                ->select('text', 'status', 'type', 'phone', 'created_at')->whereBetween('created_at', [$start, $end])->where('phone', $phone)->paginate(15);
            return response()->json($sms);   
        }

        if($phone && $token && $user && $date_to) {
            if(!$date_from) {
                $date_from = '2000-01-01';
            }
            $start = date($date_from);
            $end = date($date_to);
            $sms = DB::table('sms')
                ->select('text', 'status', 'type', 'phone', 'created_at')->whereBetween('created_at', [$start, $end])->where('phone', $phone)->paginate(15);
            return response()->json($sms);   
        }
        $result['success'] = false;
        $result['message'] = 'Не передан токен телефон или пользователь не найден';
        return response()->json($result);
    }
}
