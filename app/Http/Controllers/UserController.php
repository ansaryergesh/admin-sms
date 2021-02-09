<?php

namespace App\Http\Controllers;
use App\User;
use App\Role;
use DB;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
class UserController extends Controller
{
    public function index() {
        $users = DB::table('users')
            ->join('users_roles', 'users.id', '=', 'user_id')
            ->select('users.id','users.email', 'users.name', 'users_roles.role_id')->get();
        return response()->json($users);
    }


    public function editOwn(Request $request) {
        $token = $request->input('token');
        $email = $request->input('email');
        $user_name = $request->input('name');
        $user = User::where('remember_token', $token)->first();
        $user_id = $user->id;

        $result['success'] = false;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$email) {
                $result['message'] = 'username required';
                break;
            }
            if (!$user_name) {
                $result['message'] = 'name required';
                break;
            }

            $user = User::where('id', $user_id)->first();
            $user->email=$email;
            $user->name=$user_name;
            $user->save();
            $result['success'] = true;
            $result['message'] = 'Успешно обновлен!';
        }while(false);
        return response()->json($result);
    }


    public function edit(Request $request) {
        $token = $request->input('token');
        $id = $request->input('user_id');
        $edited_role = $request->input('role_id');
        $email = $request->input('email');
        $user_name = $request->input('name');
        $admin = User::where('remember_token', $token)->first();
        $admin_id = $admin->id;

        $role_dates = DB::table('users_roles')->where('user_id', $admin_id)->first();
        $role_id = $role_dates->role_id;
        $result['success'] = false;

        do {
            if (!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }
            if (!$email) {
                $result['message'] = 'username required';
                break;
            }
            if (!$user_name) {
                $result['message'] = 'name required';
                break;
            }
            if($role_id !== 1){
                $result['message'] = 'Только админ может this action';
                break;
            }

            $user = User::where('id', $id)->first();
            $user->email=$email;
            $user->name=$user_name;
            $user->save();
            DB::table('users_roles')->where('user_id', $id)->update(['role_id' => $edited_role]);
  
            $result['success'] = true;
            $result['message'] = 'Успешно обновлен!';
            
           
        }while(false);
        return response()->json($result);
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $result['success'] = false;

        do {
            if (!$email) {
                $result['message'] = 'Email required';
                break;
            }
            if (!$password) {
                $result['message'] = 'Password required';
                break;
            }
            $user = User::where('email', $email)->first();
            if (!$user) {
                $result['message'] = 'Такой пользователь не существует';
                break;
            }
            $psw = Hash::check($password, $user->password);
            if (!$psw) {
                $result['message'] = 'Неправильный логин или пароль';
                break;
            }
            $token = Str::random(60);
            $token = sha1($token);
            $user->remember_token = $token;
            $user->save();
            $result['success'] = true;
            $result['name'] = $user->name;
            $result['email'] = $user->email;
            $result['token'] = $token;
        } while (false);
        return response()->json($result);
    }
    
    public function getProfile(Request $request) {
        $token = $request->input('token');
        $result['success'] = false;

        do {
            if(!$token) {
                $result['message'] = 'Не передан токен';
                break;
            }

            $user = User::where('remember_token', $token)->first();
            if (!$user) {
                $result['message'] = 'Не найден пользователь';
                break;
            }
            $result['name'] = $user->name;
            $result['email'] = $user->email;
            $result['id'] = $user->id;
            $user_id = $user->id;
            $role = DB::table('users_roles')->where('user_id', $user_id)->first();
            $role_id = $role->role_id;
            $result['role_id'] = $role_id;
            $role_name = DB::table('roles')->where('id', $role_id)->first();
            $result['role_name'] = $role_name->slug;
            $permission = DB::table('users_permissions')->where('user_id', $user_id)->get();
            $result['admin'] = true;
            $permissionList = [];
            $index = 0;
            foreach ($permission as $perm){
                array_push($permissionList, $perm->permission_id);
            }
            $result['permissions'] = $permissionList;
            $result['success'] = true;
        }while (false);

        return response()->json($result);
    }

    public function deleteAccount(Request $request) {
        $token = $request->input('token');
        $id = $request->input('user_id');
        $admin = User::where('remember_token', $token)->first();
        $admin_id = $admin->id;

        $role_dates = DB::table('users_roles')->where('user_id', $admin_id)->first();
        $role_id = $role_dates->role_id;
        $result['success'] = false;

        do {
            if($role_id !== 1){
                $result['message'] = 'Только админ может удалить пользователей';
                break;
            }

            $user = User::where('id', $id)->first();
            $user->delete();
            $user_role = DB::table('users_roles')->where('user_id', $id)->first();
            DB::table('users_roles')->delete($user_role->id);
            $result['success'] = true;
            $result['message'] = 'Успешно удален!';
        }while(false);
        return response()->json($result);
    }

    public function givePermission(Request $request) {
        $token = $request->input('token');
        $user_id = $request->input('user_id');
        $permission_id = $request->input('permission_id');
        $admin = User::where('remember_token', $token)->first();
        $admin_id = $admin->id;
        $role_dates = DB::table('users_roles')->where('user_id', $admin_id)->first();
        $role_id = $role_dates->role_id;
        $result['success'] = false;

        do {
            if($role_id !== 1){
                $result['message'] = 'Только админ может дать привелигия';
                break;
            }
            DB::beginTransaction();
            foreach($permission_id as $perm){
                $give_permission = DB::table('users_permissions')->insert(
                    array(
                      'user_id' =>$user_id,
                      'permission_id'=>(int)$perm,
                    )
                );
                if (!$give_permission){
                    DB::rollback();
                    $result['message'] = 'Что то произошло не так попробуйте позже';
                    break;
                }

            }
            
            DB::commit();
            $result['success'] = true;
            $result['message'] = 'Успешно дано привелигия';
        }while(false);
        return response()->json($result);
    }

    public function addUser(Request $request) {
        $token = $request->input('token');
        $name = $request->input('name');
        $email=$request->input('email');
        $password=$request->input('password');
        $role = $request->input('role_id');
        $admin = User::where('remember_token', $token)->first();
        $admin_id = $admin->id;
        $role_dates = DB::table('users_roles')->where('user_id', $admin_id)->first();
        $role_id = $role_dates->role_id;
        $result['success'] = false;

        do {
            if($role_id !== 1){
                $result['message'] = 'Только админ может дать привелигия';
                break;  
            }
            $user = User::where('email', $email)->first();
            if ($user) {
                $result['message'] = 'Этот username уже регистирован';
                break;
            }
            DB::beginTransaction();
            $new_user = DB::table('users')->insertGetId(
                array(
                  'email'=>$email,
                  'name'=>$name,
                  'password'=>bcrypt($password),
                  'created_at'=>Carbon::now(),
                  'updated_at'=>Carbon::now(),
                )
            );
            if (!$new_user){
                DB::rollback();
                $result['message'] = 'Что то произошло не так попробуйте позже';
                break;
            }

            $user_role = DB::table('users_roles')->insert(
                array(
                    'user_id'=>$new_user,
                    'role_id'=>$role,
                )
            );
            if (!$user_role){
                DB::rollback();
                $result['message'] = 'Что то произошло не так попробуйте позже';
                break;
            }
            DB::commit();
            $result['success'] = true;
            $result['message'] = 'Успешно создан пользователь';
        }while(false);
        return response()->json($result);
    }

    public function logout(Request $request)
    {
        $email = $request->input('email');
        $result['success'] = false;

        do {
            if (!$email) {
                $result['message'] = 'Не передан эмейл';
                break;
            }
            $user = User::where('email', $email)->first();
            if (!$user) {
                $result['message'] = 'Не существует такой логин';
                break;
            }
            $user->token = '';
            $user->save;
            $result['success'] = true;
        } while (false);
        return response()->json($result);
    }

    public function changePassword(Request $request){
        $password = $request->input('password');
        $token = $request->input('token');
        $result['success'] = false;

        do{
            if (!$password){
                $result['message'] = 'Не передан пароль';
                break;
            }
         
            if (!$token){
                $result['message'] = 'Не передан токен';
                break;
            }

            $user = User::where('remember_token',$token)->first();
            if (!$user){
                $result['message'] = 'Не найден токен';
                break;
            }
            $user->password = bcrypt($password);
            $user->save();
            $result['success'] = true;
        }while(false);

        return response()->json($result);
    }
}
