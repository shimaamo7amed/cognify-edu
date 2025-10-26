<x-filament-panels::page>
    {{-- ✅ SweetAlert Messages --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6',
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33',
                });
            });
        </script>
    @endif

    {{-- 🔙 Header --}}
    <div class="bg-white rounded-xl shadow p-6 mb-6 border-b flex justify-between items-start">
        <div>
            <a href="{{ \App\Filament\Pages\ViewRequest::getUrl(['record' => $requestCase->id]) }}"
                class="text-sm text-gray-600 hover:text-primary-600 inline-flex items-center">
                <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
                Back to Request
            </a>

            <div class="mt-2 flex items-center gap-2">
                <x-heroicon-o-document-text class="w-5 h-5 text-primary-600" />
                <h2 class="text-xl font-bold text-gray-800">Observation Report</h2>
            </div>

            <p class="text-sm text-gray-500 mt-1">
                {{ $requestCase->child->name }} - Request #{{ $requestCase->id }}
            </p>
        </div>

        <div class="text-right space-y-1 text-sm">
            <div>
                <span class="font-semibold text-gray-600">Progress:</span>
                <span class="text-blue-600 font-bold">{{ $progress }}%</span>
            </div>
            <div>
                <span class="font-semibold text-gray-600">Status:</span>
                <span class="text-yellow-600 font-semibold capitalize">
                    {{ $formData['status'] ?? 'Draft' }}
                </span>
            </div>
            <div>
                <span class="font-semibold text-gray-600">Last Saved:</span>
                <span class="text-gray-500">
                    {{ $lastSavedAt?->setTimezone('Africa/Cairo')->format('h:i:s A') }}
                </span>
            </div>
        </div>
    </div>

    {{-- 🧭 Form & Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <form wire:submit.prevent="submit">
                {{ $this->form }}
            </form>

            {{-- 🎯 Actions --}}
            <div class="bg-white p-4 rounded-lg shadow border space-y-3">
                <h3 class="text-lg font-bold text-gray-700 mb-2">Actions</h3>

                <button wire:click="saveDraft"
                        class="w-full py-2 px-4 border border-red-400 text-red-600 rounded-md hover:bg-red-50 transition">
                    💾 Save Draft
                </button>

                <button onclick="showPreviewModal()"
                        type="button"
                        class="w-full py-2 px-4 bg-yellow-300 hover:bg-yellow-400 text-black font-semibold rounded-md transition">
                    👁️ Preview Report
                </button>

                @if ((int) $progress === 100)
                    <div class="mt-4 flex flex-col gap-3">
                        <button
                            wire:click="finalizeReport"
                            wire:loading.attr="disabled"
                            class="w-full bg-green-300 text-black text-base font-semibold py-3 px-4 rounded-md shadow hover:bg-green-400 transition">
                            ✅ Finalize Report
                        </button>
                        @if(
                            auth('admin')->user()?->hasRole('admin') &&
                            Str::contains(request()->url(),'cognifyAdmin') &&
                            ($observationReport?->delivery_status !== 'sent')
                        )
                            <button wire:click="sendToParent" wire:loading.attr="disabled" class="w-full bg-blue-300 text-black text-base font-semibold py-3 px-4 rounded-md shadow hover:bg-blue-400 transition">
                                ✉️ Send to Parent
                            </button>
                        @endif

                    </div>

                @endif
            </div>
        </div>

        {{-- 📦 Sidebar --}}
        <div class="space-y-4">
            <x-filament::card>
                <h3 class="text-base font-semibold text-gray-800 mb-2">👦 Child Information</h3>
                <div class="text-sm text-gray-700 space-y-1">
                    <p><strong>Name:</strong> {{ $requestCase->child->fullName }}</p>
                    <p><strong>Age:</strong> {{ $requestCase->child->age ?? '-' }}</p>
                    <p><strong>School:</strong> {{ $requestCase->child->schoolName ?? '-' }}</p>
                </div>
            </x-filament::card>

            <x-filament::card>
                <h3 class="text-base font-semibold text-gray-800 mb-2">📝 Report Status</h3>
                <div class="text-sm text-gray-700 space-y-1">
                    <p><strong>Progress:</strong> {{ $progress }}%</p>
                    <p><strong>Status:</strong> {{ $formData['status'] ?? 'Draft' }}</p>
                    <p><strong>Last Saved:</strong> {{ $lastSavedAt?->setTimezone('Africa/Cairo')->format('h:i:s A') }}</p>
                </div>
            </x-filament::card>

            <x-filament::card>
                <h3 class="text-base font-semibold text-gray-800 mb-2">📌 Report Guidelines</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start"><x-heroicon-o-check class="w-4 h-4 text-primary-500 mt-1 mr-2" /> Be specific and objective in observations</li>
                    <li class="flex items-start"><x-heroicon-o-check class="w-4 h-4 text-primary-500 mt-1 mr-2" /> Include both strengths and areas of concern</li>
                    <li class="flex items-start"><x-heroicon-o-check class="w-4 h-4 text-primary-500 mt-1 mr-2" /> Provide actionable recommendations</li>
                    <li class="flex items-start"><x-heroicon-o-check class="w-4 h-4 text-primary-500 mt-1 mr-2" /> Use professional, clear language</li>
                    <li class="flex items-start"><x-heroicon-o-check class="w-4 h-4 text-primary-500 mt-1 mr-2" /> Include measurable goals where possible</li>
                </ul>
            </x-filament::card>
        </div>
    </div>

    {{-- 📋 Preview Modal --}}
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto border border-yellow-400">
                {{-- Modal Header --}}
                <div class="flex justify-between items-center p-4 bg-yellow-100 border-b border-yellow-300 rounded-t-xl">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">📋 Observation Report Preview</h2>
                        <p class="text-sm text-gray-600">Child report preview for <span class="font-medium">{{ $requestCase->child->name }}</span></p>
                    </div>
                    <button onclick="closePreviewModal()" class="text-gray-500 hover:text-red-600 text-2xl font-bold">
                        &times;
                    </button>
                </div>

                <div class="p-6 space-y-8 bg-white">
                    <div class="text-center">
                        <h1 class="text-3xl font-extrabold text-blue-700 mb-1">Observation Report</h1>
                        <p class="text-gray-600">Comprehensive Child Assessment</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border p-4 rounded-lg bg-gray-50">
                        <div>
                            <h3 class="text-lg font-semibold text-blue-600 mb-3">👦 Child Information</h3>
                            <ul class="text-gray-700 space-y-1 text-sm">
                                <li><strong>Name:</strong> {{ $requestCase->child->fullName }}</li>
                                <li><strong>Age:</strong> {{ $requestCase->child->age ?? '-' }}</li>
                                <li><strong>School:</strong> {{ $requestCase->child->schoolName ?? '-' }}</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-blue-600 mb-3">🕵️ Observation Details</h3>
                            <ul class="text-gray-700 space-y-1 text-sm">
                                <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($requestCase->slot_date)->format('Y-m-d') }}</li>
                                <li><strong>Observer:</strong> {{ $requestCase->employee?->name.' '.$requestCase->employee?->middle_name.' '.$requestCase->employee?->last_name ?? 'Unknown' ?? '-' }}</li>
                                <li><strong>Duration:</strong> {{ $requestCase->duration }}    minutes</li>

                                <li><strong>Location:</strong> {{ $requestCase->child->homeAddress ?? 'Cairo International School' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end gap-3 p-4 bg-yellow-100 border-t border-yellow-300 rounded-b-xl">
                    <button onclick="closePreviewModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition">
                        Close
                    </button>
                    <button onclick="downloadPDF()" class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showPreviewModal() {
            document.getElementById('previewModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePreviewModal() {
            document.getElementById('previewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function downloadPDF() {
            Swal.fire({
                icon: 'info',
                title: 'PDF Download',
                text: 'PDF generation functionality would be implemented here.',
                confirmButtonColor: '#3085d6',
            });
        }

        document.addEventListener('click', function(event) {
            const modal = document.getElementById('previewModal');
            if (event.target === modal) {
                closePreviewModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePreviewModal();
            }
        });
    </script>
</x-filament-panels::page>
