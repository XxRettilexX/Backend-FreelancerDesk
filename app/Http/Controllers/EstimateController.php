<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Models\EstimateItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstimateController extends Controller
{
    public function index()
    {
        return Estimate::where('user_id', Auth::id())
            ->with(['client', 'items'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function show($id)
    {
        return Estimate::where('user_id', Auth::id())
            ->with(['client', 'project', 'items'])
            ->findOrFail($id);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'title'     => 'required|string',
            'description' => 'nullable|string',
            'vat'       => 'required|numeric',
            'items'     => 'required|array',
            'items.*.label' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0'
        ]);

        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['unit_price'] * $item['quantity'];
        }

        $total = $subtotal + ($subtotal * ($validated['vat'] / 100));

        $estimate = Estimate::create([
            'user_id'    => Auth::id(),
            'client_id'  => $validated['client_id'],
            'project_id' => $validated['project_id'] ?? null,
            'title'      => $validated['title'],
            'description' => $validated['description'],
            'subtotal'   => $subtotal,
            'vat'        => $validated['vat'],
            'total'      => $total,
        ]);

        foreach ($validated['items'] as $item) {
            EstimateItem::create([
                'estimate_id' => $estimate->id,
                'label'       => $item['label'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total'       => $item['unit_price'] * $item['quantity']
            ]);
        }

        return $estimate->load('items');
    }

    public function update(Request $request, $id)
    {
        // Per semplicità, meglio rifare da zero → come store()
        return $this->store($request);
    }

    public function destroy($id)
    {
        $estimate = Estimate::where('user_id', Auth::id())->findOrFail($id);
        $estimate->delete();

        return ['message' => 'Estimate deleted'];
    }
}
