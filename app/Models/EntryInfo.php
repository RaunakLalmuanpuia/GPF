<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntryInfo extends Model
{
    use HasFactory;
    use SoftDeletes;
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
        return $this->hasOne(Signatory::class);
    }
}
