<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * @OA\Info(
 *     title="Taskfly API",
 *     version="0.1"
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/task",
     *     @OA\Response(
     *         response="200",
     *         description="List all tasks"
     *     )
     * )
     */
    public function all(): JsonResponse
    {
        $tasks = Task::all();
        return $tasks->count() > 0 ?
            response()->json(data: Task::all()) : response()->json(status: SymfonyResponse::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     path="/api/task/{task}",
     *     @OA\Response(
     *         response="200",
     *         description="View a task"
     *     )
     * )
     */
    public function view(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    /**
     * @OA\Post(
     *     path="/api/task",
     *     @OA\Response(
     *         response="201",
     *         description="Created a task"
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/task/{task}",
     *     @OA\Response(
     *         response="202",
     *         description="Updated a task"
     *     )
     * )
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        $task->fill(attributes: $request->all());
        if ($task->isClean()) {
            return response()->json(status: SymfonyResponse::HTTP_NOT_MODIFIED);
        }
        $task->save();
        return response()->json(data: $task, status: SymfonyResponse::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *     path="/api/task/{task}",
     *     @OA\Response(
     *         response="202",
     *         description="Deleted a task"
     *     )
     * )
     */
    public function delete(Task $task): JsonResponse
    {
        $task->deleteOrFail();
        return response()->json(status: SymfonyResponse::HTTP_ACCEPTED);
    }
}
