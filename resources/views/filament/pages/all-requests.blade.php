<x-filament-panels::page>
    @php
        $panel = filament()->getCurrentPanel()?->getId();
        $employeeUser = auth('employee')->user();
        $adminUser = auth('admin')->user();
    @endphp

    {{-- 🔹 لو Teacher داخل Panel الموظفين --}}
    @if($employeeUser?->hasRole('teacher') && $panel === 'employee')
        <x-employee-navigation :panel="$panel" />
    @endif

    {{-- 🔹 لو Admin داخل Panel الأدمن --}}
    @if($adminUser?->hasRole('admin') && $panel === 'cognifyAdmin')
        <x-header-navigation :panel="$panel" />
    @endif

    {{ $this->table }}
</x-filament-panels::page>
