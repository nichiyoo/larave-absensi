<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\RekapAbsen;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAbsenRequest;
use App\Http\Requests\UpdateAbsenRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $rekap = RekapAbsen::where('user_id', $request->user()->id)
            ->whereDate('tanggal', today())
            ->firstOrCreate([
                'user_id' => $request->user()->id,
                'tanggal' => today(),
            ])->load('checkin', 'checkout');

        return view('absens.index', [
            'rekap' => $rekap,
            'checkin' => $rekap->checkin,
            'checkout' => $rekap->checkout,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAbsenRequest $request): RedirectResponse
    {
        // Get or create today's rekap
        $rekap = RekapAbsen::where('user_id', $request->user()->id)
            ->whereDate('tanggal', today())
            ->firstOrCreate([
                'user_id' => $request->user()->id,
                'tanggal' => today(),
            ]);


        // Check if user already checkin and checkout
        if ($rekap->checkin !== null && $rekap->checkout !== null) {
            return Redirect::route('absens.index')->with('status', 'Anda sudah absen hari ini.');
        }

        // Save photo
        $filename = sprintf('absens/%s.jpg', time());
        Storage::disk('public')->put($filename, base64_decode($request->photo));

        // Save absen
        if ($rekap->checkin === null) {
            $rekap->update([
                'checkin_id' => Absen::create([
                    'photo' => $filename,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ])->id,
            ]);
        } else if ($rekap->checkout === null) {
            $rekap->update([
                'checkout_id' => Absen::create([
                    'photo' => $filename,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ])->id,
            ]);
        }

        // Redirect
        return Redirect::route('absens.index')->with('status', 'Absen berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Absen $absen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absen $absen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAbsenRequest $request, Absen $absen)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absen $absen)
    {
        //
    }
}
