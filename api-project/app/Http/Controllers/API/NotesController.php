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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user(); // Get authenticated user from JWT

       // $notes = $user->notes()->latest()->get(); // Get user's notes

        return response()->json(['success' => true, 'user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $note = Note::find(2);
        $noteCreator = $note->user;
        return response()->json([
            'success' => true,
            'data' => $noteCreator,
        ]);

        // $validator = Validator::make($request->all(), [
        //     'title' => 'required|string|min:2|max:100',
        //     'body' => 'required|string|max:250',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        // $user = auth()->user(); // Get authenticated user from JWT

        // $note = $user->notes()->create([
        //     'title' => $request->title,
        //     'body' => $request->body,
        // ]);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Note created successfully',
        //     'data' => $note,
        // ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Note $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        $user = auth()->user(); // Get authenticated user from JWT

        if ($user->id !== $note->user_id) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Note $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        $validator = Validator::make($request->all(), [
            // Remove id validation
            'title' => 'required|string|max:255', // Limit title length (optional)
            'body' => 'nullable|string', // Allow empty body (optional)
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->user(); // Get authenticated user from JWT

        if ($user->id !== $note->user_id) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $note->update($request->all());

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
    public function destroy(Note $note)
    {
        $user = auth()->user(); // Get authenticated user from JWT

        if ($user->id !== $note->user_id) {
            return response()->json(['message' => 'Unauthorized access'], 403);
        }

        $note->delete();

        return response()->json(['success' => true, 'message' => 'Note deleted successfully']);
    }

    public function detail($id)
    {
        try {
            $user = auth()->user(); // Get authenticated user from JWT
            $note = $user->notes()->findOrFail($id); // Find note for the user

            return response()->json($note);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Note not found'], 404);
        }
    }
}
