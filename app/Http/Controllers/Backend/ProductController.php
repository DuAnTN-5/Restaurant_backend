<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // Sử dụng đúng model Product
use Flasher\Prime\FlasherInterface;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        // Lấy tất cả các sản phẩm
        $products = Product::all();
        return view('admin.Products.index', compact('products'));
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
            'slug' => 'required|unique:products,slug',
            'description' => 'nullable',
            'price' => 'required|numeric', // Thêm phần giá
            'stock_quantity' => 'required|integer', // Số lượng kho
        ]);

        Product::create([
            'name' => $request->name,
            'slug' => $request->slug,
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
            'slug' => 'required|unique:products,slug,' . $product->id,
            'description' => 'nullable',
            'price' => 'required|numeric', // Thêm phần giá
            'stock_quantity' => 'required|integer', // Số lượng kho
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => $request->slug,
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
