<?php

namespace App\Http\Controllers;

use App\Models\Patients;
use App\Models\Hospitals;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PatientsController extends Controller
{
    public function index()
    {
        // View sekarang akan mengisi tabel via DataTables AJAX,
        // jadi cukup kirim daftar rumah sakit untuk dropdown filter.
        $hospitals = Hospitals::orderBy('name')->get();

        return view('pages.patients', compact('hospitals'));
    }

    public function show($id)
    {
        $patient = Patients::findOrFail($id);
        return response()->json(['data' => $patient]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:15',
            'address'     => 'required|string|max:500',
        ]);

        try {
            Patients::create($validated);
            Alert::success('Sukses', 'Data pasien berhasil ditambahkan');
            return redirect()->route('patients.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Data pasien gagal ditambahkan');
            return back()->withInput();
        }
    }

    // Perbaiki parameter agar sesuai route-model binding default: {patient}
    public function update(Request $request, Patients $patient)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone'   => 'required|string|max:15',
            // Jika hospital_id juga diedit:
            'hospital_id' => 'nullable|exists:hospitals,id',
        ]);

        try {
            $patient->update($validated);
            Alert::success('Sukses', 'Data pasien berhasil diupdate');
            return redirect()->route('patients.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Data pasien gagal diupdate');
            return back()->withInput();
        }
    }

    // Pakai binding supaya DELETE /patients/{patient} langsung resolve model
    public function destroy(Patients $patient)
    {
        try {
            $patient->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    // Endpoint untuk DataTables AJAX
    public function filter(Request $request)
    {
        $q = Patients::query()
            ->select('id', 'hospital_id', 'name', 'phone', 'address')
            ->when($request->filled('hospital_id'), function ($qq) use ($request) {
                $qq->where('hospital_id', $request->hospital_id);
            })
            ->orderBy('name', 'asc');

        // DataTables (client-side) cukup butuh array 'patients'
        return response()->json([
            'patients' => $q->get()
        ]);
    }
}
