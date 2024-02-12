<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CandidateExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'company',
        'department',
        'start',
        'end',
        'designation',
        'responsibilities',
    ];

    protected $appends = ['formatted_start', 'formatted_end'];


    public function getFormattedStartAttribute(){
        return formatTime($this->start, 'd M Y');
    }

    public function getFormattedEndAttribute(){
        return formatTime($this->end, 'd M Y');
    }

}
