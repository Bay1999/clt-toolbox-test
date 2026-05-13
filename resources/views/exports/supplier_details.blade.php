<table>
    <tr>
        <td colspan="2" style="font-weight: bold;">Supplier ID</td>
        <td colspan="4">{{ 'SUP-' . $supplier->created_at->format('Y') . '-' . str_pad($supplier->id, 3, '0', STR_PAD_LEFT) }}</td>
    </tr>
    <tr>
        <td colspan="2" style="font-weight: bold;">Supplier Name</td>
        <td colspan="4">{{ $supplier->name }}</td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tbody>
        @foreach($supplier->cltLayups as $layup)
            <tr>
                <td colspan="5" style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #000000;">
                    Layup: {{ $layup->name }} ({{ $layup->grade }})
                </td>
            </tr>
            <tr>
                <th style="border: 1px solid #000000; font-weight: bold;">Layer</th>
                <th style="border: 1px solid #000000; font-weight: bold;">Thickness (mm)</th>
                <th style="border: 1px solid #000000; font-weight: bold;">Width (mm)</th>
                <th style="border: 1px solid #000000; font-weight: bold;">Angle</th>
                <th style="border: 1px solid #000000; font-weight: bold;">Grade</th>
            </tr>
            @foreach($layup->cltLayers as $index => $layer)
                <tr>
                    <td style="border: 1px solid #000000;">L{{ $index + 1 }}</td>
                    <td style="border: 1px solid #000000;">{{ $layer->thickness }}</td>
                    <td style="border: 1px solid #000000;">{{ $layer->width }}</td>
                    <td style="border: 1px solid #000000;">{{ $layer->angle }}°</td>
                    <td style="border: 1px solid #000000;">{{ $layer->grade }}</td>
                </tr>
            @endforeach
            <tr></tr>
        @endforeach
    </tbody>
</table>
