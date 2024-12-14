<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\ValueData;

class ValueDataController extends Controller
{
  // store value data
  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'key' => 'required|string',
      'value' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Validation Error',
        'errors' => $validator->errors(),
      ], 400);
    }

    $timestamp = now()->timestamp;

    $valueData = ValueData::create([
      'key' => $request->key,
      'value' => $request->value,
      'created_at' => $timestamp,
      'updated_at' => $timestamp,
    ]);

    return response()->json(['message' => 'Value stored successfully!', 'data' => $valueData], 201);
  }

  // Get all records
  public function getAllRecords()
  {
    $records = ValueData::all();
    return response()->json($records);
  }

  // Retrieve the value using key and timestamp
  public function getByKey($key, Request $request)
  {
    $timestamp = $request->query('timestamp');
    // check timestamp
    if($timestamp) {
      if(!is_numeric($timestamp)) {
        return response()->json(['error' => 'Timestamp must be numeric'], 400);
      }
    }

    $value = ValueData::where('key', $key)->where(function($query) use ($timestamp){
      if($timestamp) {
        $query->where('created_at', '=', date('Y-m-d H:i:s', $timestamp));
      }
    })->latest('created_at')->first();

    if (!$value) {
      return response()->json(['error' => 'Key not found'], 404);
    }

    return response()->json($value);
  }
}
