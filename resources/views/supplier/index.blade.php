<x-app-layout>
    <x-slot name="header">
        <header>
            <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                            {{ __('Suppliers') }}
                        </h2>
                        <p class="text-sm text-gray-800 dark:text-gray-200">
                            {{ __('Manage timber suppliers and material sourcing.') }}
                        </p>
                    </div>

                    <div>
                        <button onclick="openCreateModal()"
                            class="rounded-lg bg-[var(--main-color)] dark:bg-[var(--dark-main-color)] text-white py-2 px-4 text-sm hover:opacity-90 transition-opacity">
                            <i class="fa-solid fa-plus mr-1"></i>
                            {{ __('Add Supplier') }}
                        </button>
                    </div>
                </div>
            </div>

        </header>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between">
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="supplier-search" placeholder="Search supplier..."
                        class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-1 focus:ring-[var(--main-color)] focus:border-[var(--main-color)] dark:bg-gray-700 dark:text-white text-sm">
                </div>
                <div class="flex items-center space-x-4">
                    <button id="export-button"
                        class="px-2 py-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                        <i class="fa-solid fa-download mr-1"></i> Export
                    </button>
                </div>
            </div>

            <x-table id="suppliers-table">
                <x-slot name="header">
                    <th class="px-4 py-4 w-full">Name</th>
                    <th class="px-4 py-4 text-center whitespace-nowrap">Total Layups</th>
                    <th class="px-4 py-4 whitespace-nowrap">Created At</th>
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
                const table = $('#suppliers-table').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    width: '100%',
                    ajax: {
                        url: "{{ route('supplier.data') }}",
                        data: function(d) {
                            d.search = $('#supplier-search').val();
                        }
                    },
                    columns: [{
                            data: 'name',
                            render: function(data, type, row) {
                                return `
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-${row.avatar_color}-100 flex items-center justify-center text-${row.avatar_color}-600 font-bold text-xs uppercase">
                                            ${row.initials}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white">${data}</div>
                                            <div class="text-xs text-gray-500">${row.supplier_id}</div>
                                        </div>
                                    </div>
                                `;
                            }
                        },
                        {
                            data: 'clt_layups_count',
                            className: 'text-center'
                        },
                        {
                            data: 'created_at'
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
                                                    <a href="/supplier/${row.id}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                        <i class="fa-solid fa-eye mr-2"></i> {{ __('View') }}
                                                    </a>
                                                    <a href="javascript:void(0)" onclick='openEditModal(${JSON.stringify(row)})' class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                        <i class="fa-solid fa-edit mr-2"></i> {{ __('Edit') }}
                                                    </a>
                                                    <hr class="border-gray-100 dark:border-gray-600">
                                                    <a href="javascript:void(0)" onclick="deleteSupplier(${row.id})" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30">
                                                        <i class="fa-solid fa-trash-can mr-2"></i> {{ __('Delete') }}
                                                    </a>
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
                $('#supplier-search').on('keyup', function() {
                    table.ajax.reload();
                });

                // Export button
                $('#export-button').on('click', function() {
                    const search = $('#supplier-search').val();
                    window.location.href = `{{ route('supplier.export') }}?search=${search}`;
                });
            });

            function openCreateModal() {
                $('#modal-title').text("{{ __('Add New Supplier') }}");
                $('#supplier-form')[0].reset();
                $('#supplier-id').val('');
                $('#method-field').val('POST');
                $('.text-red-500').text(''); // Clear errors
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'supplier-form-modal'
                }));
            }

            function openEditModal(data) {
                $('#modal-title').text("{{ __('Edit Supplier') }}");
                $('#supplier-form')[0].reset();
                $('.text-red-500').text(''); // Clear errors

                $('#supplier-id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#location').val(data.location);
                $('#material_certification').val(data.material_certification);
                $('#method-field').val('PATCH');

                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'supplier-form-modal'
                }));
            }

            $('#supplier-form').on('submit', function(e) {
                e.preventDefault();
                const id = $('#supplier-id').val();
                const url = id ? `/supplier/${id}` : '/supplier';
                const method = $('#method-field').val();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.dispatchEvent(new CustomEvent('close-modal', {
                            detail: 'supplier-form-modal'
                        }));
                        $('#suppliers-table').DataTable().ajax.reload();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: id ? 'Supplier updated successfully' :
                                'Supplier created successfully',
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
                                $(`#error-${key}`).text(errors[key][0]);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong. Please try again.',
                            });
                        }
                    }
                });
            });

            function deleteSupplier(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'px-5 py-2.5 ml-3 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 order-2',
                        cancelButton: 'px-5 py-2.5 text-sm font-medium text-gray-700 bg-transparent border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 order-1'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/supplier/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                $('#suppliers-table').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Supplier has been deleted.',
                                    timer: 2000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Failed to delete supplier.',
                                });
                            }
                        });
                    }
                })
            }
        </script>
    @endpush

    {{-- Supplier Form Modal --}}
    <x-modal name="supplier-form-modal" focusable>
        <div class="p-6">
            <h2 id="modal-title" class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ __('Add New Supplier') }}
            </h2>

            <form id="supplier-form">
                @csrf
                <input type="hidden" name="_method" id="method-field" value="POST">
                <input type="hidden" id="supplier-id">

                <div class="space-y-4">
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                            placeholder="e.g. Nordic Timber Co." />
                        <span id="error-name" class="text-sm text-red-500 mt-1"></span>
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email Address')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                            placeholder="supplier@example.com" />
                        <span id="error-email" class="text-sm text-red-500 mt-1"></span>
                    </div>

                    <div>
                        <x-input-label for="location" :value="__('Location')" />
                        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full"
                            placeholder="e.g. Oslo, Norway" />
                        <span id="error-location" class="text-sm text-red-500 mt-1"></span>
                    </div>

                    <div>
                        <x-input-label for="material_certification" :value="__('Material Certification')" />
                        <x-text-input id="material_certification" name="material_certification" type="text"
                            class="mt-1 block w-full" placeholder="e.g. PEFC/01-31-123" />
                        <span id="error-material_certification" class="text-sm text-red-500 mt-1"></span>
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
