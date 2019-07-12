<?php
use App\Category;
use App\Product;
use Illuminate\Http\Request;

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/', function () {
        $categories = Category::all();
        return $categories;
    });

    $router->post('/', function (Request $req) {
        $category = new Category;
        $category->name = $req->name;
        $category->description = $req->description;
        if ($category->save()) {
            return [
                'status' => true,
                'message' => "berhasil menambahkan $category->name"
            ];
        }
        else {
            return [
                'status' => false,
                'message' => "data wajib diisi"
            ];
        }
    });

    $router->get('/{id}', function ($id) {
        $category = Category::find($id);
        if ($category) {
            $category->products;
            return [
                'status' => true,
                'data' => $category
            ];
        }
        return [
            'status' => false,
            'message' => 'kategori tidak ditemukan'
        ];
    });

    $router->put('/{id}', function ($id, Request $req) {
        $category = Category::find($id);
        if ($category) {
            $category->name = $req->name;
            $category->description = $req->description;
            if ($category->save()) {
                return [
                    'status' => true,
                    'message' => "berhasil mengubah detail $category->name"
                ];
            }
            else {
                return [
                    'status' => false,
                    'message' => "gagal mengubah detail $category->name"
                ];
            }
        }
        return [
            'status' => false,
            'message' => "kategori tidak ditemukan"
        ];
    });

    $router->delete('/{id}', function ($id) {
        $category = Category::find($id);
        if ($category) {
            app('db')->transaction( function () use (&$id, &$category) {
                Product::where('category_uuid', $id)->update(['category_uuid' => 0]);
                $category->delete();
                $id = 0;
            });
            if ($id == 0) {
                return [
                    'status' => true,
                    'message' => "berhasil manghapus $category->name"
                ];
            }
            return [
                'status' => false,
                'message' => "gagal menghapus $category->name"
            ];
        }
        return [
            'status' => false,
            'message' => "kategori tidak ditemukan"
        ];
    });
});