<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public $timestamps = false;
    protected $table = 'contact';
    protected $fillable = ['groupid','firstname','lastname','fullname','gender','phone','email','address','province','creby','datecreate','channel'];

    const DELETED = 1;
    const DELETE = null;
    const TAKE = 10;
    const SORT = 'id';
    const ORDER = 'asc';

    static function getDefault($req)
    {
    	return self::where('is_delete',self::DELETE)
    	->orderBy($req['sort_by'] ?? self::SORT, $req['sort_order'] ?? self::ORDER)
    	->paginate($req['take'] ?? self::TAKE);
    }
    static function ShowOne($id)
    {
        return self::where('is_delete',self::DELETE)->find($id);
    }
}
