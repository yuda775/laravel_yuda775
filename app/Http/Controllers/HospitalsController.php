<?php

namespace App\Http\Controllers;

use App\Models\Hospitals;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;


class HospitalsController extends Controller
{
    public function index()
    {
        // Tabel akan diisi via DataTables AJAX -> tidak perlu lempar $hospitals ke view
        return view('pages.hospitals');
    }

    // Endpoint untuk DataTables (client-side)
    public function data()
    {
        $rows = Hospitals::query()
            ->select('id', 'name', 'email', 'phone', 'address')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json(['hospitals' => $rows]);
    }

    public function show(Hospitals $hospital)
    {
        return response()->json(['data' => $hospital]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            Hospitals::create($validated);
            Alert::success('Sukses', 'Data rumah sakit berhasil ditambahkan');
            return redirect()->route('hospitals.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Data rumah sakit gagal ditambahkan');
            return back()->withInput();
        }
    }

    public function update(Request $request, Hospitals $hospital)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        try {
            $hospital->update($validated);
            Alert::success('Sukses', 'Data rumah sakit berhasil diupdate');
            return redirect()->route('hospitals.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Data rumah sakit gagal diupdate');
            return back()->withInput();
        }
    }

    public function destroy(Hospitals $hospital)
    {
        try {
            $hospital->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}
