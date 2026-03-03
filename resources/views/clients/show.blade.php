<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $client->full_name }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('clients.chart', $client) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition">
                    Dental Chart
                </a>
                <a href="{{ route('clients.report.pdf', $client) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Download PDF
                </a>
                <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Client Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->date_of_birth->format('F d, Y') }} ({{ $client->date_of_birth->age }} years)</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                    <dd class="text-sm text-gray-900">{{ ucfirst($client->gender) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->phone }}</dd>
                                </div>
                                @if($client->email)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->email }}</dd>
                                </div>
                                @endif
                                @if($client->address)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->address }}</dd>
                                </div>
                                @endif
                                @if($client->medical_notes)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Medical Notes</dt>
                                    <dd class="text-sm text-red-700 bg-red-50 rounded p-2 mt-1">{{ $client->medical_notes }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Recent Procedures</h3>
                                <span class="text-sm text-gray-500">{{ $client->tooth_histories_count }} total records</span>
                            </div>
                            @if($latestProcedures->isEmpty())
                                <p class="text-gray-500 text-sm text-center py-6">No procedures recorded. <a href="{{ route('clients.chart', $client) }}" class="text-indigo-600 hover:underline">Open the dental chart</a> to add records.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tooth</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Procedure</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dentist</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach($latestProcedures as $proc)
                                                <tr>
                                                    <td class="px-4 py-2 text-sm">#{{ $proc->tooth_number }}</td>
                                                    <td class="px-4 py-2 text-sm">{{ \App\Models\ToothHistory::PROCEDURE_TYPES[$proc->procedure_type] ?? $proc->procedure_type }}</td>
                                                    <td class="px-4 py-2 text-sm">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                                              style="background-color: {{ \App\Models\ToothHistory::STATUSES[$proc->status]['color'] ?? '#ccc' }}20; color: {{ \App\Models\ToothHistory::STATUSES[$proc->status]['color'] ?? '#333' }}">
                                                            {{ \App\Models\ToothHistory::STATUSES[$proc->status]['label'] ?? $proc->status }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $proc->dentist?->name ?? '—' }}</td>
                                                    <td class="px-4 py-2 text-sm text-gray-500">{{ $proc->date_of_procedure->format('M d, Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('clients.chart', $client) }}" class="text-sm text-indigo-600 hover:underline">View full dental chart &rarr;</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
