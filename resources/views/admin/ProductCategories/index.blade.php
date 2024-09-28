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
                    <a>Quản Lý Loại</a>
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
                        <!-- Form tìm kiếm -->
                        <div class="row">
                            <div class="col-lg-3 col-lg-offset-9 text-right">
                                <form method="GET" action="{{ route('product-categories.index') }}">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm loại sản phẩm..." value="{{ request()->input('search') }}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-categories">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Mô Tả</th>
                                        <th>Thứ Tự</th>
                                        <th>Thao Tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->description }}</td>
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
                            </table>

                            <!-- Phân trang -->
                            <div class="d-flex justify-content-center">
                                {{ $categories->appends(request()->input())->links() }} <!-- Hiển thị phân trang -->
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
