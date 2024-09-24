@extends('admin.layoutadmin')
{{-- @push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endpush --}}
@section('content')
@flasher_render
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Thêm Người Dùng Mới</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin') }}">Trang Chủ</a></li>
            <li><a>Quản Lý Người Dùng</a></li>
            <li class="active"><strong>Thêm Mới</strong></li>
        </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <!-- General Information -->
    <div class="row">
        <div class="col-lg-5">
            <div class="panel-head">
                <div class="panel-title"><h3>Thông Tin Chung</h3></div>
                <div class="panel-description">
                    <p>Nhập thông tin chung của người sử dụng</p>
                    <p>Lưu ý: Những trường đánh dấu <span class="text-danger">(*)</span> là bắt buộc</p>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thông tin chung</h5>
                </div>
                <div class="ibox-content">
                    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                        @csrf
                        <!-- Email and Name Row -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">(*)</span></label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Họ Tên <span class="text-danger">(*)</span></label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                                </div>
                            </div>
                        </div>
                         <!-- Password and Password Confirmation Row -->
                         <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="password">Mật Khẩu <span class="text-danger">(*)</span></label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Xác Nhận Mật Khẩu <span class="text-danger">(*)</span></label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <!-- Date of Birth and Gender Row -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="date_of_birth">Ngày Sinh</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="sex">Giới Tính</label>
                                    <select name="sex" class="form-control">
                                        <option value="Nam" {{ old('sex') == 'Nam' ? 'selected' : '' }}>Nam</option>
                                        <option value="Nữ" {{ old('sex') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Avatar Upload Row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="image">Ảnh đại diện</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="row">
        <div class="col-lg-5">
            <div class="panel-head">
                <div class="panel-title"><h3>Thông Tin Liên Hệ</h3></div>
                <div class="panel-description">
                    Nhập thông tin liên hệ của người sử dụng
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thông tin liên hệ</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <!-- Thành Phố -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="province_code">Mã Tỉnh/Thành Phố</label>
                                <input type="text" name="province_code" id="province_code" class="form-control" value="{{ old('province_code') }}">
                            </div>
                        </div>
                        <!-- Quận/Huyện -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="district_code">Mã Quận/Huyện</label>
                                <input type="text" name="district_code" id="district_code" class="form-control" value="{{ old('district_code') }}">
                            </div>
                        </div>
                    </div>
                    <!-- Phường/Xã và Địa chỉ -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="ward_code">Mã Phường/Xã</label>
                                <input type="text" name="ward_code" id="ward_code" class="form-control" value="{{ old('ward_code') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}">
                            </div>
                        </div>
                    </div>
                    <!-- Số điện thoại -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="phone_number">Số điện thoại</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}">
                            </div>
                        </div>
                    </div>
                    <!-- Facebook ID và Google ID -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="facebook_id">Facebook ID</label>
                                <input type="text" name="facebook_id" id="facebook_id" class="form-control" value="{{ old('facebook_id') }}">
                                <!-- Hiển thị liên kết Facebook nếu có -->
                                @if(old('facebook_id'))
                                    <a href="https://www.facebook.com/{{ old('facebook_id') }}" target="_blank">Xem tài khoản Facebook</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="google_id">Google ID</label>
                                <input type="text" name="google_id" id="google_id" class="form-control" value="{{ old('google_id') }}">
                                <!-- Chỉ hiển thị Google ID, không có URL -->
                                @if(old('google_id'))
                                    <p>ID Google: {{ old('google_id') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Nút hành động -->
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Thêm Mới</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection
