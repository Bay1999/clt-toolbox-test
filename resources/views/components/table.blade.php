<div
    class="overflow-x-auto bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl border border-gray-100 dark:border-gray-700">
    <table {{ $attributes->merge(['class' => 'w-full text-sm text-left text-gray-600 dark:text-gray-400']) }}>
        <thead
            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
            <tr>
                {{ $header }}
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            {{ $slot }}
        </tbody>
    </table>
</div>
