<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
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

    public function content($locale, $order)
    {
        return view('chooseTask', [
            "currentlyChosenTask" => $this->getCurrentTask($order)
        ]);
    }

    private function getCurrentTask($order){
        foreach (Auth::user()->appliedTo()->get() as $item) {
            if($item->pivot->order == $order){
                return $item;
            }
        }
        return null;
    }

    public function chooseTargetedTask(Request $request){
        $input = $request->all();
        $validated = $request->validate([
            "taskId" => "integer|numeric|required",
            "order" => "in:1,2,3,4,5|required"
        ]);

        $taskAlreadyChosen = false;
        $task = Task::find($input["taskId"]);
        $isInTasks = Auth::user()->appliedTo()->find($input["taskId"]);
        if($isInTasks){
            $taskAlreadyChosen = true;
        }

        if($taskAlreadyChosen){
            $taskAlreadyInThisOrder = null;
            $checkIfTaskIsAlreadyInThisOrder = false;
            foreach (Auth::user()->appliedTo()->get() as $item) {
                if($item->pivot->order == $input["order"]){
                    $taskAlreadyInThisOrder = $item;
                    $checkIfTaskIsAlreadyInThisOrder = true;
                }
            }

            if($checkIfTaskIsAlreadyInThisOrder){
                if($task->id == $taskAlreadyInThisOrder->id){
                    return redirect(App::currentLocale() . "/home");
                }
            }

            if($checkIfTaskIsAlreadyInThisOrder){
                $taskAlreadyInThisOrder->applicants()->detach(Auth::user());
            }

            foreach (Auth::user()->appliedTo()->get() as $item) {
                if($item->id == $task->id){
                    $item->pivot->order = $input["order"];
                    $item->pivot->save();
                }
            }
        } else {
            $taskAlreadyInThisOrder = null;
            $checkIfTaskIsAlreadyInThisOrder = false;
            foreach (Auth::user()->appliedTo()->get() as $item) {
                if($item->pivot->order == $input["order"]){
                    $taskAlreadyInThisOrder = $item;
                    $checkIfTaskIsAlreadyInThisOrder = true;
                }
            }
            if($checkIfTaskIsAlreadyInThisOrder){
                $taskAlreadyInThisOrder->applicants()->detach(Auth::user());
            }
            $task->applicants()->attach(Auth::user(), ["order"=>$input["order"]]);
        }


        return redirect(App::currentLocale() . "/home");
    }
}
