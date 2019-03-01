<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class tb_user_social extends Model
{
  use SoftDeletes;
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   public     $timestamps   = true;
   protected  $table        = 'tb_user_social';
   protected  $fillable     = ['user_id','social_network','social_id','social_email','social_avatar'];
   protected  $hidden       = [];
}
