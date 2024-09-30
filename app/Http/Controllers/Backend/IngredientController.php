<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Str;

class IngredientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search'); // Tìm kiếm
        $query = Ingredient::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $ingredients = $query->paginate(10)->appends(['search' => $search]);

        return view('admin.Ingredients.index', compact('ingredients', 'search'));
    }

    public function create()
    {
        return view('admin.Ingredients.create');
    }

    public function store(Request $request, FlasherInterface $flasher)
    {
        $request->validate([
            'name' => 'required|max:255',
            'quantity' => 'required|numeric',
            'status' => 'required',
        ]);

        // Tạo slug tự động từ tên nguyên liệu
        $slug = Str::slug($request->name);

        Ingredient::create([
            'name' => $request->name,
            'slug' => $slug,
            'quantity' => $request->quantity,
            'status' => $request->status,
        ]);

        $flasher->addSuccess('Nguyên liệu đã được thêm thành công!');
        return redirect()->route('ingredients.index');
    }

    public function edit($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        return view('admin.Ingredients.edit', compact('ingredient'));
    }

    public function update(Request $request, $id, FlasherInterface $flasher)
    {
        $ingredient = Ingredient::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'quantity' => 'required|numeric',
            'status' => 'required',
        ]);

        // Tạo slug tự động từ tên
        $slug = Str::slug($request->name);

        $ingredient->update([
            'name' => $request->name,
            'slug' => $slug,
            'quantity' => $request->quantity,
            'status' => $request->status,
        ]);

        $flasher->addSuccess('Nguyên liệu đã được cập nhật!');
        return redirect()->route('ingredients.index');
    }

    public function destroy($id, FlasherInterface $flasher)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();

        $flasher->addSuccess('Nguyên liệu đã được xóa!');
        return redirect()->route('ingredients.index');
    }
}
