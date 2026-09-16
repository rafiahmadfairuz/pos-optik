<?php

namespace App\Exports;

use App\Models\Orderan;
use App\Models\Frame;
use App\Models\Softlen;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use App\Models\ProdukCabang;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Relations\Relation;

class OrderansExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $filters;
    protected $orderanData;
    protected $inventoryData;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
        $this->orderanData = collect();
        $this->inventoryData = [];
    }

    public function collection(): Collection
    {
        // === 1️⃣ ORDERAN DATA ===
        $orderans = Orderan::with(['items.itemable', 'user', 'staff', 'asuransi'])
            ->where('order_status', 'complete')
            ->where('is_returned', 0) // FIX: Abaikan transaksi yang telah diretur
            ->where('cabang_id', session('cabang_id'))
            ->when($this->filters['date_from'] ?? null, fn($q) =>
                $q->whereDate('order_date', '>=', $this->filters['date_from']))
            ->when($this->filters['date_to'] ?? null, fn($q) =>
                $q->whereDate('order_date', '<=', $this->filters['date_to']))
            ->get();

        $this->orderanData = $orderans->map(function ($order) {
            $items = $order->items->map(function ($item) {
                $type = class_basename($item->itemable_type);
                $itemObj = $item->itemable;
                $name = $itemObj->merk ?? $itemObj->nama ?? '-';
                return "{$type} ({$name}) x{$item->quantity}";
            })->implode(', ');

            return [
                'ID'                => $order->id,
                'Tanggal Order'     => $order->order_date,
                'Tanggal Selesai'   => $order->complete_date ?? '-',
                'Total'             => $order->total,
                'Laba Total'        => $order->laba_total ?? 0,
                'Status Order'      => $order->order_status,
                'Jenis Pembayaran'  => $order->payment_type,
                'Metode Pembayaran' => $order->payment_method,
                'Status Bayar'      => $order->payment_status,
                'Customer Bayar'    => $order->customer_paying,
                'Perlu Dibayar'     => $order->perlu_dibayar,
                'Kembalian'         => $order->kembalian ?? 0,
                'Asuransi'          => $order->asuransi->nama ?? '-',
                'Staff'             => $order->staff->nama ?? $order->staff->name ?? '-',
                'Kasir/User'        => $order->user->name ?? '-',
                'Item Dibeli'       => $items,
                'Created At'        => $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : '-',
            ];
        });

        // === 2️⃣ INVENTORY DATA ===
        $this->inventoryData = $this->getInventoryData();

        return new Collection();
    }

    /**
     * Ambil data inventory berdasarkan role user (gudang utama vs cabang)
     */
    private function getInventoryData(): array
    {
        $isGudangUtama = Auth::user()->role === 'gudang_utama';
        $cabangId = session('cabang_id');

        // FIX: Menyesuaikan alias & class model agar mendukung fleksibilitas Morph Map
        $categories = [
            'Frame' => [
                'class' => Frame::class,
                'types' => ['frame', Frame::class]
            ],
            'Lensa Finish' => [
                'class' => LensaFinish::class,
                'types' => ['lensa_finish', LensaFinish::class]
            ],
            'Lensa Khusus' => [
                'class' => LensaKhusus::class,
                'types' => ['lensa_khusus', LensaKhusus::class]
            ],
            'Accessories' => [
                'class' => Accessories::class,
                'types' => ['accessory', 'accessories', Accessories::class]
            ],
            'Softlens' => [
                'class' => Softlen::class,
                'types' => ['softlens', Softlen::class]
            ],
        ];

        $result = [];

        foreach ($categories as $label => $info) {
            $model = $info['class'];
            $types = $info['types'];

            if ($isGudangUtama) {
                $data = $model::all()->map(fn($item) => [
                    'Kategori'   => $label,
                    'SKU'        => $item->sku ?? '-',
                    'Nama'       => $item->merk ?? $item->nama ?? '-',
                    'Tipe'       => $item->tipe ?? '-',
                    'Warna'      => $item->warna ?? '-',
                    'Stok'       => $item->stok ?? 0,
                    'Harga Jual' => $item->harga ?? 0,
                    'Harga Beli' => $item->harga_beli ?? 0,
                    'Laba'       => $item->laba ?? 0,
                ]);
            } else {
                $data = ProdukCabang::where('cabang_id', $cabangId)
                    ->whereIn('itemable_type', $types) // FIX: whereIn mendukung alias & full class path
                    ->with('itemable')
                    ->get()
                    ->map(fn($row) => [
                        'Kategori'   => $label,
                        'SKU'        => $row->itemable->sku ?? '-',
                        'Nama'       => $row->itemable->merk ?? $row->itemable->nama ?? '-',
                        'Tipe'       => $row->itemable->tipe ?? '-',
                        'Warna'      => $row->itemable->warna ?? '-',
                        'Stok'       => $row->stok ?? 0,
                        'Harga Jual' => $row->itemable->harga ?? 0,
                        'Harga Beli' => $row->itemable->harga_beli ?? 0,
                        'Laba'       => $row->itemable->laba ?? 0,
                    ]);
            }

            $result[$label] = $data->values();
        }

        return $result;
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

                // === HEADER ORDERAN ===
                $sheet->setCellValue("A{$currentRow}", 'DATA ORDERAN CABANG ' . session('nama_cabang'));
                $sheet->mergeCells("A{$currentRow}:Q{$currentRow}");
                $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(16);
                $currentRow += 2;

                $orderanHeadings = [
                    'ID', 'Tanggal Order', 'Tanggal Selesai', 'Total', 'Laba Total',
                    'Status Order', 'Jenis Pembayaran', 'Metode Pembayaran', 'Status Bayar',
                    'Customer Bayar', 'Perlu Dibayar', 'Kembalian', 'Asuransi',
                    'Staff', 'Kasir/User', 'Item Dibeli', 'Created At'
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

                // Formatting Rupiah Orderan jika ada data
                if ($orderanLastRow >= ($orderanHeaderRow + 1)) {
                    foreach (['D', 'E', 'J', 'K', 'L'] as $col) {
                        $sheet->getStyle("{$col}" . ($orderanHeaderRow + 1) . ":{$col}{$orderanLastRow}")
                            ->getNumberFormat()->setFormatCode('"Rp" #,##0');
                    }
                }

                $currentRow += 2;

                // === HEADER INVENTORY ===
                $sheet->setCellValue("A{$currentRow}", 'DATA INVENTORY CABANG ' . session('nama_cabang'));
                $sheet->mergeCells("A{$currentRow}:I{$currentRow}");
                $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(16);
                $currentRow += 2;

                $inventoryColors = [
                    'Frame'        => '4F81BD',
                    'Lensa Finish' => '9BBB59',
                    'Lensa Khusus' => 'F79646',
                    'Accessories'  => '8064A2',
                    'Softlens'     => 'C0504D',
                ];

                foreach ($this->inventoryData as $kategori => $data) {
                    $sheet->setCellValue("A{$currentRow}", strtoupper($kategori));
                    $sheet->mergeCells("A{$currentRow}:I{$currentRow}");
                    $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(14);
                    $currentRow++;

                    $headers = ['Kategori', 'SKU', 'Nama', 'Tipe', 'Warna', 'Stok', 'Harga Jual', 'Harga Beli', 'Laba'];
                    $headerRow = $currentRow;
                    $sheet->fromArray($headers, null, "A{$currentRow}");
                    $sheet->getStyle("A{$currentRow}:I{$currentRow}")->getFont()->setBold(true)
                        ->getColor()->setRGB('FFFFFF');
                    $sheet->getStyle("A{$currentRow}:I{$currentRow}")->getFill()->setFillType('solid')
                        ->getStartColor()->setRGB($inventoryColors[$kategori] ?? '4F81BD');
                    $currentRow++;

                    foreach ($data as $row) {
                        $sheet->fromArray(array_values($row), null, "A{$currentRow}");
                        $currentRow++;
                    }

                    $lastDataRow = $currentRow - 1;
                    $startRow = $headerRow + 1;

                    // FIX: Hanya berikan format rupiah jika data ada (mencegah crash range PhpSpreadsheet)
                    if ($lastDataRow >= $startRow) {
                        foreach (['G', 'H', 'I'] as $col) {
                            $sheet->getStyle("{$col}{$startRow}:{$col}{$lastDataRow}")
                                ->getNumberFormat()
                                ->setFormatCode('"Rp" #,##0');
                        }
                    }

                    $currentRow++;
                }

                // === GLOBAL BORDER & AUTO SIZE ===
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
            },
        ];
    }
}
