<x-app-layout>
    @section('title', 'Detail Pilkades')

    {{-- This view is deprecated for Desa. Redirect them back to index. --}}
    <script>window.location.href = "{{ route('desa.pilkades.index') }}";</script>
</x-app-layout>