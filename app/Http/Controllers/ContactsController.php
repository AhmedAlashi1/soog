<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactsController extends Controller
{
    public function contact (){
       
        return view('contact.index');
    }

    public function get_contacts (){
//        $contacts = Contact::orderBy('id' , 'desc')->get();
        $contacts = Contact::latest()->get();
//        $contacts = DB::table('contact')->latest('created_at')->get();
        if ($contacts) {
            return response()->json([
                'message' => 'Data Found',
                'status' => 200,
                'data' => $contacts
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }

    public function delete ($id){
        $contact = Contact::find($id);
        if ($contact) {
            $contact->delete();
            return response()->json([
                'message' => trans('category.success_delete_property'),
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'message' => 'Data Not Found',
                'status' => 404,
            ]);
        }
    }
}
