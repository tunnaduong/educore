<div class="card shadow-sm mb-4">
    @include('components.language')
    <div class="card-header bg-light">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-graph-up mr-2"></i>Thống kê điểm danh tổng quan
                </h5>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2 justify-content-end align-items-center">
                    <!-- Date Picker -->
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label mb-0 text-muted small">Chọn tháng:</label>
                        <input type="month"
                               class="form-control"
                               style="max-width: 180px;"
                               value="{{ $selectedYear }}-{{ str_pad($selectedMonth, 2, '0', STR_PAD_LEFT) }}"
                               onchange="handleDateChange(this.value)"
                               title="Chọn tháng/năm để xem dữ liệu">
                    </div>

                    <!-- Quick Navigation -->
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                onclick="navigateMonth('previous')"
                                title="Tháng trước">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                onclick="navigateMonth('next')"
                                title="Tháng sau">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="navigateMonth('current')"
                                title="Tháng hiện tại">
                            <i class="bi bi-calendar-check"></i>
                        </button>
                    </div>

                    <!-- Quick Select Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-calendar3"></i> Nhanh
                        </button>
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header">Tháng gần đây</h6></li>
                            @php
                                $currentDate = \Carbon\Carbon::now();
                                for ($i = 3; $i >= 1; $i--) {
                                    $month = $currentDate->copy()->subMonths($i);
                                    echo '<li><a class="dropdown-item" href="#" onclick="setMonth(' . $month->month . ', ' . $month->year . ')">' . $month->format('F Y') . '</a></li>';
                                }
                                echo '<li><a class="dropdown-item active" href="#" onclick="setMonth(' . $currentDate->month . ', ' . $currentDate->year . ')">' . $currentDate->format('F Y') . ' (Hiện tại)</a></li>';
                                for ($i = 1; $i <= 2; $i++) {
                                    $month = $currentDate->copy()->addMonths($i);
                                    echo '<li><a class="dropdown-item" href="#" onclick="setMonth(' . $month->month . ', ' . $month->year . ')">' . $month->format('F Y') . '</a></li>';
                                }
                            @endphp
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Thông tin tháng đang xem -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center justify-content-between" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle me-2"></i>
                        <div>
                            <strong>Đang xem dữ liệu:</strong> Tháng {{ $this->getMonthName($selectedMonth) }} năm {{ $selectedYear }}
                            @if($selectedYear == now()->year && $selectedMonth == now()->month)
                                <span class="badge bg-success ms-2">Tháng hiện tại</span>
                            @elseif($selectedYear < now()->year || ($selectedYear == now()->year && $selectedMonth < now()->month))
                                <span class="badge bg-secondary ms-2">Tháng trước</span>
                            @else
                                <span class="badge bg-warning ms-2">Tháng tương lai</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        @php
                            $currentDate = \Carbon\Carbon::create($selectedYear, $selectedMonth, 1);
                            $recentMonths = [];
                            for ($i = 2; $i >= 0; $i--) {
                                $recentMonths[] = $currentDate->copy()->subMonths($i);
                            }
                            for ($i = 1; $i <= 2; $i++) {
                                $recentMonths[] = $currentDate->copy()->addMonths($i);
                            }
                        @endphp
                        @foreach($recentMonths as $month)
                            <button type="button"
                                    class="btn btn-sm {{ $month->year == $selectedYear && $month->month == $selectedMonth ? 'btn-primary' : 'btn-outline-secondary' }}"
                                    onclick="setMonth({{ $month->month }}, {{ $month->year }})"
                                    title="{{ $month->format('F Y') }}">
                                {{ $month->format('M') }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Tổng học viên</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_students'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Lớp đang hoạt động</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_classes'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-mortarboard fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Tổng buổi có mặt</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_present'] }}</h3>
                                @if(isset($overviewStats['previous_month_present']))
                                    @php
                                        $change = $overviewStats['total_present'] - $overviewStats['previous_month_present'];
                                        $changePercent = $overviewStats['previous_month_present'] > 0 ? round(($change / $overviewStats['previous_month_present']) * 100, 1) : 0;
                                    @endphp
                                    <small class="d-block">
                                        @if($change > 0)
                                            <i class="bi bi-arrow-up text-success"></i> +{{ $change }} (+{{ $changePercent }}%)
                                        @elseif($change < 0)
                                            <i class="bi bi-arrow-down text-danger"></i> {{ $change }} ({{ $changePercent }}%)
                                        @else
                                            <i class="bi bi-dash text-muted"></i> Không đổi
                                        @endif
                                        so tháng trước
                                    </small>
                                @endif
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Tỷ lệ trung bình</h6>
                                <h3 class="mb-0">{{ $overviewStats['attendance_rate'] }}%</h3>
                                @if(isset($overviewStats['previous_month_rate']))
                                    @php
                                        $rateChange = $overviewStats['attendance_rate'] - $overviewStats['previous_month_rate'];
                                    @endphp
                                    <small class="d-block">
                                        @if($rateChange > 0)
                                            <i class="bi bi-arrow-up text-success"></i> +{{ $rateChange }}%
                                        @elseif($rateChange < 0)
                                            <i class="bi bi-arrow-down text-danger"></i> {{ $rateChange }}%
                                        @else
                                            <i class="bi bi-dash text-muted"></i> Không đổi
                                        @endif
                                        so tháng trước
                                    </small>
                                @endif
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-percent fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê chi tiết -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">
                            <i class="bi bi-pie-chart mr-2"></i>Phân bố trạng thái trong tháng
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $status = $overviewStats['status_counts'] ?? ['present' => 0, 'absent' => 0, 'late' => 0];
                            $totalStatus = ($status['present'] ?? 0) + ($status['absent'] ?? 0) + ($status['late'] ?? 0);
                            $hasData = $totalStatus > 0;

                            if ($hasData) {
                                $presentRate = round((($status['present'] ?? 0) * 100) / $totalStatus);
                                $absentRate = round((($status['absent'] ?? 0) * 100) / $totalStatus);
                                $lateRate = 100 - $presentRate - $absentRate;
                            }
                        @endphp

                        @if ($hasData)
                            <div class="mb-3 d-flex justify-content-center">
                                <canvas id="attendance-status-chart"
                                    width="320" height="180"
                                    data-present="{{ (int) ($status['present'] ?? 0) }}"
                                    data-absent="{{ (int) ($status['absent'] ?? 0) }}"
                                    data-late="{{ (int) ($status['late'] ?? 0) }}"
                                ></canvas>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-pie-chart fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted mb-2">Tháng {{ $this->getMonthName($selectedMonth) }} {{ $selectedYear }} chưa điểm danh</h5>
                                <p class="text-muted mb-0">Chưa có dữ liệu điểm danh để hiển thị biểu đồ phân bố trạng thái.</p>
                            </div>
                        @endif

                        @if ($hasData)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium">Có mặt</span>
                                <small class="text-muted">{{ $status['present'] ?? 0 }} buổi · {{ $presentRate }}%</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $presentRate }}%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium">Vắng</span>
                                <small class="text-muted">{{ $status['absent'] ?? 0 }} buổi · {{ $absentRate }}%</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $absentRate }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium">Muộn</span>
                                <small class="text-muted">{{ $status['late'] ?? 0 }} buổi · {{ $lateRate }}%</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $lateRate }}%"></div>
                            </div>
                        </div>
                        @endif
                        @if ($hasData)
                        <script>
                            (function () {
                                function ensureChartJsLoaded(callback) {
                                    if (window.Chart) return callback();
                                    var s = document.createElement('script');
                                    s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                                    s.onload = callback;
                                    document.head.appendChild(s);
                                }

                                function renderAttendanceStatusChart() {
                                    var canvas = document.getElementById('attendance-status-chart');
                                    if (!canvas) return;

                                    var ctx = canvas.getContext('2d');
                                    var present = parseInt(canvas.getAttribute('data-present') || '0', 10);
                                    var absent = parseInt(canvas.getAttribute('data-absent') || '0', 10);
                                    var late = parseInt(canvas.getAttribute('data-late') || '0', 10);

                                    // Destroy previous instance if exists
                                    if (canvas._chartInstance) {
                                        canvas._chartInstance.destroy();
                                        canvas._chartInstance = null;
                                    }

                                    canvas._chartInstance = new Chart(ctx, {
                                        type: 'doughnut',
                                        data: {
                                            labels: ['Có mặt', 'Vắng', 'Trễ'],
                                            datasets: [{
                                                data: [present, absent, late],
                                                backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                                                borderWidth: 0
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            cutout: '60%',
                                            plugins: { legend: { position: 'bottom' } }
                                        }
                                    });
                                }

                                function init() { ensureChartJsLoaded(renderAttendanceStatusChart); }

                                // Initial render
                                document.addEventListener('DOMContentLoaded', init);
                                // Livewire v2 hook
                                document.addEventListener('livewire:load', function () {
                                    if (window.Livewire) {
                                        window.Livewire.hook('message.processed', function () { init(); });
                                    }
                                });
                                // Livewire v3 morph hook (fallback)
                                if (window.Livewire && window.Livewire.hook) {
                                    window.Livewire.hook('morph.updated', function () { init(); });
                                }
                            })();
                        </script>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">
                            <i class="bi bi-graph-up-arrow mr-2"></i>Xu hướng theo ngày (tháng đã chọn)
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $trend = collect($overviewStats['daily_trend'] ?? []);
                        @endphp

                        @if ($trend->count() > 0)
                            <!-- Biểu đồ xu hướng theo ngày -->
                            <div class="mb-3">
                                <canvas id="daily-trend-chart" width="400" height="200"></canvas>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th>Ngày</th>
                                            <th class="text-center">Có mặt</th>
                                            <th class="text-center">Tổng</th>
                                            <th class="text-end">Tỷ lệ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trend as $day)
                                            @php
                                                $present = (int) ($day['present'] ?? 0);
                                                $total = max(1, (int) ($day['total'] ?? 0));
                                                $rate = isset($day['rate']) ? round($day['rate']) : round(($present * 100) / $total);
                                                $bar = min(100, max(0, $rate));
                                            @endphp
                                            <tr>
                                                <td>{{ $day['date'] ?? '-' }}</td>
                                                <td class="text-center">{{ $present }}</td>
                                                <td class="text-center">{{ $total }}</td>
                                                <td class="text-end" style="width: 40%;">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="flex-grow-1 progress" style="height: 6px;">
                                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $bar }}%"></div>
                                                        </div>
                                                        <span class="fw-medium" style="min-width: 42px;">{{ $rate }}%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted mb-2">Tháng {{ $this->getMonthName($selectedMonth) }} {{ $selectedYear }} chưa điểm danh</h5>
                                <p class="text-muted mb-0">Chưa có dữ liệu điểm danh cho tháng này. Vui lòng thực hiện điểm danh để xem thống kê.</p>
                            </div>
                        @endif

                        @if ($trend->count() > 0)
                        <script>
                            (function () {
                                function ensureChartJsLoaded(callback) {
                                    if (window.Chart) return callback();
                                    var s = document.createElement('script');
                                    s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                                    s.onload = callback;
                                    document.head.appendChild(s);
                                }

                                function renderDailyTrendChart() {
                                    var canvas = document.getElementById('daily-trend-chart');
                                    if (!canvas) return;

                                    var ctx = canvas.getContext('2d');

                                    // Destroy previous instance if exists
                                    if (canvas._chartInstance) {
                                        canvas._chartInstance.destroy();
                                        canvas._chartInstance = null;
                                    }

                                    // Lấy dữ liệu từ PHP
                                    var trendData = @json($trend->toArray());

                                    if (trendData.length === 0) return;

                                    var labels = trendData.map(function(item) { return item.date; });
                                    var presentData = trendData.map(function(item) { return item.present || 0; });
                                    var totalData = trendData.map(function(item) { return item.total || 0; });
                                    var rateData = trendData.map(function(item) {
                                        var present = item.present || 0;
                                        var total = Math.max(1, item.total || 0);
                                        return Math.round((present / total) * 100);
                                    });

                                    canvas._chartInstance = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: labels,
                                            datasets: [{
                                                label: 'Tỷ lệ điểm danh (%)',
                                                data: rateData,
                                                borderColor: '#007bff',
                                                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                                borderWidth: 2,
                                                fill: true,
                                                tension: 0.4
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    display: true,
                                                    position: 'top'
                                                },
                                                tooltip: {
                                                    callbacks: {
                                                        afterLabel: function(context) {
                                                            var index = context.dataIndex;
                                                            return 'Có mặt: ' + presentData[index] + '/' + totalData[index];
                                                        }
                                                    }
                                                }
                                            },
                                            scales: {
                                                y: {
                                                    beginAtZero: true,
                                                    max: 100,
                                                    ticks: {
                                                        callback: function(value) {
                                                            return value + '%';
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    });
                                }

                                function init() {
                                    ensureChartJsLoaded(renderDailyTrendChart);
                                }

                                // Initial render
                                document.addEventListener('DOMContentLoaded', init);
                                // Livewire v2 hook
                                document.addEventListener('livewire:load', function () {
                                    if (window.Livewire) {
                                        window.Livewire.hook('message.processed', function () { init(); });
                                    }
                                });
                                // Livewire v3 morph hook (fallback)
                                if (window.Livewire && window.Livewire.hook) {
                                    window.Livewire.hook('morph.updated', function () { init(); });
                                }
                            })();
                        </script>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ xu hướng theo tháng -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">
                            <i class="bi bi-graph-up mr-2"></i>Xu hướng điểm danh theo tháng (12 tháng gần nhất)
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $hasMonthlyData = collect($monthlyTrendData)->where('total', '>', 0)->count() > 0;
                        @endphp

                        @if ($hasMonthlyData)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary active" id="chart-type-line" onclick="changeChartType('line')">
                                            <i class="bi bi-graph-up"></i> Đường
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="chart-type-bar" onclick="changeChartType('bar')">
                                            <i class="bi bi-bar-chart"></i> Cột
                                        </button>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="bi bi-info-circle"></i> Click vào điểm dữ liệu để xem chi tiết
                                    </div>
                                </div>
                            </div>
                            <canvas id="monthly-trend-chart" width="800" height="300"></canvas>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted mb-2">Chưa có dữ liệu điểm danh</h5>
                                <p class="text-muted mb-0">Hệ thống chưa có dữ liệu điểm danh trong 12 tháng gần nhất. Vui lòng thực hiện điểm danh để xem thống kê.</p>
                            </div>
                        @endif
                        @if ($hasMonthlyData)
                        <script>
                            (function () {
                                function ensureChartJsLoaded(callback) {
                                    if (window.Chart) return callback();
                                    var s = document.createElement('script');
                                    s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                                    s.onload = callback;
                                    document.head.appendChild(s);
                                }

                                var currentChartType = 'bar';
                                var monthlyChartData = null;

                                function renderMonthlyTrendChart() {
                                    var canvas = document.getElementById('monthly-trend-chart');
                                    if (!canvas) return;

                                    var ctx = canvas.getContext('2d');

                                    // Destroy previous instance if exists
                                    if (canvas._chartInstance) {
                                        canvas._chartInstance.destroy();
                                        canvas._chartInstance = null;
                                    }

                                    // Lấy dữ liệu từ Livewire component
                                    monthlyChartData = @json($monthlyTrendData);

                                    var labels = monthlyChartData.map(function(item) { return item.month; });
                                    var rateData = monthlyChartData.map(function(item) { return item.rate; });
                                    var presentData = monthlyChartData.map(function(item) { return item.present; });
                                    var totalData = monthlyChartData.map(function(item) { return item.total; });

                                    canvas._chartInstance = new Chart(ctx, {
                                        type: currentChartType,
                                        data: {
                                            labels: labels,
                                            datasets: [{
                                                label: 'Tỷ lệ điểm danh (%)',
                                                data: rateData,
                                                backgroundColor: currentChartType === 'line' ? 'rgba(40, 167, 69, 0.1)' : 'rgba(40, 167, 69, 0.8)',
                                                borderColor: 'rgba(40, 167, 69, 1)',
                                                borderWidth: currentChartType === 'line' ? 3 : 1,
                                                fill: currentChartType === 'line',
                                                tension: currentChartType === 'line' ? 0.4 : 0,
                                                yAxisID: 'y'
                                            }, {
                                                label: 'Số buổi có mặt',
                                                data: presentData,
                                                backgroundColor: currentChartType === 'line' ? 'rgba(0, 123, 255, 0.1)' : 'rgba(0, 123, 255, 0.6)',
                                                borderColor: 'rgba(0, 123, 255, 1)',
                                                borderWidth: currentChartType === 'line' ? 3 : 1,
                                                fill: currentChartType === 'line',
                                                tension: currentChartType === 'line' ? 0.4 : 0,
                                                yAxisID: 'y1'
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            interaction: {
                                                intersect: false,
                                                mode: 'index'
                                            },
                                            plugins: {
                                                legend: {
                                                    display: true,
                                                    position: 'top'
                                                },
                                                tooltip: {
                                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                                    titleColor: 'white',
                                                    bodyColor: 'white',
                                                    borderColor: 'rgba(255, 255, 255, 0.2)',
                                                    borderWidth: 1,
                                                    callbacks: {
                                                        afterLabel: function(context) {
                                                            var index = context.dataIndex;
                                                            if (context.datasetIndex === 0) {
                                                                return 'Có mặt: ' + presentData[index] + '/' + totalData[index];
                                                            }
                                                            return 'Tỷ lệ: ' + rateData[index] + '%';
                                                        }
                                                    }
                                                }
                                            },
                                            scales: {
                                                x: {
                                                    display: true,
                                                    title: {
                                                        display: true,
                                                        text: 'Tháng',
                                                        color: '#666'
                                                    },
                                                    grid: {
                                                        color: 'rgba(0, 0, 0, 0.1)'
                                                    }
                                                },
                                                y: {
                                                    type: 'linear',
                                                    display: true,
                                                    position: 'left',
                                                    title: {
                                                        display: true,
                                                        text: 'Tỷ lệ (%)',
                                                        color: '#666'
                                                    },
                                                    max: 100,
                                                    grid: {
                                                        color: 'rgba(0, 0, 0, 0.1)'
                                                    },
                                                    ticks: {
                                                        callback: function(value) {
                                                            return value + '%';
                                                        }
                                                    }
                                                },
                                                y1: {
                                                    type: 'linear',
                                                    display: true,
                                                    position: 'right',
                                                    title: {
                                                        display: true,
                                                        text: 'Số buổi',
                                                        color: '#666'
                                                    },
                                                    grid: {
                                                        drawOnChartArea: false,
                                                    },
                                                }
                                            }
                                        }
                                    });
                                }

                                // Function to change chart type
                                window.changeChartType = function(type) {
                                    currentChartType = type;

                                    // Update button states
                                    document.getElementById('chart-type-line').classList.toggle('active', type === 'line');
                                    document.getElementById('chart-type-bar').classList.toggle('active', type === 'bar');

                                    // Re-render chart
                                    renderMonthlyTrendChart();
                                };

                                function init() {
                                    ensureChartJsLoaded(renderMonthlyTrendChart);
                                }

                                // Initial render
                                document.addEventListener('DOMContentLoaded', init);
                                // Livewire v2 hook
                                document.addEventListener('livewire:load', function () {
                                    if (window.Livewire) {
                                        window.Livewire.hook('message.processed', function () { init(); });
                                    }
                                });
                                // Livewire v3 morph hook (fallback)
                                if (window.Livewire && window.Livewire.hook) {
                                    window.Livewire.hook('morph.updated', function () { init(); });
                                }
                            })();
                        </script>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Top học viên và lớp -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">
                            <i class="bi bi-trophy mr-2"></i>Top 5 học viên điểm danh tốt nhất
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($overviewStats['top_students']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Học viên</th>
                                            <th class="text-center">Tỷ lệ</th>
                                            <th class="text-center">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overviewStats['top_students'] as $student)
                                            <tr>
                                                <td>
                                                    <div class="fw-medium">{{ $student['student']->name }}</div>
                                                    <small
                                                        class="text-muted">{{ $student['present_days'] }}/{{ $student['total_days'] }}
                                                        buổi</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success">{{ $student['rate'] }}%</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($student['rate'] >= 90)
                                                        <span class="badge bg-success">Tốt</span>
                                                    @elseif ($student['rate'] >= 70)
                                                        <span class="badge bg-warning">Khá</span>
                                                    @else
                                                        <span class="badge bg-danger">Cần cải thiện</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-calendar-x fs-1 text-muted mb-2"></i>
                                <p class="text-muted mb-0">Chưa có dữ liệu điểm danh</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">
                            <i class="bi bi-award mr-2"></i>Top 5 lớp điểm danh tốt nhất
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($overviewStats['top_classes']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Lớp học</th>
                                            <th class="text-center">Tỷ lệ</th>
                                            <th class="text-center">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overviewStats['top_classes'] as $class)
                                            <tr>
                                                <td>
                                                    <div class="fw-medium">{{ $class['classroom']->name }}</div>
                                                    <small
                                                        class="text-muted">{{ $class['present_days'] }}/{{ $class['total_days'] }}
                                                        buổi</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success">{{ $class['rate'] }}%</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($class['rate'] >= 90)
                                                        <span class="badge bg-success">Tốt</span>
                                                    @elseif ($class['rate'] >= 70)
                                                        <span class="badge bg-warning">Khá</span>
                                                    @else
                                                        <span class="badge bg-danger">Cần cải thiện</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-calendar-x fs-1 text-muted mb-2"></i>
                                <p class="text-muted mb-0">Chưa có dữ liệu điểm danh</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript functions for month navigation
function navigateMonth(direction) {
    const monthInput = document.querySelector('input[type="month"]');

    if (!monthInput) {
        console.log('Không tìm thấy month input');
        return;
    }

    let currentValue = monthInput.value;
    let [currentYear, currentMonth] = currentValue.split('-').map(Number);

    if (direction === 'previous') {
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
    } else if (direction === 'next') {
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
    } else if (direction === 'current') {
        const now = new Date();
        currentMonth = now.getMonth() + 1;
        currentYear = now.getFullYear();
    }

    console.log('Navigating to:', currentMonth, currentYear);

    // Update month input
    const newValue = `${currentYear}-${String(currentMonth).padStart(2, '0')}`;
    monthInput.value = newValue;

    // Trigger change event
    monthInput.dispatchEvent(new Event('change', { bubbles: true }));
}

function handleDateChange(value) {
    if (!value) return;

    const [year, month] = value.split('-').map(Number);
    console.log('Date changed to:', month, year);

    // Update Livewire component
    if (window.Livewire) {
        window.Livewire.emit('updatedSelectedMonth', month);
        window.Livewire.emit('updatedSelectedYear', year);
    }
}

function setMonth(month, year) {
    const monthInput = document.querySelector('input[type="month"]');

    if (!monthInput) {
        console.log('Không tìm thấy month input');
        return;
    }

    console.log('Setting month to:', month, year);

    // Update month input
    const newValue = `${year}-${String(month).padStart(2, '0')}`;
    monthInput.value = newValue;

    // Trigger change event
    monthInput.dispatchEvent(new Event('change', { bubbles: true }));
}

// Add keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.altKey) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            navigateMonth('previous');
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            navigateMonth('next');
        } else if (e.key === 'Home') {
            e.preventDefault();
            navigateMonth('current');
        }
    }
});

// Debug function to check if elements exist
document.addEventListener('DOMContentLoaded', function() {
    const monthInput = document.querySelector('input[type="month"]');

    if (monthInput) {
        console.log('Month input found and ready');
    } else {
        console.log('Month input not found');
    }
});
</script>
