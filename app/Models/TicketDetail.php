<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
	public $timestamps = false;
	
    protected $table = 'ticket_detail';

    protected $fillable = ['ticket_id','title','content','groupid'];
}
