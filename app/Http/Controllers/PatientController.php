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

        return view('livewire.patient.index', [
            'patients' => $patients,
            'title' => 'Data Pasien'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('livewire.patient.create', [
            'title' => 'Tambah Pasien Baru'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients,email',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'required|string|size:16|unique:patients,nik',
            'date_of_birth' => 'required|date',
            'gender' => ['required', Rule::in(['L', 'P'])],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'bpjs_number' => 'nullable|string|unique:patients,bpjs_number',
        ]);

        // Hash the password
        $validated['password'] = bcrypt($validated['password']);

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
return view('livewire.patient.edit', [
            'patient' => $patient,
            'title' => 'Edit Data Pasien'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('patients')->ignore($patient->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'nik' => ['required', 'string', 'size:16', Rule::unique('patients')->ignore($patient->id)],
            'date_of_birth' => 'required|date',
            'gender' => ['required', Rule::in(['L', 'P'])],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'bpjs_number' => ['nullable', 'string', Rule::unique('patients')->ignore($patient->id)],
        ]);

        // Update password only if provided
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

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
