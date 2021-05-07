<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodCategory;
use Validator;

class FoodCategoryController extends Controller
{
    /*     * ******* List FoodCategory ************** */

    public function index() {
        try {
            $categories = FoodCategory::where('status', 1)->orderBy('sort_order')->get();
            if (count($categories) > 0) {
                $result = array(
                    'categories' => $categories
                );
                return response()->json(['message' => 'FoodCategory listed successfully ', 'status' => true, 'result' => $result], 200);
            } else {
                return response()->json(['message' => 'Sorry no FoodCategory found', 'status' => false], 201);
            }
        } catch (Exception $exception) {
            report($exception);
            return response()->json(['message' => $exception->getMessage(), 'status' => false], 500);
        }
    }

    /*     * ******* Add FoodCategory ************** */

    public function postFoodCategory() {
        //Validating Post Fields.
        //Only Unique FoodCategory Name will be allowed
        $validator = Validator::make(request()->all(), [
                    'name' => 'required|unique:food_categories|max:255',
                    'description' => 'required',
                    'status' => 'in:1,0'
        ]);

        if ($validator->passes()) {
            try {
                $category = new FoodCategory();
                $category->name = request()->get('name');
                $category->description = request()->get('description');
                $category->sort_order = request()->get('sort_order') ? request()->get('sort_order') : 0;
                $category->status = request()->get('status') ? 1 : 0;
                $category->save();
                return response()->json(['message' => 'FoodCategory added successfully', 'status' => true], 200);
            } catch (Exception $exception) {
                report($exception);
                return response()->json(['message' => $exception->getMessage(), 'status' => false], 500);
            }
        } else {
            $errors = $validator->messages();
            return response()->json(['message' => 'Error Data', 'status' => false, 'Error' => $errors], 201);
        }
    }

    /*     * ******* Update FoodCategory ************** */

    public function putFoodCategory($id = 0) {
        //dd(request()->all());
        $validator = Validator::make(request()->all(), [
                    'name' => 'required|max:255',
                    'description' => 'required',
                    'status' => 'in:1,0'
        ]);

        if ($validator->passes()) {
            $category = FoodCategory::find($id);
            if ($category) {
                $category->name = request()->get('name');
                $category->description = request()->get('description');
                $category->sort_order = request()->get('sort_order') ? request()->get('sort_order') : 0;
                $category->status = request()->get('status') ? 1 : 0;
                $category->save();
                return response()->json(['message' => 'FoodCategory updated successfully', 'status' => true], 200);
            } else {
                return response()->json(['message' => 'Error Data not found', 'status' => false], 404);
            }
        } else {
            $errors = $validator->messages();
            return response()->json(['message' => 'Error Data', 'status' => false, 'Error' => $errors], 201);
        }
    }

    /*     * ******* Delete FoodCategory ************** */

    public function deleteFoodCategory($id = 0) {

        try {
            $category = FoodCategory::find($id);

            if ($category) {
                //Soft Delete
                $category->delete();
                return response()->json(['message' => 'FoodCategory deleted successfully', 'status' => true], 200);
            } else {
                return response()->json(['message' => 'Error Data not found', 'status' => false], 404);
            }
        } catch (Exception $exception) {
            report($exception);
            return response()->json(['message' => $exception->getMessage(), 'status' => false], 500);
        }
    }
}
