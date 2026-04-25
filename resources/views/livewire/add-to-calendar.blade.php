<?php

use Livewire\Volt\Component;
use Carbon\Carbon;
use App\Models\CalendarDate;

new class extends Component {
    public int $year;
    public array $months = [];
    public array $days = [];
    public bool $period = false;
    public bool $fertility = false;
    public bool $sex = false;
    public bool $orgasms = false;
    public bool $medication = false;
    public bool $pregnancy = false;
    public bool $clearAll= false;
    public bool $showAll = false;

    public string $viewState = 'Click to Add or Remove';
    public $alreadyAdded = [];

    public function mount() {
        $this->alreadyAdded = \App\Models\CalendarDate::where('user_id', auth()->id())
            ->get()
            ->groupBy('date')
            ->map(function ($items) {
                return $items->pluck('type')
                    ->mapWithKeys(fn ($type) => [$type => true])
                    ->toArray();
            })
            ->toArray();
        $this->year = now()->year;
        $this->days = range(1, 31);
        $this->months = collect(range(1, 12))
            ->map(fn ($month) => Carbon::create($this->year, $month, 1))
            ->all();
    
    }

    public function toggle($date, $type) {
        $user = auth()->user();

        $existing = CalendarDate::where('user_id', $user->id)
        ->whereDate('date', $date)
        ->where('type', $type)
        ->first();

        if ($existing) {
            $existing->delete();
            $this->alreadyAdded[$date][$type] = false;

        } else {
            CalendarDate::create([
                'user_id' => $user->id,
                'date' => $date,
                'type' => $type,
            ]);
            $this->alreadyAdded[$date][$type] = true;
        }
    }

    public function updatedClearAll($value)
    {
        if ($value) {
            $this->period = false;
            $this->fertility = false;
            $this->sex = false;
            $this->orgasms = false;
            $this->medication = false;
            $this->pregnancy = false;
            $this->clearAll = false;
        }
    }

    public function updatedShowAll($value)
    {
        if ($value) {
            $this->period = true;
            $this->fertility = true;
            $this->sex = true;
            $this->orgasms = true;
            $this->medication = true;
            $this->pregnancy = true;
            $this->showAll = false;
        }
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


<div class="lg:p-8 bg-white dark:text-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/100 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
       {{ $viewState }}
    </h1>

    <div class="overflow-auto">
        <div class="flex items-center gap-2 mt-2 mb-2">
            <button wire:click="prevYear">←</button>
            <span class="font-bold text-lg">{{ $year }}</span>
            <button wire:click="nextYear">→</button>
        </div>
        <div class="flex flex-row flex-wrap gap-x-6 gap-y-2">
            <div class="flex items-center gap-2">
                <x-checkbox wire:model.live="period" id="period" />
                <x-label for="period" value="Add Period" />
                <div class="mx-auto w-4 h-4 rounded-full bg-red-800"></div>
            </div>
            <div class="flex items-center gap-2">
                <x-checkbox wire:model.live="fertility"  />
                <x-label for="fertility" value="Add Fertility" />
                <div class="mx-auto w-4 h-4 rounded-full bg-orange-600"></div>
            </div>
            <div class="flex items-center gap-2">
                <x-checkbox wire:model.live="sex"  />
                <x-label for="sex" value="Add Sexual Activity" />
                <div class="mx-auto w-4 h-4 rounded-full bg-purple-800"></div>
            </div>
            <div class="flex items-center gap-2">
                <x-checkbox wire:model.live="orgasms" />
                <x-label for="orgasms" value="Add Orgasms" />
                <div class="mx-auto w-4 h-4 rounded-full bg-indigo-500"></div>
            </div>
            <div class="flex items-center gap-2">
                <x-checkbox wire:model.live="medication"/>
                <x-label for="medication" value="Add Medication" />
                <div class="mx-auto w-4 h-4 rounded-full bg-green-600"></div>
            </div>
            <div class="flex items-center gap-2">
                <x-checkbox wire:model.live="pregnancy" />
                <x-label for="pregnancy" value="Add Pregnancy" />
                <div class="mx-auto w-4 h-4 rounded-full bg-blue-500"></div>
            </div>
            <div class="flex items-center gap-2">
                <x-checkbox wire:model.live="clearAll" />
                <x-label for="clearAll" value="Clear All" />
            </div>
            <div class="flex items-center gap-2">
                <x-checkbox wire:model.live="showAll" />
                <x-label for="showAll" value="Show All" />
            </div>
        </div>
    </div>


    <div class="overflow-auto" x-data="{ added: @entangle('alreadyAdded') }">
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
                        <td class="font-bold text-center">{{ $day }}</td>

                        @foreach ($months as $month)
                            <td class="text-center align-middle border-l border-r border-gray-700 dark:border-gray-200">
                                @if ($day <= $month->daysInMonth)
                                @php
                                    $date = Carbon::create($this->year, $month->month, $day)->format('Y-m-d');
                                @endphp
                                <div class="flex flex-row" x-data="{ period: false, fertility: false, sex: false, orgasms: false, medication: false, pregnancy: false }">
                                    @if($period) <div @click="added['{{ $date }}'] ??= {}; added['{{ $date }}']['period'] = !added['{{ $date }}']['period']; $wire.toggle('{{ $date }}', 'period')" x-bind:class="(added['{{ $date }}']?.period ?? false) ? 'bg-red-800' : 'bg-gray-200 dark:bg-gray-700'" class="mx-auto w-4 h-4 rounded-full"></div>@endif
                                    @if($fertility)<div @click="added['{{ $date }}'] ??= {}; added['{{ $date }}']['fertility'] = !added['{{ $date }}']['fertility']; $wire.toggle('{{ $date }}', 'fertility')" x-bind:class="(added['{{ $date }}']?.fertility ?? false) ? 'bg-orange-600' : 'bg-gray-200 dark:bg-gray-700'" class="mx-auto w-4 h-4 rounded-full"></div>@endif
                                    @if($sex)<div @click="added['{{ $date }}'] ??= {}; added['{{ $date }}']['sex'] = !added['{{ $date }}']['sex']; $wire.toggle('{{ $date }}', 'sex')" x-bind:class="(added['{{ $date }}']?.sex ?? false) ? 'bg-purple-800' : 'bg-gray-200 dark:bg-gray-700'" class="mx-auto w-4 h-4 rounded-full"></div>@endif
                                    @if($orgasms)<div @click="added['{{ $date }}'] ??= {}; added['{{ $date }}']['orgasms'] = !added['{{ $date }}']['orgasms']; $wire.toggle('{{ $date }}', 'orgasms')" x-bind:class="(added['{{ $date }}']?.orgasms ?? false) ? 'bg-indigo-500' : 'bg-gray-200 dark:bg-gray-700'" class="mx-auto w-4 h-4 rounded-full"></div>@endif
                                    @if($medication)<div @click="added['{{ $date }}'] ??= {}; added['{{ $date }}']['medication'] = !added['{{ $date }}']['medication']; $wire.toggle('{{ $date }}', 'medication')" x-bind:class="(added['{{ $date }}']?.medication ?? false) ? 'bg-green-600' : 'bg-gray-200 dark:bg-gray-700'" class="mx-auto w-4 h-4 rounded-full"></div>@endif
                                    @if($pregnancy)<div @click="added['{{ $date }}'] ??= {}; added['{{ $date }}']['pregnancy'] = !added['{{ $date }}']['pregnancy']; $wire.toggle('{{ $date }}', 'pregnancy')" x-bind:class="(added['{{ $date }}']?.pregnancy ?? false) ? 'bg-blue-500' : 'bg-gray-200 dark:bg-gray-700'" class="mx-auto w-4 h-4 rounded-full"></div>@endif
                                </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</div>
