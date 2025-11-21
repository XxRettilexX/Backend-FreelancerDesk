<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        return Client::where('user_id', Auth::id())->get();
    }

    public function show($id)
    {
        return Client::where('user_id', Auth::id())->findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email',
            'phone'  => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'notes'  => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::id();

        return Client::create($validated);
    }

    public function update(Request $request, $id)
    {
        $client = Client::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'nullable|email',
            'phone'  => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'notes'  => 'nullable|string'
        ]);

        $client->update($validated);
        return $client;
    }

    public function destroy($id)
    {
        $client = Client::where('user_id', Auth::id())->findOrFail($id);
        $client->delete();

        return ['message' => 'Client deleted'];
    }
}
