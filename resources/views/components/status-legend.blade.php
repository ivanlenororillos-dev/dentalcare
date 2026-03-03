<div class="bg-white rounded-lg shadow-sm p-4">
    <h4 class="text-sm font-semibold text-gray-700 mb-3">Status Legend</h4>
    <div class="space-y-2">
        @foreach(\App\Models\ToothHistory::STATUSES as $key => $status)
            <div class="flex items-center gap-2">
                <span class="inline-block w-4 h-4 rounded-full border border-gray-200" style="background-color: {{ $status['color'] }}"></span>
                <span class="text-xs text-gray-600">{{ $status['label'] }}</span>
                @if($status['symbol'])
                    <span class="text-xs font-mono text-gray-400">({{ $status['symbol'] }})</span>
                @endif
            </div>
        @endforeach
    </div>
</div>
