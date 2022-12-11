<?php

namespace App\Http\Controllers;

use App\Interfaces\ProdjectRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller 
{
    private ProjectRepositoryInterface $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository) 
    {
        $this->projectRepository = $projectRepository;
    }

    public function index(): JsonResponse 
    {
        return response()->json([
            'data' => $this->projectRepository->getAllProjects()
        ]);
    }

    public function store(Request $request): JsonResponse 
    {
        $projectDetails = $request->only([
            'name',
        ]);

        return response()->json(
            [
                'data' => $this->projectRepository->createProject($projectDetails)
            ],
            Response::HTTP_CREATED
        );
    }

    public function show(Request $request): JsonResponse 
    {
        $projectId = $request->route('id');

        return response()->json([
            'data' => $this->projectRepository->getProjectById($projectId)
        ]);
    }

    public function update(Request $request): JsonResponse 
    {
        $projectId = $request->route('id');
        $projectDetails = $request->only([
            'name'
        ]);
        return response()->json([
            'data' => $this->projectRepository->updateProject($projectId, $projectDetails)
        ]);
    }

    public function destroy(Request $request): JsonResponse 
    {
        $projectId = $request->route('id');
        $this->projectRepository->deleteProject($projectId);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
