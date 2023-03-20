<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Penawaran;
use Illuminate\Http\Request;

class PenawaranController extends Controller
{
    protected $penawaran = '';
    public function __construct(Penawaran $penawaran)
    {
        $this->middleware('auth:api');
        $this->penawaran = $penawaran;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penawaran = $this->penawaran->latest()->paginate(10);

        return $this->sendResponse($penawaran, 'penawaran list');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $penawaran = $this->penawaran->pluck('name', 'id');

        return $this->sendResponse($penawaran, 'penawaran list');
    }


    /**
     * Store a newly created resource in storage.
     *
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $tag = $this->penawaran->create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ]);

        return $this->sendResponse($tag, 'penawaran Created Successfully');
    }

    /**
     * Update the resource in storage
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $tag = $this->penawaran->findOrFail($id);

        $tag->update($request->all());

        return $this->sendResponse($tag, 'penawaran Information has been updated');
    }
}
