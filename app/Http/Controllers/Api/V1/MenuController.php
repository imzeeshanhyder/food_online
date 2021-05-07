<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Validator;

class MenuController extends Controller {
    /*     * ******* List Menu ************** */

    public function index() {
        try {
            $menus = Menu::where('status', 1)->orderBy('sort_order')->get();
            if (count($menus) > 0) {
                $result = array(
                    'menus' => $menus
                );
                return response()->json(['message' => 'Menu listed successfully ', 'status' => true, 'result' => $result], 200);
            } else {
                return response()->json(['message' => 'Sorry no menu found', 'status' => false], 201);
            }
        } catch (Exception $exception) {
            report($exception);
            return response()->json(['message' => $exception->getMessage(), 'status' => false], 500);
        }
    }

    /*     * ******* Add Menu ************** */

    public function postMenu() {
        //Validating Post Fields.
        //Only Unique Menu Name will be allowed
        $validator = Validator::make(request()->all(), [
                    'name' => 'required|unique:menus|max:255',
                    'description' => 'required',
                    'status' => 'in:1,0'
        ]);

        if ($validator->passes()) {
            try {
                $menu = new Menu();
                $menu->name = request()->get('name');
                $menu->description = request()->get('description');
                $menu->sort_order = request()->get('sort_order') ? request()->get('sort_order') : 0;
                $menu->status = request()->get('status') ? 1 : 0;
                $menu->save();
                return response()->json(['message' => 'Menu added successfully', 'status' => true], 200);
            } catch (Exception $exception) {
                report($exception);
                return response()->json(['message' => $exception->getMessage(), 'status' => false], 500);
            }
        } else {
            $errors = $validator->messages();
            return response()->json(['message' => 'Error Data', 'status' => false, 'Error' => $errors], 201);
        }
    }

    /*     * ******* Update Menu ************** */

    public function putMenu($id = 0) {
        //dd(request()->all());
        $validator = Validator::make(request()->all(), [
                    'name' => 'required|max:255',
                    'description' => 'required',
                    'status' => 'in:1,0'
        ]);

        if ($validator->passes()) {
            $menu = Menu::find($id);
            if ($menu) {
                $menu->name = request()->get('name');
                $menu->description = request()->get('description');
                $menu->sort_order = request()->get('sort_order') ? request()->get('sort_order') : 0;
                $menu->status = request()->get('status') ? 1 : 0;
                $menu->save();
                return response()->json(['message' => 'Menu updated successfully', 'status' => true], 200);
            } else {
                return response()->json(['message' => 'Error Data not found', 'status' => false], 404);
            }
        } else {
            $errors = $validator->messages();
            return response()->json(['message' => 'Error Data', 'status' => false, 'Error' => $errors], 201);
        }
    }

    /*     * ******* Delete Menu ************** */

    public function deleteMenu($id = 0) {

        try {
            $menu = Menu::find($id);

            if ($menu) {
                //Soft Delete
                $menu->delete();
                return response()->json(['message' => 'Menu deleted successfully', 'status' => true], 200);
            } else {
                return response()->json(['message' => 'Error Data not found', 'status' => false], 404);
            }
        } catch (Exception $exception) {
            report($exception);
            return response()->json(['message' => $exception->getMessage(), 'status' => false], 500);
        }
    }

}
