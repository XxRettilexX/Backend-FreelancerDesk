<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        return Invoice::where('user_id', Auth::id())
            ->with(['client', 'project'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_number' => 'required|string',
            'amount' => 'required|numeric',
            'vat'    => 'required|numeric',
            'due_date' => 'nullable|date'
        ]);

        $validated['user_id'] = Auth::id();

        return Invoice::create($validated);
    }

    public function destroy($id)
    {
        $invoice = Invoice::where('user_id', Auth::id())->findOrFail($id);
        $invoice->delete();

        return ['message' => 'Invoice deleted'];
    }
}
