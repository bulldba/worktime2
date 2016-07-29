<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;
use App\Pro;

class ProController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        return view('pro-list', [
            'pros' => Pro::all()
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function postStore(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            $pro = Pro::find( $id );
        } else {
            $pro = new Pro;
        }
        foreach ($request->input('row') as $key => $value) {
            $pro->$key = $value;
        }
        $pro->save( );

        return redirect('pro/index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy($id)
    {
        $query = DB::table('tasks');
        $query->where( 'pro', '=', $id );

        if ($query->count() > 0) {
            return view('error', ['error' => '本项目下还有任务', 'backurl' => 'pro/index']);
        }

        Pro::destroy($id);

        return redirect('pro/index');
    }
}
