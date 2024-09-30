<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search');

        // Tìm kiếm theo tên hoặc mô tả sản phẩm
        $products = Product::when($search, function ($query) use ($search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('description', 'LIKE', "%{$search}%");
        })->paginate(10)->appends(['search' => $search]);

        return view('admin.Products.index', compact('products', 'search'));
    }

    // Hiển thị form tạo sản phẩm mới
    public function create()
    {
        return view('admin.Products.create');
    }

    // Lưu sản phẩm vào cơ sở dữ liệu
    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'stock_quantity' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Nếu có upload ảnh
        ]);

        // Tạo slug tự động từ tên và kiểm tra trùng lặp
        $slug = Str::slug($request->name);
        if (Product::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        // Xử lý upload ảnh
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageUrl = $file->store('images/products', 'public');
        }

        // Tạo sản phẩm mới
        Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image_url' => $imageUrl,
        ]);

        // Thông báo thêm thành công
        $flasher->addSuccess('Sản phẩm đã được thêm thành công!');

        return redirect()->route('products.index');
    }

    // Hiển thị form chỉnh sửa sản phẩm
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.Products.edit', compact('product'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'stock_quantity' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Nếu có upload ảnh
        ]);

        // Tạo slug tự động từ tên nếu không cung cấp
        $slug = Str::slug($request->name);
        if (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $slug . '-' . time();
        }

        // Xử lý cập nhật ảnh nếu có upload ảnh mới
        $imageUrl = $product->image_url;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageUrl = $file->store('images/products', 'public');
        }

        // Cập nhật sản phẩm
        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image_url' => $imageUrl,
        ]);

        // Thông báo cập nhật thành công
        $flasher->addSuccess('Sản phẩm đã được cập nhật!');

        return redirect()->route('products.index');
    }

    // Xóa sản phẩm
    public function destroy($id, FlasherInterface $flasher)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        // Thông báo xóa thành công
        $flasher->addSuccess('Sản phẩm đã được xóa!');

        return redirect()->route('products.index');
    }
}
