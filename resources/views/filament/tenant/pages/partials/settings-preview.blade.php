{{-- Live preview — renders the real tenant landing page inside a sandboxed iframe --}}
<div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
    {{-- Browser chrome mock --}}
    <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 px-3 py-2 border-b border-gray-200 dark:border-gray-700">
        <div class="flex gap-1.5 shrink-0">
            <span class="w-3 h-3 rounded-full bg-red-400"></span>
            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
            <span class="w-3 h-3 rounded-full bg-green-400"></span>
        </div>
        <div class="flex-1 truncate rounded bg-white dark:bg-gray-700 px-3 py-0.5 text-xs text-gray-400 dark:text-gray-500 font-mono">
            {{ tenant('id') }}.lvh.me
        </div>
    </div>

    @if ($this->previewHtml)
        <iframe
            title="Landing page preview"
            srcdoc="{{ $this->previewHtml }}"
            class="w-full border-0 block"
            style="height: 560px;"
            sandbox="allow-scripts allow-same-origin"
        ></iframe>
    @else
        <div class="flex items-center justify-center text-gray-400 text-sm" style="height: 560px;">
            Loading preview…
        </div>
    @endif
</div>
<p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500 text-center italic">
    Live preview — updates on blur. Save to persist changes.
</p>
