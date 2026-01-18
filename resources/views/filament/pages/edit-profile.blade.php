<x-filament-panels::page>
    <form wire:submit="save">
        
        {{ $this->form }}
        
        <!-- 
            NUCLEAR OPTION: 
            We use style="margin-top: 50px;" to FORCE the space.
            We also added a red border to make sure you can see this box.
        -->
        <div style="margin-top: 50px;padding: 20px;">
            <p>Current Path: {{ $data['photo'] ?? 'No Path' }}</p>
<p>Full URL: {{ \Illuminate\Support\Facades\Storage::disk('public')->url($data['photo'] ?? '') }}</p>

            <div class="flex justify-start gap-x-4">
                <x-filament::button type="submit">
                    Save Changes
                </x-filament::button>

                <x-filament::button color="gray" tag="a" href="/admin">
                    Cancel
                </x-filament::button>
            </div>

        </div>

    </form>
</x-filament-panels::page>