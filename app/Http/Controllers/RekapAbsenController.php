<?php

namespace App\Http\Controllers;

use App\Models\RekapAbsen;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRekapAbsenRequest;
use App\Http\Requests\UpdateRekapAbsenRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class RekapAbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRekapAbsenRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(RekapAbsen $rekapAbsen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RekapAbsen $rekapAbsen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRekapAbsenRequest $request, RekapAbsen $rekapAbsen): RedirectResponse
    {
        $rekapAbsen->update($request->validated());
        $rekapAbsen->save();
        return Redirect::route('absens.index')->with('status', 'Absen berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RekapAbsen $rekapAbsen)
    {
        //
    }
}
