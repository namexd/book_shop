<?php

namespace App\Http\Controllers\Service;

use App\Models\Member;
use App\Tool\UUID;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TempPhone;
use App\Entity\M3Result;
use Mail;
use App\Models\TempEmail;
use App\Entity\M3Email;

class MemberController extends Controller
{
    public function register(Request $request)
    {

        $email = $request->input('email', '');
        $phone = $request->input('phone', '');
        $password = $request->input('password', '');
        $confirm = $request->input('confirm', '');
        $phone_code = $request->input('phone_code', '');
        $validate_code = $request->input('validate_code', '');

        $m3_result = new M3Result;

        if ($email == '' && $phone == '') {
            $m3_result->status = 1;
            $m3_result->message = '手机号或邮箱不能为空';
            return $m3_result->toJson();
        }
        if ($password == '' || strlen($password) < 6) {
            $m3_result->status = 2;
            $m3_result->message = '密码不少于6位';
            return $m3_result->toJson();
        }
        if ($confirm == '' || strlen($confirm) < 6) {
            $m3_result->status = 3;
            $m3_result->message = '确认密码不少于6位';
            return $m3_result->toJson();
        }
        if ($password != $confirm) {
            $m3_result->status = 4;
            $m3_result->message = '两次密码不相同';
            return $m3_result->toJson();
        }

        $member = new Member();
        $m3_result = new  M3Result();
        if ($request->phone != '') {
            if ($member->is_register($request->phone)) {
                $m3_result->status = 1;
                $m3_result->message = '手机号已存在';
                return $m3_result->toJson();
            }
            $temp_phone = TempPhone::where('phone', $request->phone)->first();
            if ($temp_phone != null) {
                if ($temp_phone->code == $phone_code) {
                    if (strtotime($temp_phone->deadline) > time()) {
                        $member->nickname = '用户' . $request->phone;
                        $member->phone = $request->phone;
                        $member->password = md5($request->password);
                        $member->save();
                        $m3_result->status = 0;
                        $m3_result->message = '注册成功';
                        return $m3_result->toJson();
                    } else {
                        $m3_result->status = 3;
                        $m3_result->message = '验证码超时,请重新发送';
                        return $m3_result->toJson();
                    }

                } else {
                    $m3_result->status = 2;
                    $m3_result->message = '验证码错误';
                    return $m3_result->toJson();
                }
            } else {
                $m3_result->status = 4;
                $m3_result->message = '请发送验证码';
                return $m3_result->toJson();
            }
        } else {
            if ($validate_code != $request->session()->get('validate_code', '')) {
                $m3_result->status = 5;
                $m3_result->message = '验证码错误';
            }
            $member->nickname = '用户' . $email;
            $member->email = $request->email;
            $member->password = md5($request->password);
            $member->save();
            $uuid = UUID::create();
            $m3_email = new M3Email;
            $m3_email->to = $email;
            $m3_email->cc = '458103210@qq.com';
            $m3_email->subject = '凯恩书店验证';
            $m3_email->content = '请于24小时点击该链接完成验证. http://book.app/service/validate_email' . '?member_id=' . $member->id . '&code=' . $uuid;

            $tempEmail = new TempEmail;
            $tempEmail->member_id = $member->id;
            $tempEmail->code = $uuid;
            $tempEmail->deadline = date('Y-m-d H-i-s', time() + 24 * 60 * 60);
            $tempEmail->save();

            Mail::send('email_register', [ 'm3_email' => $m3_email ], function ($m) use ($m3_email) {
                // $m->from('hello@app.com', 'Your Application');
                $m->to($m3_email->to, '尊敬的用户')->cc($m3_email->cc)->subject($m3_email->subject);
            });

            $m3_result->status = 0;
            $m3_result->message = '注册成功';
        }

        return $m3_result->toJson();
    }

    public function login(Request $request)
    {
        $m3_result = new M3Result();
        $username = $request->input('username', '');
        $password = $request->input('password', '');
        $validate_code = $request->input('validate_code', '');
        if ($username == '' && $username == '') {
            $m3_result->status = 1;
            $m3_result->message = '手机号或邮箱不能为空';
            return $m3_result->toJson();
        }
        if ($password == '' || strlen($password) < 6) {
            $m3_result->status = 2;
            $m3_result->message = '密码不少于6位';
            return $m3_result->toJson();
        }
        $validate_code_session = $request->session()->get('validate_code', '');
        if ($validate_code != $validate_code_session) {
            $m3_result->status = 3;
            $m3_result->message = '验证码错误';
            return $m3_result->toJson();
        }
        if (strpos($username, '@') == true) {
            $member = Member::where('email', $username)->first();
        } else {
            $member = Member::where('phone', $username)->first();
        }
        if ($member == null) {
            $m3_result->status = 4;
            $m3_result->message = '用户不存在';
            return $m3_result->toJson();
        } else {
            if ($member->password == md5($password)) {
                $m3_result->status = 0;
                $m3_result->message = '登陆成功';
                return $m3_result->toJson();
            } else {
                $m3_result->status = 5;
                $m3_result->message = '密码错误';
                return $m3_result->toJson();
            }
        }
    }
}
