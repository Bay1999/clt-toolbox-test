<x-app-layout>
    <x-slot name="header">
        <header>
            <div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Supplier') }} /
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $supplier->name }} /
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Layup') }} /
                        </p>
                        <p class="text-sm text-gray-800 dark:text-gray-100 font-semibold">
                            {{ $layup->name }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('supplier.show', $layup->supplier_id) }}" class="px-4 py-1 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back
                        </a>
                        <button id="save-changes-btn" onclick="window.dispatchEvent(new CustomEvent('trigger-sync'))" class="px-4 py-1 rounded-lg bg-[var(--main-color)] dark:bg-[var(--dark-main-color)] text-white hover:opacity-90 transition-opacity">
                            <i class="fa-regular fa-floppy-disk"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </header>
    </x-slot>

    <div class="py-4">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
  
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                    <h2 class="text-xl font-bold text-gray-800">Layup : {{ $layup->name }}</h2>
                    <span class="px-3 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-100">
                        Active
                    </span>
                    </div>
                    <p class="text-gray-500 text-xs">Standard 5-layer panel for residential structural walls.</p>
                </div>

                <div class="flex gap-10 px-10">
                    <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Created By</p>
                    <p class="text-sm font-bold text-gray-700">Eng. Dept A</p>
                    </div>
                    <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Last Modified</p>
                    <p class="text-sm font-bold text-gray-700">Oct 24, 2023</p>
                    </div>
                </div>

                <div class="flex border-l border-gray-200">
                    <div class="px-10">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Thickness</p>
                    <p class="text-sm font-bold text-green-800" id="top-total-thickness">0mm</p>
                    </div>
                    <div class="px-10 border-l border-gray-200">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Layers</p>
                    <p class="text-sm font-bold text-green-800" id="top-total-layers">0 Layers</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="py-12" x-data="layupBuilder(@js($layup->cltLayers->map(fn($layer) => $layer->withoutRelations()->toArray())), {{ $layup->id }})" @trigger-sync.window="syncLayers">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                <!-- Left Column: Layer Composition -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Layer Composition</h3>
                        <button @click="openModal()" class="text-sm text-[var(--main-color)] hover:text-green-700 font-medium">
                            <i class="fa-solid fa-plus mr-1"></i> Add Layer
                        </button>
                    </div>
                    
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 w-10 text-center">Order</th>
                                    <th class="px-4 py-3">Thickness</th>
                                    <th class="px-4 py-3">Width</th>
                                    <th class="px-4 py-3 text-center">Angle</th>
                                    <th class="px-4 py-3">Grade</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-layers" class="divide-y divide-gray-100">
                                <template x-for="(layer, index) in layers" :key="layer.uid">
                                    <tr class="hover:bg-gray-50 bg-white group cursor-move">
                                        <td class="px-4 py-3 text-center text-gray-400 drag-handle">
                                            <i class="fa-solid fa-grip-vertical"></i>
                                        </td>
                                        <td class="px-4 py-3 font-medium text-gray-900" x-text="layer.thickness + 'mm'"></td>
                                        <td class="px-4 py-3 text-gray-600" x-text="layer.width + 'mm'"></td>
                                        <td class="px-4 py-3 text-center">
                                            <span x-show="layer.angle == 0" class="inline-flex items-center gap-1 px-2 py-1 rounded bg-blue-50 text-blue-700 text-xs font-medium border border-blue-100">
                                                <i class="fa-solid fa-arrow-up"></i> 0°
                                            </span>
                                            <span x-show="layer.angle == 90" class="inline-flex items-center gap-1 px-2 py-1 rounded bg-orange-50 text-orange-700 text-xs font-medium border border-orange-100">
                                                <i class="fa-solid fa-rotate-right"></i> 90°
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full" :class="layer.angle == 0 ? 'bg-green-600' : 'bg-amber-700'"></div>
                                                <span x-text="layer.grade"></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right">
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
                                                            left: rect.right + window.scrollX - 128
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
                                                         :style="`position: absolute; top: ${triggerPos.top}px; left: ${triggerPos.left}px; z-index: 9999;`"
                                                         class="w-32 rounded-md bg-white dark:bg-gray-700 shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none" 
                                                         style="display: none;">
                                                        <div class="py-1">
                                                            <a href="javascript:void(0)" @click.prevent="openModal(index); open = false" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                                                <i class="fa-solid fa-edit mr-2"></i> Edit
                                                            </a>
                                                            <hr class="border-gray-100 dark:border-gray-600">
                                                            <a href="javascript:void(0)" @click.prevent="deleteLayer(index); open = false" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30">
                                                                <i class="fa-solid fa-trash-can mr-2"></i> Delete
                                                            </a>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="layers.length === 0">
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                        No layers added yet. Click "+ Add Layer" to start.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 flex justify-between items-center text-sm text-gray-500">
                            <span x-text="'Showing ' + layers.length + ' layers'"></span>
                            <span x-text="'Calculated Sum: ' + totalThickness + ' mm'"></span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Structure Visualizer -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Structure Visualizer</h3>
                        <div class="flex items-center gap-4 text-xs text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded-sm bg-[#e8cdab]"></div> Longitudinal (0°)
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-3 h-3 rounded-sm bg-[#c19a6b]"></div> Transverse (90°)
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm relative min-h-[400px] flex items-center justify-center">
                        
                        <div class="absolute left-6 top-8 bottom-8 flex flex-col justify-between text-[10px] font-bold text-gray-400">
                            <span>TOP<br>(OUTSIDE)</span>
                            <div class="flex-1 w-px bg-dashed bg-gray-200 mx-auto my-4 border-l border-dashed border-gray-300"></div>
                            <span>BOTTOM<br>(INSIDE)</span>
                        </div>

                        <div class="w-full max-w-sm flex flex-col gap-1 mx-auto relative z-10 p-8 bg-white rounded-2xl shadow-[0_0_40px_rgba(0,0,0,0.05)]">
                            <template x-for="(layer, index) in layers" :key="'vis-'+layer.uid">
                                <div class="relative w-full rounded flex items-center justify-center shadow-sm border transition-all duration-300"
                                     :class="layer.angle == 0 ? 'bg-[#e8cdab] border-[#d4b58c] text-[#8c6b45]' : 'bg-[#c19a6b] border-[#a68054] text-[#5c4021]'"
                                     :style="`height: ${Math.max(30, layer.thickness * 1.5)}px`">
                                    
                                    <div class="font-mono text-sm font-semibold tracking-wider flex items-center gap-2">
                                        <span x-text="`L${index + 1}`"></span>
                                        <span class="opacity-50">·</span>
                                        <span x-text="`${layer.thickness}mm`"></span>
                                    </div>
                                    
                                    <div class="absolute right-4 opacity-50">
                                        <i class="fa-solid fa-arrow-up" x-show="layer.angle == 0"></i>
                                        <i class="fa-solid fa-rotate-right" x-show="layer.angle == 90"></i>
                                    </div>
                                </div>
                            </template>
                            <div x-show="layers.length === 0" class="text-center text-gray-400 py-10 text-sm">
                                Add layers to see visualization
                            </div>
                        </div>
                        
                        <div class="absolute bottom-4 left-0 right-0 text-center">
                            <p class="text-[10px] text-gray-400 italic">Cross-Laminated Structural Assembly<br>Note: 3D orientation is for schematic purposes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Add/Edit Layer -->
        <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="isModalOpen" @click.away="closeModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form @submit.prevent="saveLayer">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title" x-text="editingIndex !== null ? 'Edit Layer' : 'Add New Layer'"></h3>
                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Thickness (mm)</label>
                                            <input type="number" x-model="formData.thickness" required min="1" step="0.1" class="mt-1 focus:ring-[var(--main-color)] focus:border-[var(--main-color)] block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Width (mm)</label>
                                            <input type="number" x-model="formData.width" required min="1" class="mt-1 focus:ring-[var(--main-color)] focus:border-[var(--main-color)] block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Angle</label>
                                            <select x-model="formData.angle" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-[var(--main-color)] focus:border-[var(--main-color)] sm:text-sm">
                                                <option value="0">0° (Longitudinal)</option>
                                                <option value="90">90° (Transverse)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Grade</label>
                                            <input type="text" x-model="formData.grade" required placeholder="e.g. C24" class="mt-1 focus:ring-[var(--main-color)] focus:border-[var(--main-color)] block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[var(--main-color)] text-base font-medium text-white hover:opacity-90 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Save
                            </button>
                            <button type="button" @click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('layupBuilder', (initialLayers, layupId) => ({
                layers: initialLayers || [],
                layupId: layupId,
                isModalOpen: false,
                isSyncing: false,
                isSubmitting: false,
                editingIndex: null,
                formData: {
                    thickness: '',
                    width: 1200,
                    angle: 0,
                    grade: ''
                },
                
                get totalThickness() {
                    return this.layers.reduce((sum, layer) => sum + parseFloat(layer.thickness || 0), 0).toFixed(2);
                },
                
                init() {
                    // Add unique IDs for stable keys
                    this.layers = this.layers.map(layer => ({
                        ...layer,
                        uid: layer.id || Math.random().toString(36).substr(2, 9)
                    }));

                    this.$nextTick(() => {
                        this.initSortable();
                        this.updateTopBarTotals();
                    });
                    
                    this.$watch('layers', value => {
                        this.updateTopBarTotals();
                    });

                    window.addEventListener('trigger-sync', () => {
                        this.syncLayers();
                    });
                },
                
                initSortable() {
                    const el = document.getElementById('sortable-layers');
                    if (el) {
                        Sortable.create(el, {
                            handle: '.drag-handle',
                            animation: 150,
                            ghostClass: 'bg-gray-100',
                            onEnd: (evt) => {
                                // Update alpine state without triggering full redraw if possible
                                const itemEl = this.layers[evt.oldIndex];
                                this.layers.splice(evt.oldIndex, 1);
                                this.layers.splice(evt.newIndex, 0, itemEl);
                            }
                        });
                    }
                },
                
                openModal(index = null) {
                    if (index !== null) {
                        this.editingIndex = index;
                        this.formData = { ...this.layers[index] };
                    } else {
                        this.editingIndex = null;
                        this.formData = {
                            thickness: '',
                            width: 1200,
                            angle: 0,
                            grade: ''
                        };
                    }
                    this.isModalOpen = true;
                },
                
                closeModal() {
                    this.isModalOpen = false;
                    this.editingIndex = null;
                },
                
                saveLayer() {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;

                    if (this.editingIndex !== null) {
                        // Use splice for better reactivity in some edge cases
                        this.layers.splice(this.editingIndex, 1, { 
                            ...this.layers[this.editingIndex], 
                            ...this.formData 
                        });
                    } else {
                        this.layers.push({ 
                            ...this.formData,
                            uid: Math.random().toString(36).substr(2, 9)
                        });
                    }
                    this.closeModal();
                    this.isSubmitting = false;
                },
                
                deleteLayer(index) {
                    Swal.fire({
                        title: 'Delete layer?',
                        text: "Are you sure you want to remove this layer?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.layers.splice(index, 1);
                        }
                    });
                },
                
                syncLayers() {
                    if (this.isSyncing) return;
                    this.isSyncing = true;

                    const btn = document.getElementById('save-changes-btn');
                    const originalHtml = btn.innerHTML;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...';
                    btn.disabled = true;

                    fetch(`/clt-layup/${this.layupId}/layers/sync`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ layers: this.layers })
                    })
                    .then(response => response.json())
                    .then(data => {
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                        
                        if (data.status === 'success') {
                            this.layers = data.data.map(layer => ({
                                ...layer,
                                uid: layer.id || Math.random().toString(36).substr(2, 9)
                            }));
                            Swal.fire({
                                icon: 'success',
                                title: 'Saved!',
                                text: 'Layup composition synchronized successfully.',
                                timer: 2000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                        } else {
                            throw new Error(data.message || 'Failed to sync');
                        }
                    })
                    .catch(error => {
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                        Swal.fire('Error', error.message, 'error');
                    })
                    .finally(() => {
                        this.isSyncing = false;
                    });
                },
                
                updateTopBarTotals() {
                    const thicknessEl = document.getElementById('top-total-thickness');
                    const layersEl = document.getElementById('top-total-layers');
                    if (thicknessEl) thicknessEl.textContent = this.totalThickness + 'mm';
                    if (layersEl) layersEl.textContent = this.layers.length + ' Layers';
                }
            }));
        });
    </script>
    @endpush

</x-app-layout>
