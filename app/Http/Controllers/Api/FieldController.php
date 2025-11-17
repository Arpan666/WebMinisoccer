<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     * Endpoint: GET /api/fields
     */
    public function index(Request $request): JsonResponse
    {
        // Hapus filter is_active, hanya gunakan status
        $query = Field::query(); // Mulai dari query dasar

        // Filter opsional berdasarkan status (tersedia/tidak_tersedia)
        if ($request->has('status') && in_array($request->status, ['tersedia', 'tidak_tersedia'])) {
            $query->where('status', $request->status);
        }

        // Ambil data
        $fields = $query->get();

        return response()->json($fields);
    }

    /**
     * Display the specified resource.
     * Endpoint: GET /api/fields/{field}
     */
    public function show(Field $field): JsonResponse
    {

        $field->load('prices');

        return response()->json($field);
    }
}