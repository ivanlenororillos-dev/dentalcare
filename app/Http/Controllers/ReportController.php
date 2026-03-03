<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\TeethMaster;
use App\Models\ToothHistory;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generatePdf(Client $client)
    {
        $teethMaster = TeethMaster::orderBy('tooth_number')
            ->get()
            ->keyBy('tooth_number');

        $toothStatuses = $client->getLatestToothStatuses();

        $historyByTooth = $client->toothHistories()
            ->with('dentist')
            ->orderBy('tooth_number')
            ->orderBy('date_of_procedure', 'desc')
            ->get()
            ->groupBy('tooth_number');

        $pdf = Pdf::loadView('reports.client-chart-pdf', compact(
            'client',
            'teethMaster',
            'toothStatuses',
            'historyByTooth',
        ));

        $pdf->setPaper('A4', 'portrait');

        $filename = 'dental-chart-' . str_replace(' ', '-', strtolower($client->full_name)) . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
