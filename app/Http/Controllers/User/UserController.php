<?php

namespace App\Http\Controllers\User;
use stroage;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //direct user home
    public function home(){
        $pizza = Product::orderBy('created_at','desc')->get();
        $category = Category::get();
        $cart = Cart::where('user_id',Auth::user()->id)->get();
        $history = Order::where('user_id',Auth::user()->id)->get();
        return view('user.main.home',compact('pizza','category','cart','history'));
    }

    // direct user list page
    public function userList(){
        $users = User::where('role','user')->paginate(3);
        return view('admin.user.list',compact('users'));
    }

    // change user role
     public function userChangeRole(Request $request){
        $updateSource = [
            'role' => $request->role
        ];
        User::where('id',$request->userId)->update($updateSource);

     }

     // direct contact page
     public function contact(){
        return view('user.contact.contact');
     }

     // store contact data to database
     public function storeContact(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);
        Contact::create($request->all());
        return redirect()->back()->with(['success' => 'Thank you for contact us.']);
     }

     // draw data from contact table
     public function message(){
            $contact = Contact::get();

            return view('admin.message.message',compact('contact'));
     }

     public function delete($id){
        User::where('id',$id)->delete();
        return back()->with(['deleteSuccess' => 'User Account Deleted...']);
     }

    //direct change password page
    public function changePasswordPage(){
        return view('user.password.change');
    }

    //change password
    public function changePassword(Request $request){
        $this->passwordValidationCheck($request);

        $user = User::select('password')->where('id',Auth::user()->id)->first();
        $dbHashValue = $user->password;

        if(Hash::check($request->oldPassword, $dbHashValue)){
            $data = [
                'password' => Hash::make($request->newPassword)
            ];
            User::where('id',Auth::user()->id)->update($data);
            // Auth::logout();
            // return redirect()->route('auth#loginPage');
            return back()->with(['changeSuccess'=>'Password ပြောင်းလဲခြင်းအောင်မြင်ပါသည်']);
        }
        return back()->with(['notMatch' => 'Old Password မတူညီပါ။ ထပ်မံစမ်းကြည့်ပါ!']);
    }

    // user account change
    public function accountChangePage(){
        return view('user.profile.account');
    }

    //filter pizza
    public function filter($categoryId){
        $pizza = Product::where('category_id',$categoryId)->orderBy('created_at','desc')->get();
        $category = Category::get();
        $cart = Cart::where('user_id',Auth::user()->id)->get();
        $history = Order::where('user_id',Auth::user()->id)->get();
        return view('user.main.home',compact('pizza','category','cart','history'));
    }

    // account change
    public function accountChange($id,Request $request){
        $this->accountValidationCheck($request);
        $data = $this->getUserData($request);

        if($request->hasFile('image')){
             $dbImage = User::where('id',$id)->first();
             $dbImage = $dbImage->image;
             if($dbImage != null){
                Storage::delete('public/'.$dbImage);
             }
            $fileName = uniqid() . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('public',$fileName);
            $data['image'] = $fileName;
        }

        User::where('id',$id)->update($data);
        return back()->with(['updateSuccess' => 'Admin Account Updated....']);
    }
    

    //direct pizza details
    public function pizzaDetails($pizzaId){
        $pizza = Product::where('id',$pizzaId)->first();
        $pizzaList = Product::get();
        return view('user.main.details',compact('pizza','pizzaList'));
    }

    // cart list

    public function cartList(){
        $cartList = Cart::select('carts.*','products.name as pizza_name','products.price as pizza_price','products.image as product_image')
                            ->leftjoin('products','products.id','carts.product_id')
                            ->where('carts.user_id',Auth::user()->id)
                            ->get();
        $totalPrice = 0;
        foreach($cartList as $c){
            $totalPrice += $c->pizza_price*$c->qty;

        }

        return view('user.main.cart',compact('cartList','totalPrice'));

    }

     // request user data
     private function getUserData($request){
        return [
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'address' => $request->address,
            'updated_at' => Carbon::now()
        ];
        return back();
    }

    // direct history page
        public function history(){
            $order = Order::where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(6);
            return view('user.main.history',compact('order'));
        }
     // account validation check for update profile
     private function accountValidationCheck($request){
        Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'image' => 'mimes:png,jpg,jpeg,webp|file',
            'address' => 'required',
        ],[
            'name.required' => 'Name field ဖြည့်ရန်လိုအပ်ပါသည်',
            'email.required' => 'Email field ဖြည့်ရန်လိုအပ်ပါသည်',
            'gender.required' => 'Gender field ဖြည့်ရန်လိုအပ်ပါသည်',
            'phone.required' => 'Phone field ဖြည့်ရန်လိုအပ်ပါသည်',
            'image.mimes' => 'png,jpg,jpeg file type သာဖြစ်ရပါမည်',
            'address.required' => 'Address field ဖြည့်ရန်လိုအပ်ပါသည်',
        ])->validate();
    }

    // password validation check
    private function passwordValidationCheck($request){
        Validator::make($request->all(),[
            'oldPassword' => 'required|min:6|max:10',
            'newPassword' => 'required|min:6|max:10',
            'confirmPassword' => 'required|min:6|max:10|same:newPassword',


        ],[
            'oldPassword.required' => 'Old Password ဖြည့်ရန်လိုအပ်ပါသည်',
            'newPassword.required' => 'New Password ဖြည့်ရန်လိုအပ်ပါသည်',
            'confirmPassword.required' => 'Confirm Password ဖြည့်ရန်လိုအပ်ပါသည်',
            'oldPassword.min' => 'Password သည်ခြောက်လုံးနှင့်အထက်ဖြစ်ရပါမည်',
            'newPassword.min' => 'Password သည်ခြောက်လုံးနှင့်အထက်ဖြစ်ရပါမည်',
            'confirmPassword.min' => 'Password သည်ခြောက်လုံးနှင့်အထက်ဖြစ်ရပါမည်',
            'oldPassword.max' => 'Password သည်ဆယ်လုံးထက် မကျော်ရပါ',
            'newPassword.max' => 'Password သည်ဆယ်လုံးထက် မကျော်ရပါ',
            'confirmPassword.max' => 'Password သည်ဆယ်လုံးထက် မကျော်ရပါ',
            'confirmPassword.same' => 'Password သည် New Password နှင့်ထပ်တူညီရပါမည်'
        ])->validate();

    }
}
