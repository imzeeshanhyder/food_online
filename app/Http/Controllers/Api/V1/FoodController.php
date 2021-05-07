<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodItems;
use App\Models\FoodCategory;
use App\Models\FoodItemCategories;
use Validator;

class FoodController extends Controller {

    public function index() {
        try {
            $food = FoodItems::where('status', 1)->orderBy('id')->get();
            if (count($food) > 0) {
                $result = array(
                    'food' => $food
                );
                return response()->json(['message' => 'Food listed successfully ', 'status' => true, 'result' => $result], 200);
            } else {
                return response()->json(['message' => 'Sorry no food found', 'status' => false], 201);
            }
        } catch (Exception $exception) {
            report($exception);
            return response()->json(['message' => $exception->getMessage(), 'status' => false], 500);
        }
    }

    /*     * ******* Add Food ************** */

    public function postFood() {
        //Validating Post Fields.
        //Only Unique Food Name will be allowed
        $validator = Validator::make(request()->all(), [
                    'name' => 'required|unique:food_items|max:255',
                    'description' => 'required',
                    'menu_id' => 'required|numeric',
                    'price' => 'required|numeric',
                    'status' => 'in:1,0'
        ]);

        if ($validator->passes()) {
            try {
                $food = new FoodItems();

                //Upload Image
                if (!empty(request()->file('image'))) {
                    if (request()->file('image')->isValid()) {
                        $destinationPath = public_path('media/images/Foods'); // upload path
                        $fileName = time() . request()->file('image')->getClientOriginalName();
                        request()->file('image')->move($destinationPath, $fileName);
                        $food->image = $fileName;
                    }
                }
                $food->menu_id = request()->get('menu_id');
                $food->name = request()->get('name');
                $food->price = request()->get('price');
                $food->description = request()->get('description');
                $food->quantity = (request()->get('quantity') > 0) ? request()->get('quantity') : 0;
                $food->quantity_per_day = request()->get('quantity_per_day') ? 1 : 0;
                $food->status = request()->get('status') ? 1 : 0;
                $food->save();

                // Save Food Item Categories Start
                if (count(request()->get('category_ids')) > 0) {
                    foreach (request()->get('category_ids') as $category_ids) {
                        if ($category_ids > 0) {
                            $foodCategory = FoodCategory::find($category_ids);
                            //Checking the Category Id is Valid
                            if ($foodCategory) {
                                $foodItemCategory = new FoodItemCategories();
                                $foodItemCategory->food_id = $food->id;
                                $foodItemCategory->category_id = $category_ids;
                                $foodItemCategory->save();
                            }
                        }
                    }
                }
                // Save Food Item Categories End

                return response()->json(['message' => 'Food added successfully', 'status' => true], 200);
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

    public function putFood($id = 0) {
        //dd(request()->all());
        $validator = Validator::make(request()->all(), [
                    'name' => 'required|max:255',
                    'description' => 'required',
                    'menu_id' => 'required|numeric',
                    'price' => 'required|numeric',
                    'status' => 'in:1,0'
        ]);

        if ($validator->passes()) {
            $food = FoodItems::find($id);
            if ($food) {
                //updating food image            
                if (!empty(request()->file('image'))) {
                    if (request()->file('image')->isValid()) {
                        $destinationPath = public_path('media/images/Foods'); // upload path
                        //delte image
                        $image_name = $food->image;
                        if (!empty($image_name) && file_exists("$destinationPath/$image_name")) {
                            unlink("$destinationPath/$image_name");
                        }
                        $fileName = time() . request()->file('image')->getClientOriginalName();
                        request()->file('image')->move($destinationPath, $fileName);
                        $food->image = $fileName;
                    }
                }

                $food->menu_id = request()->get('menu_id');
                $food->name = request()->get('name');
                $food->price = request()->get('price');
                $food->description = request()->get('description');
                $food->quantity = request()->get('quantity');
                $food->quantity_per_day = request()->get('quantity_per_day') ? 1 : 0;
                $food->status = request()->get('status') ? 1 : 0;
                $food->save();
                // Save Food Item Categories Start
                if (count(request()->get('category_ids')) > 0) {
                    //Deleting Old Entries
                    FoodItemCategories::where('food_id', $food->id)->delete();

                    foreach (request()->get('category_ids') as $category_ids) {
                        if ($category_ids > 0) {
                            $foodCategory = FoodCategory::find($category_ids);
                            //Checking the Category Id is Valid
                            if ($foodCategory) {
                                $foodItemCategory = new FoodItemCategories();
                                $foodItemCategory->food_id = $food->id;
                                $foodItemCategory->category_id = $category_ids;
                                $foodItemCategory->save();
                            }
                        }
                    }
                }
                // Save Food Item Categories End
                return response()->json(['message' => 'Food updated successfully', 'status' => true], 200);
            } else {
                return response()->json(['message' => 'Error Data not found', 'status' => false], 404);
            }
        } else {
            $errors = $validator->messages();
            return response()->json(['message' => 'Error Data', 'status' => false, 'Error' => $errors], 201);
        }
    }

    /*     * ******* Delete Food ************** */

    public function deleteFood($id = 0) {

        try {
            $food = FoodItems::find($id);

            if ($food) {
                //Soft Delete
                $food->delete();
                return response()->json(['message' => 'Food deleted successfully', 'status' => true], 200);
            } else {
                return response()->json(['message' => 'Error Data not found', 'status' => false], 404);
            }
        } catch (Exception $exception) {
            report($exception);
            return response()->json(['message' => $exception->getMessage(), 'status' => false], 500);
        }
    }

}
