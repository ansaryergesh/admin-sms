<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    public function sms_type() {
      return $this->belongsTo(SmsType::class, 'type', 'id');
    }
}
