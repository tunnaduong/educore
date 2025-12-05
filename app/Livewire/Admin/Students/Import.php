<?php

namespace App\Livewire\Admin\Students;

use App\Exports\StudentsTemplateExport;
use App\Imports\StudentsImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Component
{
    use WithFileUploads;

    public $file;

    public $showModal = false;

    public $importResult = null;

    protected $listeners = ['openImportModal' => 'openModal'];

    public function mount()
    {
        // Component được mount nhưng modal đóng
    }

    // Không đặt rules ở đây để tránh validate tự động khi chọn file
    // Chỉ validate khi submit form

    public function openModal()
    {
        $this->showModal = true;
        $this->reset(['file', 'importResult']);
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['file', 'importResult']);
        $this->resetValidation();
    }

    public function updatedFile()
    {
        // Clear validation errors khi file được chọn
        $this->resetValidation('file');
    }

    public function downloadTemplate()
    {
        return Excel::download(new StudentsTemplateExport, 'mau_nhap_hoc_vien.xlsx');
    }

    public function import()
    {
        // Validate chỉ khi submit
        $this->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240',
        ], [
            'file.required' => 'Vui lòng chọn file Excel để import.',
            'file.mimes' => 'File phải có định dạng .xlsx hoặc .xls.',
            'file.max' => 'File không được vượt quá 10MB.',
        ]);

        try {
            $import = new StudentsImport;
            Excel::import($import, $this->file);

            $this->importResult = [
                'success' => true,
                'successCount' => $import->successCount,
                'errorCount' => $import->errorCount,
                'errors' => $import->errors,
            ];

            if ($import->successCount > 0) {
                session()->flash('message', "Đã import thành công {$import->successCount} học viên!");
                // Dispatch event để refresh danh sách học viên
                $this->dispatch('studentsImported');
            }

            if ($import->errorCount > 0) {
                session()->flash('error', "Có {$import->errorCount} dòng bị lỗi. Vui lòng xem chi tiết bên dưới.");
            }

            // Reset file sau khi import
            $this->reset('file');
        } catch (\Exception $e) {
            $this->importResult = [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi import: '.$e->getMessage(),
            ];
            session()->flash('error', 'Có lỗi xảy ra khi import: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('admin.students.import');
    }
}
