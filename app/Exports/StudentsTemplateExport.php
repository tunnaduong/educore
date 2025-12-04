<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsTemplateExport implements FromArray, WithColumnWidths, WithHeadings, WithStyles
{
    public function array(): array
    {
        // Trả về dòng mẫu
        return [
            [
                'Nguyễn Văn A',
                'nguyenvana@example.com',
                '0901234567',
                'password123',
                '2000-01-15',
                '2024-01-01',
                'new',
                'Beginner',
                'Học viên mới',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Họ tên',
            'Email',
            'Số điện thoại',
            'Mật khẩu',
            'Ngày sinh',
            'Ngày vào học',
            'Trạng thái',
            'Cấp độ',
            'Ghi chú',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, // Họ tên
            'B' => 30, // Email
            'C' => 15, // Số điện thoại
            'D' => 15, // Mật khẩu
            'E' => 15, // Ngày sinh
            'F' => 15, // Ngày vào học
            'G' => 15, // Trạng thái
            'H' => 15, // Cấp độ
            'I' => 30, // Ghi chú
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style cho header
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style cho dòng mẫu
        $sheet->getStyle('A2:I2')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E7E6E6'],
            ],
        ]);

        // Wrap text cho các cột
        $sheet->getStyle('A1:I100')->getAlignment()->setWrapText(true);

        return $sheet;
    }
}
