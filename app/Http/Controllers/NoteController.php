<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    //GET

    public function index()
    {
        $notes = Note::all();
        return response()->json(['data' => $notes]);
    }

    public function show($id)
    {
        $notes = Note::findOrFail($id);
        return response()->json(['data' => $notes]);
    }

    //POST
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);
        // Get the authenticated user
        $user = auth()->user();
        // Create a new note with created_by set to the authenticated user's ID
        $note = Note::create([
            'title' => $request->title,
            'content' => $request->content,
            'created_by' => $user->username
        ]);

        return response()->json([
            'message' => 'Successfully stored',
            'data' => $note
        ]);
    }

    //PUT
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);
        $notes = Note::findOrFail($id);
        $notes->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'data' => $notes
        ]);
    }

    //DELETE
    public function destroy($id)
    {
        $notes = Note::findOrFail($id);
        $notes->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ]);
    }
}
