@extends('admin.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
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
                                        {{-- <th>Ngày Sinh</th> --}}
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
                                                    <img src="{{ asset($user->image) }}" alt="Image" width="50">
                                                @else
                                                    <img src="{{ asset('default-avatar.png') }}" alt="Default Image" width="50">
                                                @endif
                                            </td>
                                            <td>{{ $user->phone }}</td>
                                            {{-- <td>{{ $user->date_of_birth }}</td> --}}
                                            <td>
                                                <input type="checkbox" class="js-switch" data-id="{{ $user->id }}"
                                                    {{ $user->status ? 'checked' : '' }} />
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
                            
                            <!-- Phần hiển thị phân trang -->
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

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css" rel="stylesheet">
    <style>
        .table-responsive {
            overflow-x: auto;
            white-space: nowrap;
        }

        table {
            table-layout: fixed;
            width: 100%;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Khởi tạo Switchery cho các checkbox có class js-switch
            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            elems.forEach(function(html) {
                var switchery = new Switchery(html, {
                    color: '#1AB394',
                    size: 'small'
                });
            });

            // Xử lý sự kiện khi switch (tình trạng) thay đổi
            $('.js-switch').change(function() {
                var userId = $(this).data('id');
                var status = $(this).is(':checked') ? 1 : 0;

                // AJAX để cập nhật trạng thái người dùng
                $.ajax({
                    url: '{{ route("users.updateStatus") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: userId,
                        status: status
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                        } else {
                            alert('Cập nhật thất bại.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
