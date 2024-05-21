<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         title="API de Notas",
 *         version="1.0.0",
 *         description="API para gestionar notas."
 *     ),
 *     @OA\Server(
 *         url="http://127.0.0.1:8000"
 *     )
 * )
 */
class NotesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Crear una nueva nota
     * @OA\Post (
     *     path="/api/notes_create",
     *     tags={"Nota"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la nota a crear",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string", example="examen de matematica"),
     *                 @OA\Property(property="body", type="string", example="mañana")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Note created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */


    public function noteCreate(Request $request)
    {
        $user = auth()->user(); // Obtener el usuario autenticado desde JWT

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|min:2|max:100',
            'body' => 'required|string|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $note = Note::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => $user->id, // Asignar el id del usuario autenticado
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note created successfully',

        ]);
    }
    /**
     * Actualizar una nota
     * @OA\Put (
     *     path="/api/note-update/{id}",
     *     tags={"Nota"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         description="ID de la nota a actualizar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Note updated successfully"),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="number", example=1),
     *                  @OA\Property(property="user_id", type="number", example=5),
     *                  @OA\Property(property="title", type="string", example="miguel velasco 2"),
     *                  @OA\Property(property="body", type="string", example="exitoso6"),
     *                  @OA\Property(property="created_at", type="string", example="2024-05-21T22:21:25.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", example="2024-05-21T22:21:50.000000Z")
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="NOT FOUND",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Note] #id"),
     *         )
     *     )
     * )
     */

    public function noteUpdate(Request $request, $id)
    {
        $user = auth()->user(); // Obtener el usuario autenticado desde JWT

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $note = Note::find($id);

        if (!$note) {
            return response()->json(['success' => false, 'message' => 'Note not found'], 404);
        }

        // Verificar si la nota pertenece al usuario autenticado
        if ($user->id !== $note->user_id) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255', // Validar título
            'body' => 'nullable|string', // Validar cuerpo
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Actualizar la nota usando el método `update` del modelo de Eloquent
        $note->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully',
            'data' => $note,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Note $note
     * @return \Illuminate\Http\Response
     */

    /**
     * Eliminar una nota existente
     * @OA\Delete (
     *     path="/api/note-delete/{id}",
     *     tags={"Nota"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         description="ID de la nota a eliminar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Note deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function noteDestroy($id)
    {
        $user = auth()->user(); // Get authenticated user from JWT
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        $note = Note::find($id);
        if (!$note) {
            return response()->json(['success' => false, 'message' => 'Note not found'], 404);
        }
        // Verificar si la nota pertenece al usuario autenticado
        if ($user->id !== $note->user_id) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }
        $note->delete();

        return response()->json(['success' => true, 'message' => 'Note deleted successfully']);
    }

    /**
 * Obtener detalles de una nota
 * @OA\Get (
 *     path="/api/note-detail/{id}",
 *     tags={"Nota"},
 *     @OA\Parameter(
 *         in="path",
 *         name="id",
 *         required=true,
 *         description="ID de la nota",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *              @OA\Property(property="id", type="number", example=2),
 *              @OA\Property(property="user_id", type="number", example=5),
 *              @OA\Property(property="title", type="string", example="test"),
 *              @OA\Property(property="body", type="string", example="nueva nota"),
 *              @OA\Property(property="created_at", type="string", example="2024-05-21T22:35:12.000000Z"),
 *              @OA\Property(property="updated_at", type="string", example="2024-05-21T22:35:12.000000Z")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="NOT FOUND",
 *         @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Note] #id"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 *     security={{"bearerAuth": {}}}
 * )
 */

    public function noteDetail($id)
    {
        $user = auth()->user();
        if ($user) {
            try {
                $user = auth()->user(); // Get authenticated user from JWT
                $note = $user->notes()->findOrFail($id); // Find note for the user

                return response()->json($note);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Note not found'], 404);
            }
        } else {
            return response()->json(['message' => $user]);
        }
    }

    /**
     * Ordenar notas por título
     * @OA\Get (
     *     path="/api/note-filter/title",
     *     tags={"Nota"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="notes", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="number", example=4),
     *                      @OA\Property(property="user_id", type="number", example=5),
     *                      @OA\Property(property="title", type="string", example="examen de matematica"),
     *                      @OA\Property(property="body", type="string", example="mañana"),
     *                      @OA\Property(property="created_at", type="string", example="2024-05-21T22:36:43.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-05-21T22:36:43.000000Z")
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function orderby($filter)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $sortBy = 'updated_at'; // Default to 'created_at'
        $sortDirection = 'desc'; // Default to ascending

        // Determine sorting criteria based on filter
        switch ($filter) {
            case 'date':
                $sortBy = 'updated_at';
                break;
            case 'title':
                $sortBy = 'title';
                $sortDirection = 'asc'; // Ensure alphabetical order for titles
                break;
            default:
                // Handle invalid filter (optional: return error message)
                return response()->json(['success' => false, 'message' => 'Invalid sort filter'], 400);
        }

        $notes = $user->notes()
            ->orderBy($sortBy, $sortDirection)
            ->get();

        return response()->json(['success' => true, 'notes' => $notes]);
    }

    /**
     * Buscar notas
     * @OA\Post (
     *     path="/api/search-notes",
     *     tags={"Nota"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Parámetros de búsqueda",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="search", type="string", example="a")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              @OA\Property(property="success", type="boolean", example=true),
     *              @OA\Property(property="results", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="number", example=2),
     *                      @OA\Property(property="user_id", type="number", example=5),
     *                      @OA\Property(property="title", type="string", example="test"),
     *                      @OA\Property(property="body", type="string", example="nueva nota"),
     *                      @OA\Property(property="created_at", type="string", example="2024-05-21T22:35:12.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-05-21T22:35:12.000000Z")
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */

    public function searchNotes(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        // Get search term from request
        $searchTerm = $request->get('search');

        // Validate search term (optional)
        if (!$searchTerm) {
            return response()->json(['success' => false, 'message' => 'Search term required'], 400);
        }

        // Search for notes
        $notes = $user->notes()
            ->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('body', 'like', "%{$searchTerm}%");
            })
            ->get();

        return response()->json(['success' => true, 'results' => $notes]);
    }
}

