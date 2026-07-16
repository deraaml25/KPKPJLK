<x-app-layout>
    @section('title', 'Drive Dokumen Digital')

    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-muted text-sm mt-1">Eksplorasi arsip dokumen persyaratan secara terstruktur.</p>
        </div>
        
        <a href="{{ route('admin.drive.download-zip', ['path' => request('path', 'dokumen')]) }}" class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-btn hover:bg-primary-light transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Unduh ZIP Folder Ini
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
        <div class="mb-6 p-4 rounded-card bg-red-50 border border-red-200 text-red-700 flex items-start">
            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="bg-surface rounded-card border border-border shadow-sm overflow-hidden flex flex-col h-[calc(100vh-220px)]">
        <!-- Breadcrumb & Top Bar -->
        <div class="px-6 py-4 border-b border-border bg-gray-50 flex items-center">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    @foreach($breadcrumbs as $idx => $crumb)
                        <li class="inline-flex items-center">
                            @if($idx > 0)
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                            @endif
                            <a href="{{ route('admin.drive.index', ['path' => $crumb['path']]) }}" class="inline-flex items-center text-sm font-medium {{ $loop->last ? 'text-ink font-semibold' : 'text-muted hover:text-primary' }}">
                                {{ $crumb['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>

        <!-- Drive Content (Grid) -->
        <div class="p-6 flex-1 overflow-y-auto bg-white">
            @if(empty($folders) && empty($files))
                <div class="h-full flex flex-col items-center justify-center text-muted">
                    <svg class="w-16 h-16 mb-4 text-border" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>
                    <p class="font-medium text-lg text-ink">Folder ini kosong</p>
                    <p class="text-sm mt-1">Belum ada dokumen atau sub-folder di dalam direktori ini.</p>
                </div>
            @else
                <!-- Folders -->
                @if(count($folders) > 0)
                    <h3 class="text-xs font-semibold text-muted uppercase tracking-wider mb-4">Folder</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
                        @foreach($folders as $folder)
                            <a href="{{ route('admin.drive.index', ['path' => $folder['path']]) }}" class="group bg-white border border-border rounded-xl p-4 hover:border-primary hover:shadow-md transition-all flex items-center gap-3">
                                <svg class="w-8 h-8 text-indigo-400 group-hover:text-primary transition-colors flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                <div class="overflow-hidden">
                                    <h4 class="text-sm font-medium text-ink truncate" title="{{ $folder['name'] }}">{{ $folder['name'] }}</h4>
                                    <p class="text-xs text-muted mt-0.5">{{ $folder['count'] }} item</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Files -->
                @if(count($files) > 0)
                    <h3 class="text-xs font-semibold text-muted uppercase tracking-wider mb-4">File Dokumen</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($files as $file)
                            @php
                                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                                $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                            @endphp
                            <a href="{{ $file['url'] }}" target="_blank" class="group bg-white border border-border rounded-xl overflow-hidden hover:border-primary hover:shadow-md transition-all flex flex-col h-40">
                                <!-- Preview Area -->
                                <div class="flex-1 bg-gray-50 flex items-center justify-center relative overflow-hidden border-b border-border">
                                    @if($isImg)
                                        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $file['url'] }}')"></div>
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                    @else
                                        <svg class="w-12 h-12 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                                    @endif
                                </div>
                                <!-- Info Area -->
                                <div class="p-3 bg-white">
                                    <h4 class="text-xs font-medium text-ink truncate mb-1" title="{{ $file['name'] }}">{{ $file['name'] }}</h4>
                                    <p class="text-[10px] text-muted">{{ round($file['size'] / 1024) }} KB</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
