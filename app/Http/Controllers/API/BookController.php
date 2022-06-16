<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Books;
use App\Models\Rating;
use App\Models\Review;
use Validator;


class BookController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $data = Books::all();
        return $this->sendResponse($data, 'Fetch Data Success');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'pages' => 'required',
            'isbn' => 'required',
            'authors' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try { 
            $data = new Books();
            $data->title = $request->title;
            $data->pages = $request->pages;
            $data->isbn = $request->isbn;
            $data->authors = $request->authors;
            $data->added_by = $request->user()->id;
            $data->save();
        } catch (\Illuminate\Database\QueryException $ex){ 
            return $this->sendError('Woopzzz... Something Wrong!', null);
        }

        return $this->sendResponse($data, 'Add Books Successfully!');
    }

    public function show(Request $request, $id)
    {
        try {
            $data = Books::find($id);
            if (!$request->user()) {
                return $this->sendError('Woopzzz... Something Wrong!', null);
            }

        } catch (\Illuminate\Database\QueryException $ex){ 
            return $this->sendError('Woopzzz... Something Wrong!', null);
        }

        return $this->sendResponse($data, 'Get Books Successfully!');
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'pages' => 'required',
            'isbn' => 'required',
            'authors' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $check = Books::find($id);
        if ($check->added_by != $request->user()->id) {
            return $this->sendError('Woopzzz... You are never publish this books!', null);
        }

        try {
            $data = Books::find($id);
            $data->title = $request->title;
            $data->pages = $request->pages;
            $data->isbn = $request->isbn;
            $data->authors = $request->authors;
            $data->added_by = $request->user()->id;
            $data->update();
        } catch (\Illuminate\Database\QueryException $ex){ 
            return $this->sendError('Woopzzz... Something Wrong!', null);
        }
        
        return $this->sendResponse($data, 'Get Books Successfully!');
    }

    public function destroy(Request $request, $id)
    {
        try {
            $data = Books::find($id);
            $data->delete();
        } catch (\Illuminate\Database\QueryException $ex){ 
            return $this->sendError('Woopzzz... Something Wrong!', null);
        }

        return $this->sendResponse($data, 'Remove Books Successfully!');
    }

    public function store_rating(Request $request, $id) {
        try {
            $check = Rating::where('user_id', '=', $request->user()->id)->where('book_id', '=', $id)->get();
            if (count($check) === 0) {
                $data = new Rating();
                $data->user_id = $request->user()->id;
                $data->rating = $request->rating;
                $data->book_id = $id;
                $data->save();
                return $this->sendResponse($data, 'Store Rating Successfully!');
            }

            $get_data = Rating::where('user_id', '=', $request->user()->id)->where('book_id', '=', $id)->first();
            $get_data->user_id = $request->user()->id;
            $get_data->rating = $request->rating;
            $get_data->book_id = $id;
            $get_data->update();
            return $this->sendResponse($get_data, 'Edit Rating Successfully!');
        } catch (\Illuminate\Database\QueryException $ex){ 
            return $this->sendError('Woopzzz... Something Wrong!', null);
        }
    }

    public function store_review(Request $request, $id) {
        try {
            $check = Review::where('user_id', '=', $request->user()->id)->where('book_id', '=', $id)->get();
            if (count($check) === 0) {
                $data = new Review();
                $data->user_id = $request->user()->id;
                $data->review = $request->review;
                $data->book_id = $id;
                $data->save();
                return $this->sendResponse($data, 'Store Reviews Successfully!');
            }

            $update_data = Review::where('user_id', '=', $request->user()->id)->where('book_id', '=', $id)->first();
            $update_data->user_id = $request->user()->id;
            $update_data->review = $request->review;
            $update_data->book_id = $id;
            $update_data->update();
            return $this->sendResponse($update_data, 'Edit Reviews Successfully!');
        } catch (\Illuminate\Database\QueryException $ex){ 
            return $this->sendError('Woopzzz... Something Wrong!', null);
        }
    }
}

