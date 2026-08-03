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
    <?php $__env->startSection('title', 'SK Kades'); ?>

    <div class="flex items-center justify-between mb-6 mt-1">
        <div>
            <p class="text-muted text-sm mt-1">Kelola usulan pemberhentian Kades dan penunjukan Pj/Plt secara digital.</p>
        </div>
        <a href="<?php echo e(route('desa.pjkades.create')); ?>"
            class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-btn hover:bg-primary-light hover:-translate-y-0.5 hover:shadow-lg transition-all active:scale-95 shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Usulan Baru
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="p-4 bg-green-50 text-green-800 rounded-lg border border-green-200 text-sm mb-6 font-medium flex items-center justify-between">
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="p-4 bg-red-50 text-red-800 rounded-lg border border-red-200 text-sm mb-6 font-medium">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden transition-shadow duration-300 hover:shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">No. Registrasi / Tanggal</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Jenis Pemberhentian & Alasan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Pengganti (Pj / Plt Kades)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Kelengkapan Dokumen</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    <?php $__empty_1 = true; $__currentLoopData = $pjkades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-ink font-mono"><?php echo e($pj->no_registrasi ?? ('SKK-' . $pj->id)); ?></div>
                                <div class="text-xs text-muted mt-0.5"><?php echo e($pj->tgl_diajukan ? $pj->tgl_diajukan->format('d M Y') : $pj->created_at->format('d M Y')); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($pj->kategori === 'plt_kades'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        Pemberhentian Sementara / Cuti
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        Pemberhentian Definitif
                                    </span>
                                <?php endif; ?>
                                <div class="text-xs font-medium text-ink mt-1">Alasan: <strong><?php echo e($pj->alasan_nama ?? ($pj->alasanPemberhentian->nama ?? '-')); ?></strong></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($pj->kategori === 'plt_kades'): ?>
                                    <div class="text-sm font-semibold text-ink font-display"><?php echo e($pj->nama_plt ?? '-'); ?></div>
                                    <div class="text-xs text-amber-700 font-medium">Plt Kades (Sekretaris Desa)</div>
                                <?php else: ?>
                                    <div class="text-sm font-semibold text-ink font-display"><?php echo e($pj->nama_pns ?? '-'); ?></div>
                                    <div class="text-xs text-indigo-700 font-medium">Pj Kades (PNS <?php echo e($pj->pangkat ?? ''); ?>)</div>
                                    <div class="text-xs text-muted font-mono">NIP. <?php echo e($pj->nip ?? '-'); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $totalChecklist = $pj->checklists->count();
                                    $uploadedChecklist = $pj->checklists->whereNotNull('file_path')->count();
                                    $approvedChecklist = $pj->checklists->where('status_verifikasi', 'valid')->count();
                                    $percent = $totalChecklist > 0 ? round(($uploadedChecklist / $totalChecklist) * 100) : 0;
                                ?>
                                <div class="flex items-center gap-2">
                                    <div class="w-24 bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="bg-primary h-2 rounded-full" style="width: <?php echo e($percent); ?>%"></div>
                                    </div>
                                    <span class="text-xs font-bold text-ink"><?php echo e($uploadedChecklist); ?>/<?php echo e($totalChecklist); ?></span>
                                </div>
                                <div class="text-xs text-muted mt-1"><?php echo e($approvedChecklist); ?> disetujui</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($pj->status === 'approved'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Disetujui / SK Terbit
                                    </span>
                                <?php elseif($pj->status === 'submitted'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Menunggu Verifikasi
                                    </span>
                                <?php elseif($pj->status === 'rejected'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Ditolak / Dikembalikan
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Draft (Lengkapi Berkas)
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?php echo e(route('desa.pjkades.show', $pj->id)); ?>"
                                    class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-ink text-xs font-medium rounded transition-all group-hover:bg-primary-soft group-hover:text-primary group-hover:scale-105">
                                    Lihat & Unggah
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-muted">
                                Belum ada usulan SK Kades. Klik tombol <strong>+ Buat Usulan SK Pemberhentian & Kades Baru</strong> di atas untuk membuat.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($pjkades->hasPages()): ?>
            <div class="px-6 py-4 border-t border-border">
                <?php echo e($pjkades->links()); ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\sidmini\resources\views/desa/pjkades/index.blade.php ENDPATH**/ ?>