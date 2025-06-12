<?php
namespace App\Exports;

use App\Models\Orderan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderansExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection(): Collection
    {
        $orderans = Orderan::with(['items.itemable', 'user', 'staff', 'asuransi'])
            ->where('order_status', 'complete')
            ->where('cabang_id', session('cabang_id'))
            ->when($this->filters['date_from'], fn($q) =>
                $q->whereDate('order_date', '>=', $this->filters['date_from']))
            ->when($this->filters['date_to'], fn($q) =>
                $q->whereDate('order_date', '<=', $this->filters['date_to']))
            ->get();

        return $orderans->map(function ($order) {
            $items = $order->items->map(function ($item) {
                $type = class_basename($item->itemable_type); // e.g., Frame
                $name = $item->itemable->merk ?? $item->itemable->nama ?? '-';
                return "{$type} ({$name}) x{$item->quantity}";
            })->implode(', ');

            return [
                'ID'                => $order->id,
                'Tanggal Order'     => $order->order_date,
                'Tanggal Selesai'   => $order->complete_date ?? '-',
                'Total'             => $order->total,
                'Status Order'      => $order->order_status,
                'Jenis Pembayaran'  => $order->payment_type,
                'Metode Pembayaran' => $order->payment_method,
                'Status Bayar'      => $order->payment_status,
                'Customer Bayar'    => $order->customer_paying,
                'Perlu Dibayar'     => $order->perlu_dibayar,
                'Kembalian'         => $order->kembalian ?? '-',
                'Asuransi'          => $order->asuransi->nama ?? '-',
                'Staff'             => $order->staff->nama ?? '-',
                'Kasir/User'        => $order->user->name ?? '-',
                'Item Dibeli'       => $items,
                'Created At'        => $order->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tanggal Order',
            'Tanggal Selesai',
            'Total',
            'Status Order',
            'Jenis Pembayaran',
            'Metode Pembayaran',
            'Status Bayar',
            'Customer Bayar',
            'Perlu Dibayar',
            'Kembalian',
            'Asuransi',
            'Staff',
            'Kasir/User',
            'Item Dibeli',
            'Created At',
        ];
    }
}

