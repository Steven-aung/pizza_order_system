<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RouteController extends Controller
{
    //get all product list
    public function productList(){
        $products = Product::get();

        return response()->json($products, 200);
    }

    // get all category
    public function categoryList(){
        $category = Category::orderBy('id','desc')->get();
        return response()->json($category, 200);
    }
    //get all user
    public function userList(){
        $user = User::get();
        $order = Order::get();
        $order_list = OrderList::get();
        $contact = Contact::get();
        $data = [
            'user' => $user,
            'order' => $order,
            'order_list' => $order_list,
            'contact' => $contact
        ];
        return response()->json($data, 200);

    }

    // create category
    public function categoryCreate(Request $request){
        $data = [
            'name' => $request->name,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
        $response = Category::create($data);
        return response()->json($response, 200);
    }

    // create contact
    public function createContact(Request $request){
        $data = $this->getContactData($request);
        Contact::create($data);
        $contact = Contact::orderBy('created_at','desc')->get();
        return response()->json($contact, 200);

    }


    // delete category
    public function deleteCategory($id){
        $data = Category::where('id',$id)->first();
        if(isset($data)){
            Category::where('id',$id)->delete();
            return response()->json(['status' => true,'message' => 'delete success','deleteData' => $data], 200);
        }
        return response()->json(['status' => false,'message' => 'Insert data not found in database'], 200);
    }

    //category details
    public function categoryDetails($id){
        $data = Category::where('id',$id)->first();
        if(isset($data)){
            return response()->json(['status' => true,'category' => $data], 200);
        }
        return response()->json(['status' => false,'category' => 'There is no category...'], 500);
    }

    // update category
    public function categoryUpdate(Request $request){
        $categoryId = $request->category_id;
        $dbSource = Category::where('id',$categoryId)->first();
        if(isset($dbSource)){
            $data = $this->getCategoryData($request);
            Category::where('id',$categoryId)->update($data);
            $response = Category::where('id',$categoryId)->first();
            return response()->json(['status' => true,'message' => 'category update success...','category' => $response], 200);
        }
        return response()->json(['status' => false,'message' => 'There is no category for update'], 500);

    }

    //get category data
    private function getCategoryData($request){
        return [
            'name' =>$request->category_name,
            'updated_at' => Carbon::now()
        ];
    }
    //get contact data
    private function getContactData($request){
        return [
            'name' =>$request->name,
            'email' => $request->email,
            'message' => $request->message,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ];
    }

}
