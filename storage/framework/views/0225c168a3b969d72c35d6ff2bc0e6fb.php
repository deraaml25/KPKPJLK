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
    <?php $__env->startSection('title', 'Pembinaan'); ?>
    <?php $__env->startSection('page-description', 'Daftar permohonan narasumber dan pembinaan yang diajukan oleh desa-desa.'); ?>


    <!-- Tabs Nav -->
    <div class="border-b border-border mb-6">
        <nav class="flex space-x-8" aria-label="Tabs">
            <a href="<?php echo e(route('admin.bimtek-informasi.index')); ?>"
               class="border-b-2 py-4 px-1 text-sm font-semibold <?php echo e(request()->routeIs('admin.bimtek-informasi.*') ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-ink hover:border-gray-300'); ?>">
                Berita & Informasi Pembinaan
            </a>
            <a href="<?php echo e(route('admin.pengajuan-pembinaan.index')); ?>"
               class="border-b-2 py-4 px-1 text-sm font-semibold <?php echo e(request()->routeIs('admin.pengajuan-pembinaan.*') ? 'border-primary text-primary' : 'border-transparent text-muted hover:text-ink hover:border-gray-300'); ?>">
                Pengajuan Pembinaan Desa
            </a>
        </nav>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-card text-sm">
            ✅ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Desa</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Judul Kegiatan</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tanggal Diajukan</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Dokumen</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    <?php $__empty_1 = true; $__currentLoopData = $pengajuans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-4 text-sm font-medium text-ink"><?php echo e($p->desa->nama_desa ?? '-'); ?></td>
                            <td class="px-5 py-4">
                                <div class="text-sm text-ink font-medium"><?php echo e($p->judul_kegiatan); ?></div>
                                <?php if($p->deskripsi): ?>
                                    <div class="text-xs text-muted mt-0.5"><?php echo e(Str::limit($p->deskripsi, 80)); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4 text-sm text-muted whitespace-nowrap">
                                <?php echo e($p->tanggal_diajukan->format('d M Y')); ?>

                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-col gap-1">
                                    <?php if($p->file_surat_permohonan): ?>
                                        <a href="<?php echo e(asset('storage/' . $p->file_surat_permohonan)); ?>" target="_blank"
                                            class="text-primary text-xs hover:underline">📄 Surat Permohonan</a>
                                    <?php endif; ?>
                                    <?php if($p->file_undangan): ?>
                                        <a href="<?php echo e(asset('storage/' . $p->file_undangan)); ?>" target="_blank"
                                            class="text-primary text-xs hover:underline">📄 Surat Undangan</a>
                                    <?php endif; ?>
                                    <?php if(!$p->file_surat_permohonan && !$p->file_undangan): ?>
                                        <span class="text-xs text-muted italic">Tidak ada dokumen</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium <?php echo e($p->status_color); ?>">
                                    <?php echo e($p->status_label); ?>

                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <a href="<?php echo e(route('admin.pengajuan-pembinaan.show', $p)); ?>"
                                    class="text-primary text-sm hover:underline font-medium">
                                    Detail & Balas →
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-muted">
                                Belum ada pengajuan pembinaan dari desa.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($pengajuans->hasPages()): ?>
            <div class="px-5 py-4 border-t border-border"><?php echo e($pengajuans->links()); ?></div>
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
<?php /**PATH C:\laragon\www\sidmini\resources\views/admin/pengajuan-pembinaan/index.blade.php ENDPATH**/ ?>