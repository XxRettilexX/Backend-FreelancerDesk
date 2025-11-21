<?php

namespace App\Http\Controllers;

use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeEntryController extends Controller
{
    public function index()
    {
        return TimeEntry::where('user_id', Auth::id())
            ->with('project')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'minutes' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();

        return TimeEntry::create($validated);
    }

    public function destroy($id)
    {
        $entry = TimeEntry::where('user_id', Auth::id())->findOrFail($id);
        $entry->delete();

        return ['message' => 'Time entry deleted'];
    }
}
