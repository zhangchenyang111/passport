<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
//header("Access-Control-Allow-Origin:*");
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

Class LoginController extends Controller
{
    //注册
    public function res(Request $request)
    {
        $user_name=$_POST['user_name'];
        $user_pwd=$_POST['user_pwd'];
        $res = DB::table('login')->where(['user_name' => $user_name])->first();
        if ($res) {
            return json_encode(['msg' => '用户名已存在', 'status' => 0], JSON_UNESCAPED_UNICODE);
        } else {
            $arr = [
                'user_name' => $user_name,
                'user_pwd' => $user_pwd
            ];
            $data = DB::table('login')->insert($arr);
            if ($data) {
                return json_encode(['msg' => '注册成功', 'status' => 1], JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode(['msg' => '注册失败', 'status' => 0], JSON_UNESCAPED_UNICODE);
            }
        }
    }
    //登录
    public function login(Request $request){
        $user_name=$_GET['user_name'];
        $user_pwd=$_GET['user_pwd'];
        $user_name=$request->input('user_name');
        $user_pwd=$request->input('user_pwd');
        $res=DB::table('login')->where(['user_name'=>$user_name,'user_pwd'=>$user_pwd])->first();

        if($res){
            $token=$this->loginToken($res->user_id);
//               print_r($token);die;

            $redis_token_key='login_token:user_id:'.$res->user_id;
            Redis::set($redis_token_key,$token);
            Redis::expire($redis_token_key,604800);

            return json_encode(['msg'=>'登陆成功','status'=>1,'token'=>$token,'user_id'=>$res->user_id],JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(['msg'=>'用户名或者密码错误','status'=>0],JSON_UNESCAPED_UNICODE);
        }
    }
    //定义token值
    public function loginToken($user_id){
        return substr(sha1($user_id.time() .Str::random(10)),5,15);
    }
    //个人中心
    public function center(Request $request){
        $user_id=$_GET['user_id'];
        $data=DB::table('login')->where(['user_id'=>$user_id])->first();
//        print_r($data);
        if($data){
//            $data = $data->toArray();
            $arr = [
                "code"=>1,
                "msg"=>$data,
            ];
            return json_encode($arr);
        }

    }


}