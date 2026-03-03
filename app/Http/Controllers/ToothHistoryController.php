<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreToothHistoryRequest;
use App\Models\Client;
use App\Models\ToothHistory;
use Illuminate\Http\JsonResponse;

class ToothHistoryController extends Controller
{
    public function index(Client $client, int $toothNumber): JsonResponse
    {
        $history = $client->toothHistories()
            ->where('tooth_number', $toothNumber)
            ->with('dentist')
            ->orderBy('date_of_procedure', 'desc')
            ->get()
            ->map(fn (ToothHistory $h) => [
                'id' => $h->id,
                'procedure_type' => $h->procedure_type,
                'procedure_label' => ToothHistory::PROCEDURE_TYPES[$h->procedure_type] ?? $h->procedure_type,
                'status' => $h->status,
                'status_label' => ToothHistory::STATUSES[$h->status]['label'] ?? $h->status,
                'surface' => $h->surface,
                'detailed_notes' => $h->detailed_notes,
                'dentist_name' => $h->dentist?->name,
                'date_of_procedure' => $h->date_of_procedure->format('Y-m-d'),
                'created_at' => $h->created_at->format('M d, Y H:i'),
            ]);

        return response()->json($history);
    }

    public function store(StoreToothHistoryRequest $request, Client $client, int $toothNumber): JsonResponse
    {
        $record = $client->toothHistories()->create(
            array_merge($request->validated(), ['tooth_number' => $toothNumber])
        );

        $record->load('dentist');

        return response()->json([
            'id' => $record->id,
            'procedure_type' => $record->procedure_type,
            'procedure_label' => ToothHistory::PROCEDURE_TYPES[$record->procedure_type] ?? $record->procedure_type,
            'status' => $record->status,
            'status_label' => ToothHistory::STATUSES[$record->status]['label'] ?? $record->status,
            'surface' => $record->surface,
            'detailed_notes' => $record->detailed_notes,
            'dentist_name' => $record->dentist?->name,
            'date_of_procedure' => $record->date_of_procedure->format('Y-m-d'),
            'created_at' => $record->created_at->format('M d, Y H:i'),
            'new_status' => $record->status,
        ], 201);
    }

    public function update(StoreToothHistoryRequest $request, Client $client, int $toothNumber, ToothHistory $history): JsonResponse
    {
        $history->update($request->validated());
        $history->load('dentist');

        $latestStatus = $client->toothHistories()
            ->where('tooth_number', $toothNumber)
            ->orderBy('date_of_procedure', 'desc')
            ->orderBy('created_at', 'desc')
            ->value('status');

        return response()->json([
            'id' => $history->id,
            'procedure_type' => $history->procedure_type,
            'procedure_label' => ToothHistory::PROCEDURE_TYPES[$history->procedure_type] ?? $history->procedure_type,
            'status' => $history->status,
            'status_label' => ToothHistory::STATUSES[$history->status]['label'] ?? $history->status,
            'surface' => $history->surface,
            'detailed_notes' => $history->detailed_notes,
            'dentist_name' => $history->dentist?->name,
            'date_of_procedure' => $history->date_of_procedure->format('Y-m-d'),
            'created_at' => $history->created_at->format('M d, Y H:i'),
            'new_status' => $latestStatus,
        ]);
    }

    public function destroy(Client $client, int $toothNumber, ToothHistory $history): JsonResponse
    {
        $history->delete();

        $latestStatus = $client->toothHistories()
            ->where('tooth_number', $toothNumber)
            ->orderBy('date_of_procedure', 'desc')
            ->orderBy('created_at', 'desc')
            ->value('status') ?? 'healthy';

        return response()->json([
            'message' => 'Record deleted.',
            'new_status' => $latestStatus,
        ]);
    }
}
