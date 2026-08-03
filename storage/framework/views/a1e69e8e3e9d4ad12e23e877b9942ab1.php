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
    <?php $__env->startSection('title', 'Verifikasi e-Rekomendasi'); ?>

    <?php if(session('success')): ?>
        <div class="mb-5 p-4 rounded-card bg-green-50 border border-green-200 text-green-800 flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-surface rounded-card border border-border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-border">
                        <th class="px-6 py-4 text-center text-xs font-semibold text-muted uppercase tracking-wider">No. Registrasi</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-muted uppercase tracking-wider">Desa & Pemohon</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-muted uppercase tracking-wider">Layanan</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-muted uppercase tracking-wider">Posisi Surat</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-muted uppercase tracking-wider">Status & SLA</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-muted uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    <?php $__empty_1 = true; $__currentLoopData = $ajuans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ajuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-mono text-ink"><?php echo e($ajuan->no_registrasi); ?></span>
                                <div class="text-xs text-muted mt-1"><?php echo e($ajuan->tgl_diajukan ? $ajuan->tgl_diajukan->format('d M Y') : '-'); ?></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-sm font-semibold text-ink"><?php echo e($ajuan->desa->nama_desa); ?></div>
                                <div class="text-xs text-muted mt-0.5">
                                    <?php echo e($ajuan->pesertas->first() ? $ajuan->pesertas->first()->perangkatDesa->nama : '-'); ?>

                                    <?php if($ajuan->pesertas->count() > 1): ?>
                                        <span class="text-primary font-bold ml-1">(+<?php echo e($ajuan->pesertas->count() - 1); ?>)</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold <?php echo e($ajuan->jenisLayanan->nama == 'Pengangkatan' ? 'bg-indigo-100 text-indigo-800' : ($ajuan->jenisLayanan->nama == 'Pemberhentian' ? 'bg-red-100 text-danger' : 'bg-yellow-100 text-yellow-800')); ?>">
                                    <?php echo e($ajuan->jenisLayanan->nama); ?>

                                </span>
                                <div class="text-xs text-muted mt-1 uppercase font-bold"><?php echo e($ajuan->metode); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium text-ink"><?php echo e($ajuan->posisi_surat ?? 'Front Office (FO)'); ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <?php
                                    $statusBadge = match($ajuan->status) {
                                        'submitted' => ['label' => 'Menunggu Verifikasi', 'css' => 'bg-blue-100 text-blue-800'],
                                        'direvisi'  => ['label' => 'Perlu Perbaikan', 'css' => 'bg-red-100 text-red-800'],
                                        'diproses'  => ['label' => 'Dalam Proses', 'css' => 'bg-yellow-100 text-yellow-800'],
                                        'selesai'   => ['label' => 'Selesai', 'css' => 'bg-green-100 text-green-800'],
                                        'ditolak'   => ['label' => 'Ditolak', 'css' => 'bg-gray-200 text-gray-800'],
                                        default     => ['label' => $ajuan->status, 'css' => 'bg-gray-100 text-gray-800'],
                                    };
                                ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mb-1 <?php echo e($statusBadge['css']); ?>">
                                    <?php echo e($statusBadge['label']); ?>

                                </span>
                                <?php if($ajuan->tgl_sla_batas): ?>
                                    <?php
                                        $sisaHari = now()->startOfDay()->diffInDays($ajuan->tgl_sla_batas, false);
                                        $slaClass = $sisaHari < 3 ? 'text-danger' : ($sisaHari <= 7 ? 'text-warning' : 'text-success');
                                    ?>
                                    <div class="text-xs font-medium <?php echo e($slaClass); ?>">
                                        SLA: Sisa <?php echo e($sisaHari); ?> Hari
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 font-medium text-center whitespace-nowrap">
                                <a href="<?php echo e(route('admin.ajuan.show', $ajuan)); ?>" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-medium rounded-btn bg-primary text-white hover:bg-primary-light transition-colors shadow-sm">
                                    Verifikasi Split-Screen
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-muted">
                                Tidak ada pengajuan e-rekomendasi yang berjalan saat ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
<?php /**PATH C:\laragon\www\sidmini\resources\views/admin/ajuan/index.blade.php ENDPATH**/ ?>