<x-app-layout>
    @section('title', 'Profil Saya')
    @section('page-kicker', 'Pengaturan Akun')

    <div class="py-4 space-y-6">
        <div class="bg-white shadow-[0_2px_10px_rgba(0,0,0,0.02)] border border-slate-200/60 rounded-xl">
            <div class="p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="bg-white shadow-[0_2px_10px_rgba(0,0,0,0.02)] border border-slate-200/60 rounded-xl">
            <div class="p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
