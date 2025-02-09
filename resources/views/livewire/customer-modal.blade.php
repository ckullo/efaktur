<div class="p-4">
    <h2 class="text-lg font-bold mb-3">Customers</h2>

    @if ($customer)
        <div class="bg-gray-100 p-3 rounded-md mb-4">
            <p><strong>File Name:</strong> {{ $customer->nama_file }}</p>
            <p><strong>File Location:</strong> {{ $customer->lokasi_file }}</p>
        </div>
    @endif

    @if ($customerDetails->isEmpty())
        <p class="text-gray-500">No records found.</p>
    @else
    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">Kode</th>
                <th class="border p-2">Nama</th>
                <th class="border p-2">Alamat</th>
                <th class="border p-2">Kota</th>
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

        <!-- ✅ Isolated Pagination for Modal -->
        <div class="mt-4">
            {{ $customerDetails->links() }}
        </div>
    @endif
</div>
