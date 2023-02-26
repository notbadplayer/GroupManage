<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function destroy(Questionnaire $Questionnaire)
    {
        $Questionnaire->delete();
    }
}
