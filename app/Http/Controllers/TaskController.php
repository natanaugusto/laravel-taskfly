<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/task",
     *     @OA\Response(
     *         response="200",
     *         description="List all tasks",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task"),
     *             example="[{'id':1,'uuid':'4ee78f62-3170-5fa1-b518-5e39eca7b875','creator_id':1,'shortcode':'#PCST-0001','title':'Princess Schmidt','due':'2022-07-1716:29:51','status':'doing','created_at':'2022-07-17T16:19:51.000000Z','updated_at':'2022-07-17T16:19:51.000000Z'}]"
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="No content"
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
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found"
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
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
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
     *     ),
     *     @OA\Response(
     *         response="304",
     *         description="Not modified"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error"
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
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found"
     *     )
     * )
     */
    public function delete(Task $task): JsonResponse
    {
        $task->deleteOrFail();
        return response()->json(status: SymfonyResponse::HTTP_ACCEPTED);
    }
}
