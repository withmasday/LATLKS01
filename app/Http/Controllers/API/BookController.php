<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Books;
use Validator;


class BookController extends BaseController
{
    public function index()
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

    public function show($id)
    {
        try {
            $data = Books::find($id);
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
}
