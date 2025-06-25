<?php

namespace App\Exports;

use App\Models\Orderan;
use App\Models\Frame;
use App\Models\Softlen;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
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
    protected $orderanData;
    protected $inventoryData;

    public function __construct(array $filters = [])
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

        $orderanData = $orderans->map(function ($order) {
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
                'Laba Total'        => $order->laba_total ?? '-',
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

        $frames = Frame::when(session('cabang_id'), fn($q) => $q->where('cabang_id', session('cabang_id')))
            ->get()->map(fn($item) => [
                'Kategori' => 'Frame',
                'Nama'     => $item->merk,
                'Tipe'     => $item->tipe ?? '-',
                'Warna'    => $item->warna ?? '-',
                'Stok'     => $item->stok,
                'Harga Jual' => $item->harga,
                'Harga Beli' => $item->harga_beli,
                'Laba'     => $item->laba,
            ]);

        $lensaFinish = LensaFinish::when(session('cabang_id'), fn($q) => $q->where('cabang_id', session('cabang_id')))
            ->get()->map(fn($item) => [
                'Kategori' => 'Lensa Finish',
                'Nama'     => $item->merk,
                'Tipe'     => $item->tipe ?? '-',
                'Warna'    => $item->warna ?? '-',
                'Stok'     => $item->stok,
                'Harga Jual' => $item->harga,
                'Harga Beli' => $item->harga_beli,
                'Laba'     => $item->laba,
            ]);

        $lensaKhusus = LensaKhusus::when(session('cabang_id'), fn($q) => $q->where('cabang_id', session('cabang_id')))
            ->get()->map(fn($item) => [
                'Kategori' => 'Lensa Khusus',
                'Nama'     => $item->merk,
                'Tipe'     => $item->tipe ?? '-',
                'Warna'    => $item->warna ?? '-',
                'Stok'     => $item->stok,
                'Harga Jual' => $item->harga,
                'Harga Beli' => $item->harga_beli,
                'Laba'     => $item->laba,
            ]);

        $softlens = Softlen::when(session('cabang_id'), fn($q) => $q->where('cabang_id', session('cabang_id')))
            ->get()->map(fn($item) => [
                'Kategori' => 'Softlens',
                'Nama'     => $item->merk,
                'Tipe'     => $item->tipe ?? '-',
                'Warna'    => $item->warna ?? '-',
                'Stok'     => $item->stok,
                'Harga Jual' => $item->harga,
                'Harga Beli' => $item->harga_beli,
                'Laba'     => $item->laba,
            ]);

        $accessories = Accessories::when(session('cabang_id'), fn($q) => $q->where('cabang_id', session('cabang_id')))
            ->get()->map(fn($item) => [
                'Kategori' => 'Accessories',
                'Nama'     => $item->nama,
                'Tipe'     => '-',
                'Warna'    => '-',
                'Stok'     => $item->stok,
                'Harga Jual' => $item->harga,
                'Harga Beli' => $item->harga_beli,
                'Laba'     => $item->laba,
            ]);

        $this->orderanData = $orderanData->values();
        $this->inventoryData = [
            'Frame' => $frames->values(),
            'Lensa Finish' => $lensaFinish->values(),
            'Lensa Khusus' => $lensaKhusus->values(),
            'Accessories' => $accessories->values(),
            'Softlens' => $softlens->values(),
        ];

        return new Collection();
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet)
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $currentRow = 1;

                $sheet->setCellValue("A{$currentRow}", 'DATA ORDERAN CABANG ' . session('nama_cabang'));
                $sheet->mergeCells("A{$currentRow}:Q{$currentRow}");
                $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(16);
                $currentRow += 2;

                $orderanHeadings = [
                    'ID',
                    'Tanggal Order',
                    'Tanggal Selesai',
                    'Total',
                    'Laba Total',
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
                    'Created At'
                ];

                $orderanHeaderRow = $currentRow;
                $sheet->fromArray($orderanHeadings, null, "A{$currentRow}");
                $sheet->getStyle("A{$currentRow}:Q{$currentRow}")->getFont()->setBold(true)
                    ->getColor()->setRGB('FFFFFF');
                $sheet->getStyle("A{$currentRow}:Q{$currentRow}")->getFill()->setFillType('solid')
                    ->getStartColor()->setRGB('4F81BD');
                $currentRow++;

                foreach ($this->orderanData as $order) {
                    $sheet->fromArray(array_values($order), null, "A{$currentRow}");
                    $currentRow++;
                }

                $orderanLastRow = $currentRow - 1;

                $currentRow++;

                $sheet->setCellValue("A{$currentRow}", 'DATA INVENTORY CABANG ' . session('nama_cabang'));
                $sheet->mergeCells("A{$currentRow}:H{$currentRow}");
                $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(16);
                $currentRow += 2;

                $inventoryColors = [
                    'Frame' => '4F81BD',
                    'Lensa Finish' => '9BBB59',
                    'Lensa Khusus' => 'F79646',
                    'Accessories' => '8064A2',
                    'Softlens' => 'C0504D',
                ];

                foreach ($this->inventoryData as $kategori => $data) {
                    $sheet->setCellValue("A{$currentRow}", strtoupper($kategori));
                    $sheet->mergeCells("A{$currentRow}:H{$currentRow}");
                    $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(14);
                    $currentRow++;

                    $inventoryHeadings = ['Kategori', 'Nama', 'Tipe', 'Warna', 'Stok', 'Harga Jual', 'Harga Beli', 'Laba'];
                    $headerRow = $currentRow;
                    $sheet->fromArray($inventoryHeadings, null, "A{$currentRow}");
                    $sheet->getStyle("A{$currentRow}:H{$currentRow}")->getFont()->setBold(true)
                        ->getColor()->setRGB('FFFFFF');
                    $sheet->getStyle("A{$currentRow}:H{$currentRow}")->getFill()->setFillType('solid')
                        ->getStartColor()->setRGB($inventoryColors[$kategori]);
                    $currentRow++;

                    foreach ($data as $row) {
                        $sheet->fromArray(array_values($row), null, "A{$currentRow}");
                        $currentRow++;
                    }

                    $lastDataRow = $currentRow - 1;
                    $startRow = $headerRow + 1;

                    foreach (['F', 'G', 'H'] as $col) {
                        $sheet->getStyle("{$col}{$startRow}:{$col}{$lastDataRow}")
                            ->getNumberFormat()
                            ->setFormatCode('"Rp" #,##0');
                    }

                    $currentRow++;
                }


                $highestColumn = 'Q';
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle("A1:{$highestColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                foreach (range('A', $highestColumn) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                foreach (['D', 'E', 'I', 'J', 'K'] as $col) {
                    $sheet->getStyle("{$col}" . ($orderanHeaderRow + 1) . ":{$col}{$orderanLastRow}")
                        ->getNumberFormat()->setFormatCode('"Rp" #,##0');
                }
            },
        ];
    }
}
