<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactsController extends Controller
{
    public function getContacts(){
        $contacts = Contacts::all();
        $changed_contacts = [];

        foreach ($contacts as $contact_item){
            $sorted_contacts = [
                'id' => $contact_item['id'],
                'whatsapp' => $contact_item['whatsapp'],
                'instagram' => $contact_item['instagram'],
                'facebook' => $contact_item['facebook'],
                'primary_phone' => $contact_item['first_phone'],
                'secondary_phone' => $contact_item['second_phone'],
                'address' => $contact_item['address'],
                'publishedAt' => $contact_item['updated_at'] ?? $contact_item['created_at'],

            ];
            array_push($changed_contacts, $sorted_contacts);
        }

        return response()->json([
            'data' => [
                'contacts' => $changed_contacts
            ]
        ], 200);

    }

    public function addContacts(Request $request){
        $validator = Validator::make($request->all(), [
            'whatsapp' => 'required|string',
            'instagram' => 'required|string',
            'facebook' => 'required|string',
            'first_phone' => 'required|string',
            'second_phone' => 'required|string',
            'address' => 'required|string'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ]
            ],422);
        }

        $contacts = new Contacts();

        $contacts->whatsapp = $request->whatsapp;
        $contacts->instagram = $request->instagram;
        $contacts->facebook = $request->facebook;
        $contacts->first_phone = $request->first_phone;
        $contacts->second_phone = $request->second_phone;
        $contacts->address = $request->address;

        $contacts->save();

        return response()->json([], 204);

    }

    public function updateContacts(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'whatsapp' => 'nullable|string',
            'instagram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'first_phone' => 'nullable|string',
            'second_phone' => 'nullable|string',
            'address' => 'nullable|string'
        ]);

        if ($validator->fails()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ]
            ], 422);
        }
        $contacts = Contacts::find($request->id);
        if($contacts === null){
            return response()->json([
                'error' => [
                    'code' => 404,
                    'message' => 'Data with that id doesnt exist'
                ]
            ], 404);
        }

        isset($request->whatsapp) ? $contacts->whatsapp = $request->whatsapp : false;
        isset($request->instagram) ? $contacts->instagram = $request->instagram : false;
        isset($request->facebook) ? $contacts->facebook = $request->facebook : false;
        isset($request->first_phone) ? $contacts->first_phone = $request->first_phone : false;
        isset($request->second_phone) ? $contacts->second_phone = $request->second_phone : false;
        isset($request->address) ? $contacts->address = $request->address : false;

        $contacts->save();

        return response()->json([], 204);
    }
}
