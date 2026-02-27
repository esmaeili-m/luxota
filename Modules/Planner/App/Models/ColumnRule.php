<?php

namespace Modules\Planner\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Planner\Database\factories\ColumnRuleFactory;

class ColumnRule extends Model
{
    use HasFactory,SoftDeletes;
    protected $casts=[
      'rules_json'=>'json'
    ];
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): ColumnRuleFactory
    {
        //return ColumnRuleFactory::new();
    }
}
