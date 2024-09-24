@extends('admin.layoutadmin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    @flasher_render
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2(); // Khởi tạo select2
        });
    </script>
@endpush

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Chỉnh Sửa Danh Mục</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin') }}">Trang Chủ</a></li>
            <li><a>Quản Lý Danh Mục</a></li>
            <li class="active"><strong>Chỉnh Sửa</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Chỉnh Sửa Thông Tin Danh Mục</h5>
                </div>
                <div class="ibox-content">
                    <form method="POST" action="{{ route('categories.update', $category->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Category Name -->
                        <div class="form-group">
                            <label for="name">Tên Danh Mục <span class="text-danger">(*)</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                        </div>

                        <!-- Parent Category -->
                        <div class="form-group">
                            <label for="parent_id">Danh Mục Cha</label>
                            <select name="parent_id" id="parent_id" class="form-control select2">
                                <option value="">[Chọn Danh Mục Cha]</option>
                                @foreach ($categories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}" {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                        {{ $parentCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Mô Tả</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                        </div>

                        <!-- Image Upload -->
                        <div class="form-group">
                            <label for="image">Ảnh Danh Mục</label>
                            <input type="file" name="image" id="image" class="form-control">
                            @if ($category->image)
                                <img src="{{ asset($category->image) }}" height="64" alt="Current Image">
                            @endif
                        </div>

                        <!-- SEO Information -->
                        <div class="form-group">
                            <label for="meta_title">Thẻ Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" class="form-control" value="{{ old('meta_title', $category->meta_title) }}">
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Thẻ Meta Description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="3">{{ old('meta_description', $category->meta_description) }}</textarea>
                        </div>

                        <!-- Save Button -->
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Cập Nhật</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
