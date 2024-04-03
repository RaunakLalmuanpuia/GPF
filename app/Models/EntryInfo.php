<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryInfo extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['file_number','status', 'amount', 'date', 'signatory_id', 'from_designation', 'gpf_name', 'from_deparment'];
    public function individualInfos()
    {
        return $this->hasMany(IndividualInfo::class);
    }

    public function template()
    {
        return $this->hasOne(Template::class);
    }

    public function signatory()
    {
        return $this->belongsTo(Signatory::class);
    }
}
