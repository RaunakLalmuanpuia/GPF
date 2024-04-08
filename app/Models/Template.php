<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['purpose', 'contents', 'entry_info_id'];
    public function entryInfo()
    {
        return $this->belongsTo(EntryInfo::class);
    }

    public function individualInfo()
    {
        return $this->belongsTo(IndividualInfo::class);
    }
}
