<?php

namespace App\Exports;

use App\Models\Orderan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderansExport implements FromCollection, WithHeadings, WithStyles, WithEvents
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
                $type = class_basename($item->itemable_type);
                $name = $item->itemable->merk ?? $item->itemable->nama ?? '-';
                return "{$type} ({$name}) x{$item->quantity}";
            })->implode(', ');

            return [
                'ID'                => $order->id,
                'Tanggal Order'     => $order->order_date,
                'Tanggal Selesai'   => $order->complete_date ?? '-',
                'Total'             => $order->total,
                'Laba Total'        => $order->laba_total ?? '-', // tambahkan di sini
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
            'Laba Total', // tambahkan heading di sini
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

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '4F81BD'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Border semua
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Autosize kolom
                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $currencyColumns = ['D', 'E', 'I', 'J', 'K']; // D = Total, E = Laba Total, I = Customer Bayar, J = Perlu Dibayar, K = Kembalian

                foreach ($currencyColumns as $col) {
                    $sheet->getStyle("{$col}2:{$col}{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode('"Rp" #,##0');
                }

                // Tambahkan total laba di bawah tabel
                $totalRow = $highestRow + 1;


                // Format hasil SUM ke rupiah juga
                $sheet->getStyle("E{$totalRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');
            },
        ];
    }
}
