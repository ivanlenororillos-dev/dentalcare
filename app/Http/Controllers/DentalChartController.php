<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Dentist;
use App\Models\TeethMaster;
use App\Models\ToothHistory;

class DentalChartController extends Controller
{
    public function show(Client $client)
    {
        $teethMaster = TeethMaster::orderBy('tooth_number')
            ->get()
            ->keyBy('tooth_number')
            ->map(fn($tooth) => [
        'number' => $tooth->tooth_number,
        'name' => $tooth->standard_name,
        'alternate' => $tooth->alternate_name,
        'quadrant' => $tooth->quadrant,
        'type' => $tooth->tooth_type,
        ]);

        $toothStatuses = $client->getLatestToothStatuses();

        $dentists = Dentist::orderBy('name')->get();

        return view('clients.chart', compact(
            'client',
            'teethMaster',
            'toothStatuses',
            'dentists',
        ));
    }

    public function calibrate(Client $client)
    {
        return view('clients.calibrate', compact('client'));
    }

    public function saveCalibration(Client $client)
    {
        $teeth = request()->input('teeth');
        if (!$teeth || !is_array($teeth)) {
            return response()->json(['success' => false, 'message' => 'Invalid data']);
        }

        $chartPath = resource_path('views/clients/chart.blade.php');
        $content = file_get_contents($chartPath);

        // Build the new $allTeeth array
        $sections = [
            ['label' => 'Upper Right Quadrant (1-8)', 'start' => 1, 'end' => 8],
            ['label' => 'Upper Left Quadrant (9-16)', 'start' => 9, 'end' => 16],
            ['label' => 'Lower Left Quadrant (17-24)', 'start' => 17, 'end' => 24],
            ['label' => 'Lower Right Quadrant (25-32)', 'start' => 25, 'end' => 32],
        ];

        $lines = ["                    \$allTeeth = ["];
        foreach ($sections as $sec) {
            $lines[] = "                        // {$sec['label']}";
            for ($i = $sec['start']; $i <= $sec['end']; $i++) {
                $t = $teeth[$i] ?? $teeth[(string)$i] ?? null;
                if (!$t)
                    continue;
                $pad = $i < 10 ? ' ' : '';
                $x = str_pad((int)$t['x'], 3, ' ', STR_PAD_LEFT);
                $y = str_pad((int)$t['y'], 3, ' ', STR_PAD_LEFT);
                $w = (int)$t['w'];
                $h = (int)$t['h'];
                $lines[] = "                        {$pad}{$i}  => ['x' => {$x},  'y' => {$y}, 'w' => {$w}, 'h' => {$h}],";
            }
            if ($sec['end'] < 32)
                $lines[] = '';
        }
        $lines[] = "                    ];";

        $newArray = implode("\n", $lines);

        // Replace the $allTeeth block using regex
        $pattern = '/\$allTeeth\s*=\s*\[.*?\];/s';
        $newContent = preg_replace($pattern, $newArray, $content, 1);

        if ($newContent && $newContent !== $content) {
            file_put_contents($chartPath, $newContent);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Could not update file']);
    }
}
