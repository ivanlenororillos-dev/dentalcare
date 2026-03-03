<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dental Chart - {{ $client->full_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        .header h1 { font-size: 20px; color: #1a1a2e; }
        .header p { font-size: 10px; color: #666; margin-top: 4px; }
        .client-info { margin-bottom: 20px; }
        .client-info table { width: 100%; }
        .client-info td { padding: 3px 8px; vertical-align: top; }
        .client-info .label { font-weight: bold; color: #555; width: 120px; }
        .section-title { font-size: 14px; font-weight: bold; color: #1a1a2e; margin: 15px 0 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .chart-grid { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .chart-grid th { background: #f5f5f5; padding: 4px 6px; text-align: left; font-size: 9px; border: 1px solid #ddd; }
        .chart-grid td { padding: 4px 6px; border: 1px solid #ddd; font-size: 9px; }
        .status-badge { display: inline-block; padding: 1px 6px; border-radius: 8px; font-size: 8px; font-weight: bold; color: #fff; }
        .history-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .history-table th { background: #f9f9f9; padding: 4px 6px; text-align: left; font-size: 9px; border: 1px solid #ddd; }
        .history-table td { padding: 3px 6px; border: 1px solid #ddd; font-size: 9px; }
        .legend { margin-bottom: 15px; }
        .legend-item { display: inline-block; margin-right: 12px; font-size: 9px; }
        .legend-color { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 3px; vertical-align: middle; }
        .footer { text-align: center; font-size: 8px; color: #999; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 8px; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dental Care - Client Dental Chart</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>

    <div class="client-info">
        <table>
            <tr>
                <td class="label">Client Name:</td>
                <td>{{ $client->full_name }}</td>
                <td class="label">Date of Birth:</td>
                <td>{{ $client->date_of_birth->format('F d, Y') }} ({{ $client->date_of_birth->age }} years)</td>
            </tr>
            <tr>
                <td class="label">Phone:</td>
                <td>{{ $client->phone }}</td>
                <td class="label">Email:</td>
                <td>{{ $client->email ?? 'N/A' }}</td>
            </tr>
            @if($client->medical_notes)
            <tr>
                <td class="label">Medical Notes:</td>
                <td colspan="3" style="color: #c00;">{{ $client->medical_notes }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="section-title">Current Tooth Status Overview</div>

    <div class="legend">
        @foreach(\App\Models\ToothHistory::STATUSES as $key => $status)
            <span class="legend-item">
                <span class="legend-color" style="background-color: {{ $status['color'] }};"></span>
                {{ $status['label'] }}
            </span>
        @endforeach
    </div>

    {{-- Tooth status grid --}}
    <table class="chart-grid">
        <thead>
            <tr>
                <th colspan="16" style="text-align: center;">Upper Jaw</th>
            </tr>
            <tr>
                @for($i = 1; $i <= 16; $i++)
                    <th style="text-align: center; width: 6.25%;">#{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            <tr>
                @for($i = 1; $i <= 16; $i++)
                    @php $status = $toothStatuses[$i] ?? 'healthy'; $info = \App\Models\ToothHistory::STATUSES[$status] ?? ['color' => '#4CAF50', 'label' => 'Healthy', 'symbol' => '']; @endphp
                    <td style="text-align: center;">
                        <span class="status-badge" style="background-color: {{ $info['color'] }};">{{ $info['symbol'] ?: 'OK' }}</span>
                    </td>
                @endfor
            </tr>
        </tbody>
    </table>

    <table class="chart-grid">
        <thead>
            <tr>
                <th colspan="16" style="text-align: center;">Lower Jaw</th>
            </tr>
            <tr>
                @for($i = 32; $i >= 17; $i--)
                    <th style="text-align: center; width: 6.25%;">#{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            <tr>
                @for($i = 32; $i >= 17; $i--)
                    @php $status = $toothStatuses[$i] ?? 'healthy'; $info = \App\Models\ToothHistory::STATUSES[$status] ?? ['color' => '#4CAF50', 'label' => 'Healthy', 'symbol' => '']; @endphp
                    <td style="text-align: center;">
                        <span class="status-badge" style="background-color: {{ $info['color'] }};">{{ $info['symbol'] ?: 'OK' }}</span>
                    </td>
                @endfor
            </tr>
        </tbody>
    </table>

    @if($historyByTooth->isNotEmpty())
        <div class="section-title">Complete Procedure History</div>

        @foreach($historyByTooth as $toothNum => $records)
            @php $tooth = $teethMaster[$toothNum] ?? null; @endphp
            <p style="font-weight: bold; margin: 8px 0 4px; font-size: 10px;">
                Tooth #{{ $toothNum }} &mdash; {{ $tooth?->standard_name ?? 'Unknown' }}
                @if($tooth?->alternate_name) ({{ $tooth->alternate_name }}) @endif
                &mdash; {{ ucwords(str_replace('_', ' ', $tooth?->quadrant ?? '')) }}
            </p>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Procedure</th>
                        <th>Status</th>
                        <th>Surface</th>
                        <th>Dentist</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>{{ $record->date_of_procedure->format('Y-m-d') }}</td>
                            <td>{{ \App\Models\ToothHistory::PROCEDURE_TYPES[$record->procedure_type] ?? $record->procedure_type }}</td>
                            <td>{{ \App\Models\ToothHistory::STATUSES[$record->status]['label'] ?? $record->status }}</td>
                            <td>{{ $record->surface ? ucfirst($record->surface) : '—' }}</td>
                            <td>{{ $record->dentist?->name ?? '—' }}</td>
                            <td>{{ $record->detailed_notes ? \Illuminate\Support\Str::limit($record->detailed_notes, 60) : '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @else
        <p style="text-align: center; color: #999; margin-top: 20px;">No procedure history recorded for this client.</p>
    @endif

    <div class="footer">
        Dental Care Clinic Management System &bull; Confidential Patient Record &bull; Page 1
    </div>
</body>
</html>
