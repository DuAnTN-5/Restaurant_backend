@extends('admin.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Tạo Bài Viết Mới</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin') }}">Trang Chủ</a>
                </li>
                <li>
                    <a href="{{ route('posts.index') }}">Danh Sách Bài Viết</a>
                </li>
                <li class="active">
                    <strong>Tạo Bài Viết</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Tạo Bài Viết</h5>
                    </div>
                    <div class="ibox-content">
                        <!-- Hiển thị lỗi nếu có -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Form tạo bài viết -->
                        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Tiêu đề -->
                            <div class="form-group">
                                <label for="title">Tiêu đề:</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            </div>

                            <!-- Code -->
                            <div class="form-group">
                                <label for="code">Mã bài viết:</label>
                                <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                            </div>

                            <!-- Danh mục -->
                            <div class="form-group">
                                <label for="category_id">Danh mục:</label>
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Thứ tự -->
                            <div class="form-group">
                                <label for="position">Thứ tự:</label>
                                <input type="number" name="position" class="form-control" value="{{ old('position') }}" required>
                            </div>

                            <!-- Nội dung -->
                            <div class="form-group">
                                <label for="body">Nội dung:</label>
                                <textarea name="body" class="form-control" rows="5" required>{{ old('body') }}</textarea>
                            </div>

                            <!-- Tóm tắt -->
                            <div class="form-group">
                                <label for="summary">Tóm tắt:</label>
                                <textarea name="summary" class="form-control" rows="3">{{ old('summary') }}</textarea>
                            </div>

                            <!-- Ảnh -->
                            <div class="form-group">
                                <label for="image_url">Ảnh:</label>
                                <input type="file" name="image_url" class="form-control">
                            </div>

                            <!-- Trạng thái -->
                            <div class="form-group">
                                <label for="status">Trạng thái:</label>
                                <select name="status" class="form-control" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                                    <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                                </select>
                            </div>

                            <!-- Nút lưu và hủy -->
                            <button type="submit" class="btn btn-primary">Lưu Bài Viết</button>
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
