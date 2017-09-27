<?php

namespace App\Http\Controllers\Service;

use App\Models\Category;
use App\Models\Member;
use App\Tool\UUID;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TempPhone;
use App\Entity\M3Result;
use Mail;
use App\Models\TempEmail;
use App\Entity\M3Email;

class BookController extends Controller
{
  public function getCategoryByParentId($parent_id)
  {
      $categorys=Category::where('parent_id',$parent_id)->get();
      $m3_result=new M3Result();
      $m3_result->status=0;
      $m3_result->message='返回成功';
      $m3_result->categorys=$categorys;
      return $m3_result->toJson();
  }
}
