<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Billing\BillingNovedadImporterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BillingNovedadImportController extends Controller
{
    protected BillingNovedadImporterService $importer;

    public function __construct(BillingNovedadImporterService $importer)
    {
        $this->importer = $importer;
    }

    /**
     * Phase 1: Dry-run CSV validation endpoint
     */
    public function validateImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'mode' => 'nullable|string|in:create_only,create_or_update,update_only',
        ]);

        $file = $request->file('file');
        $mode = $request->input('mode', 'create_only');

        $result = $this->importer->validateCsv($file->getRealPath(), $mode);

        return response()->json($result);
    }

    /**
     * Phase 2: Execute actual CSV import endpoint
     */
    public function executeImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'mode' => 'nullable|string|in:create_only,create_or_update,update_only',
        ]);

        $file = $request->file('file');
        $mode = $request->input('mode', 'create_only');

        $result = $this->importer->importCsv($file->getRealPath(), $mode);

        return response()->json($result);
    }

    /**
     * Download sample CSV template
     */
    public function downloadTemplate()
    {
        $samplePath = public_path('samples/novedades_import_example.csv');

        if (!file_exists($samplePath)) {
            return response()->json(['error' => 'Plantilla no encontrada'], 404);
        }

        return Response::download($samplePath, 'plantilla_importacion_novedades.csv', [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    }
}
