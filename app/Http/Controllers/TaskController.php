<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[Route('get', '/tasks', name: 'tasks.index')]
    public function index(Request $request)
    {
        $tasks = Auth::user()->tasks();
        //dd($request);
        // Filtering by completion status
        $completed = $request->query('done');
        if ($completed == '1') {
            $tasks->where('done', true);
        } elseif ($completed == '0') {
            $tasks->where('done', false);
        }

        // Filtering by search query
        $search = $request->query('search');
        if (!empty($search)) {
            $tasks->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%")
                      ->orWhere('description', 'LIKE', "%$search%")
                      ->orWhere('deadline', 'LIKE', "%$search%");
            });
        }
        $filter = $request->query('filter', 'all');

        $tasks = $tasks->orderBy('deadline', 'asc')->paginate(10);
        return view('tasks.index', compact('tasks', 'filter', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'deadline' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('tasks.create')
                ->withErrors($validator)
                ->withInput();
        }

        $task = new Task($request->all());
        $task->user_id = Auth::user()->id;
        $task->save();


        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, $id)
    {   
        $task = Task::findOrFail($id);

        $task->deadline = Carbon::parse($task->deadline)->format('Y-m-d');

        return response()->json([
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task, $id)
    {
        $task = Task::findOrFail($id);
        //dd($task->deadline);
        $task->deadline = Carbon::parse($task->deadline)->format('Y-m-d');
        return view('tasks.edit', compact('task', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'deadline' => 'required|date_format:Y-m-d',
        ]);
        

        $task->title = $request->title;
        $task->description = $request->description;
        $task->deadline = $request->deadline;
        $task->done = $request->done ? true : false;
        $task->save();

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->done = $request->done ? true : false;
        $task->save();
/*
        if ($task->done == 1) {
            $emailData = [
                'taskTitle' => $task->title,
                'taskStatus' => 'wykonane'
            ];

            // Wyślij wiadomość e-mail
            Mail::to($task->user->email)->send(new TaskStatusChanged($emailData));
        }
*/
        return response()->json(['status' => $task->done]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Task deleted successfully'
    ]);
    }

    #[Route('get', '/tasks/filter', name: 'tasks.filter')]
    public function filter(Request $request)
    {
        $filter = $request->query('filter');
        
        $tasks = Auth::user()->tasks();
        $search = '';

        if ($filter === 'all') {
            // Nie ma dodatkowego filtrowania
        } elseif ($filter === 'done') {
            $tasks = $tasks->where('done', true);
        } elseif ($filter === 'undone') {
            $tasks = $tasks->where('done', false);
        }

        if (!empty($search)) {
            $tasks->where(function ($query) use ($search) {
                $query->where('done', 'LIKE', "%$search%");
            });
        }

        $tasks = $tasks->orderBy('deadline', 'asc')->paginate(10);

        if ($request->ajax()) {
            return view('tasks.index', compact('tasks', 'filter', 'search'))->render();
        }

        return view('tasks.index', compact('tasks', 'filter', 'search'))->render();
    }
}
