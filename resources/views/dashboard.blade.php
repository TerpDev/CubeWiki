<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <h2 class="text-xl font-semibold mb-4">Tenants</h2>
        <form method="POST" action="{{ route('dashboard.tenants.store') }}" class="mb-6 flex gap-2 items-end">
            @csrf
            <div>
                <label for="tenant-name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">New Tenant Name</label>
                <input id="tenant-name" name="name" type="text" required class="mt-1 block w-full rounded-md border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm shadow-sm focus:border-green-600 focus:ring focus:ring-green-600/20" placeholder="Tenant name">
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">Create</button>
        </form>
        <div class="grid auto-rows-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse($tenants as $tenant)
                <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 flex flex-col justify-center items-center">
                    <div class="text-lg font-bold">{{ $tenant->name }}</div>
                    <div class="text-sm text-zinc-500">{{ $tenant->slug }}</div>
                    <form method="POST" action="{{ route('dashboard.tenants.destroy', $tenant) }}" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:underline">Delete</button>
                    </form>
                </div>
            @empty
                <div class="col-span-3 text-center text-zinc-500">No tenants found.</div>
            @endforelse
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 mt-6">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>
