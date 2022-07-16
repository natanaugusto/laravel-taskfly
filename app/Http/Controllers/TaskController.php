<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class TaskController extends Controller
{
    public function all(): JsonResponse
    {
        $tasks = Task::all();
        return $tasks->count() > 0 ?
            response()->json(data: Task::all()) : response()->json(status: SymfonyResponse::HTTP_NO_CONTENT);
    }

    public function view(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(rules: [
            'title' => 'required',
            'creator_id' => ['required', 'exists:users,id'],
            'due' => ['required', 'date_format:' . Task::DUE_DATETIME_FORMAT]
        ]);

        $task = Task::create($request->all());
        return response()->json(data: $task, status: SymfonyResponse::HTTP_CREATED);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $task->fill(attributes: $request->all());
        if ($task->isClean()) {
            return response()->json(status: SymfonyResponse::HTTP_NOT_MODIFIED);
        }
        $task->save();
        return response()->json(data: $task, status: SymfonyResponse::HTTP_ACCEPTED);
    }

    public function delete(Task $task): JsonResponse
    {
        $task->deleteOrFail();
        return response()->json(status: SymfonyResponse::HTTP_ACCEPTED);
    }
}
