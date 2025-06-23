<div class="col-md-12">
    <div class="card">
        <div class="card-header fw-bold text-decoration-underline">Shopping Cart</div>
        <div class="card-body text-muted text-center">
            @if (count($cart) === 0)
                Your cart is empty
            @else
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $index => $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>Rp. {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>Rp. {{ number_format(($item['price'] ?? 0) * $item['quantity'], 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-sm "
                                        wire:click="decreaseQuantity({{ $index }})">-</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button class="btn btn-sm btn-danger mt-2" wire:click="clearCart">Kosongkan Keranjang</button>
            @endif
        </div>
    </div>
</div>
