<?php

namespace App\Http\Controllers;

use App\InvoiceSerie;
use Illuminate\Http\Request;
use App\Services\InvoiceSerieService;
use Illuminate\Support\Facades\Route;

class InvoiceSerieController extends Controller
{
    protected $serieService;

    public function __construct(InvoiceSerieService $serieService)
    {
        $this->serieService = $serieService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $series = $this->serieService->paginate();

        return response()->json($series);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $newSerie = $this->serieService->create($request->all());

        return response()->json($newSerie, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\InvoiceSerie  $invoiceSerie
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceSerie $series)
    {
        return response()->json($series);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\InvoiceSerie  $invoiceSerie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceSerie $series)
    {
        $updatedSerie = $this->serieService->update($series->id, $request->all());

        return response()->json($updatedSerie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\InvoiceSerie  $invoiceSerie
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvoiceSerie $series)
    {
        $this->serieService->delete($series->id);

        return response()->json([
            'id' => $series->id,
        ]);
    }
}
