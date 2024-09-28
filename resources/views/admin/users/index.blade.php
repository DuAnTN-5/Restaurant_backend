@extends('admin.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        @flasher_render
        <div class="col-lg-10">
            <h2>Danh Sách Người Dùng</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin') }}">Trang Chủ</a>
                </li>
                <li>
                    <a>Quản Lý Người Dùng</a>
                </li>
                <li class="active">
                    <strong>Danh Sách Người Dùng</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('users.create') }}" class="btn btn-primary" style="margin-top: 20px;">Thêm Người Dùng</a>
        </div>
    </div>

    @if ($errors->has('error'))
        <div class="alert alert-danger">{{ $errors->first('error') }}</div>
    @endif

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Người Dùng</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>

                    <div class="ibox-content">
                        <div class="table-responsive">
                            <!-- Search form -->
                            <div class="row">
                                <div class="col-lg-3 col-lg-offset-9 text-right">
                                    <form method="GET" action="{{ route('users.index') }}">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm người dùng..."
                                                value="{{ request()->input('search') }}">
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Table -->
                            <table class="table table-striped table-bordered table-hover dataTables-users">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Ảnh</th>
                                        <th>Điện Thoại</th>
                                        <th>Tình trạng</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->image)
                                                    <img src="{{ asset($user->image) }}" alt="Image" width="80">
                                                @else
                                                    <img src="{{ asset('default-avatar.png') }}" alt="Default Image" width="50">
                                                @endif
                                            </td>
                                            <td>{{ $user->phone_number }}</td>
                                            <td>
                                                <button class="btn btn-status-toggle" data-id="{{ $user->id }}">
                                                    @if ($user->status == 'active')
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </button>
                                            </td>
                                            <td>
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-success">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Xác nhận xóa?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Phân trang -->
                            <div class="text-left">
                                {{ $users->appends(request()->input())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-status-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                var userId = this.getAttribute('data-id'); // Sử dụng userId thay vì categoryId
                var button = this;

                // Gửi AJAX yêu cầu để cập nhật trạng thái người dùng
                fetch(`/admin/users/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id: userId,
                        status: button.innerText.trim() === 'Active' ? 'inactive' : 'active' // Chuyển đổi trạng thái
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Kiểm tra nếu response có status không
                    if (data.status) {
                        if (data.status === 'active') {
                            button.innerHTML = '<span class="badge badge-success">Active</span>';
                        } else {
                            button.innerHTML = '<span class="badge badge-danger">Inactive</span>';
                        }
                    } else {
                        console.error('Không có dữ liệu trạng thái trả về');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    });
</script>

</script>
{{-- @push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css" rel="stylesheet">
    <style>
        .table-responsive {
            overflow-x: auto;
            white-space: normal; /* Đảm bảo nội dung có thể xuống dòng */
        }

        .dataTables-users {
            width: 100%;
            table-layout: auto; /* Cho phép bảng điều chỉnh kích thước */
        }

        th, td {
            white-space: normal; /* Ngăn ngừa nội dung bảng tạo ra thanh cuộn ngang */
        }

        img {
            max-width: 100%; /* Đảm bảo hình ảnh không vượt quá kích thước ô */
            height: auto;
        }
    </style>
@endpush --}}

{{-- @push('scripts')
    <script>
        $(document).ready(function() {
            // Xử lý sự kiện khi click nút trạng thái
            $('.btn-status-toggle').on('click', function() {
                var userId = $(this).data('id');
                var button = $(this);
                var currentStatus = button.find('span').hasClass('badge-success') ? 'inactive' : 'active';

                // Gửi yêu cầu AJAX để cập nhật trạng thái người dùng
                $.ajax({
                    url: '{{ route("users.updateStatus") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: userId,
                        status: currentStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.status === 'active') {
                                button.find('span').removeClass('badge-danger').addClass('badge-success').text('Active');
                            } else {
                                button.find('span').removeClass('badge-success').addClass('badge-danger').text('Inactive');
                            }
                            alert(response.message);
                        } else {
                            alert('Cập nhật trạng thái thất bại.');
                        }
                    },
                    error: function() {
                        alert('Đã có lỗi xảy ra, vui lòng thử lại.');
                    }
                });
            });
        });
    </script>
@endpush --}}
