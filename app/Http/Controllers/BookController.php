<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index()
    {
        $data = Books::all();

        return response()->json($data);
    }

    public function create(Request $request)
    {
        // Định nghĩa rules cho validation
        $rules = [
            'name' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/',
            'author' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/',
            'publishing_year' => 'required|numeric',
            'cate_id' => 'required|numeric',
        ];

        // Tạo một instance của Validator
        $validator = Validator::make($request->all(), $rules);

        // Kiểm tra xem validation có pass hay không
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $data = Books::create([
                'name' => preg_replace('/[^a-zA-Z0-9\s]/', '', $request->input('name')),
                'author' => preg_replace('/[^a-zA-Z0-9\s]/', '', $request->input('author')),
                'publishing_year' => $request->input('publishing_year'),
                'cate_id' => $request->input('cate_id'),
            ]);
        } catch (\Throwable $th) {
            return response()->json('error: ' . $th, 303);
        }

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        try {
            $data = Books::findOrFail($id);
            $data->update($request->all());
        } catch (\Throwable $th) {
            return response()->json('error: ' . $th);
        }

        return response()->json($data);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $results = Books::where('name', 'LIKE', "%{$keyword}%")
            ->orWhere('author', 'like', "%{$keyword}%")
            ->orWhere('cate_id', 'like', "%{$keyword}%")
            ->get();

        return response()->json($results);
    }

    public function delete($id)
    {
        try {
            $data = Books::findOrFail($id);
            $data->delete();

            return response()->json('Xóa dữ liệu thành công');
        } catch (ModelNotFoundException $e) {
            return response()->json('Không tìm thấy dữ liệu để xóa', 404);
        }
    }

    public function restore($id)
    {
        try {
            $data = Books::onlyTrashed()->findOrFail($id);

            if ($data->trashed()) {
                $data->restore();
                return response()->json('Khôi Phục Thành Công');
            } else {
                return response()->json('Bản Ghi Không Bị Xóa, không cần khôi phục ');
            }
        } catch (ModelNotFoundException $e) {
            return response()->json('Không Tìm Thấy Dữ Liệu Để Khôi Phục', 404);
        }
    }
}
