<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public $errors = [];

    public $successCount = 0;

    public $errorCount = 0;

    public function collection(Collection $rows)
    {
        $rowNumber = 2; // Bắt đầu từ dòng 2 (dòng 1 là header)

        foreach ($rows as $row) {
            try {
                // Chuẩn hóa dữ liệu từ Excel
                $data = [
                    'name' => $this->getValue($row, 'ho_ten', 'name'),
                    'email' => $this->getValue($row, 'email', 'email'),
                    'phone' => $this->getValue($row, 'so_dien_thoai', 'phone'),
                    'password' => $this->getValue($row, 'mat_khau', 'password'),
                    'date_of_birth' => $this->getValue($row, 'ngay_sinh', 'date_of_birth'),
                    'joined_at' => $this->getValue($row, 'ngay_vao_hoc', 'joined_at'),
                    'status' => $this->getValue($row, 'trang_thai', 'status'),
                    'level' => $this->getValue($row, 'cap_do', 'level'),
                    'notes' => $this->getValue($row, 'ghi_chu', 'notes'),
                ];

                // Validate dữ liệu
                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'email' => 'nullable|email|unique:users,email',
                    'phone' => 'required|string|max:20',
                    'password' => 'required|min:6',
                    'date_of_birth' => 'nullable|date',
                    'joined_at' => 'nullable|date',
                    'status' => 'required|in:new,active,paused,dropped',
                    'level' => 'nullable|string|max:50',
                    'notes' => 'nullable|string|max:500',
                ], [
                    'name.required' => 'Họ tên là bắt buộc.',
                    'email.email' => 'Email không đúng định dạng.',
                    'email.unique' => 'Email này đã được sử dụng.',
                    'phone.required' => 'Số điện thoại là bắt buộc.',
                    'password.required' => 'Mật khẩu là bắt buộc.',
                    'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
                    'date_of_birth.date' => 'Ngày sinh không đúng định dạng.',
                    'joined_at.date' => 'Ngày vào học không đúng định dạng.',
                    'status.required' => 'Trạng thái là bắt buộc.',
                    'status.in' => 'Trạng thái phải là: new, active, paused, hoặc dropped.',
                ]);

                if ($validator->fails()) {
                    $this->errorCount++;
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'name' => $data['name'] ?? 'N/A',
                        'errors' => $validator->errors()->all(),
                    ];
                    $rowNumber++;

                    continue;
                }

                // Kiểm tra email trùng (nếu có)
                if (! empty($data['email']) && User::where('email', $data['email'])->exists()) {
                    $this->errorCount++;
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'name' => $data['name'],
                        'errors' => ['Email này đã được sử dụng.'],
                    ];
                    $rowNumber++;

                    continue;
                }

                // Parse ngày tháng
                $dateOfBirth = null;
                if (! empty($data['date_of_birth'])) {
                    try {
                        $dateOfBirth = $this->parseDate($data['date_of_birth']);
                    } catch (\Exception $e) {
                        $this->errorCount++;
                        $this->errors[] = [
                            'row' => $rowNumber,
                            'name' => $data['name'],
                            'errors' => ['Ngày sinh không đúng định dạng.'],
                        ];
                        $rowNumber++;

                        continue;
                    }
                }

                $joinedAt = null;
                if (! empty($data['joined_at'])) {
                    try {
                        $joinedAt = $this->parseDate($data['joined_at']);
                    } catch (\Exception $e) {
                        // Nếu không parse được, bỏ qua
                    }
                }

                // Tạo User
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'] ?: null,
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['password']),
                    'role' => 'student',
                    'is_active' => true,
                ]);

                // Tạo Student profile
                $user->studentProfile()->create([
                    'date_of_birth' => $dateOfBirth,
                    'joined_at' => $joinedAt,
                    'status' => $data['status'],
                    'level' => $data['level'] ?: null,
                    'notes' => $data['notes'] ?: null,
                ]);

                $this->successCount++;
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->errors[] = [
                    'row' => $rowNumber,
                    'name' => $data['name'] ?? 'N/A',
                    'errors' => ['Lỗi: '.$e->getMessage()],
                ];
            }

            $rowNumber++;
        }
    }

    /**
     * Lấy giá trị từ row với nhiều tên cột có thể
     */
    private function getValue($row, $key1, $key2)
    {
        // Thử các tên cột có thể (tiếng Việt và tiếng Anh)
        if (isset($row[$key1])) {
            return $row[$key1];
        }
        if (isset($row[$key2])) {
            return $row[$key2];
        }
        // Thử với snake_case
        $snakeKey1 = str_replace('_', ' ', $key1);
        $snakeKey2 = str_replace('_', ' ', $key2);
        if (isset($row[$snakeKey1])) {
            return $row[$snakeKey1];
        }
        if (isset($row[$snakeKey2])) {
            return $row[$snakeKey2];
        }

        return null;
    }

    /**
     * Parse ngày tháng từ nhiều format
     */
    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        // Nếu là số (Excel date serial number)
        if (is_numeric($date)) {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date));
        }

        // Thử parse với Carbon
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            // Thử các format khác
            $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d'];
            foreach ($formats as $format) {
                try {
                    return Carbon::createFromFormat($format, $date);
                } catch (\Exception $e) {
                    continue;
                }
            }
            throw new \Exception('Không thể parse ngày tháng: '.$date);
        }
    }
}
