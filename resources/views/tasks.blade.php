<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Create new task') }}
                            </h2>
                        </header>

                        <form method="post" action="{{ route('tasks.store') }}" class="mt-6 space-y-6">
                            @csrf

                            <div>
                                <x-input-label for="title" :value="__('Name task')" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Create') }}</x-primary-button>
                            </div>

                            @if (session('status') === 'task-created')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    class="text-sm text-green-400"
                                >{{ session('text') }}</p>
                            @endif
                        </form>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
                @if (session('status') === 'task-updated' || session('status') === 'task-deleted')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-lg text-center text-green-400 mb-3"
                    >{{ session('text') }}</p>
                @endif

                @if($tasks->isEmpty())
                    <p class="text-center text-gray-400">Нет задач для отображения.</p>
                @else
                    <div class="flex flex-col gap-3">
                        @foreach($tasks as $task)
                            <div class="rounded-lg p-4 flex items-center justify-between dark:bg-gray-900">
                                <div>
                                    <h2 class="text-lg text-gray-800 dark:text-gray-200 font-semibold">{{ $task->title }}</h2>
                                    <p class="text-gray-400">{{ $task->description }}</p>
                                </div>
                                <div class="flex gap-3">
                                    @if(!$task->is_completed)
                                        <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">
                                                {{ __('Done') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-200 px-2 py-1 rounded">{{ __('Completed') }}</span>
                                    @endif
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
