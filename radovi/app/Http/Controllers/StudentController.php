<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Models\User;
use App\Models\Task;

class StudentController extends Controller
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
    public function content($locale, $taskId)
    {
        return view('chooseStudent', [
            "eligibleStudents" => $this->getEligibleStudents($taskId),
            "currentTask" => $this->getCurrentTask($taskId)
        ]);
    }

    private function getEligibleStudents($taskId){
        $students = User::where("role_id", 3)->get();
        $task = Task::find($taskId);

        foreach ($students as $student) {
            if($student->chosenTask()->get()->isNotEmpty()){
                $students = $students->filter(function ($value, $key) use ($student){
                    return $value != $student;
                });
            } else {
                if($student->appliedTo()->get()->isNotEmpty()){
                    $orderCheck = false;
                    foreach ($student->appliedTo()->get() as $item) {
                        if($item->id == $task->id){

                            if($item->pivot->order == 1){
                                $orderCheck = true;
                            }
                        }
                    }
                    if(!$orderCheck){
                        $students = $students->filter(function ($value, $key) use ($student){
                            return $value != $student;
                        });
                    }
                }
            }
        }
        return $students;
    }

    private function getCurrentTask($taskId){
        $task = Task::find($taskId);
        return $task;
    }

    public function chooseNewStudent(Request $request){
        $input = $request->all();
        $validated = $request->validate([
            "taskId" => "integer|numeric|required",
            "studentId" => "integer|numeric|required"
        ]);

        $task = Task::find($input["taskId"]);
        $student = User::find($input["studentId"]);

        $task->chosenStudent()->associate($student);
        $task->save();
        return redirect(App::currentLocale() . "/home");
    }
}
