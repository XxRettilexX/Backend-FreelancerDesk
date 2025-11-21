<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        return Project::where('user_id', Auth::id())
            ->with(['client'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function show($id)
    {
        return Project::where('user_id', Auth::id())
            ->with(['client', 'timeEntries'])
            ->findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'    => 'nullable|exists:clients,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'status'       => 'required|in:pending,active,completed',
            'hourly_rate'  => 'nullable|numeric',
            'due_date'     => 'nullable|date'
        ]);

        $validated['user_id'] = Auth::id();

        return Project::create($validated);
    }

    public function update(Request $request, $id)
    {
        $project = Project::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'client_id'    => 'nullable|exists:clients,id',
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'status'       => 'required|in:pending,active,completed',
            'hourly_rate'  => 'nullable|numeric',
            'due_date'     => 'nullable|date'
        ]);

        $project->update($validated);
        return $project;
    }

    public function destroy($id)
    {
        $project = Project::where('user_id', Auth::id())->findOrFail($id);
        $project->delete();

        return ['message' => 'Project deleted'];
    }
}
