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
    <?php $__env->startSection('title', 'Arsip Dokumen Digital'); ?>

    <div class="mb-4">
        <!-- Breadcrumb -->
        <div class="text-sm mb-6 flex items-center gap-1.5">
            <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($idx > 0): ?>
                    <span class="text-muted">/</span>
                <?php endif; ?>
                <a href="<?php echo e(route('admin.drive.index', ['path' => $crumb['path']])); ?>" 
                   class="<?php echo e($loop->last ? 'text-ink font-medium' : 'text-muted hover:text-primary transition-colors'); ?>">
                    <?php echo e($crumb['label']); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3 mb-6">
            <form action="<?php echo e(route('admin.drive.upload')); ?>" method="POST" enctype="multipart/form-data" class="inline-flex">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="path" value="<?php echo e(request('path', 'dokumen')); ?>">
                <input type="file" name="file" id="file_upload" class="hidden" onchange="this.form.submit()">
                <button type="button" onclick="document.getElementById('file_upload').click()" class="inline-flex items-center px-4 py-2.5 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                    Upload
                </button>
            </form>
            <?php
                $folderName = basename(request('path', 'dokumen'));
                $label = $folderName === 'dokumen' ? 'Semua_Arsip' : 'Arsip_' . ucwords(str_replace('_', ' ', $folderName));
            ?>
            <a href="<?php echo e(route('admin.drive.download-zip', ['path' => request('path', 'dokumen'), 'label' => $label])); ?>" class="inline-flex items-center px-4 py-2.5 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download All
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if(session('success')): ?>
        <div class="mb-6 p-4 rounded-card bg-green-50 border border-green-200 text-green-700 flex items-start">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <div><?php echo e(session('success')); ?></div>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-6 p-4 rounded-card bg-red-50 border border-red-200 text-red-700 flex items-start">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div><?php echo e(session('error')); ?></div>
        </div>
    <?php endif; ?>

    <div>
        <?php if(empty($folders) && empty($files)): ?>
            <div class="flex flex-col items-center justify-center text-muted py-12">
                <svg class="w-16 h-16 mb-4 text-border" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                <p class="font-medium text-lg text-ink">Folder ini kosong</p>
                <p class="text-sm mt-1">Belum ada dokumen atau sub-folder di dalam direktori ini.</p>
            </div>
        <?php else: ?>
            <!-- Folders -->
            <?php if(count($folders) > 0): ?>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">FOLDER</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-7 gap-4 mb-10">
                    <?php
                        $colors = [
                            'text-blue-400',
                            'text-orange-400',
                            'text-blue-400',
                            'text-orange-400',
                            'text-green-400',
                            'text-orange-400',
                            'text-purple-400'
                        ];
                    ?>
                    <?php $__currentLoopData = $folders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $colorClass = $colors[$idx % count($colors)];
                            $isActive = (request('path') == $folder['path'] || ($idx === 0 && !request('path')));
                            
                            $bgClass = $isActive ? 'bg-blue-50 border-blue-200' : 'bg-white border-slate-200';
                        ?>
                        <a href="<?php echo e(route('admin.drive.index', ['path' => $folder['path']])); ?>" class="block border rounded-2xl p-5 hover:shadow-sm transition-all <?php echo e($bgClass); ?> min-h-[140px] flex flex-col justify-between">
                            <div class="mb-4">
                                <svg class="w-12 h-12 <?php echo e($colorClass); ?>" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M10 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20H20C21.1046 20 22 19.1046 22 18V8C22 6.89543 21.1046 6 20 6H12L10 4Z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-[15px] font-semibold text-slate-800 truncate" title="<?php echo e($folder['name']); ?>"><?php echo e($folder['name']); ?></h4>
                                <p class="text-[11px] text-slate-500 mt-1"><?php echo e($folder['count']); ?> item</p>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <!-- Files -->
            <?php if(count($files) > 0): ?>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">FILE</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                        ?>
                        <a href="<?php echo e($file['url']); ?>" target="_blank" class="block bg-white border border-slate-100 rounded-[20px] p-4 hover:shadow-sm transition-all flex flex-col justify-between">
                            <div class="mb-4 flex items-center justify-center h-16 bg-slate-50 rounded-lg overflow-hidden relative">
                                <?php if($isImg): ?>
                                    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('<?php echo e($file['url']); ?>')"></div>
                                <?php else: ?>
                                    <svg class="w-8 h-8 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h4 class="text-[13px] font-medium text-ink truncate" title="<?php echo e($file['name']); ?>"><?php echo e($file['name']); ?></h4>
                                <p class="text-[10px] text-slate-400 mt-1"><?php echo e(round($file['size'] / 1024)); ?> KB</p>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
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
<?php /**PATH C:\laragon\www\sidmini\resources\views/admin/drive/index.blade.php ENDPATH**/ ?>