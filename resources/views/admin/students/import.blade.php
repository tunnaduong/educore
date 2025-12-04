<!-- Import Modal -->
@if ($showModal)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="importModalLabel">
                        <i class="bi bi-file-earmark-excel mr-2"></i>Nhập học viên từ file Excel
                    </h5>
                    <button type="button" class="close text-white" wire:click="closeModal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Hướng dẫn -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-info-circle mr-2"></i>Hướng dẫn:</h6>
                        <ul class="mb-0 small">
                            <li>File Excel phải có định dạng .xlsx hoặc .xls</li>
                            <li>Dòng đầu tiên là tiêu đề các cột</li>
                            <li>Các cột bắt buộc: Họ tên, Số điện thoại, Mật khẩu, Trạng thái</li>
                            <li>Các cột tùy chọn: Email, Ngày sinh, Ngày vào học, Cấp độ, Ghi chú</li>
                            <li>Trạng thái phải là: new, active, paused, hoặc dropped</li>
                            <li>Mật khẩu phải có ít nhất 6 ký tự</li>
                        </ul>
                    </div>

                    <!-- Form upload -->
                    <form wire:submit.prevent="import">
                        <div class="mb-3">
                            <label for="file" class="form-label">Chọn file Excel <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" wire:model="file"
                                    class="form-control @error('file') is-invalid @enderror" id="file"
                                    accept=".xlsx,.xls">
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($file)
                                <small class="text-muted mt-1 d-block">
                                    <i class="bi bi-file-earmark-check mr-1"></i>
                                    Đã chọn: {{ $file->getClientOriginalName() }}
                                    ({{ number_format($file->getSize() / 1024, 2) }} KB)
                                </small>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button type="button" class="btn btn-outline-secondary" wire:click="downloadTemplate">
                                <i class="bi bi-download mr-2"></i>Tải file mẫu
                            </button>
                            <div>
                                <button type="button" class="btn btn-secondary mr-2" wire:click="closeModal">
                                    Hủy
                                </button>
                                <button type="submit" class="btn btn-primary" @if (!$file) disabled @endif>
                                    <i class="bi bi-upload mr-2"></i>Import
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Kết quả import -->
                    @if ($importResult)
                        <div class="mt-4">
                            <hr>
                            <h6 class="mb-3">Kết quả import:</h6>

                            @if ($importResult['success'])
                                <div class="alert alert-success">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-check-circle mr-2"></i>Import hoàn tất!
                                    </h6>
                                    <p class="mb-0">
                                        <strong>Thành công:</strong> {{ $importResult['successCount'] }} học viên<br>
                                        @if ($importResult['errorCount'] > 0)
                                            <strong class="text-danger">Lỗi:</strong> {{ $importResult['errorCount'] }} dòng
                                        @endif
                                    </p>
                                </div>

                                @if ($importResult['errorCount'] > 0 && !empty($importResult['errors']))
                                    <div class="alert alert-warning">
                                        <h6 class="alert-heading">
                                            <i class="bi bi-exclamation-triangle mr-2"></i>Chi tiết lỗi:
                                        </h6>
                                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 80px;">Dòng</th>
                                                        <th>Họ tên</th>
                                                        <th>Lỗi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($importResult['errors'] as $error)
                                                        <tr>
                                                            <td class="text-center">{{ $error['row'] }}</td>
                                                            <td>{{ $error['name'] }}</td>
                                                            <td>
                                                                <ul class="mb-0 small">
                                                                    @foreach ($error['errors'] as $err)
                                                                        <li>{{ $err }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-danger">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-x-circle mr-2"></i>Import thất bại!
                                    </h6>
                                    <p class="mb-0">{{ $importResult['message'] ?? 'Có lỗi xảy ra khi import file.' }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" wire:click="closeModal"></div>
    </div>
@endif

