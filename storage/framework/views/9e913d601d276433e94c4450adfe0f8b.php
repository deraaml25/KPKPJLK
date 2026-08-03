<?php
    $navs = [
        ['route' => 'desa.dashboard', 'icon' => 'grid_view', 'text' => 'Dashboard', 'active' => request()->routeIs('desa.dashboard')],
        ['route' => 'desa.regulasi.index', 'icon' => 'description', 'text' => 'Draft Regulasi', 'active' => request()->routeIs('desa.regulasi.*')],
        ['route' => 'desa.pengajuan-pembinaan.index', 'icon' => 'history_edu', 'text' => 'Pembinaan', 'active' => request()->routeIs('desa.pengajuan-pembinaan.*') || request()->routeIs('desa.bimtek-informasi.*')],
        ['route' => 'desa.ajuan.index', 'icon' => 'approval', 'text' => 'e-Rekomendasi', 'active' => request()->routeIs('desa.ajuan.*')],
        ['route' => 'desa.pjkades.index', 'icon' => 'admin_panel_settings', 'text' => 'SK Kades', 'active' => request()->routeIs('desa.pjkades.*')],
        ['route' => 'desa.rencana-p3d.index', 'icon' => 'assignment', 'text' => 'Rencana P3D', 'active' => request()->routeIs('desa.rencana-p3d.*')],
        // ['route' => 'desa.drive.index', 'icon' => 'cloud_circle', 'text' => 'Arsip Dokumen', 'active' => request()->routeIs('desa.drive.*')],


        ['route' => 'desa.perangkat.index', 'icon' => 'badge', 'text' => 'Data Kepala dan Perangkat Desa', 'active' => request()->routeIs('desa.perangkat.*')],
        ['route' => 'desa.bpd.index', 'icon' => 'groups', 'text' => 'Data BPD', 'active' => request()->routeIs('desa.bpd.*')],
        ['route' => 'desa.ajuan-bpd.index', 'icon' => 'post_add', 'text' => 'Ajuan BPD', 'active' => request()->routeIs('desa.ajuan-bpd.*')],

    ];
?>

<?php $__currentLoopData = $navs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nav): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(!isset($nav['route'])): ?> <?php continue; ?> <?php endif; ?>
    
    <?php if($nav['active']): ?>
        <a href="<?php echo e(route($nav['route'])); ?>" class="group flex items-center h-[44px] w-full relative">
            <!-- WHITE BACKGROUND CURVE ACROSS BOTH COLUMNS -->
            <div class="active-nav-curve absolute inset-y-0 right-0 z-0" style="left: 10px;"></div>

            <div class="w-[60px] flex-shrink-0 flex items-center justify-center text-[#1A2E4B] relative z-10">
                <span class="material-symbols-outlined text-[20px]"><?php echo e($nav['icon']); ?></span>
            </div>
            <div class="flex-grow h-full flex items-center relative z-10 pr-3">
                <span class="font-bold text-[13px] text-[#1A2E4B] pl-4"><?php echo e($nav['text']); ?></span>
            </div>
        </a>
    <?php else: ?>
        <a href="<?php echo e(route($nav['route'])); ?>" class="group flex items-center h-[44px] w-full relative hover:bg-white/5 transition-colors rounded-xl">
            <div class="w-[60px] flex-shrink-0 flex items-center justify-center text-white transition-colors relative z-10">
                <span class="material-symbols-outlined text-[20px]"><?php echo e($nav['icon']); ?></span>
            </div>
            <div class="flex-grow h-full flex items-center pl-4 transition-all duration-300 group-hover:pl-6">
                <span class="font-semibold text-[13px] text-white/80 group-hover:text-white transition-colors">
                    <?php echo e($nav['text']); ?>

                </span>
            </div>
        </a>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\laragon\www\sidmini\resources\views/layouts/partials/desa-nav.blade.php ENDPATH**/ ?>