<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Questionnaire extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'description',
        'validTill',
        'publication_id',
        'type',
    ];

    public function answers(): BelongsToMany
    {
        return $this->BelongsToMany(Answer::class);
    }

    public function userAnswers(){
        return $this->answers()->where('user_id', Auth::user()->id);
    }

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }
}
