<x-app-layout>
    <div class="p-container-padding space-y-12 pb-24">
        
        <!-- Greeting Section -->
        <section class="opacity-0 translate-y-12 blur-md animate-[fade-up_800ms_cubic-bezier(0.32,0.72,0,1)_forwards]">
            <h2 class="font-headline-sm text-headline-sm text-on-background tracking-tight">Halo, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
            <span class="inline-block mt-2 bg-surface-variant text-on-surface-variant font-label-sm px-3 py-1 rounded-full uppercase tracking-[0.1em]">
                {{ auth()->user()->roles->pluck('name')->join(', ') ?: 'Anggota' }}
            </span>
        </section>

        <!-- Overall Progress Card (Double-Bezel Architecture) -->
        <section class="opacity-0 translate-y-12 blur-md animate-[fade-up_800ms_cubic-bezier(0.32,0.72,0,1)_100ms_forwards]">
            <div class="bg-black/[0.03] dark:bg-white/[0.02] p-1.5 rounded-[2rem] ring-1 ring-black/5 dark:ring-white/10">
                <div class="bg-surface rounded-[1.625rem] p-6 shadow-[inset_0_1px_1px_rgba(255,255,255,1),0_2px_12px_rgba(0,0,0,0.03)] dark:shadow-[inset_0_1px_1px_rgba(255,255,255,0.1)] flex flex-col items-center">
                    <div class="relative w-32 h-32 flex items-center justify-center">
                        <svg class="w-32 h-32 -rotate-90" viewBox="0 0 100 100">
                            <circle class="text-surface-variant stroke-current" cx="50" cy="50" fill="transparent" r="40" stroke-width="8"></circle>
                            <circle class="text-progress-medium stroke-current transition-all duration-1000 ease-spring" cx="50" cy="50" fill="transparent" r="40" stroke-dasharray="251.2" stroke-dashoffset="82.89" stroke-linecap="round" stroke-width="8"></circle>
                        </svg>
                        <span class="absolute font-headline-lg text-headline-lg text-on-surface font-bold tracking-tighter">67%</span>
                    </div>
                    <p class="mt-4 font-label-md text-secondary uppercase tracking-widest text-[10px]">Progress Keseluruhan</p>
                </div>
            </div>
        </section>

        <!-- Progress Per Divisi Grid -->
        <section class="opacity-0 translate-y-12 blur-md animate-[fade-up_800ms_cubic-bezier(0.32,0.72,0,1)_200ms_forwards]">
            <div class="grid grid-cols-2 gap-3">
                <!-- BPH -->
                <div class="bg-black/[0.02] p-1 rounded-2xl ring-1 ring-black/5">
                    <div class="bg-surface rounded-[0.875rem] p-4 shadow-sm">
                        <h3 class="font-label-md text-on-surface mb-3">BPH</h3>
                        <div class="w-full bg-surface-variant rounded-full h-1.5 mb-3 overflow-hidden">
                            <div class="bg-progress-high h-1.5 rounded-full" style="width: 80%"></div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-label-sm text-secondary font-bold">80%</span>
                            <span class="font-label-sm text-secondary">8/10 selesai</span>
                        </div>
                    </div>
                </div>

                <!-- Sie Acara -->
                <div class="bg-black/[0.02] p-1 rounded-2xl ring-1 ring-black/5">
                    <div class="bg-surface rounded-[0.875rem] p-4 shadow-sm">
                        <h3 class="font-label-md text-on-surface mb-3">Sie Acara</h3>
                        <div class="w-full bg-surface-variant rounded-full h-1.5 mb-3 overflow-hidden">
                            <div class="bg-progress-medium h-1.5 rounded-full" style="width: 55%"></div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-label-sm text-secondary font-bold">55%</span>
                            <span class="font-label-sm text-secondary">11/20 selesai</span>
                        </div>
                    </div>
                </div>

                <!-- Sie Perkap -->
                <div class="bg-black/[0.02] p-1 rounded-2xl ring-1 ring-black/5">
                    <div class="bg-surface rounded-[0.875rem] p-4 shadow-sm">
                        <h3 class="font-label-md text-on-surface mb-3">Sie Perkap</h3>
                        <div class="w-full bg-surface-variant rounded-full h-1.5 mb-3 overflow-hidden">
                            <div class="bg-progress-low h-1.5 rounded-full" style="width: 40%"></div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-label-sm text-secondary font-bold">40%</span>
                            <span class="font-label-sm text-secondary">6/15 selesai</span>
                        </div>
                    </div>
                </div>

                <!-- Sie Konsumsi -->
                <div class="bg-black/[0.02] p-1 rounded-2xl ring-1 ring-black/5">
                    <div class="bg-surface rounded-[0.875rem] p-4 shadow-sm">
                        <h3 class="font-label-md text-on-surface mb-3">Sie Konsumsi</h3>
                        <div class="w-full bg-surface-variant rounded-full h-1.5 mb-3 overflow-hidden">
                            <div class="bg-progress-medium h-1.5 rounded-full" style="width: 75%"></div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-label-sm text-secondary font-bold">75%</span>
                            <span class="font-label-sm text-secondary">9/12 selesai</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Livewire Tables Wrapped in Double-Bezel -->
        @if(auth()->user()->hasRole('bph') || auth()->user()->hasPermissionTo('admin_task.read') || auth()->user()->divisis->where('pivot.is_koordinator', true)->count() > 0)
        <section class="opacity-0 translate-y-12 blur-md animate-[fade-up_800ms_cubic-bezier(0.32,0.72,0,1)_300ms_forwards]">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-headline-md tracking-tight text-on-background">Administrasi & Tugas</h3>
            </div>
            
            <div class="bg-black/[0.03] p-1.5 rounded-[1.5rem] ring-1 ring-black/5">
                <div class="bg-surface rounded-[1.125rem] p-4 shadow-sm overflow-hidden">
                    <!-- PowerGrid Table -->
                    <div class="overflow-x-auto">
                        <livewire:admin-task-table />
                    </div>
                </div>
            </div>
        </section>
        @endif

        @if(auth()->user()->hasPermissionTo('event_task.read') || auth()->user()->hasRole('bph'))
        <section class="opacity-0 translate-y-12 blur-md animate-[fade-up_800ms_cubic-bezier(0.32,0.72,0,1)_400ms_forwards]">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-headline-md tracking-tight text-on-background">Checklist Acara</h3>
            </div>
            
            <div class="bg-black/[0.03] p-1.5 rounded-[1.5rem] ring-1 ring-black/5">
                <div class="bg-surface rounded-[1.125rem] p-4 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <livewire:event-task-table />
                    </div>
                </div>
            </div>
        </section>
        @endif

    </div>

    <style>
        /* Fade up animation keyframes for scroll interpolation feel */
        @keyframes fade-up {
            from {
                opacity: 0;
                transform: translateY(3rem);
                filter: blur(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
                filter: blur(0);
            }
        }
    </style>
</x-app-layout>
