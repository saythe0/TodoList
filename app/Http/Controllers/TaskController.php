<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;

class TaskController extends Controller
{
    public function index() {
        $tasks = auth()->user()->tasks()->get();
        return view('tasks', compact('tasks'));
    }

    public function store(Request $request) {
        $request->validate([
            'title' => ['required','max:255', 'string'],
        ], [
            'title.required' => 'Введите название задачи.',
            'title.max' => 'Название не должно превышать 255 символов.',
            'title.string' => 'Название должно быть строкой.',
        ]);

        auth()->user()->tasks()->create([
            'title' => $request->title,
        ]);

        return redirect()->route(route: 'tasks.index')->with([
            'status' => 'task-created',
            'text' => 'Задача успешно создана.',
        ]);
    }

    public function destroy(Task $task) {
        if ($task->user_id != auth()->id()) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with([
            'status' => 'task-deleted',
            'text' => 'Задача успешно удалена.',
        ]);
    }

    public function updateStatus(Task $task) {
        if ($task->user_id != auth()->id()) {
            abort(403);
        }

        $task->is_completed = !$task->is_completed;
        $task->save();

        return redirect()->route('tasks.index')->with([
            'status' => 'task-updated',
            'text' => 'Задача успешно обновлена.',
        ]);
    }
}
