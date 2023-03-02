<ol>
    @foreach ($questionnaire->answers as $answer)
    <li data-bs-toggle="tooltip" data-bs-placement="right" title="autor: {{$answer->user->name}} {{$answer->user->surname}}">{{$answer->userValue}}</li>
    @endforeach
</ol>
