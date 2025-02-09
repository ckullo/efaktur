<div class="p-4">
    <h2 class="text-lg font-bold mb-3">Material Codes</h2>
    @if ($matcodeFile)
        <div class="bg-gray-100 p-3 rounded-md mb-4">
            <p><strong>File Name:</strong> {{ $matcodeFile->nama_file }}</p>
            <p><strong>File Location:</strong> {{ $matcodeFile->lokasi_file }}</p>
        </div>
    @endif

    @if ($matcodes->isEmpty())
        <p class="text-gray-500">No records found.</p>
    @else
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Matcode</th>
                    <th class="border p-2">Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($matcodes as $matcode)
                    <tr>
                        <td class="border p-2">{{ $matcode->nama }}</td>
                        <td class="border p-2">{{ $matcode->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $matcodes->links() }}
        </div>
    @endif
</div>
