@extends('admin.layoutadmin')

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>Danh Sách Bài Viết</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route('admin') }}">Trang Chủ</a>
                </li>
                <li class="active">
                    <strong>Danh Sách Bài Viết</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2 text-right">
            <a href="{{ route('posts.create') }}" class="btn btn-primary" style="margin-top: 20px;">Tạo Bài Viết</a>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Danh Sách Bài Viết</h5>
                    </div>
                    <div class="ibox-content">
                        <!-- Form tìm kiếm -->
                        <div class="row">
                            <div class="col-lg-3 col-lg-offset-9 text-right">
                                <form method="GET" action="{{ route('posts.index') }}">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Tìm kiếm bài viết..." value="{{ request()->input('search') }}">
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
                                    <th>Tiêu Đề</th>
                                    <th>Hình Ảnh</th>
                                    <th>Thứ Tự</th>
                                    <th>Tình Trạng</th>
                                    <th>Ngày Tạo</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $post)
                                    <tr>
                                        <td>{{ $post->id }}</td>
                                        <td>
                                            {{ $post->title }}
                                            <br>
                                            <small style="color: red;">Danh mục :
                                                {{ $post->category->name ?? 'Không có' }}</small>
                                        </td>
                                        <td>
                                            @if ($post->image_url)
                                                <img src="{{ $post->image_url }}" alt="Image" width="80">
                                            @else
                                                <img src="{{ asset('default-post.png') }}" alt="Default Image"
                                                    width="50">
                                            @endif
                                        </td>
                                        <td>{{ $post->position ?? 'Không xác định' }}</td>
                                        <td>
                                            @if ($post->status == 'published')
                                                <span class="badge badge-success">Đã xuất bản</span>
                                            @elseif ($post->status == 'draft')
                                                <span class="badge badge-warning">Nháp</span>
                                            @else
                                                <span class="badge badge-secondary">Lưu trữ</span>
                                            @endif
                                        </td>
                                        <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-success">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">
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
                            {{ $posts->appends(request()->input())->links('pagination::bootstrap-4') }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
