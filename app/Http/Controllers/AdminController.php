<?php

namespace App\Http\Controllers;
use Stroage;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //change password page
    public function changePasswordPage(){
        return view('admin.account.changePassword');
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
    // direct admin details page
    public function details(){
        return view('admin.account.details');
    }
    // direct admin profile page
    public function edit(){
        return view('admin.account.edit');
    }

    //update admin profile
    public function update($id,Request $request){
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
        return redirect()->route('admin#details')->with(['updateSuccess' => 'Admin Account Updated....']);
    }

    //admin list
    public function list(){
        $admin = User::when(request('key'),function($query){
                        $query->orWhere('name','like','%'.request('key').'%')
                              ->orWhere('email','like','%'.request('key').'%')
                              ->orWhere('gender','like','%'.request('key').'%')
                              ->orWhere('phone','like','%'.request('key').'%')
                              ->orWhere('address','like','%'.request('key').'%');
                        })
                        ->where('role','admin')->paginate(3);
        $admin->appends(request()->all());
        return view('admin.account.list',compact('admin'));
    }

    // change admin role
    public function changeRole($id){
        $account = User::where('id',$id)->first();
        return view('admin.account.changeRole',compact('account'));
    }

    // change

    public function change($id, Request $request){
        $data = $this->requestUserData($request);
        User::where('id',$id)->update($data);
        return redirect()->route('admin#list');
    }

    // change user in userlist
    public function changeUser($id){
        $user = User::where('id',$id)->first();
        return view('admin.user.edit',compact('user'));
    }

    // change user data in userlist
   public function changeUserData($id, Request $request){
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'gender' => $request->gender
            ];
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = uniqid() . $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('public',$fileName);
                $data['image'] = $fileName;
            };
            User::where('id',$id)->update($data);
            return redirect()->route('admin#userList');
   }

    // request user data
    private function requestUserData($request){
        return [
            'role' => $request->role
        ];
    }

    //delete account
    public function delete($id){
       User::where('id',$id)->delete();
       return back()->with(['deleteSuccess' => 'Admin Account Deleted...']);
    }

    // request user data for update admin profile
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
