<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Conflicting Emotions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4">{{ __('Summary') }}</h3>
                    
                    @if ($diaryEntries->isEmpty())
                        <p>No conflicting entries found.</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider ">Content</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Emotion</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Intensity</th>
                               
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                                @foreach ($diaryEntries as $entry)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $entry->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($entry->date)->format('F j, Y') }}</td>
                                        <td class="px-6 py-4 break-words max-w-[600px]">{{ $entry->content }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $entry->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $entry->intensity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
