<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Constructor del controlador.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // Obtener todos los registros de tareas.
        $tasks = Task::all();

        // Devolver una respuesta JSON con la lista de tareas.
        return response()->json(['data' => $tasks], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        // Obtener los datos de entrada validados
        $validated = $request->validated();

        // Se crea una nueva tarea con los datos de entrada
        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'completed' => $request->has('completed'),
        ]);

        // Se retorna el registro creado y un mensaje de confirmación
        return response()->json([
            'data' => $task,
            'message' => 'A new task record has been created.'
        ], Response::HTTP_OK);
    }
}
