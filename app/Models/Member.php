<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Entity\M3Result;
class Member extends Model
{
    protected $table='member';
    public function is_register($phone)
    {
        $result= Member::where('phone',$phone)->first();
        if ($result){
          return true;
        }else{
            return false;
        }

    }
    /**
     * Get the connection of the entity.
     *
     * @return string|null
     */
    public function getQueueableConnection()
    {
        // TODO: Implement getQueueableConnection() method.
    }

    /**
     * Retrieve the model for a bound value.
     *
     *
     * @param  mixed $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        // TODO: Implement resolveRouteBinding() method.
    }
}
