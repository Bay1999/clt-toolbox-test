<x-app-layout>
    <x-slot name="header">
        <header>
            <div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Supplier') }} /
                        </p>
                        <p class="text-sm text-gray-800 dark:text-gray-100 font-semibold">
                            {{ $supplier->name }}
                        </p>
                    </div>
                    <a href="{{ route('supplier.index') }}" class="px-4 py-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="py-4">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-3xl text-gray-900 dark:text-gray-100 mb-2">
                            <div class="flex items-center gap-3">
                                {{ $supplier->name }}
                                <span class="text-gray-400 text-xs font-normal bg-[var(--main-color)] text-white px-3 py-1 rounded-full">{{ __('Active Partner') }}</span>
                            </div>
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400">ID : {{ 'SUP-' . $supplier->created_at->format('Y') . '-' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <button onclick="openEditSupplierModal()" class="px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-edit mr-2"></i>
                        Edit Supplier
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg grid grid-cols-4 rounded divide-x-2 divide-gray-100">
                <div class="px-6 py-8">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Primary Contact</p>
                    <div class="flex items-center gap-2 text-gray-700">
                        <i class="fa-solid fa-envelope text-[var(--main-color)]"></i>
                        <span class="text-sm">{{ $supplier->email }}</span>
                    </div>
                </div>

                <div class="px-6 py-8">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Location</p>
                    <div class="flex items-center gap-2 text-gray-700">
                        <i class="fa-solid fa-location-dot text-[var(--main-color)]"></i>
                        <span class="text-sm">{{ $supplier->location }}</span>
                    </div>
                </div>

                <div class="px-6 py-8">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Material Certifications</p>
                    <div class="flex items-center gap-2 text-gray-700">
                        <i class="fa-solid fa-certificate text-[var(--main-color)]"></i>
                        <span class="text-sm">{{ $supplier->material_certification }}</span>
                    </div>
                </div>

                <div class="px-6 py-8">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Last Audit Date</p>
                    <div class="flex items-center gap-2 text-gray-700">
                        <i class="fa-solid fa-calendar text-[var(--main-color)]"></i>
                        <span class="text-sm">{{ $supplier->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-bold text-2xl text-gray-900 dark:text-gray-100">Associated Layups</h2>
                <div class="flex items-center space-x-2">
                    <button id="import-layups-button" onclick="openImportLayupModal()"
                        class="px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-upload mr-1"></i> Import
                    </button>
                    <button id="export-layups-button"
                        class="px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-download mr-1"></i> Export
                    </button>
                    <button id="add-layup-button" onclick="openCreateLayupModal()"
                        class="px-2 py-1 rounded-lg bg-[var(--main-color)] dark:bg-[var(--dark-main-color)] text-white text-sm hover:opacity-90 transition-opacity">
                        <i class="fa-solid fa-plus mr-1"></i> Add Layup
                    </button>
                </div>
            </div>

            <x-table id="layups-table">
                <x-slot name="header">
                    <th class="px-4 py-4 whitespace-nowrap">Name</th>
                    <th class="px-4 py-4 text-center whitespace-nowrap">Grade</th>
                    <th class="px-4 py-4 text-center whitespace-nowrap">Thickness</th>
                    <th class="px-4 py-4 text-center whitespace-nowrap">Ply Count</th>
                    <th class="px-4 py-4 text-center whitespace-nowrap">Revision</th>
                    <th class="px-4 py-4 text-center whitespace-nowrap">Status</th>
                    <th class="px-4 py-4 text-right w-1">Actions</th>
                </x-slot>
            </x-table>
        </div>
    </div>
    
    
    {{-- Layup Form Modal --}}
    <x-modal name="layup-form-modal" focusable>
        <div class="p-6">
            <h2 id="layup-modal-title" class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ __('Add New Layup') }}
            </h2>

            <form id="layup-form">
                @csrf
                <input type="hidden" name="_method" id="layup-method-field" value="POST">
                <input type="hidden" id="layup-id">
                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">

                <div class="space-y-4">
                    <div>
                        <x-input-label for="layup-name" :value="__('Name')" />
                        <x-text-input id="layup-name" name="name" type="text" class="mt-1 block w-full"
                            placeholder="e.g. Standard 5-ply" />
                        <span id="error-layup-name" class="text-sm text-red-500 mt-1"></span>
                    </div>

                    <div>
                        <x-input-label for="layup-grade" :value="__('Grade')" />
                        <x-text-input id="layup-grade" name="grade" type="text" class="mt-1 block w-full"
                            placeholder="e.g. C24" />
                        <span id="error-layup-grade" class="text-sm text-red-500 mt-1"></span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button x-on:click="$dispatch('close')" class="focus:ring-0 focus:ring-offset-0">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button
                        class="bg-[var(--main-color)] dark:bg-[var(--dark-main-color)] hover:bg-[var(--main-color)] dark:hover:bg-[var(--dark-main-color)] opacity-90 hover:opacity-100">
                        {{ __('Save Changes') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Supplier Form Modal --}}
    <x-modal name="supplier-form-modal" focusable>
        <div class="p-6">
            <h2 id="supplier-modal-title" class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ __('Edit Supplier') }}
            </h2>

            <form id="supplier-form">
                @csrf
                <input type="hidden" name="_method" id="supplier-method-field" value="PATCH">
                <input type="hidden" id="supplier-id">

                <div class="space-y-4">
                    <div>
                        <x-input-label for="supplier-name" :value="__('Name')" />
                        <x-text-input id="supplier-name" name="name" type="text" class="mt-1 block w-full" />
                        <span id="error-supplier-name" class="text-sm text-red-500 mt-1"></span>
                    </div>

                    <div>
                        <x-input-label for="supplier-email" :value="__('Email Address')" />
                        <x-text-input id="supplier-email" name="email" type="email" class="mt-1 block w-full" />
                        <span id="error-supplier-email" class="text-sm text-red-500 mt-1"></span>
                    </div>

                    <div>
                        <x-input-label for="supplier-location" :value="__('Location')" />
                        <x-text-input id="supplier-location" name="location" type="text" class="mt-1 block w-full" />
                        <span id="error-supplier-location" class="text-sm text-red-500 mt-1"></span>
                    </div>

                    <div>
                        <x-input-label for="supplier-material_certification" :value="__('Material Certification')" />
                        <x-text-input id="supplier-material_certification" name="material_certification" type="text"
                            class="mt-1 block w-full" />
                        <span id="error-supplier-material_certification" class="text-sm text-red-500 mt-1"></span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button x-on:click="$dispatch('close')" class="focus:ring-0 focus:ring-offset-0">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button
                        class="bg-[var(--main-color)] dark:bg-[var(--dark-main-color)] hover:bg-[var(--main-color)] dark:hover:bg-[var(--dark-main-color)] opacity-90 hover:opacity-100">
                        {{ __('Save Changes') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Import Layup Modal --}}
    <x-modal name="import-layup-modal" focusable>
        <div class="p-6">
            <h2 id="import-layup-modal-title" class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ __('Import Layups') }}
            </h2>

            <form id="import-layup-form" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Upload the previously exported Excel file to import layups and layers. Ensure the format matches exactly.</p>
                        <input type="file" id="import-file" name="file" accept=".xlsx, .xls" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
                        <span id="error-import-file" class="text-sm text-red-500 mt-1"></span>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <x-secondary-button x-on:click="$dispatch('close')" class="focus:ring-0 focus:ring-offset-0">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button id="import-submit-button"
                        class="bg-[var(--main-color)] dark:bg-[var(--dark-main-color)] hover:bg-[var(--main-color)] dark:hover:bg-[var(--dark-main-color)] opacity-90 hover:opacity-100">
                        {{ __('Import') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    {{-- Conflict Resolution Modal --}}
    <x-modal name="conflict-resolution-modal" focusable maxWidth="7xl">
        <div class="p-6 h-[85vh] flex flex-col bg-white dark:bg-gray-900">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                        Conflict Resolution: Import Layups
                        <span class="text-sm ml-3 px-3 py-1 text-xs font-bold bg-orange-100 text-orange-800 rounded-full border border-orange-200">Needs Review</span>
                    </h2>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Please review discrepancies between incoming data and existing records.</p>
                </div>
                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500 transition-colors">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="flex-1 flex overflow-hidden gap-4">
                {{-- Sidebar --}}
                <div class="w-80 flex flex-col border rounded-xl overflow-hidden bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-4 border-b bg-white dark:bg-gray-900 font-bold text-xs flex items-center gap-2 text-gray-700 dark:text-gray-200">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        Conflicting Layups (<span id="conflict-count">0</span>)
                    </div>
                    <div id="conflict-list" class="flex-1 overflow-y-auto p-3 space-y-3">
                        {{-- Items injected here --}}
                    </div>
                    <div class="p-4 border-t bg-gray-100 dark:bg-gray-900/50">
                        <div class="text-xs uppercase font-black text-gray-400 mb-3 tracking-widest">Resolved</div>
                        <div id="resolved-list" class="space-y-2">
                            {{-- Resolved items injected here --}}
                        </div>
                    </div>
                </div>

                {{-- Main Comparison --}}
                <div class="flex-1 flex flex-col">
                    <div id="comparison-header" class="mb-4 flex items-center justify-between bg-white dark:bg-gray-900 p-4 border rounded-xl shadow-sm border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-4">
                            <h3 id="current-layup-name" class="text-lg font-black text-gray-900 dark:text-gray-100">Layup Name</h3>
                            <span id="current-layup-layers" class="px-3 py-1 text-xs font-black bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-lg border border-gray-200 dark:border-gray-700">5 LAYERS</span>
                        </div>
                        <div class="text-xs text-gray-500 flex items-center gap-2 font-medium">
                            <span class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></span> Differences highlighted in <span class="text-red-500 font-bold">Red</span>
                        </div>
                    </div>

                    <div class="flex-1 grid grid-cols-2 gap-6 overflow-hidden">
                        {{-- Existing Version --}}
                        <div class="flex flex-col border rounded-2xl overflow-hidden bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 shadow-lg">
                            <div class="p-5 border-b bg-gray-50/80 dark:bg-gray-800 flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2 text-gray-900 dark:text-gray-100 font-black">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        Existing Version
                                    </div>
                                    <div id="last-updated" class="text-xs uppercase font-bold text-gray-400 mt-1 tracking-tighter">Last updated: Oct 12, 2023</div>
                                </div>
                                <span class="w-3 h-3 rounded-full bg-gray-200 dark:bg-gray-700"></span>
                            </div>
                            <div class="flex-1 overflow-y-auto">
                                <table class="w-full text-xs text-left">
                                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 sticky top-0 backdrop-blur-sm">
                                        <tr>
                                            <th class="px-6 py-3 font-black">Order</th>
                                            <th class="px-6 py-3 font-black text-center">Thickness (mm)</th>
                                            <th class="px-6 py-3 font-black text-center">Width (mm)</th>
                                            <th class="px-6 py-3 font-black text-center">Angle (°)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="existing-layers-body" class="divide-y divide-gray-100 dark:divide-gray-800">
                                        {{-- Rows --}}
                                    </tbody>
                                </table>
                            </div>
                            <div id="keep-existing-container" class="p-5 border-t bg-gray-50/80 dark:bg-gray-800">
                                <button id="keep-existing-btn" class="text-xs w-full py-1 border-2 border-emerald-600 text-emerald-600 rounded-xl font-black hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all flex items-center justify-center gap-3 active:scale-95 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                    Keep Existing Version
                                </button>
                            </div>
                        </div>

                        {{-- Importing Version --}}
                        <div class="flex flex-col border rounded-2xl overflow-hidden bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-700 shadow-lg">
                            <div class="p-5 border-b bg-gray-50/80 dark:bg-gray-800 flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2 text-gray-900 dark:text-gray-100 font-black">
                                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                        Importing Version
                                    </div>
                                    <div id="excel-source" class="text-xs uppercase font-bold text-gray-400 mt-1 tracking-tighter">Source: Uploaded Excel Template</div>
                                </div>
                                <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            </div>
                            <div class="flex-1 overflow-y-auto">
                                <table class="w-full text-xs text-left">
                                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-800/50 sticky top-0 backdrop-blur-sm">
                                        <tr>
                                            <th class="px-6 py-3 font-black">Order</th>
                                            <th class="px-6 py-3 font-black text-center">Thickness (mm)</th>
                                            <th class="px-6 py-3 font-black text-center">Width (mm)</th>
                                            <th class="px-6 py-3 font-black text-center">Angle (°)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="importing-layers-body" class="divide-y divide-gray-100 dark:divide-gray-800">
                                        {{-- Rows --}}
                                    </tbody>
                                </table>
                            </div>
                            <div id="accept-new-container" class="p-5 border-t bg-gray-50/80 dark:bg-gray-800">
                                <button id="accept-new-btn" class="text-xs w-full py-1 bg-emerald-600 text-white rounded-xl font-black hover:bg-emerald-700 transition-all flex items-center justify-center gap-3 active:scale-95 shadow-lg shadow-emerald-200 dark:shadow-none">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Accept New Version
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <button id="cancel-import-btn" class="px-8 py-3 border-2 rounded-xl text-gray-500 font-bold hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">Cancel Import</button>
                <div class="flex items-center gap-10">
                    <button id="prev-conflict-btn" class="text-gray-900 dark:text-gray-100 text-xs font-black flex items-center gap-3 hover:text-emerald-600 transition-colors disabled:opacity-20 disabled:hover:text-current">
                        <i class="fa-solid fa-arrow-left"></i>
                        Previous Conflict
                    </button>
                    <span id="conflict-pagination" class="text-xs font-black text-gray-400 bg-gray-100 dark:bg-gray-800 px-4 py-2 rounded-full border border-gray-200 dark:border-gray-700">1 of 3 DISCREPANCIES</span>
                    <button id="next-conflict-btn" class="text-gray-900 dark:text-gray-100 text-xs font-black flex items-center gap-3 hover:text-emerald-600 transition-colors disabled:opacity-20 disabled:hover:text-current">
                        Next Conflict
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
                <button id="confirm-resolution-btn" disabled class="px-10 py-3 bg-gray-200 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-xl font-black disabled:cursor-not-allowed transition-all">
                    Confirm & Complete Import
                </button>
            </div>
        </div>
    </x-modal>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <style>
            .dataTables_info {
                font-size: 14px
            }
            .conflict-row-highlight { background-color: rgba(254, 226, 226, 0.6); }
            .conflict-text-highlight { color: #dc2626 !important; font-weight: 900 !important; }
            .resolved-strikethrough { text-decoration: line-through; opacity: 0.5; }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0px
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                background: none;
                border: none;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled div {
                background-color: #f3f4f6 !important;
                border-color: #e5e7eb !important;
                color: #9ca3af !important;
                cursor: not-allowed;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button:not(.disabled):hover div {
                background-color: #f9fafb;
                border-color: var(--main-color);
                color: var(--main-color);
            }

            table.dataTable thead th {
                border-bottom: 1px solid #e5e7eb !important;
                text-align: center !important;
            }

            table.dataTable thead th:first-child {
                text-align: left !important;
            }

            table.dataTable thead th:last-child {
                text-align: right !important;
            }

            table.dataTable.no-footer {
                border-bottom: none !important;
            }
        </style>
    @endpush
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                const table = $('#layups-table').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    width: '100%',
                    pageLength: 5,
                    ajax: {
                        url: "{{ route('clt-layup.data') }}",
                        data: function(d) {
                            d.supplier_id = "{{ $supplier->id }}";
                            d.search = $('#layup-search').val();
                        }
                    },
                    columns: [{
                            data: 'name',
                            render: function(data, type, row) {
                                return `<a href="/clt-layup/${row.id}" class="font-bold text-gray-900 dark:text-white hover:text-[var(--main-color)] transition-colors">${data}</a>`;
                            }
                        },
                        {
                            data: 'grade',
                            className: 'text-center'
                        },
                        {
                            data: 'thickness',
                            className: 'text-center'
                        },
                        {
                            data: 'ply_count',
                            className: 'text-center',
                            render: function(data) {
                                return `<span class="bg-gray-200 px-2 rounded">${data}</span>`
                            }
                        },
                        {
                            data: 'revision_counter',
                            className: 'text-center',
                        },
                        {
                            data: 'is_active',
                            className: 'text-center',
                            render: function(data) {
                                if (data === 'Active') return `<span class="px-5 py-1 text-xs rounded-full bg-green-100 text-green-600">${data}</span>`;
                                if (data === 'Draft') return `<span class="px-5 py-1 text-xs rounded-full bg-gray-200">${data}</span>`;
                                if (data === 'Archived') return `<span class="px-5 py-1 text-xs rounded-full bg-red-100 text-red-600">${data}</span>`;
                            }
                        },
                        {
                            data: null,
                            orderable: false,
                            className: 'text-right w-1',
                            render: function(data, type, row) {
                                return `
                                    <div x-data="{ 
                                        open: false, 
                                        triggerPos: { top: 0, left: 0 },
                                        toggle(e) {
                                            if (this.open) {
                                                this.open = false;
                                            } else {
                                                let rect = e.currentTarget.getBoundingClientRect();
                                                this.triggerPos = { 
                                                    top: rect.bottom + window.scrollY + 5, 
                                                    left: rect.right + window.scrollX - 192
                                                };
                                                this.open = true;
                                            }
                                        }
                                    }" class="inline-block text-left">
                                        <button @click="toggle($event)" @click.away="open = false" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                                            <i class="fa-solid fa-ellipsis-v"></i>
                                        </button>
                                        
                                        <template x-teleport="body">
                                            <div x-show="open" 
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                 x-transition:enter-end="transform opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="transform opacity-100 scale-100"
                                                 x-transition:leave-end="transform opacity-0 scale-95"
                                                 :style="\`position: absolute; top: \${triggerPos.top}px; left: \${triggerPos.left}px; z-index: 9999;\`"
                                                 class="w-48 rounded-md bg-white dark:bg-gray-700 shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                                 style="display: none;">
                                                <div class="">
                                                    <a href="/clt-layup/${row.id}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                        <i class="fa-solid fa-eye mr-2"></i> {{ __('View') }}
                                                    </a>
                                                    ${row.deleted_at === null ? `
                                                        <a href="javascript:void(0)" onclick='openEditLayupModal(${JSON.stringify(row)})' class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                            <i class="fa-solid fa-edit mr-2"></i> {{ __('Edit') }}
                                                        </a>
                                                        <hr class="border-gray-100 dark:border-gray-600">
                                                        ${row.is_active === 'Draft' ? `
                                                            <a href="javascript:void(0)" onclick="changeStatus(${row.id}, 1)" class="block px-4 py-2 text-sm text-green-600 hover:bg-green-600/10 transition-colors">
                                                                <i class="fa-solid fa-check mr-2"></i> {{ __('Set to Active') }}
                                                            </a>
                                                            <a href="javascript:void(0)" onclick="archiveLayup(${row.id})" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30">
                                                                <i class="fa-solid fa-box-archive mr-2"></i> {{ __('Archive') }}
                                                            </a>
                                                        ` : `
                                                            <a href="javascript:void(0)" onclick="changeStatus(${row.id}, 0)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                                <i class="fa-solid fa-file-lines mr-2"></i> {{ __('Set to Draft') }}
                                                            </a>
                                                            <a href="javascript:void(0)" onclick="archiveLayup(${row.id})" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30">
                                                                <i class="fa-solid fa-box-archive mr-2"></i> {{ __('Archive') }}
                                                            </a>
                                                        `}
                                                    ` : `
                                                        <hr class="border-gray-100 dark:border-gray-600">
                                                        <a href="javascript:void(0)" onclick="restoreAndStatus(${row.id}, 1)" class="block px-4 py-2 text-sm text-green-600 hover:bg-green-600/10 transition-colors">
                                                            <i class="fa-solid fa-check mr-2"></i> {{ __('Set to Active') }}
                                                        </a>
                                                        <a href="javascript:void(0)" onclick="restoreAndStatus(${row.id}, 0)" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                            <i class="fa-solid fa-file-lines mr-2"></i> {{ __('Set to Draft') }}
                                                        </a>
                                                    `}
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                `;
                            }
                        }
                    ],
                    responsive: true,
                    paging: true,
                    pagingType: 'simple',
                    dom: 'rt<"flex flex-col md:flex-row justify-between items-center m-4"ip>',
                    language: {
                        info: "Showing _START_ to _END_ of _TOTAL_ result",
                        paginate: {
                            previous: '<div class="border border-gray-300 rounded-lg text-sm p-1"><i class="fa-solid fa-chevron-left"></i></div>',
                            next: '<div class="border border-gray-300 rounded-lg text-sm p-1"><i class="fa-solid fa-chevron-right"></i></div>'
                        }
                    }
                });

                // Custom search
                $('#layup-search').on('keyup', function() {
                    table.ajax.reload();
                });
            });

            function changeStatus(id, isActive) {
                $.ajax({
                    url: `/clt-layup/${id}/status`,
                    method: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_active: isActive
                    },
                    success: function() {
                        $('#layups-table').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated',
                            text: 'Status updated successfully',
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                });
            }

            function restoreAndStatus(id, isActive) {
                $.ajax({
                    url: `/clt-layup/${id}/restore`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        changeStatus(id, isActive);
                    }
                });
            }

            function restoreLayup(id) {
                $.ajax({
                    url: `/clt-layup/${id}/restore`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#layups-table').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Restored',
                            text: 'Layup restored successfully',
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                });
            }

            function archiveLayup(id) {
                Swal.fire({
                    title: 'Archive Layup?',
                    text: "This layup will be moved to archived state.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, archive it!',
                    cancelButtonText: 'Cancel',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'px-5 py-2.5 ml-3 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 order-2',
                        cancelButton: 'px-5 py-2.5 text-sm font-medium text-gray-700 bg-transparent border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 order-1'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/clt-layup/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                $('#layups-table').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Archived!',
                                    text: 'Layup has been archived.',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }
                        });
                    }
                });
            }

            function openCreateLayupModal() {
                $('#layup-modal-title').text("{{ __('Add New Layup') }}");
                $('#layup-form')[0].reset();
                $('#layup-id').val('');
                $('#layup-method-field').val('POST');
                $('.text-red-500').text('');
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'layup-form-modal'
                }));
            }

            function openEditLayupModal(data) {
                $('#layup-modal-title').text("{{ __('Edit Layup') }}");
                $('#layup-form')[0].reset();
                $('.text-red-500').text('');

                $('#layup-id').val(data.id);
                $('#layup-name').val(data.name);
                $('#layup-grade').val(data.grade);
                $('#layup-method-field').val('PATCH');

                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'layup-form-modal'
                }));
            }

            function openEditSupplierModal() {
                const data = {
                    id: "{{ $supplier->id }}",
                    name: "{{ $supplier->name }}",
                    email: "{{ $supplier->email }}",
                    location: "{{ $supplier->location }}",
                    material_certification: "{{ $supplier->material_certification }}"
                };
                
                $('#supplier-modal-title').text("{{ __('Edit Supplier') }}");
                $('#supplier-form')[0].reset();
                $('[id^="error-supplier-"]').text(''); // Clear errors

                $('#supplier-id').val(data.id);
                $('#supplier-name').val(data.name);
                $('#supplier-email').val(data.email);
                $('#supplier-location').val(data.location);
                $('#supplier-material_certification').val(data.material_certification);
                $('#supplier-method-field').val('PATCH');

                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'supplier-form-modal'
                }));
            }

            $('#supplier-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#supplier-id').val();
                const url = `/supplier/${id}`;

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.dispatchEvent(new CustomEvent('close-modal', {
                            detail: 'supplier-form-modal'
                        }));

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Supplier updated successfully',
                            timer: 1500,
                            showConfirmButton: false,
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $('[id^="error-supplier-"]').text('');
                            for (const key in errors) {
                                $(`#error-supplier-${key}`).text(errors[key][0]);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong.',
                            });
                        }
                    }
                });
            });

            $('#layup-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#layup-id').val();
                const url = id ? `/clt-layup/${id}` : '/clt-layup';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.dispatchEvent(new CustomEvent('close-modal', {
                            detail: 'layup-form-modal'
                        }));
                        $('#layups-table').DataTable().ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: id ? 'Layup updated successfully' : 'Layup created successfully',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $('.text-red-500').text('');
                            for (const key in errors) {
                                $(`#error-layup-${key}`).text(errors[key][0]);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong.',
                            });
                        }
                    }
                });
            });

            $('#export-layups-button').on('click', function() {
                const supplierId = "{{ $supplier->id }}";
                window.location.href = `/clt-layup/supplier/${supplierId}/export-template`;
            });

            function openImportLayupModal() {
                $('#import-layup-form')[0].reset();
                $('#error-import-file').text('');
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'import-layup-modal'
                }));
            }

            $('#import-layup-form').on('submit', function(e) {
                e.preventDefault();
                const supplierId = "{{ $supplier->id }}";
                const url = `/clt-layup/supplier/${supplierId}/import`;
                
                let formData = new FormData(this);
                let $submitBtn = $('#import-submit-button');
                let originalText = $submitBtn.text();
                
                $submitBtn.text('Importing...').prop('disabled', true);
                $('#error-import-file').text('');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $submitBtn.text(originalText).prop('disabled', false);
                        
                        if (response.status === 'success_with_conflicts') {
                            window.dispatchEvent(new CustomEvent('close-modal', {
                                detail: 'import-layup-modal'
                            }));
                            handleImportConflicts(response.conflicts);
                        } else {
                            window.dispatchEvent(new CustomEvent('close-modal', {
                                detail: 'import-layup-modal'
                            }));
                            $('#layups-table').DataTable().ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Layups imported successfully',
                                timer: 2000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                        }
                    },
                    error: function(xhr) {
                        $submitBtn.text(originalText).prop('disabled', false);
                        
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.file) {
                                $('#error-import-file').text(errors.file[0]);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    text: xhr.responseJSON.message || 'Check the file format.',
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.message || 'Something went wrong during import.',
                            });
                        }
                    }
                });
            });

            // Conflict Resolution Logic
            let allImportData = [];
            let currentConflictIndex = 0;
            let resolutions = {};

            function handleImportConflicts(conflicts) {
                allImportData = conflicts;
                currentConflictIndex = 0;
                resolutions = {};

                // Initial resolutions for non-conflicting layups
                allImportData.forEach(layup => {
                    if (!layup.has_conflict) {
                        resolutions[layup.name] = {
                            name: layup.name,
                            grade: layup.grade,
                            is_active: layup.is_active,
                            layers: Object.values(layup.layers).map(l => ({ 
                                order: l.order,
                                thickness: l.thickness,
                                width: l.width,
                                angle: l.angle,
                                grade: l.grade,
                                action: 'new' 
                            }))
                        };
                    }
                });

                // Find first conflict to show
                const firstConflictIndex = allImportData.findIndex(l => l.has_conflict);
                showConflictDetail(firstConflictIndex >= 0 ? firstConflictIndex : 0);
                
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'conflict-resolution-modal'
                }));
            }

            function showConflictDetail(index) {
                if (index < 0 || index >= allImportData.length) return;
                currentConflictIndex = index;
                const layup = allImportData[index];

                $('#current-layup-name').text(layup.name);
                $('#current-layup-layers').text(`${Object.keys(layup.layers).length} LAYERS`);
                $('#last-updated').text(layup.exists ? 'Existing in Database' : 'New Entry');

                const $existingBody = $('#existing-layers-body').empty();
                const $importingBody = $('#importing-layers-body').empty();

                const sortedLayers = Object.values(layup.layers).sort((a, b) => a.order - b.order);

                sortedLayers.forEach(layer => {
                    const hasConflict = layer.is_conflict;
                    
                    // Existing row
                    if (layup.exists && layer.current) {
                        $existingBody.append(`
                            <tr class="${hasConflict ? 'conflict-row-highlight' : ''}">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-gray-100">${layer.order}</td>
                                <td class="px-6 py-4 text-center ${hasConflict && layer.current.thickness != layer.thickness ? 'conflict-text-highlight' : 'text-gray-600 dark:text-gray-400'}">${layer.current.thickness}</td>
                                <td class="px-6 py-4 text-center ${hasConflict && layer.current.width != layer.width ? 'conflict-text-highlight' : 'text-gray-600 dark:text-gray-400'}">${layer.current.width}</td>
                                <td class="px-6 py-4 text-center ${hasConflict && layer.current.angle != layer.angle ? 'conflict-text-highlight' : 'text-gray-600 dark:text-gray-400'}">${layer.current.angle}°</td>
                            </tr>
                        `);
                    } else {
                        $existingBody.append(`<tr><td colspan="4" class="px-6 py-4 text-center text-gray-400 italic font-medium">New layer for this order</td></tr>`);
                    }

                    // Importing row
                    $importingBody.append(`
                        <tr class="${hasConflict ? 'conflict-row-highlight' : ''}">
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-gray-100">${layer.order}</td>
                            <td class="px-6 py-4 text-center ${hasConflict && (!layer.current || layer.current.thickness != layer.thickness) ? 'conflict-text-highlight' : 'text-gray-600 dark:text-gray-400'}">${layer.thickness}</td>
                            <td class="px-6 py-4 text-center ${hasConflict && (!layer.current || layer.current.width != layer.width) ? 'conflict-text-highlight' : 'text-gray-600 dark:text-gray-400'}">${layer.width}</td>
                            <td class="px-6 py-4 text-center ${hasConflict && (!layer.current || layer.current.angle != layer.angle) ? 'conflict-text-highlight' : 'text-gray-600 dark:text-gray-400'}">${layer.angle}°</td>
                        </tr>
                    `);
                });

                // Update resolution buttons state
                const isResolved = resolutions[layup.name];
                const resolvedAs = isResolved ? (isResolved.layers.some(l => l.action === 'new') ? 'new' : 'keep') : null;

                $('#keep-existing-btn').toggleClass('bg-emerald-50 dark:bg-emerald-900/40', resolvedAs === 'keep')
                    .toggleClass('ring-4 ring-emerald-500/20', resolvedAs === 'keep');
                $('#accept-new-btn').toggleClass('ring-4 ring-emerald-500/20', resolvedAs === 'new');

                // Update pagination
                const conflictsOnly = allImportData.filter(l => l.has_conflict);
                const currentConflictPos = conflictsOnly.indexOf(layup) + 1;
                if (currentConflictPos > 0) {
                    $('#conflict-pagination').text(`${currentConflictPos} of ${conflictsOnly.length} DISCREPANCIES`);
                } else {
                    $('#conflict-pagination').text('RESOLVED');
                }

                $('#prev-conflict-btn').prop('disabled', index === 0);
                $('#next-conflict-btn').prop('disabled', index === allImportData.length - 1);

                renderConflictSidebar();
            }

            function renderConflictSidebar() {
                const $conflictList = $('#conflict-list').empty();
                const $resolvedList = $('#resolved-list').empty();
                let conflictCount = 0;

                allImportData.forEach((layup, index) => {
                    const isResolved = resolutions[layup.name];
                    const isActive = currentConflictIndex === index;
                    
                    const itemHtml = `
                        <div onclick="showConflictDetail(${index})" class="p-4 border rounded-xl cursor-pointer transition-all duration-200 group ${isActive ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 ring-2 ring-emerald-500/50 shadow-md' : 'bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-sm'}">
                            <div class="flex justify-between items-start">
                                <div class="font-black text-xs tracking-tight ${isResolved ? 'text-gray-400 resolved-strikethrough' : 'text-gray-900 dark:text-gray-100'}">${layup.name}</div>
                                ${isResolved ? 
                                    '<svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>' : 
                                    '<span class="w-2 h-2 rounded-full bg-red-500 mt-1 shadow-[0_0_8px_rgba(239,68,68,0.5)]"></span>'}
                            </div>
                            <div class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-wider">${layup.has_conflict ? 'Conflict in Layers' : 'New Layup'}</div>
                        </div>
                    `;

                    if (!layup.has_conflict || isResolved) {
                        $resolvedList.append(itemHtml);
                    } else {
                        $conflictList.append(itemHtml);
                        conflictCount++;
                    }
                });

                $('#conflict-count').text(conflictCount);
                checkAllResolved();
            }

            function resolveCurrent(action) {
                const layup = allImportData[currentConflictIndex];
                resolutions[layup.name] = {
                    name: layup.name,
                    grade: layup.grade,
                    is_active: layup.is_active,
                    layers: Object.values(layup.layers).map(l => ({
                        order: l.order,
                        thickness: l.thickness,
                        width: l.width,
                        angle: l.angle,
                        grade: l.grade,
                        action: action === 'new' ? 'new' : (l.is_conflict ? 'keep' : 'new') // keep existing means don't update if conflict
                    }))
                };
                
                // Move to next conflict if available
                const nextIndex = allImportData.findIndex((l, i) => i > currentConflictIndex && l.has_conflict && !resolutions[l.name]);
                if (nextIndex >= 0) {
                    showConflictDetail(nextIndex);
                } else {
                    renderConflictSidebar();
                    showConflictDetail(currentConflictIndex);
                }
            }

            function checkAllResolved() {
                const allResolved = allImportData.every(l => resolutions[l.name]);
                $('#confirm-resolution-btn').prop('disabled', !allResolved)
                    .text('Confirm & Complete Import')
                    .toggleClass('bg-emerald-600', allResolved)
                    .toggleClass('text-white', allResolved)
                    .toggleClass('shadow-lg shadow-emerald-200 dark:shadow-none', allResolved)
                    .toggleClass('bg-gray-200 dark:bg-gray-800', !allResolved)
                    .toggleClass('text-gray-400 dark:text-gray-600', !allResolved);
            }

            $('#keep-existing-btn').on('click', () => resolveCurrent('keep'));
            $('#accept-new-btn').on('click', () => resolveCurrent('new'));
            
            $('#prev-conflict-btn').on('click', () => showConflictDetail(currentConflictIndex - 1));
            $('#next-conflict-btn').on('click', () => showConflictDetail(currentConflictIndex + 1));
            
            $('#cancel-import-btn').on('click', () => {
                Swal.fire({
                    title: 'Cancel Import?',
                    text: 'All progress in resolving conflicts will be lost.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, cancel it',
                    confirmButtonColor: '#dc2626'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'conflict-resolution-modal' }));
                    }
                });
            });

            $('#confirm-resolution-btn').on('click', function() {
                const supplierId = "{{ $supplier->id }}";
                const $btn = $(this);
                $btn.prop('disabled', true).text('Processing...');

                $.ajax({
                    url: `/clt-layup/supplier/${supplierId}/import/resolve`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        resolutions: Object.values(resolutions)
                    },
                    success: function(response) {
                        $btn.prop('disabled', false).text('Confirm & Complete Import');
                        window.dispatchEvent(new CustomEvent('close-modal', { detail: 'conflict-resolution-modal' }));
                        $('#layups-table').DataTable().ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Import Complete',
                            text: 'All layups and layers have been processed successfully.',
                        });
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).text('Confirm & Complete Import');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to complete import resolution.',
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
