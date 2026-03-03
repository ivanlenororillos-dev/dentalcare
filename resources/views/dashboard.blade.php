<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Total Clients</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_clients'] }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Dentists on Staff</div>
                        <div class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_dentists'] }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Procedures Today</div>
                        <div class="mt-1 text-3xl font-semibold text-indigo-600">{{ $stats['procedures_today'] }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">This Month</div>
                        <div class="mt-1 text-3xl font-semibold text-indigo-600">{{ $stats['procedures_this_month'] }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Clients</h3>
                        @if($recentClients->isEmpty())
                            <p class="text-gray-500 text-sm">No clients yet. <a href="{{ route('clients.create') }}" class="text-indigo-600 hover:underline">Add your first client</a>.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($recentClients as $client)
                                    <li class="py-3">
                                        <a href="{{ route('clients.show', $client) }}" class="flex items-center justify-between hover:bg-gray-50 -mx-2 px-2 py-1 rounded">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $client->full_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $client->phone }}</div>
                                            </div>
                                            <span class="text-xs text-gray-400">{{ $client->created_at->diffForHumans() }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                        @if($recentActivity->isEmpty())
                            <p class="text-gray-500 text-sm">No procedures recorded yet.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($recentActivity as $activity)
                                    <li class="py-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $activity->client?->full_name ?? 'Unknown' }}
                                                    &mdash; Tooth #{{ $activity->tooth_number }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ \App\Models\ToothHistory::PROCEDURE_TYPES[$activity->procedure_type] ?? $activity->procedure_type }}
                                                    @if($activity->dentist)
                                                        by {{ $activity->dentist->name }}
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-400">{{ $activity->date_of_procedure->format('M d, Y') }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
