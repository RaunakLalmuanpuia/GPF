<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Department extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name','address', 'phone'];
    public function entry_info(){
        return $this->hasMany(EntryInfo::class);
    }
}
