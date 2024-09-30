@extends('admin.layoutadmin')

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Loại Sản Phẩm</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin') }}">Trang Chủ</a>
                </li>
                <li>
                    <a>Quản Lý Loại Sản Phẩm</a>
                </li>
                <li class="active">
                    <strong>Danh Sách Loại Sản Phẩm</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('product-categories.create') }}" class="btn btn-primary" style="margin-top: 20px;">Thêm Loại Sản Phẩm</a>
        </div>
    </div>

    @if ($errors->has('error'))
        <div class="alert alert-danger">{{ $errors->first('error') }}</div>
    @endif

    <div class="wrapper wrapper-content animated fadeInRight">
        @flasher_render
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Loại Sản Phẩm</h5>
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
                            <!-- Form tìm kiếm -->
                            <div class="row">
                                <div class="col-lg-3 col-lg-offset-9 text-right">
                                    <form method="GET" action="{{ route('product-categories.index') }}">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục sản phẩm..."
                                                value="{{ request()->input('search') }}">
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <table class="table table-striped table-bordered table-hover dataTables-categories">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Trạng thái</th>
                                        <th>Slug</th>
                                        <th>Thứ Tự</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <button class="btn btn-status-toggle" data-id="{{ $category->id }}">
                                                    @if ($category->status == 'active')
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </button>
                                            </td>
                                            <td>{{ $category->slug }}</td>
                                            <td>{{ $category->position }}</td>
                                            <td>
                                                <a href="{{ route('product-categories.edit', $category->id) }}" class="btn btn-success">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('product-categories.destroy', $category->id) }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Xác nhận xóa?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Trạng thái</th>
                                        <th>Slug</th>
                                        <th>Thứ Tự</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </tfoot>
                            </table>

                            <!-- Paginate với Bootstrap 4 -->
                            <div class="text-left">
                                {{ $categories->appends(request()->input())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-status-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                var categoryId = this.getAttribute('data-id');
                var button = this;

                // Gửi yêu cầu AJAX để cập nhật trạng thái
                fetch(`/admin/categories/product-categories/${categoryId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
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
