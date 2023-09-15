<?php

namespace App\Http\Controllers;

use App\Models\Rekanan;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\StoreRekananRequest;
use App\Http\Requests\UpdateRekananRequest;

use Maatwebsite\Excel\Excel as ExcelType;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekananExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Rekanan::all();
    } 
    
    public function headings(): array
    {
        return Schema::getColumnListing('rekanans');
    }
}

class RekananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $rekanans = Rekanan::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%");
        })->paginate(10);

        return view('rekanans.index', compact('rekanans'));
    }

    /**
     * Download the specified resource.
     */
    public function download()
    {
        return Excel::download(new RekananExport, 'rekanans.xlsx', ExcelType::XLSX);
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
    public function store(StoreRekananRequest $request): RedirectResponse
    {
        Rekanan::create($request->validated());
        return Redirect::route('rekanans.index')->with('status', 'Rekanan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rekanan $rekanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rekanan $rekanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRekananRequest $request, Rekanan $rekanan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rekanan $rekanan)
    {
        //
    }
}
