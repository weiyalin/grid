<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use DB;
use Log;
use App\Models\Admin;
use Illuminate\Http\Request;

/**
 * Class AuthController 认证登录
 * @package App\Http\Controllers\Admin
 */
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('admin.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $credentials['login_name'] = trim($request->input('login_name'));
        $credentials['password'] = trim($request->input('password'));
        //验证验证码
        /*
        if(session('failLoginTimes') >= 3){
            $captcha = trim($request->input('captcha'));
            //失败则
            return responseToJson(1,'验证码错误');
        }
        */

        //验证用户名、密码
        $user = Admin::getCurrentUser($credentials['login_name']);

        if($user){
            $credentials['password'] = md5(md5($credentials['password'])).'|@**@|'.$user->salt;
            //验证并登陆
            if (Auth::attempt($credentials, $request->has('remember'))) {
                /*暂时屏蔽掉验证码部分 需要时打开
                    $request->session()->forget('failLoginTimes');  //登录成功，删除登录次数统计
                */
                \App\Models\User::save_user_info_to_session($request);
                //记录session
                $user->org_name=DB::table('organization')->where('id',$user->org_id)->value('name');
                session(['user'=>$user]);
                session(['permissions'=>\App\Models\User::get_menus(2)]);

                return responseToJson(0,'success');
            }
        }

        /*暂时屏蔽掉验证码部分 需要时打开
            $failLoginTimes = $this->countFailLoginTimes();   //计数登录错误次数
        return responseToJson(2,'用户名或密码错误',['failLoginTimes'=>$failLoginTimes]);
        */
        return responseToJson(2,'用户名或密码错误',['failLoginTimes'=>0]);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect('/login');
    }

    /**
     * 记录失败登录次数 并返回次数
     * @return mixed
     */
    protected function countFailLoginTimes()
    {
        if(session('failLoginTimes')){
            $times = session('failLoginTimes');
            $times++;
            session(['failLoginTimes'=>$times]);
        }else{
            session(['failLoginTimes'=>1]);
        }

        return session('failLoginTimes');
    }
}