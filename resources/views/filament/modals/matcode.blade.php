<div class="p-4">
    <h2 class="text-lg font-bold mb-3">Matcode</h2>
    
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">Material Code</th>
                <th class="border p-2">Description</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach ($customerDetails as $detail)
                <tr>
                    <td class="border p-2">{{ $detail->kode }}</td>
                    <td class="border p-2">{{ $detail->nama }}</td>
                    <td class="border p-2">{{ $detail->alamat }}</td>
                    <td class="border p-2">{{ $detail->kota }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
