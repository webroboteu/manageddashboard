<?php

namespace App\Http\Controllers;
use App\Interfaces\TaskInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller 
{
    private TaskInterface $taskRepository;

    public function __construct(TaskInterface $taskRepository) 
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(): JsonResponse 
    {
        return response()->json([
            'data' => $this->taskRepository->getAllTasks()
        ]);
    }

    public function store(Request $request): JsonResponse 
    {
        $tasksDetails = $request->only([
            'name'
        ]);

        return response()->json(
            [
                'data' => $this->taskRepository->createTask($tasksDetails)
            ],
            Response::HTTP_CREATED
        );
    }

    public function show(Request $request): JsonResponse 
    {
        $taskId = $request->route('id');
        return response()->json([
            'data' => $this->taskRepository->getTaskById($taskId)
        ]);
    }

    public function update(Request $request): JsonResponse 
    {
        $taskId = $request->route('id');
        $tasksDetails = $request->only([
            'name'
        ]);
        return response()->json([
            'data' => $this->taskRepository->updateTask($taskId, $tasksDetails)
        ]);
    }

    public function destroy(Request $request): JsonResponse 
    {
        $taskId = $request->route('id');
        $this->taskRepository->deleteTask($taskId);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
