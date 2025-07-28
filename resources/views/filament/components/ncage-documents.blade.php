@php
    $record = $getRecord();
    $documents = json_decode($record?->documents ?? '{}', true);
@endphp

@if(is_array($documents) && count($documents) > 0)
    <div class="flex flex-col gap-4">
        <div>
            <label for="doc-select" class="block mb-2 font-semibold text-gray-700">Pilih Dokumen untuk Preview:</label>
            <select
                id="doc-select"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300
                    bg-white text-gray-900 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400
                    dark:border-gray-600"
                onchange="handleDocumentChange(this)"
            >
                <option value="" selected>-- Pilih dokumen --</option>
                @foreach ($documents as $label => $path)
                    <option value="{{ asset('storage/' . $path) }}">
                        {{ \Illuminate\Support\Str::headline($label) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <h3 id="preview-title" class="font-semibold mb-2 text-lg text-gray-800">Preview Dokumen</h3>
            <iframe
                id="pdf-preview"
                src=""
                class="w-full rounded border"
                style="height: 600px; display: none;"
                frameborder="0"
            ></iframe>
            <p id="no-preview" class="text-gray-500">Belum ada dokumen yang dipilih.</p>
        </div>
    </div>

    <script>
        function handleDocumentChange(select) {
            const iframe = document.getElementById('pdf-preview');
            const titleElem = document.getElementById('preview-title');
            const noPreviewText = document.getElementById('no-preview');

            const selectedOption = select.options[select.selectedIndex];
            const url = select.value;
            const title = selectedOption.text;

            if (url) {
                iframe.src = url;
                iframe.style.display = 'block';
                noPreviewText.style.display = 'none';
                titleElem.textContent = 'Preview: ' + title;
            } else {
                iframe.style.display = 'none';
                iframe.src = '';
                noPreviewText.style.display = 'block';
                titleElem.textContent = 'Preview Dokumen';
            }
        }
    </script>

@else
    <p>Tidak ada dokumen</p>
@endif
