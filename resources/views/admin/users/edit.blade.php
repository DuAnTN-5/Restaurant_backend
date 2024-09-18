@extends('admin.layoutadmin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <style>
        .form-group {
            margin-bottom: 15px; /* Adjust spacing */
        }

        .form-group label {
            font-weight: bold;
            font-size: 13px; /* Adjust font size */
            margin-bottom: 5px;
            display: block;
        }

        .form-control {
            border-radius: 4px;
            border: 1px solid #ccc;
            padding: 6px; /* Adjust padding */
            height: 34px; /* Adjust height */
            font-size: 13px; /* Adjust font size */
            max-width: 100%; /* Ensure it doesn't exceed width */
        }

        .ibox-title {
            background-color: #f3f3f4;
            border-bottom: 1px solid #e7eaec;
            color: #676a6c;
            padding: 10px; /* Adjust padding */
            margin-bottom: 10px;
        }

        .ibox-content {
            padding: 15px; /* Adjust padding */
            background-color: #ffffff;
            border: 1px solid #e7eaec;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .panel-title h3 {
            font-weight: 700;
            color: black;
            font-size: 20px;
        }

        .panel-description {
            font-size: 14px; /* Font size smaller than title */
            color: #666; /* Lighter font color */
        }

        .col-lg-6, .col-lg-12 {
            padding-left: 15px;
            padding-right: 15px;
        }
    </style> --}}
@endpush

@push('scripts')
    @flasher_render
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('backend/library/location.js') }}"></script> <!-- Tải location.js -->
    <script>
        $(document).ready(function() {
            $('.select2').select2(); // Khởi tạo select2
        });

        // Đặt giá trị cho các biến province_id, district_id, ward_id từ phía server
        var province_id = "{{ old('province_id', $user->province_id ?? '') }}";
        var district_id = "{{ old('district_id', $user->district_id ?? '') }}";
        var ward_id = "{{ old('ward_id', $user->ward_id ?? '') }}";
    </script>
@endpush


@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Chỉnh Sửa Người Dùng</h2>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin') }}">Trang Chủ</a></li>
            <li><a>Quản Lý Người Dùng</a></li>
            <li class="active"><strong>Chỉnh Sửa</strong></li>
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
                    <p>Chỉnh sửa thông tin của người dùng</p>
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
                    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Email and Name Row -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">(*)</span></label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Họ Tên <span class="text-danger">(*)</span></label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                                </div>
                            </div>
                        </div>
                        <!-- Password Row -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="password">Mật Khẩu</label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu mới nếu muốn thay đổi">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Xác Nhận Mật Khẩu</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Xác nhận mật khẩu mới nếu muốn thay đổi">
                                </div>
                            </div>
                        </div>
                        <!-- Date of Birth and Gender Row -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="date_of_birth">Ngày Sinh</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ $user->date_of_birth }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="sex">Giới Tính</label>
                                    <select name="sex" class="form-control">
                                        <option value="Nam" {{ $user->sex == 'Nam' ? 'selected' : '' }}>Nam</option>
                                        <option value="Nữ" {{ $user->sex == 'Nữ' ? 'selected' : '' }}>Nữ</option>
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
                                    @if ($user->image)
                                        <img src="{{ asset($user->image) }}" height="64" alt="Current Image">
                                    @endif
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
                    Chỉnh sửa thông tin liên hệ của người sử dụng
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Thông tin liên hệ</h5>
                </div>
                <div class="ibox-content">
                    <!-- Province and District Row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="province_id">Thành Phố</label>
                                <select name="province_id" id="province_id" class="form-control select2 location" data-target="districts">
                                    <option value="">[Chọn Thành Phố]</option>
                                    @if(isset($provinces))
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->code }}" {{ old('province_id', $user->province_id ?? '') == $province->code ? 'selected' : '' }}>{{ $province->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="district_id">Quận/Huyện</label>
                                <select name="district_id" id="district_id" class="form-control select2 location districts" data-target="wards">
                                    <option value="">[Chọn Quận/Huyện]</option>
                                    {{-- Options sẽ được tải động từ JS --}}
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ward and Address Row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="ward_id">Phường/Xã</label>
                                <select name="ward_id" id="ward_id" class="form-control select2 wards">
                                    <option value="">[Chọn Phường/Xã]</option>
                                    {{-- Options sẽ được tải động từ JS --}}
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="address">Địa chỉ</label>
                                <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $user->address ?? '') }}">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Phone Row -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" name="phone" id="phone" class="form-control" value="{{ $user->phone }}">
                            </div>
                        </div>
                    </div>
                    <!-- Action Buttons -->
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Cập Nhật</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
