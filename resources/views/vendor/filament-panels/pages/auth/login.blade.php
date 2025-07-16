<x-filament-panels::page.simple>
    <x-slot name="heading">
        <div class="flex flex-col items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Admin Pelayanan NCAGE" class="h-24 mb-4" />
            {{-- <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Admin Pelayanan NCAGE
            </h1> --}}
            <p class="text-lg mt-2">Sign in</p>
        </div>
    </x-slot>

    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>
