<?php

use Livewire\Volt\Component;
use Carbon\Carbon;

new class extends Component {
    public int $year;
    public array $months = [];
    public array $days = [];
    public function mount() {
        $this->year = now()->year;

        // Days 1–31
        $this->days = range(1, 31);

        // Build months using Carbon
        $this->months = collect(range(1, 12))
            ->map(fn ($month) => Carbon::create($this->year, $month, 1))
            ->all();
    
    }

    public function nextYear()
    {
        $this->year++;
        $this->generateMonths();
    }

    public function prevYear()
    {
        $this->year--;
        $this->generateMonths();
    }

    private function generateMonths()
    {
        $this->months = collect(range(1, 12))
            ->map(fn ($month) => Carbon::create($this->year, $month, 1))
            ->all();
    }
}; ?>


<div class="p-6 lg:p-8 bg-white dark:text-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

    <h1 class="mt-2 text-2xl font-medium text-gray-900 dark:text-white">
        Calendar
    </h1>

    <p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">
        This is where you can interact with your calendar
    </p>

<div class="overflow-auto">
    <div class="flex items-center gap-2 mb-4">
        <button wire:click="prevYear">←</button>
        <span class="font-bold text-lg">{{ $year }}</span>
        <button wire:click="nextYear">→</button>
    </div>
    <div class="flex flex-row flex-wrap">
        <div class="flex items-center ml-4 gap-2 mb-4">
            <x-checkbox wire:model="period" />
            <x-label for="period" value="Show Period" />
        </div>
        <div class="flex items-center ml-4 gap-2 mb-4">
            <x-checkbox wire:model="fertility"  />
            <x-label for="fertility" value="Show Fertility" />
        </div>
        <div class="flex items-center ml-4 gap-2 mb-4">
            <x-checkbox wire:model="sex"  />
            <x-label for="sex" value="Show Sexual Activity" />
        </div>
        <div class="flex items-center ml-4 gap-2 mb-4">
            <x-checkbox wire:model="orgasms" />
            <x-label for="orgasms" value="Show Orgasms" />
        </div>
        <div class="flex items-center ml-4 gap-2 mb-4">
            <x-checkbox wire:model="medication"/>
            <x-label for="medication" value="Show Medication" />
        </div>
        <div class="flex items-center ml-4 gap-2 mb-4">
            <x-checkbox wire:model="pregnancy" />
            <x-label for="pregnancy" value="Show Pregnancy" />
        </div>
        <div class="flex items-center ml-4 gap-2 mb-4">
            <x-checkbox wire:model="showNone" />
            <x-label for="showNone" value="Show None" />
        </div>
    </div>


    <table class="table-auto w-full text-sm">
        <thead>
            <tr>
                <th class="p-2">Day</th>

                @foreach ($months as $month)
                    <th class="p-2 text-center">
                        {{ $month->format('M') }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach ($days as $day)
                <tr>
                    <td class="p-2 font-bold text-center">{{ $day }}</td>

                    @foreach ($months as $month)
                        <td class="text-center align-middle">
                            @if ($day <= $month->daysInMonth)
                                <div class="mx-auto w-4 h-4 bg-green-600 rounded-full"></div>
                                <!-- bg-blue-500 bg-red-800 bg-purple-800 -->
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    
</div>