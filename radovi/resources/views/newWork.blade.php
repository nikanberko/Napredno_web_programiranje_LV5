@extends('layouts.app')

@section('content')
<div class="container">
    <div class="dropdown">
        <button type="button" href="#" class="dropdown-toggle btn btn-light" data-toggle="dropdown">
            {{ Config::get('languages')[App::getLocale()] }}
        </button>
        <div class="dropdown-menu">
            @foreach (Config::get('languages') as $lang => $language)
                @if ($lang != App::getLocale())
                    <a class="dropdown-item" href="{{ url($lang . "/newThesis") }}">{{$language}}</a>
                @endif
            @endforeach
            </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a class="btn btn-info mb-1" href="{{url(App::getLocale() . "\home")}}">{{__("newThesis.buttonHome")}}</a>
            <div class="card">
                <div class="card-header">
                    <h2>{{ __('newThesis.title') }}</h2>
                </div>

                <div class="card-body">
                    <form action="{{url("createNewThesis")}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="newThesisName">{{__("newThesis.inputLabelNewThesisName")}}</label>
                            <input type="text" name="newThesisName" id="newThesisname" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="newThesisNameEnglish">{{__("newThesis.inputLabelNewThesisNameEnglish")}}</label>
                            <input type="text" name="newThesisNameEnglish" id="newThesisNameEnglish" class="form-control" value="default">
                        </div>
                        <div class="form-group">
                            <label for="newThesisDescription">{{__("newThesis.inputLabelNewThesisDescription")}}</label>
                            <input type="text" name="newThesisDescription" id="newThesisDescription" class="form-control">
                        </div>
                        <p>{{__("newThesis.inputLabelNewThesisStudyType")}}</p>
                        <div class="container">
                            <div class="form-check-inline">
                                <label for="newThesisStudyProfessionalStudy" class="form-check-label">
                                    <input class="form-check-input" id="newThesisStudyProfessionalStudy" type="radio" name="newThesisStudyType" value="professionalStudy">{{__("newThesis.inputLabelNewThesisStudyTypeProfessionalStudy")}}
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="newThesisStudyUndergraduate" class="form-check-label">
                                    <input class="form-check-input" id="newThesisStudyUndergraduate" type="radio" name="newThesisStudyType" value="undergraduate">{{__("newThesis.inputLabelNewThesisStudyTypeUndergraduate")}}
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label for="newThesisStudyGraduate" class="form-check-label">
                                    <input class="form-check-input" id="newThesisStudyGraduate" type="radio" name="newThesisStudyType" value="graduate">{{__("newThesis.inputLabelNewThesisStudyTypeGraduate")}}
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-2" type="submit">{{__("newThesis.inputSubmit")}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
