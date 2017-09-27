<?php

namespace App\Http\Controllers\Service;

use App\Models\TempPhone;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Tool\Validate\ValidateCode;
use App\Tool\SMS\SendTemplateSMS;
use App\Models\TempEmail;
use App\Models\Member;
class ValidateController extends Controller
{
   public function create(Request $request)
   {
       $validateCode=new ValidateCode();
       $request->session()->put('validate_code',$validateCode->getCode());
       return $validateCode->doimg();
   }
   public function sendSms(Request $request)
   {
       $phone=$request->input('phone','');

       $sendsms=new SendTemplateSMS;
       $code='';
       $charset='0123456789';
       for ($i=0;$i<4;$i++){
           $code.=$charset[mt_rand(0,9)];
       }
       $result=$sendsms->sendTemplateSMS('18052128292',array($code,60),1);
       if ($result->status==0){
           $tempPhone=TempPhone::where('phone',$phone)->first();
           if ($tempPhone==null){
               $tempPhone=new TempPhone();
           }
           $tempPhone->phone=$phone;
           $tempPhone->code=$code;
           $tempPhone->deadline=date('Y-m-d H-i-s',time()+60*60);
           $tempPhone->save();
       }
       return $result->toJson();
   }

    public function validateEmail(Request $request)
    {
        $member_id = $request->input('member_id', '');
        $code = $request->input('code', '');
        if($member_id == '' || $code == '') {
            return '验证异常';
        }

        $tempEmail = TempEmail::where('member_id', $member_id)->first();
        if($tempEmail == null) {
            return '验证异常';
        }

        if($tempEmail->code == $code) {
            if(time() > strtotime($tempEmail->deadline)) {
                return '该链接已失效';
            }

            $member = Member::find($member_id);
            $member->active = 1;
            $member->save();

            return redirect('/login');
        } else {
            return '该链接已失效';
        }
    }
}
