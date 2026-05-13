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
                    <button id="import-layups-button"
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

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <style>
            .dataTables_info {
                font-size: 14px
            }

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
        </script>
    @endpush
    
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
</x-app-layout>
