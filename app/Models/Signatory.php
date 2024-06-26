<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signatory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name','designation', 'phone'];
    public function entryInfo()
    {
        return $this->hasOne(EntryInfo::class);
    }
}
