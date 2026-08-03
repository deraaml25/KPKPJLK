<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <?php $__env->startSection('title', 'Rencana P3D'); ?>



    <?php if(session('success')): ?>
        <div class="p-4 bg-green-50 text-green-800 rounded-lg border border-green-200 text-sm mb-6 font-medium">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        
        <div class="bg-white p-6 rounded-card border border-border shadow-sm flex items-center justify-between overflow-hidden">
            <div class="min-w-0 flex-1">
                <span class="text-xs font-semibold text-muted uppercase tracking-wider block truncate">Total Formasi Kosong</span>
                <span class="text-xl font-extrabold text-ink block mt-2 font-display whitespace-nowrap truncate"><?php echo e($totalFormasi); ?></span>
                <span class="text-xs text-muted block mt-1 truncate">Jabatan Perangkat Desa</span>
            </div>
            <div class="h-12 w-12 rounded-full bg-red-50 flex-shrink-0 flex items-center justify-center text-red-600 ml-4">
                <span class="material-symbols-outlined text-[28px]">assignment_late</span>
            </div>
        </div>

        
        <div class="bg-white p-6 rounded-card border border-border shadow-sm flex items-center justify-between overflow-hidden">
            <div class="min-w-0 flex-1">
                <span class="text-xs font-semibold text-muted uppercase tracking-wider block truncate">Total Rencana Anggaran P3D</span>
                <span class="text-xl font-extrabold text-ink block mt-2 font-display whitespace-nowrap truncate">Rp <?php echo e(number_format($totalAnggaran, 0, ',', '.')); ?></span>
                <span class="text-xs text-muted block mt-1 truncate">Alokasi Anggaran Terkumpul</span>
            </div>
            <div class="h-12 w-12 rounded-full bg-emerald-50 flex-shrink-0 flex items-center justify-center text-emerald-600 ml-4">
                <span class="material-symbols-outlined text-[28px]">payments</span>
            </div>
        </div>

        
        <div class="bg-white p-6 rounded-card border border-border shadow-sm flex items-center justify-between overflow-hidden">
            <div class="min-w-0 flex-1">
                <span class="text-xs font-semibold text-muted uppercase tracking-wider block truncate">Desa yang Sudah Melapor</span>
                <span class="text-xl font-extrabold text-ink block mt-2 font-display whitespace-nowrap truncate"><?php echo e($totalDesa); ?></span>
                <span class="text-xs text-muted block mt-1 truncate">Desa Terdata</span>
            </div>
            <div class="h-12 w-12 rounded-full bg-indigo-50 flex-shrink-0 flex items-center justify-center text-indigo-600 ml-4">
                <span class="material-symbols-outlined text-[28px]">holiday_village</span>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-card shadow-sm border border-border p-6 mb-6">
        <form action="<?php echo e(route('admin.rencana-p3d.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            
            <div>
                <label for="kecamatan_id" class="block text-xs font-bold text-ink mb-2">Filter Kecamatan</label>
                <select name="kecamatan_id" id="kecamatan_id" class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm">
                    <option value="">Semua Kecamatan</option>
                    <?php $__currentLoopData = $kecamatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kec->id); ?>" <?php echo e(request('kecamatan_id') == $kec->id ? 'selected' : ''); ?>>
                            <?php echo e($kec->nama_kecamatan); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            
            <div>
                <label for="search" class="block text-xs font-bold text-ink mb-2">Cari Nama Desa</label>
                <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>"
                    class="w-full text-sm rounded-md border-border text-ink bg-white focus:border-primary focus:ring-primary shadow-sm"
                    placeholder="Masukkan nama desa...">
            </div>

            
            <div class="flex gap-2">
                <button type="submit" class="flex-grow inline-flex items-center justify-center px-4 py-2 bg-primary text-white font-medium rounded-btn hover:bg-primary-light transition-colors text-sm shadow-sm">
                    <span class="material-symbols-outlined mr-1.5 text-[18px]">search</span>
                    Cari & Filter
                </button>
                <?php if(request()->filled('kecamatan_id') || request()->filled('search')): ?>
                    <a href="<?php echo e(route('admin.rencana-p3d.index')); ?>" class="inline-flex items-center justify-center px-4 py-2 border border-border text-sm font-medium rounded-btn text-ink bg-white hover:bg-gray-50 transition-colors" title="Reset Filter">
                        <span class="material-symbols-outlined text-[18px]">refresh</span>
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('admin.rencana-p3d.export-csv', request()->query())); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 text-white font-medium rounded-btn hover:bg-emerald-700 transition-colors text-sm shadow-sm">
                    <span class="material-symbols-outlined mr-1.5 text-[18px]">download</span>
                    Excel
                </a>
            </div>
        </form>
    </div>

    
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Kecamatan & Desa</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Formasi Kosong</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Jabatan yang Kosong</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Rencana Pelaksanaan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Rencana Anggaran</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Tahun</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    <?php $__empty_1 = true; $__currentLoopData = $rencana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-ink">Desa <?php echo e($item->desa->nama_desa); ?></div>
                                <div class="text-xs text-muted">Kec. <?php echo e($item->kecamatan->nama_kecamatan ?? '-'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-800 border border-rose-100">
                                    <?php echo e($item->jumlah_formasi_kosong); ?> Formasi
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-ink max-w-[200px] truncate" title="<?php echo e($item->jabatan_kosong); ?>">
                                    <?php echo e($item->jabatan_kosong); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-ink font-medium">
                                    <?php echo e($item->rencana_pelaksanaan ? $item->rencana_pelaksanaan->format('d M Y') : '-'); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-ink">
                                    Rp <?php echo e(number_format($item->rencana_anggaran, 0, ',', '.')); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-ink font-mono"><?php echo e($item->tahun ?? '-'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($item->status === 'disetujui'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Disetujui Admin
                                    </span>
                                <?php elseif($item->status === 'dikirim'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Dikirim / Proses
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Draft
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="<?php echo e(route('admin.rencana-p3d.show', $item->id)); ?>"
                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 text-xs font-semibold rounded-btn transition-colors">
                                    <span class="material-symbols-outlined text-[14px] mr-1">visibility</span>
                                    Detail / Evaluasi
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-muted">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="material-symbols-outlined text-[48px] text-gray-300 mb-2">assignment_late</span>
                                    <p class="font-medium text-sm">Belum ada data Rencana P3D.</p>
                                    <p class="text-xs text-slate-400 mt-1">Belum ada desa yang mengirimkan data formasi kosong.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($rencana->hasPages()): ?>
            <div class="px-6 py-4 border-t border-border">
                <?php echo e($rencana->links()); ?>

            </div>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\sidmini\resources\views/admin/rencana_p3d/index.blade.php ENDPATH**/ ?>