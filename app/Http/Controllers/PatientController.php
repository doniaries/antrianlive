<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::latest()
            ->filter(request(['search']))
            ->paginate(10)
            ->withQueryString();

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:patients,nik',
            'date_of_birth' => 'required|date',
            'gender' => ['required', Rule::in(['L', 'P'])],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        // Generate nomor rekam medis
        $validated['medical_record_number'] = 'RM-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        Patient::create($validated);

        return redirect()
            ->route('patients.index')
            ->with('success', 'Data pasien berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => ['required', 'string', 'size:16', Rule::unique('patients')->ignore($patient->id)],
            'date_of_birth' => 'required|date',
            'gender' => ['required', Rule::in(['L', 'P'])],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()
            ->route('patients.index')
            ->with('success', 'Data pasien berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        // Cek apakah pasien memiliki riwayat antrian
        if ($patient->antrians()->exists()) {
            return back()
                ->with('error', 'Tidak dapat menghapus pasien karena memiliki riwayat antrian');
        }

        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', 'Data pasien berhasil dihapus');
    }
}
