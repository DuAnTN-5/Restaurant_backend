@extends('admin.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Chỉnh Sửa Loại Bài Viết</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin') }}">Trang Chủ</a>
                </li>
                <li>
                    <a>Quản Lý Loại Bài Viết</a>
                </li>
                <li class="active">
                    <strong>Chỉnh Sửa Loại Bài Viết</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('PostCategories.index') }}" class="btn btn-primary" style="margin-top: 20px;">Danh Sách</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Chỉnh Sửa Loại Bài Viết</h5>
                    </div>
                    <div class="ibox-content">
                        <form action="{{ route('PostCategories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Tên Loại</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $category->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" class="form-control" id="slug" value="{{ old('slug', $category->slug) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Mô Tả</label>
                                <textarea name="description" class="form-control" id="description" rows="4">{{ old('description', $category->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="position">Thứ Tự</label>
                                <input type="number" name="position" class="form-control" id="position" value="{{ old('position', $category->position) }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Cập Nhật Loại Bài Viết</button>
                            <a href="{{ route('PostCategories.index') }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
