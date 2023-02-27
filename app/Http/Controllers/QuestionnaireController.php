<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Group;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    public function destroy(Questionnaire $Questionnaire)
    {
        $Questionnaire->delete();
    }

    public function vote(Questionnaire $Questionnaire, Request $request)
    {
        if($Questionnaire->type = 'closed' && count($Questionnaire->userAnswers) > 0)
        {
            return response()->json(['message' => 'Action failed'], 400);
            exit;

        }


        $answer = Answer::create([
            'user_id' => Auth::user()->id,
            'value' => $request->value,
        ]);

        $Questionnaire->answers()->attach($answer);
    }

    public function results(Questionnaire $Questionnaire)
    {
        $entitledToVote = $Questionnaire->publication->restrictedVisibilityUserIds();
        $votes = $Questionnaire->answers;

        $yes = count($Questionnaire->answers()->where('value', 1)->get());
        $no = count($Questionnaire->answers()->where('value', 0)->get());


        return view('questionnaires.results', [
            'questionnaire' => $Questionnaire,
            'entitledToVote' => $entitledToVote,
            'votes' => count($votes),
            'held' => (count($entitledToVote) - count($votes)),
            'yes' => $yes,
            'no' => $no,

        ]);
    }
}
