<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseNote extends Model
{
    protected $fillable = ['case_id', 'author_id', 'note_type', 'body'];

    public function caseRecord()
    {
        return $this->belongsTo(CaseRecord::class, 'case_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
