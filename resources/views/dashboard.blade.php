<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('dddddDashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="px-[30%] text-gray-900 dark:text-gray-100  mt-8">
                <div class="min-w-[100px] text-center">วันนี้กินข้าวกับอะไรดี {{ Auth::user()->name }}</div>
                </div>
                <div class="my-4 px-[40%]">
                
                    <div class="">
                        @if(Auth::user()->profile_photo)
                    <div class="max-w-48">
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile Photo" class="object-cover rounded-full">
                    </div>
                        @else
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('No profile photo uploaded.') }}</p>
            @endif
        </div>
        <p class="text-white mt-4 max-w-96" > แนะนำให้กินข้าวมันไก่ ก๋วยเตี๋ยวไก่ ต้มข่าไก่ </p>
        </div>
            </div>
        </div>
    </div>
</x-app-layout>
