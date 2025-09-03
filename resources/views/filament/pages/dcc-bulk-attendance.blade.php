<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Form Section -->
        <div class="fi-section-content-ctn">
            {{ $this->form }}
        </div>

        <!-- G12 Leader Team Members Section -->
        @if($this->selectedNetwork && $this->attendees->count() > 0)
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-header flex items-center gap-x-3 overflow-hidden px-6 py-4">
                    <div class="grid flex-1 gap-y-1">
                        <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            @php
                                $g12Leader = \App\Models\G12Leader::with('churchAttender')->find($this->selectedNetwork);
                                $leaderName = $g12Leader && $g12Leader->churchAttender ? $g12Leader->churchAttender->full_name : 'Unknown Leader';
                            @endphp
                            {{ $leaderName }}'s Team Members
                        </h3>
                        <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                            Click on member cards to toggle attendance status. Blue = Completed DCC, Green = Present, Red = Absent
                        </p>
                    </div>
                    <div class="flex gap-x-2">
                        <x-filament::button
                            color="success"
                            size="sm"
                            wire:click="markAllPresent"
                        >
                            Mark All Present
                        </x-filament::button>
                        <x-filament::button
                            color="danger"
                            size="sm"
                            wire:click="markAllAbsent"
                        >
                            Mark All Absent
                        </x-filament::button>
                    </div>
                </div>
                
                <div class="fi-section-content p-6">
                    <!-- Statistics -->
                    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-4">
                        <div class="rounded-lg bg-gray-50 p-4 text-center dark:bg-gray-800">
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $this->attendees->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total Members</div>
                        </div>
                        <div class="rounded-lg bg-green-50 p-4 text-center dark:bg-green-900/20">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ collect($this->attendanceData)->where('present', true)->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Present</div>
                        </div>
                        <div class="rounded-lg bg-red-50 p-4 text-center dark:bg-red-900/20">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                {{ collect($this->attendanceData)->where('present', false)->count() }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Absent</div>
                        </div>
                        <div class="rounded-lg bg-blue-50 p-4 text-center dark:bg-blue-900/20">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                {{ collect($this->attendanceData)->where('present', true)->count() > 0 ? round((collect($this->attendanceData)->where('present', true)->count() / $this->attendees->count()) * 100, 1) : 0 }}%
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Attendance Rate</div>
                        </div>
                    </div>

                    <!-- Members Grid -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($this->attendees as $attendee)
                            @php
                                $isPresent = $this->attendanceData[$attendee->id]['present'] ?? true;
                                $serviceNumber = $this->attendanceData[$attendee->id]['service_number'] ?? 1;
                                $isCompleted = $this->attendanceData[$attendee->id]['is_completed'] ?? false;
                                $completedServices = $attendee->sundayServiceCompletions()->count();
                            @endphp
                            
                            <div 
                                wire:click="toggleAttendance({{ $attendee->id }})"
                                class="cursor-pointer rounded-lg border-2 p-4 transition-all hover:shadow-md
                                    @if($isCompleted)
                                        border-blue-200 bg-blue-50 dark:border-blue-700 dark:bg-blue-900/20
                                    @elseif($isPresent)
                                        border-green-200 bg-green-50 dark:border-green-700 dark:bg-green-900/20
                                    @else
                                        border-red-200 bg-red-50 dark:border-red-700 dark:bg-red-900/20
                                    @endif"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            @if($attendee->gender === 'Male')
                                                <x-heroicon-s-user class="h-4 w-4 text-blue-500" />
                                            @else
                                                <x-heroicon-s-user class="h-4 w-4 text-pink-500" />
                                            @endif
                                            <h4 class="font-medium text-gray-900 dark:text-white">
                                                {{ $attendee->first_name }} {{ $attendee->last_name }}
                                            </h4>
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                {{ $completedServices >= 4 
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' 
                                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' 
                                                }}">
                                                DCC: {{ $completedServices }}/4
                                            </span>
                                            
                                            @if($isCompleted)
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                                    Completed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                    {{ $isPresent 
                                                        ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' 
                                                        : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' 
                                                    }}">
                                                    {{ $isPresent ? 'Present' : 'Absent' }}
                                                </span>
                                            @endif
                                        </div>

                                        @if(!$isCompleted)
                                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                Next Service: #{{ $serviceNumber }}
                                            </div>
                                        @else
                                            <div class="mt-2 text-xs text-blue-600 dark:text-blue-400">
                                                All DCC services completed ✓
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="ml-4">
                                        @if($isCompleted)
                                            <x-heroicon-s-check-badge class="h-6 w-6 text-blue-500" />
                                        @elseif($isPresent)
                                            <x-heroicon-s-check-circle class="h-6 w-6 text-green-500" />
                                        @else
                                            <x-heroicon-s-x-circle class="h-6 w-6 text-red-500" />
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @elseif($this->selectedNetwork && $this->attendees->count() === 0)
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="p-6 text-center">
                    <x-heroicon-o-users class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No Team Members Found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        All team members under this G12 leader have completed their 4 DCC services or no team members are assigned.
                    </p>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
