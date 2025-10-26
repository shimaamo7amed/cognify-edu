@props(['recordId'])

<div class="flex gap-6 text-sm font-medium border-b pb-2 mb-6">
    <a href="{{ \App\Filament\Pages\ViewRequest::getUrl(['record' => $recordId]) }}"
        class="{{ request()->is('admin/request/'.$recordId) ? 'text-primary-700 border-b-2 border-primary-700 pb-1' : 'text-gray-500 hover:text-primary-600' }}">
        Overview
    </a>

    <a href="{{ \App\Filament\Pages\ChildInfoPage::getUrl(['record' => $recordId]) }}"
        class="{{ request()->is('admin/request/'.$recordId.'/child-info') ? 'text-primary-700 border-b-2 border-primary-700 pb-1' : 'text-gray-500 hover:text-primary-600' }}">
        Child Info
    </a>

    <a href="{{ \App\Filament\Pages\TimelinePage::getUrl(['record' => $recordId]) }}"
        class="{{ request()->is('admin/request/'.$recordId.'/timeline') ? 'text-primary-700 border-b-2 border-primary-700 pb-1' : 'text-gray-500 hover:text-primary-600' }}">
        Timeline
    </a>
    <a href="{{ \App\Filament\Pages\DocumentsPage::getUrl(['record' => $recordId]) }}"
        class="{{ request()->is('admin/request/'.$recordId.'/documents') ? 'text-primary-700 border-b-2 border-primary-700 pb-1' : 'text-gray-500 hover:text-primary-600' }}">
        Documents
    </a>
</div>
