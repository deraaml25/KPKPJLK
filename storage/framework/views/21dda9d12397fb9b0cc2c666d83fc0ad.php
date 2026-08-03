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
    <?php $__env->startSection('title', 'Verifikasi Usulan SK Kades'); ?>

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <a href="<?php echo e(route('admin.pjkades.index')); ?>"
            class="inline-flex items-center text-sm font-medium text-muted hover:text-ink">
            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Evaluasi SK Kades
        </a>
        <div class="flex items-center gap-3">
            <!-- Badge status dihilangkan atas permintaan user -->
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-5 p-4 rounded-card bg-green-50 border border-green-200 text-green-800 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-5 p-4 rounded-card bg-red-50 border border-red-200 text-red-800 flex items-start gap-3 shadow-sm">
            <span class="font-medium"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <div class="<?php echo e($pjkades->metode !== 'offline' ? 'grid grid-cols-1 lg:grid-cols-12 gap-6' : 'max-w-4xl mx-auto'); ?> h-[80vh]">

        <?php if($pjkades->metode !== 'offline'): ?>
        
        <div class="lg:col-span-7 bg-surface rounded-card border border-border shadow-sm flex flex-col overflow-hidden h-full">
            <div class="px-4 py-3 border-b border-border bg-gray-50 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-ink">Berkas Keseluruhan Persyaratan</p>
                    <p class="text-xs text-muted">SK Kades (<?php echo e($pjkades->kategori === 'plt_kades' ? 'Plt Kades' : 'Pj Kades'); ?>) — <?php echo e($pjkades->desa->nama_desa); ?></p>
                </div>
                <?php if($pjkades->berkas_zip): ?>
                    <div class="flex gap-2">
                        <?php if(preg_match('/\.(pdf|jpe?g|png)$/i', $pjkades->berkas_zip)): ?>
                            <button type="button" onclick="previewFile('<?php echo e(Storage::disk('public')->url($pjkades->berkas_zip)); ?>')"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-xs font-medium rounded hover:bg-blue-200 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Lihat
                            </button>
                        <?php endif; ?>
                        <a href="<?php echo e(Storage::disk('public')->url($pjkades->berkas_zip)); ?>" target="_blank"
                            class="inline-flex items-center px-3 py-1.5 bg-primary text-white text-xs font-medium rounded hover:bg-primary-light transition-colors flex-shrink-0">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <div class="flex-1 bg-gray-200 relative p-2" id="pdf-container">
                <div id="pdf-empty-state" class="absolute inset-0 flex flex-col items-center justify-center text-muted">
                    <svg class="w-16 h-16 mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <?php if($pjkades->berkas_zip && preg_match('/\.zip|\.rar$/i', $pjkades->berkas_zip)): ?>
                        <p class="font-medium text-center px-4">Berkas gabungan berupa file ZIP/RAR.<br>Silakan klik tombol "Unduh" di sudut kanan atas untuk melihat isinya.</p>
                    <?php else: ?>
                        <p class="font-medium text-center px-4">Pilih dokumen pada tabel di kanan untuk memuat pratinjau</p>
                    <?php endif; ?>
                </div>
                <iframe id="pdf-iframe" src="" class="w-full h-full rounded shadow-sm border border-gray-300 hidden" frameborder="0"></iframe>
                <img id="img-preview" src="" class="w-full h-full object-contain rounded shadow-sm border border-gray-300 hidden">
            </div>
        </div>
        <?php endif; ?>

        
        <div class="<?php echo e($pjkades->metode !== 'offline' ? 'lg:col-span-5' : 'w-full'); ?> flex flex-col gap-6 h-full overflow-y-auto pr-2 custom-scrollbar">

            
            <div class="bg-primary text-white rounded-card shadow-sm p-4 flex-shrink-0">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-[10px] font-mono text-primary-soft"><?php echo e($pjkades->no_registrasi ?? ('SKK-' . $pjkades->id)); ?></p>
                        <h2 class="text-lg font-display font-bold leading-tight"><?php echo e($pjkades->desa->nama_desa); ?> (Kec. <?php echo e($pjkades->desa->kecamatan->nama_kecamatan ?? '-'); ?>)</h2>
                    </div>
                </div>
                <div class="text-xs border-t border-white/20 pt-2 flex flex-col gap-1">
                    <p><span class="text-primary-soft inline-block w-16">Layanan:</span>
                        Usulan SK Kades (<?php echo e($pjkades->kategori === 'plt_kades' ? 'Plt / Pelaksana Tugas' : 'Pj / Penjabat'); ?>)</p>
                    <p><span class="text-primary-soft inline-block w-16">Alasan:</span>
                        <?php echo e($pjkades->alasan_nama ?? ($pjkades->alasanPemberhentian->nama ?? '-')); ?></p>

                    <p class="text-primary-soft font-medium mt-1">Profil Calon:</p>
                    <div class="bg-black/10 rounded p-1.5 border border-white/5 text-[10px]">
                        <?php if($pjkades->kategori === 'plt_kades'): ?>
                            <span class="font-bold block"><?php echo e($pjkades->nama_plt ?? '-'); ?></span>
                            <span class="opacity-80 block"><?php echo e($pjkades->nip_plt ?? '-'); ?> - <?php echo e($pjkades->pangkat_plt ?? 'Sekretaris Desa'); ?></span>
                        <?php else: ?>
                            <span class="font-bold block"><?php echo e($pjkades->nama_pns ?? '-'); ?></span>
                            <span class="opacity-80 block"><?php echo e($pjkades->nip ?? '-'); ?> - <?php echo e($pjkades->pangkat ?? '-'); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="bg-surface rounded-card shadow-sm border border-border overflow-hidden flex-shrink-0">
                <div class="px-5 py-4 border-b border-border bg-gray-50 flex items-center justify-between">
                    <h3 class="font-display font-semibold text-ink">Verifikasi Syarat</h3>
                    <a href="<?php echo e(route('admin.pjkades.print-syarat', $pjkades->id)); ?>" target="_blank" class="inline-flex items-center text-xs px-2 py-1 bg-white border border-gray-300 rounded font-medium text-ink hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print Checklist
                    </a>
                </div>

                <form action="<?php echo e(route('admin.pjkades.verify-bulk', $pjkades->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="divide-y divide-border">
                        <?php $__currentLoopData = $pjkades->checklists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-4 border-l-4 transition-colors <?php echo e($item->status_verifikasi == 'valid' ? 'border-green-500 bg-green-50/40' : ($item->status_verifikasi == 'tidak_sesuai' ? 'border-red-500 bg-red-50/40' : 'border-amber-400 bg-amber-50/30')); ?>" id="row-<?php echo e($item->id); ?>">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white text-xs font-bold text-ink border border-border shadow-sm flex-shrink-0"><?php echo e($index + 1); ?></span>
                                    
                                    <div class="flex-1 flex flex-col sm:flex-row sm:items-center gap-2 justify-between">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-semibold text-ink leading-tight">
                                                <?php echo e($item->nama_dokumen); ?>

                                            </p>

                                            <?php if($item->file_path): ?>
                                                <button type="button"
                                                    onclick="previewFile('<?php echo e(asset('storage/' . $item->file_path)); ?>')"
                                                    class="ml-2 inline-flex items-center text-xs px-2 py-1 bg-white hover:bg-gray-50 border border-gray-300 rounded font-medium text-ink transition-colors shadow-sm">
                                                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Lihat Dokumen
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <div class="flex-shrink-0 ml-auto sm:ml-4">
                                            <input type="checkbox" name="status[<?php echo e($item->id); ?>]" value="valid" 
                                                   class="verify-checkbox w-6 h-6 text-primary focus:ring-primary border-gray-300 rounded shadow-sm cursor-pointer transition-colors" 
                                                   <?php echo e($item->status_verifikasi == 'valid' ? 'checked' : ''); ?>

                                                   onchange="toggleRowColor(this, 'row-<?php echo e($item->id); ?>')"
                                                   title="Tandai Sesuai">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="p-4 bg-gray-50 border-t border-border flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:bg-primary-dark active:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                            <i class="fas fa-save mr-2"></i> Simpan Centang
                        </button>
                    </div>
                </form>
            </div>

            
            <div class="bg-surface rounded-card shadow-sm border border-border p-5 relative">
                <form action="<?php echo e(route('admin.pjkades.update-catatan', $pjkades->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <label class="block text-sm font-display font-semibold text-ink mb-2">Evaluasi Syarat Formil</label>
                    <p class="text-[11px] text-muted mb-3">Tuliskan jika ada syarat yang kurang (khususnya untuk metode offline) atau perbaikan yang harus dilakukan desa.</p>
                    
                    <textarea name="catatan_admin" rows="3" placeholder="Tulis catatan jika ada berkas yang kurang/salah..." 
                              class="w-full text-sm rounded-lg border-border focus:ring-primary focus:border-primary placeholder-gray-400"><?php echo e(old('catatan_admin', $pjkades->catatan_admin)); ?></textarea>
                              
                    <div class="mt-3 text-right">
                        <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white text-xs font-medium rounded shadow-sm transition-colors">
                            Kirim Catatan / Simpan
                        </button>
                    </div>
                </form>
            </div>

            
            
            <div class="bg-surface rounded-card shadow-sm border border-border flex flex-col mb-10">
                <div class="p-5">
                    <h3 class="text-base font-display font-semibold text-ink mb-1">Status Proses</h3>
                    <p class="text-xs text-muted mb-4 pb-4 border-b border-border">Pantau tahapan perjalanan usulan SK Kades.</p>
                    <?php if (isset($component)) { $__componentOriginalf1a79c4c7d0a9771787f75524d3bd7ea = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf1a79c4c7d0a9771787f75524d3bd7ea = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pjkades-tracker','data' => ['posisiAktif' => $pjkades->posisi_surat ?? 'Berkas Diterima','status' => $pjkades->status,'pjkades' => $pjkades]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pjkades-tracker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['posisiAktif' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pjkades->posisi_surat ?? 'Berkas Diterima'),'status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pjkades->status),'pjkades' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pjkades)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf1a79c4c7d0a9771787f75524d3bd7ea)): ?>
<?php $attributes = $__attributesOriginalf1a79c4c7d0a9771787f75524d3bd7ea; ?>
<?php unset($__attributesOriginalf1a79c4c7d0a9771787f75524d3bd7ea); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf1a79c4c7d0a9771787f75524d3bd7ea)): ?>
<?php $component = $__componentOriginalf1a79c4c7d0a9771787f75524d3bd7ea; ?>
<?php unset($__componentOriginalf1a79c4c7d0a9771787f75524d3bd7ea); ?>
<?php endif; ?>
                </div>

                <div class="px-5 py-4 bg-gray-50 border-t border-border mt-auto rounded-b-card">
                    <h3 class="text-xs font-semibold text-muted mb-2">Tindak Lanjut Cepat</h3>
                    <div class="flex flex-col gap-2">
                        <?php
                            $posisiOptions = [
                                'Berkas Diterima',
                                'Verifikasi & Validasi Petugas',
                                'Penyusunan Draft Rekomendasi',
                                'Verifikasi & Validasi Kabid PDPD',
                                'Verifikasi & Validasi Sekretaris Dinas',
                                'Verifikasi & Validasi Kepala Dinas',
                                'Verifikasi & Validasi Kepala Bagian Hukum',
                                'Verifikasi & Validasi Asisten Pemerintahan & Kesra',
                                'Verifikasi & Validasi Sekda',
                                'Tanda Tangan Bupati',
                                'Penomoran TU Umum Setda',
                                'Sudah di Dinpermasdes',
                                'Sudah di Desa (Nama Penerima)'
                            ];
                            $currentIndex = array_search($pjkades->posisi_surat ?? 'Berkas Diterima', $posisiOptions);
                            if ($currentIndex === false) $currentIndex = 0;
                            $nextPosisi = isset($posisiOptions[$currentIndex + 1]) ? $posisiOptions[$currentIndex + 1] : null;
                        ?>

                        <?php if($nextPosisi === 'Sudah di Desa (Nama Penerima)'): ?>
                        <form action="<?php echo e(route('admin.pjkades.disposisi', $pjkades->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="posisi_surat" value="<?php echo e($nextPosisi); ?>">
                            <input type="hidden" name="status_baru" value="approved">
                            <button type="submit"
                                onclick="return confirm('Selesaikan dan setujui usulan ini?')"
                                class="w-full py-2 px-3 bg-green-600 rounded text-white text-xs font-medium hover:bg-green-700 transition-colors flex items-center justify-center shadow-sm">
                                Selesai & Setujui Usulan
                            </button>
                        </form>
                        <?php elseif($nextPosisi): ?>
                        <form action="<?php echo e(route('admin.pjkades.disposisi', $pjkades->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="posisi_surat" value="<?php echo e($nextPosisi); ?>">
                            <button type="submit"
                                class="w-full py-2 px-3 bg-primary rounded text-white text-xs font-medium hover:bg-primary-light transition-colors flex items-center justify-center shadow-sm">
                                Lanjutkan ke : <?php echo e($nextPosisi); ?>

                            </button>
                        </form>
                        <?php elseif($pjkades->status !== 'approved'): ?>
                        <form action="<?php echo e(route('admin.pjkades.disposisi', $pjkades->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="posisi_surat" value="<?php echo e($pjkades->posisi_surat); ?>">
                            <input type="hidden" name="status_baru" value="approved">
                            <button type="submit"
                                onclick="return confirm('Selesaikan dan setujui usulan ini?')"
                                class="w-full py-2 px-3 bg-green-600 rounded text-white text-xs font-medium hover:bg-green-700 transition-colors flex items-center justify-center shadow-sm">
                                Selesai & Setujui Usulan
                            </button>
                        </form>
                        <?php endif; ?>

                        <form action="<?php echo e(route('admin.pjkades.disposisi', $pjkades->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="posisi_surat" value="Berkas Diterima">
                            <input type="hidden" name="status_baru" value="direvisi">
                            <button type="submit"
                                class="w-full py-2 px-3 bg-white border border-red-300 text-red-600 rounded text-xs font-medium hover:bg-red-50 transition-colors flex items-center justify-center shadow-sm" title="Kembalikan ke awal (Butuh Revisi)">
                                Revisi
                            </button>
                        </form>
                    </div>
                </div>
            </div>



        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            function previewFile(url) {
                document.getElementById('pdf-empty-state').classList.add('hidden');
                const iframe = document.getElementById('pdf-iframe');
                const img = document.getElementById('img-preview');
                
                iframe.classList.add('hidden');
                img.classList.add('hidden');

                if (url.toLowerCase().includes('.pdf')) {
                    iframe.src = url;
                    iframe.classList.remove('hidden');
                } else {
                    img.src = url;
                    img.classList.remove('hidden');
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Auto preview berkas zip/pdf if available
                <?php if($pjkades->berkas_zip && preg_match('/\.(pdf|jpe?g|png)$/i', $pjkades->berkas_zip)): ?>
                    setTimeout(() => {
                        previewFile('<?php echo e(Storage::disk("public")->url($pjkades->berkas_zip)); ?>');
                    }, 500);
                <?php endif; ?>
            });

            function toggleRowColor(checkbox, rowId) {
                const row = document.getElementById(rowId);
                if (checkbox.checked) {
                    row.classList.remove('border-red-500', 'bg-red-50/40', 'border-amber-400', 'bg-amber-50/30');
                    row.classList.add('border-green-500', 'bg-green-50/40');
                } else {
                    row.classList.remove('border-green-500', 'bg-green-50/40', 'border-red-500', 'bg-red-50/40');
                    row.classList.add('border-amber-400', 'bg-amber-50/30');
                }
            }
        </script>
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 6px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background-color: #cbd5e1;
                border-radius: 20px;
            }
        </style>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\sidmini\resources\views/admin/pjkades/show.blade.php ENDPATH**/ ?>