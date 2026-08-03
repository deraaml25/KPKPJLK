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
    <?php $__env->startSection('title', 'SK Pemberhentian Kades & Pengangkatan Pj/Plt Kades'); ?>

    <?php if(session('success')): ?>
        <div class="p-4 bg-green-50 text-green-800 rounded-lg border border-green-200 text-sm mb-6 font-medium">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="p-4 bg-red-50 text-red-800 rounded-lg border border-red-200 text-sm mb-6 font-medium">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    
    <?php
        $alertPj = $pjkades->filter(fn($p) => $p->status === 'approved' && $p->hampir_berakhir);
    ?>
    <?php if($alertPj->count() > 0): ?>
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg text-sm mb-6">
            <strong class="font-bold block">⚠️ PERINGATAN DINI — Masa Jabatan Hampir Habis</strong>
            <p class="text-xs mt-1">Terdapat <strong><?php echo e($alertPj->count()); ?></strong> SK Kades yang masa jabatannya akan berakhir dalam 30 hari:</p>
            <ul class="list-disc ml-5 mt-1 text-xs">
                <?php $__currentLoopData = $alertPj; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><strong><?php echo e($a->kategori === 'plt_kades' ? ($a->nama_plt ?? 'Plt Sekdes') : $a->nama_pns); ?></strong> (Desa <?php echo e($a->desa->nama_desa); ?>) — Sisa <strong><?php echo e($a->sisa_hari); ?> hari</strong></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Kecamatan / Desa</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Jenis Pemberhentian & Alasan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Pengganti (Pj / Plt Kades)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Verifikasi Berkas</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-muted uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    <?php $__empty_1 = true; $__currentLoopData = $pjkades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50/50 <?php echo e($pj->status === 'approved' && $pj->hampir_berakhir ? 'bg-red-50/30' : ''); ?>">
                            <td class="px-6 py-4 whitespace-nowrap text-left">
                                <div class="text-sm font-bold text-ink">Desa <?php echo e($pj->desa->nama_desa); ?></div>
                                <div class="text-xs text-muted">Kec. <?php echo e($pj->desa->kecamatan->nama_kecamatan ?? '-'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-left">
                                <?php if($pj->kategori === 'plt_kades'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-indigo-100 text-indigo-800">
                                        Pemberhentian Sementara / Cuti
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-indigo-100 text-indigo-800">
                                        Pemberhentian Definitif
                                    </span>
                                <?php endif; ?>
                                <div class="text-xs text-ink mt-1 font-medium">Alasan: <strong><?php echo e($pj->alasan_nama ?? ($pj->alasanPemberhentian->nama ?? '-')); ?></strong></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-left">
                                <?php if($pj->kategori === 'plt_kades'): ?>
                                    <div class="text-sm font-bold text-ink"><?php echo e($pj->nama_plt ?? '-'); ?></div>
                                    <div class="text-xs text-indigo-700 font-medium">Plt Kades (Sekretaris Desa)</div>
                                <?php else: ?>
                                    <div class="text-sm font-bold text-ink"><?php echo e($pj->nama_pns ?? '-'); ?></div>
                                    <div class="text-xs text-indigo-700 font-medium">Pj Kades (PNS <?php echo e($pj->pangkat ?? ''); ?>)</div>
                                    <div class="text-xs text-muted font-mono">NIP. <?php echo e($pj->nip ?? '-'); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-left">
                                <?php
                                    $totalChecklist = $pj->checklists->count();
                                    $uploadedChecklist = $pj->checklists->whereNotNull('file_path')->count();
                                    $approvedChecklist = $pj->checklists->where('status_verifikasi', 'valid')->count();
                                ?>
                                <div class="text-xs font-bold text-ink"><?php echo e($approvedChecklist); ?>/<?php echo e($totalChecklist); ?> Disetujui</div>
                                <div class="text-xs text-muted"><?php echo e($uploadedChecklist); ?> Berkas Diunggah</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <?php if($pj->status === 'approved'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Approved / SK Bupati Terbit
                                    </span>
                                <?php elseif($pj->status === 'rejected'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                <?php elseif($pj->status === 'submitted'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Perlu Verifikasi
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Draft Desa
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium flex justify-center gap-2">
                                <a href="<?php echo e(route('admin.pjkades.show', $pj->id)); ?>"
                                    class="inline-flex items-center px-3 py-1.5 bg-primary text-white text-xs font-medium rounded-btn hover:bg-primary-light transition-colors">
                                    Verifikasi & SK
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-muted">
                                Belum ada usulan SK Kades dari desa.
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
<?php endif; ?><?php /**PATH C:\laragon\www\sidmini\resources\views/admin/pjkades/index.blade.php ENDPATH**/ ?>