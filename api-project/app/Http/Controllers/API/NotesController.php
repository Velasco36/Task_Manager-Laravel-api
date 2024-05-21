<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

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


    public function noteList()
    {
        $user = auth()->user(); // Obtener el usuario autenticado desde JWT

        if (!$user) {
            return response()->json(['success' => $user, 'message' => 'User not authenticated'], 401);
        }
        $notes = $user->notes;
        return response()->json(['success' => true, 'notes' => $notes]);
    }

    /**
     * Display the specified resource.
     *
     * @param Note $note
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Note $note
     * @return \Illuminate\Http\Response
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

