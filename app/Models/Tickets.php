<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    protected $table = 'ticket';

    protected $fillable = ['title','priority','status','category','assign_agent','assign_team','requester','groupid','createby','channel','requester_type','datecreate','is_delete','is_delete_date','is_delete_creby'];

    const DELETED = 1;
    const DELETE = 0;
    const TAKE = 10;
    const SORT = 'id';
    const ORDER = 'asc';

    static function getDefault($req)
    {
    	return self::with('getTicketsDetail')
    	->where('is_delete',self::DELETE)
    	->orderBy($req['sort_by'] ?? self::SORT, $req['sort_order'] ?? self::ORDER)
    	->paginate($req['take'] ?? self::TAKE);
    }
    public function getTicketsDetail()
    {
    	return $this->hasMany(TicketDetail::class,'ticket_id','id');
    }
    static function ShowOne($id)
    {
        return self::with('getTicketsDetail')
        ->where('is_delete',self::DELETE)
        ->find($id);
    }
}
