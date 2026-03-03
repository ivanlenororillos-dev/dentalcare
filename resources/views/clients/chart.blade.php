<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dental Chart: {{ $client->full_name }}</h2>
                <p class="text-sm text-gray-500 mt-1">Click any tooth to view history and add procedures</p>
            </div>
            <div class="flex gap-2">
                {{-- <a href="{{ route('calibrate', $client) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600 transition">
                    Calibrate Overlays
                </a> --}}
                <a href="{{ route('clients.report.pdf', $client) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                    Download PDF
                </a>
                <a href="{{ route('clients.show', $client) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    Client Profile
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" x-data="dentalChart()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                {{-- Left sidebar: quadrant legend --}}
                <div class="xl:col-span-1 space-y-4">
                    @include('components.status-legend')

                    <div class="bg-white rounded-lg shadow-sm p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Quick Stats</h4>
                        <div class="space-y-1 text-xs text-gray-600">
                            <template x-for="(info, status) in statusCounts" :key="status">
                                <div class="flex items-center justify-between" x-show="info.count > 0">
                                    <span x-text="info.label"></span>
                                    <span class="font-semibold" x-text="info.count"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Main chart area --}}
                <div class="xl:col-span-3">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        {{-- Tooltip --}}
                        <div x-show="hoveredTooth" x-cloak
                             class="fixed z-50 px-3 py-2 text-sm bg-gray-900 text-white rounded-lg shadow-lg pointer-events-none"
                             :style="'left:' + tooltipX + 'px; top:' + tooltipY + 'px; transform: translate(-50%, -120%)'">
                            <span x-text="hoveredToothLabel"></span>
                        </div>

                        <h3 class="text-center text-lg font-bold text-gray-800 mb-3">Tooth Number Chart</h3>
                        <svg viewBox="0 0 403 672" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto mx-auto" style="max-height: 75vh; max-width: 500px;">
                            <image href="{{ asset('images/tooth-chart.png') }}" x="0" y="0" width="403" height="672" />

                            @php
                                                                        $allTeeth = [
                        // Upper Right Quadrant (1-8)
                         1  => ['x' =>  42,  'y' => 281, 'w' => 28, 'h' => 22],
                         2  => ['x' =>  41,  'y' => 247, 'w' => 28, 'h' => 22],
                         3  => ['x' =>  43,  'y' => 214, 'w' => 28, 'h' => 22],
                         4  => ['x' =>  54,  'y' => 181, 'w' => 28, 'h' => 22],
                         5  => ['x' =>  68,  'y' => 147, 'w' => 28, 'h' => 22],
                         6  => ['x' =>  74,  'y' => 104, 'w' => 28, 'h' => 22],
                         7  => ['x' => 108,  'y' =>  78, 'w' => 28, 'h' => 22],
                         8  => ['x' => 160,  'y' =>  65, 'w' => 28, 'h' => 22],

                        // Upper Left Quadrant (9-16)
                         9  => ['x' => 212,  'y' =>  66, 'w' => 28, 'h' => 22],
                        10  => ['x' => 263,  'y' =>  78, 'w' => 28, 'h' => 22],
                        11  => ['x' => 297,  'y' => 104, 'w' => 28, 'h' => 22],
                        12  => ['x' => 304,  'y' => 149, 'w' => 28, 'h' => 22],
                        13  => ['x' => 318,  'y' => 182, 'w' => 28, 'h' => 22],
                        14  => ['x' => 328,  'y' => 215, 'w' => 28, 'h' => 22],
                        15  => ['x' => 329,  'y' => 248, 'w' => 28, 'h' => 22],
                        16  => ['x' => 333,  'y' => 280, 'w' => 28, 'h' => 22],

                        // Lower Left Quadrant (17-24)
                        17  => ['x' => 331,  'y' => 401, 'w' => 28, 'h' => 22],
                        18  => ['x' => 333,  'y' => 434, 'w' => 28, 'h' => 22],
                        19  => ['x' => 327,  'y' => 470, 'w' => 28, 'h' => 22],
                        20  => ['x' => 320,  'y' => 503, 'w' => 28, 'h' => 22],
                        21  => ['x' => 302,  'y' => 533, 'w' => 28, 'h' => 22],
                        22  => ['x' => 296,  'y' => 579, 'w' => 28, 'h' => 22],
                        23  => ['x' => 259,  'y' => 604, 'w' => 28, 'h' => 22],
                        24  => ['x' => 211,  'y' => 621, 'w' => 28, 'h' => 22],

                        // Lower Right Quadrant (25-32)
                        25  => ['x' => 159,  'y' => 621, 'w' => 28, 'h' => 22],
                        26  => ['x' => 114,  'y' => 604, 'w' => 28, 'h' => 22],
                        27  => ['x' =>  74,  'y' => 578, 'w' => 28, 'h' => 22],
                        28  => ['x' =>  65,  'y' => 535, 'w' => 28, 'h' => 22],
                        29  => ['x' =>  53,  'y' => 501, 'w' => 28, 'h' => 22],
                        30  => ['x' =>  45,  'y' => 471, 'w' => 28, 'h' => 22],
                        31  => ['x' =>  38,  'y' => 434, 'w' => 28, 'h' => 22],
                        32  => ['x' =>  38,  'y' => 399, 'w' => 28, 'h' => 22],
                    ];
                            @endphp

                            @foreach($allTeeth as $num => $pos)
                                <g class="cursor-pointer" pointer-events="all"
                                   @click="selectTooth({{ $num }})"
                                   @mouseenter="showTooltip({{ $num }}, $event)"
                                   @mouseleave="hideTooltip()">
                                    <rect x="{{ $pos['x'] }}" y="{{ $pos['y'] }}"
                                          width="{{ $pos['w'] }}" height="{{ $pos['h'] }}"
                                          rx="6" ry="6"
                                          :fill="getToothColor({{ $num }})"
                                          :fill-opacity="getToothOpacity({{ $num }})"
                                          :stroke="selectedTooth === {{ $num }} ? '#1a1a2e' : 'transparent'"
                                          :stroke-width="selectedTooth === {{ $num }} ? 2.5 : 0"
                                          class="transition-all duration-150"/>
                                    <text x="{{ $pos['x'] + $pos['w']/2 }}" y="{{ $pos['y'] + $pos['h']/2 + 1 }}"
                                          text-anchor="middle" dominant-baseline="middle"
                                          font-size="10" font-weight="bold" fill="#fff"
                                          stroke="#000" stroke-width="0.5" paint-order="stroke"
                                          x-text="getToothSymbol({{ $num }})"
                                          x-show="getToothSymbol({{ $num }})"></text>
                                </g>
                            @endforeach
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== SLIDE-OVER PANEL ===== --}}
        <div x-show="showPanel" x-cloak
             class="fixed inset-0 z-40" @keydown.escape.window="closePanel()">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/30" @click="closePanel()" x-show="showPanel"
                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

            {{-- Panel --}}
            <div class="absolute right-0 top-0 h-full w-full max-w-lg bg-white shadow-xl flex flex-col"
                 x-show="showPanel"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                {{-- Panel header --}}
                <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Tooth #<span x-text="selectedTooth"></span>
                        </h3>
                        <p class="text-sm text-gray-500" x-text="selectedToothName"></p>
                    </div>
                    <button @click="closePanel()" class="rounded-md p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Panel body (scrollable) --}}
                <div class="flex-1 overflow-y-auto p-6">
                    {{-- Loading state --}}
                    <div x-show="loadingHistory" class="flex items-center justify-center py-12">
                        <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </div>

                    <div x-show="!loadingHistory">
                        {{-- Add new procedure form --}}
                        <div class="mb-6">
                            <button @click="showForm = !showForm"
                                    class="w-full flex items-center justify-between px-4 py-3 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition text-sm font-medium">
                                <span x-text="showForm ? 'Hide Form' : '+ Add New Procedure'"></span>
                                <svg class="w-4 h-4 transition-transform" :class="showForm && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="showForm" x-collapse class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Procedure Type *</label>
                                    <select x-model="form.procedure_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Select...</option>
                                        @foreach(\App\Models\ToothHistory::PROCEDURE_TYPES as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Resulting Status *</label>
                                    <select x-model="form.status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Select...</option>
                                        @foreach(\App\Models\ToothHistory::STATUSES as $key => $info)
                                            <option value="{{ $key }}">{{ $info['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Surface</label>
                                    <select x-model="form.surface" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">N/A</option>
                                        @foreach(\App\Models\ToothHistory::SURFACES as $key => $label)
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Dentist</label>
                                    <select x-model="form.dentist_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Select...</option>
                                        @foreach($dentists as $dentist)
                                            <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Procedure *</label>
                                    <input type="date" x-model="form.date_of_procedure"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea x-model="form.detailed_notes" rows="3"
                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                              placeholder="Additional details about the procedure..."></textarea>
                                </div>

                                <div x-show="formError" class="text-sm text-red-600 bg-red-50 rounded p-2" x-text="formError"></div>

                                <div class="flex gap-2">
                                    <button @click="saveRecord()"
                                            :disabled="saving"
                                            class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition disabled:opacity-50">
                                        <span x-show="!saving">Save Procedure</span>
                                        <span x-show="saving">Saving...</span>
                                    </button>
                                    <button @click="resetForm()" class="px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- History list --}}
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Procedure History</h4>
                            <div x-show="toothHistory.length === 0" class="text-sm text-gray-400 text-center py-6">
                                No procedures recorded for this tooth.
                            </div>
                            <div class="space-y-3">
                                <template x-for="record in toothHistory" :key="record.id">
                                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900" x-text="record.procedure_label"></span>
                                                <span class="text-xs px-2 py-0.5 rounded-full ml-2" x-text="record.status_label"
                                                      :style="'background-color:' + getStatusColor(record.status) + '20; color:' + getStatusColor(record.status)"></span>
                                            </div>
                                            <button @click="deleteRecord(record.id)"
                                                    class="text-xs text-red-500 hover:text-red-700 hover:underline">Delete</button>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            <span x-text="record.date_of_procedure"></span>
                                            <span x-show="record.dentist_name"> &mdash; <span x-text="record.dentist_name"></span></span>
                                            <span x-show="record.surface"> &mdash; Surface: <span x-text="record.surface"></span></span>
                                        </div>
                                        <div x-show="record.detailed_notes" class="mt-2 text-xs text-gray-600 bg-gray-50 rounded p-2" x-text="record.detailed_notes"></div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dentalChart', () => ({
                clientId: @json($client->id),
                toothStatuses: @json((object)$toothStatuses),
                teethMaster: @json($teethMaster),
                selectedTooth: null,
                selectedToothName: '',
                toothHistory: [],
                showPanel: false,
                showForm: false,
                loadingHistory: false,
                saving: false,
                formError: '',
                hoveredTooth: null,
                hoveredToothLabel: '',
                tooltipX: 0,
                tooltipY: 0,

                statusColors: @json(collect(\App\Models\ToothHistory::STATUSES)->map(fn($s) => $s['color'])),
                statusSymbols: @json(collect(\App\Models\ToothHistory::STATUSES)->map(fn($s) => $s['symbol'])),

                form: {
                    procedure_type: '',
                    status: '',
                    surface: '',
                    dentist_id: '',
                    date_of_procedure: new Date().toISOString().split('T')[0],
                    detailed_notes: '',
                },

                get statusCounts() {
                    const counts = {};
                    const labels = @json(collect(\App\Models\ToothHistory::STATUSES)->map(fn($s) => $s['label']));
                    for (const [key, label] of Object.entries(labels)) {
                        counts[key] = {
                            label: label,
                            count: Object.values(this.toothStatuses).filter(s => s === key).length
                        };
                    }
                    return counts;
                },

                getToothColor(num) {
                    const status = this.toothStatuses[num] || 'healthy';
                    return this.statusColors[status] || '#4CAF50';
                },

                getToothSymbol(num) {
                    const status = this.toothStatuses[num];
                    if (!status || status === 'healthy') return '';
                    return this.statusSymbols[status] || '';
                },

                getToothOpacity(num) {
                    const status = this.toothStatuses[num] || 'healthy';
                    if (this.selectedTooth === num) return 0.65;
                    return status === 'healthy' ? 0 : 0.5;
                },

                getStatusColor(status) {
                    return this.statusColors[status] || '#999';
                },

                showTooltip(num, event) {
                    this.hoveredTooth = num;
                    const tooth = this.teethMaster[num];
                    if (tooth) {
                        let label = `#${num} - ${tooth.name}`;
                        if (tooth.alternate) label += ` (${tooth.alternate})`;
                        this.hoveredToothLabel = label;
                    }
                    const rect = event.target.closest('g').getBoundingClientRect();
                    this.tooltipX = rect.left + rect.width / 2;
                    this.tooltipY = rect.top;
                },

                hideTooltip() {
                    this.hoveredTooth = null;
                },

                selectTooth(num) {
                    this.selectedTooth = num;
                    const tooth = this.teethMaster[num];
                    if (tooth) {
                        let label = `${tooth.name}`;
                        if (tooth.alternate) label += ` (${tooth.alternate})`;
                        label += ` - ${tooth.quadrant.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}`;
                        this.selectedToothName = label;
                    }
                    this.showPanel = true;
                    this.fetchHistory();
                },

                closePanel() {
                    this.showPanel = false;
                    this.showForm = false;
                    this.resetForm();
                },

                async fetchHistory() {
                    this.loadingHistory = true;
                    try {
                        const res = await fetch(`/clients/${this.clientId}/teeth/${this.selectedTooth}/history`);
                        this.toothHistory = await res.json();
                    } catch (e) {
                        console.error('Failed to fetch history:', e);
                    }
                    this.loadingHistory = false;
                },

                async saveRecord() {
                    this.formError = '';
                    if (!this.form.procedure_type || !this.form.status || !this.form.date_of_procedure) {
                        this.formError = 'Please fill in all required fields.';
                        return;
                    }
                    this.saving = true;
                    try {
                        const res = await fetch(`/clients/${this.clientId}/teeth/${this.selectedTooth}/history`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(this.form),
                        });
                        if (!res.ok) {
                            const err = await res.json();
                            this.formError = err.message || 'Validation failed.';
                            this.saving = false;
                            return;
                        }
                        const data = await res.json();
                        this.toothHistory.unshift(data);
                        if (data.new_status) {
                            this.toothStatuses[this.selectedTooth] = data.new_status;
                        }
                        this.resetForm();
                        this.showForm = false;
                    } catch (e) {
                        this.formError = 'Network error. Please try again.';
                    }
                    this.saving = false;
                },

                async deleteRecord(id) {
                    if (!confirm('Delete this procedure record?')) return;
                    try {
                        const res = await fetch(`/clients/${this.clientId}/teeth/${this.selectedTooth}/history/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                        });
                        const data = await res.json();
                        this.toothHistory = this.toothHistory.filter(r => r.id !== id);
                        if (data.new_status) {
                            this.toothStatuses[this.selectedTooth] = data.new_status;
                        }
                    } catch (e) {
                        console.error('Failed to delete:', e);
                    }
                },

                resetForm() {
                    this.form = {
                        procedure_type: '',
                        status: '',
                        surface: '',
                        dentist_id: '',
                        date_of_procedure: new Date().toISOString().split('T')[0],
                        detailed_notes: '',
                    };
                    this.formError = '';
                },
            }));
        });
    </script>
</x-app-layout>
