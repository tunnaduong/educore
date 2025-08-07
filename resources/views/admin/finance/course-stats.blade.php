<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-hover bg-white">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Khóa học</th>
                        <th>Tổng học viên</th>
                        <th>Đã hoàn thành tài chính</th>
                        <th>Tổng thu</th>
                        <th>Chi phí</th>
                        <th>Lợi nhuận</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>{{ $course['id'] }}</td>
                            <td>{{ $course['name'] }}</td>
                            <td>{{ $course['total_students'] }}</td>
                            <td><span class="badge badge-success">{{ $course['students_paid'] }}</span></td>
                            <td>{{ number_format($course['total_income']) }}₫</td>
                            <td>{{ number_format($course['total_expense']) }}₫</td>
                            <td><strong>{{ number_format($course['profit']) }}₫</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
