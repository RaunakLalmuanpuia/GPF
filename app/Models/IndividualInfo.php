<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndividualInfo extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name', 'phone', 'designation', 'entry_info_id', 'account', 'amount', 'status'];
    
    public function entryInfo()
    {
        return $this->belongsTo(EntryInfo::class);
    }

    public function template()
    {
        return $this->hasOne(Template::class);
    }
}
