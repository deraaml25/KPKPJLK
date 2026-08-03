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
    <?php $__env->startSection('title', 'Draft Regulasi'); ?>

    <!-- List -->
    <div class="bg-white rounded-card shadow-sm border border-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Desa
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">No.
                            Regulasi</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Judul /
                            Tipe</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">Tanggal
                            Masuk</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-muted tracking-wider uppercase">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-muted tracking-wider uppercase">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-border">
                    <?php $__empty_1 = true; $__currentLoopData = $regulasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-ink font-semibold">
                                <?php echo e($reg->desa->nama_desa); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-ink font-medium">
                                <?php echo e($reg->no_regulasi ?? '-'); ?></td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-ink font-display"><?php echo e($reg->judul); ?></div>
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-primary-soft text-primary mt-1 capitalize"><?php echo e($reg->tipe); ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-muted">
                                <?php echo e($reg->tgl_diajukan ? $reg->tgl_diajukan->format('d M Y') : '-'); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($reg->status === 'disahkan'): ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disahkan</span>
                                <?php elseif($reg->status === 'perlu_revisi'): ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Perlu
                                        Revisi</span>
                                <?php elseif($reg->status === 'disetujui'): ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Menunggu Desa Sahkan</span>
                                <?php else: ?>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Menunggu
                                        Verifikasi</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="<?php echo e(route('admin.regulasi.show', $reg)); ?>"
                                    class="text-primary hover:text-primary-light bg-primary-soft px-3 py-1.5 rounded-md transition-colors">Tinjau</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-muted">Belum ada usulan produk hukum
                                masuk.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($regulasis->hasPages()): ?>
            <div class="px-6 py-4 border-t border-border">
                <?php echo e($regulasis->links()); ?>

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
<?php endif; ?><?php /**PATH C:\laragon\www\sidmini\resources\views/admin/regulasi/index.blade.php ENDPATH**/ ?>