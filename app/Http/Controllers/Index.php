<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

Class Index extends Controller
{
   //登陆
    public function login(){
        return view('user.login');
    }
    public function logindo(Request $request){
       $name=$_POST['name'];
       $passwod=$_POST['password'];
       $data=DB::table('user')->where(['name'=>$name])->first();
        if($data){
            //用库存在
            if(password_verify($passwod,$data->pwd)){
                //登陆逻辑
                $token=$this->loginToken($data->uid);
                $uid=$data->uid;
//               print_r($token);die;
                $redis_token_key='login_token:uid:'.$uid;
                Redis::set($redis_token_key,$token);
                Redis::expire($redis_token_key,604800);
//                $arr=[
//                    'code'=>0,l
//                    'msg'=>'ok',
//                    'data'=>$token
//                ];
//               die(json_encode($arr));
             setcookie('token',$token,time()+200,'/','api.com',false,true);
             setcookie('uid',$uid,time()+200,'/','api.com',false,true);
            }else{
                //登录失败
                $arr=[
                    'code'=>50001,
                    'msg'=>'密码不正确'
                ];
                die(json_encode($arr));
            }
        }else{
            //用户不存在
            $arr=[
                'code'=>50002,
                'msg'=>'用户不存在'
            ];
            die(json_encode($arr));
        }
    }
    public function loginToken($uid){

        return substr(sha1($uid.time() .Str::random(10)),5,15);
    }
}

