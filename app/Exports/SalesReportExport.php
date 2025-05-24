<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data['sales'];
    }

    public function headings(): array
    {
        return [
            'Date',
            'Order ID',
            'Customer',
            'Products',
            'Total Amount',
            'Status'
        ];
    }

    public function map($sale): array
    {
        $products = $sale->items->map(function ($item) {
            return $item->quantity . 'x ' . $item->product->name;
        })->join("\n");

        return [
            $sale->created_at->format('M d, Y H:i'),
            '#' . $sale->id,
            $sale->customer->name,
            $products,
            '$' . number_format($sale->total_amount, 2),
            ucfirst($sale->status)
        ];
    }

    public function title(): string
    {
        return 'Sales Report';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
} 