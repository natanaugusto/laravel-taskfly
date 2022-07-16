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
}
