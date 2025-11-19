<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Queue Test') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">
                                {{ session('status') }}
                            </h3>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <form method="POST" action="{{ route('queue-test.dispatch') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="payload" :value="__('Payload (optional)')" />
                            <x-text-input id="payload" name="payload" type="text" class="mt-1 block w-full" value="{{ old('payload') }}" />
                            <x-input-error :messages="$errors->get('payload')" class="mt-2" />
                        </div>
                        <x-primary-button>
                            {{ __('Dispatch Job') }}
                        </x-primary-button>
                    </form>

                    <div class="border-t pt-4 text-sm text-gray-600 space-y-2">
                        <p>
                            <span class="font-medium">{{ __('Last run:') }}</span>
                            {{ $lastRun ?? __('never') }}
                        </p>
                        <p>
                            <span class="font-medium">{{ __('Processed jobs:') }}</span>
                            {{ $count }}
                        </p>
                        <p>
                            <span class="font-medium">{{ __('Scheduler last run:') }}</span>
                            {{ $schedulerLastRun ?? __('never') }}
                        </p>
                        <p>
                            <span class="font-medium">{{ __('Scheduler ticks:') }}</span>
                            {{ $schedulerCount }}
                        </p>
                        <form method="POST" action="{{ route('queue-test.run-schedule') }}" class="mt-2">
                            @csrf
                            <x-primary-button>{{ __('Run Scheduler Now') }}</x-primary-button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-2">
                    <h3 class="font-semibold text-lg">{{ __('Instructions') }}</h3>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>{{ __('Invia un payload per accodare un job.') }}</li>
                        <li>{{ __('Controlla i log o questa pagina dopo che il worker l’ha processato.') }}</li>
                        <li>{{ __('Assicurati che un processo worker sia in esecuzione su Dokploy.') }}</li>
                    </ol>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-3">
                    <h3 class="font-semibold text-lg">{{ __('Recent Logs') }}</h3>
                    <pre class="text-xs bg-gray-100 p-4 rounded overflow-auto max-h-96">{{ $logTail }}</pre>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
