@extends('admin.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Thêm Bài Viết Mới</h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">Trang Chủ</a></li>
                <li><a>Quản Lý Bài Viết</a></li>
                <li class="active"><strong>Thêm Mới</strong></li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Thông tin bài viết</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Title and Position -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="title">Tiêu Đề <span class="text-danger">(*)</span></label>
                                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="position">Thứ Tự</label>
                                        <input type="number" name="position" id="position" class="form-control" value="{{ old('position') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Image -->
                            <div class="form-group">
                                <label for="image">Hình Ảnh</label>
                                <input type="file" name="image" id="image" class="form-control">
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label for="status">Trạng Thái</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt Động</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không Hoạt Động</option>
                                </select>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">Lưu</button>
                                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
