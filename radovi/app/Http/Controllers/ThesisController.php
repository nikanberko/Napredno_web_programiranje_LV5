<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class ThesisController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function content()
    {
        if(Auth::user()->role_id == 3){
            redirect(App::currentLocale() . "/home");
        }
        return view('newThesis');
    }

    public function createThesis(Request $request){
        $input = $request->all();
        $validated = $request->validate([
            "newThesisName" => "string|required",
            "newThesisNameEnglish" => "string|required",
            "newThesisDescription" => "string",
            "newThesisStudyType" => "required|in:professionalStudy,undergraduate,graduate"
        ]);
        //dd($input);
        $studyType = "enum('stručni', 'preddiplomski', 'diplomski')";
        if($input["newThesisStudyType"] == "professionalStudy"){
            $studyType = "stručni";
        } else if($input["newThesisStudyType"] == "undergraduate"){
            $studyType = "preddiplomski";
        } else if($input["newThesisStudyType"] == "graduate"){
            $studyType = "diplomski";
        }

        $thesis = new Task();
        $thesis->naziv_rada = $input["newThesisName"];
        $thesis->naziv_rada_engleski = $input["newThesisNameEnglish"];
        $thesis->zadatak_rada = $input["newThesisDescription"];
        $thesis->tip_studija = $studyType;
        $thesis->creator_id = Auth::user()->id;

        $thesis->save();

        return redirect(App::currentLocale() . "/home");
    }
}
