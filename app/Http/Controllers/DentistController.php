<?php

namespace App\Http\Controllers;

use App\Models\Dentist;
use Illuminate\Http\Request;

class DentistController extends Controller
{
    public function index()
    {
        $dentists = Dentist::orderBy('name')->paginate(15);
        return view('dentists.index', compact('dentists'));
    }

    public function create()
    {
        return view('dentists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255', 'unique:dentists,license_number'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        Dentist::create($validated);

        return redirect()->route('dentists.index')
            ->with('success', 'Dentist added successfully.');
    }

    public function edit(Dentist $dentist)
    {
        return view('dentists.edit', compact('dentist'));
    }

    public function update(Request $request, Dentist $dentist)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255', 'unique:dentists,license_number,' . $dentist->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $dentist->update($validated);

        return redirect()->route('dentists.index')
            ->with('success', 'Dentist updated successfully.');
    }

    public function destroy(Dentist $dentist)
    {
        $dentist->delete();

        return redirect()->route('dentists.index')
            ->with('success', 'Dentist removed successfully.');
    }
}
