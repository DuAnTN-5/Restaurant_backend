<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str; // Sử dụng Str để tạo slug tự động

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm với tìm kiếm và phân trang
    public function index(Request $request)
    {
        $search = $request->query('search'); // Lấy từ khóa tìm kiếm từ request

        if ($search) {
            // Tìm kiếm theo tên hoặc mô tả sản phẩm
            $products = Product::where('name', 'LIKE', "%{$search}%")
                               ->orWhere('description', 'LIKE', "%{$search}%")
                               ->paginate(10)
                               ->appends(['search' => $search]); // Giữ tham số tìm kiếm khi phân trang
        } else {
            // Hiển thị toàn bộ sản phẩm nếu không tìm kiếm
            $products = Product::paginate(10);
        }

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
            'price' => 'required|numeric', // Thêm phần giá
            'stock_quantity' => 'required|integer', // Số lượng kho
        ]);

        // Tạo slug tự động từ tên nếu người dùng không cung cấp slug
        $slug = $request->input('slug') ? $request->input('slug') : Str::slug($request->name);

        Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
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
            'price' => 'required|numeric', // Thêm phần giá
            'stock_quantity' => 'required|integer', // Số lượng kho
        ]);

        // Tạo slug tự động từ tên nếu người dùng không cung cấp slug
        $slug = $request->input('slug') ? $request->input('slug') : Str::slug($request->name);

        $product->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
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
