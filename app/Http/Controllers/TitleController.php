<?php

namespace App\Http\Controllers;

use DB;
use Config;
use Auth;
use App\User;
use App\Title;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TitleController extends Controller
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
        return view('title-list', [
            'titles' => Title::orderBy('caty')->orderBy('id')->get()
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function postStore(Request $req) {
        $id = $req->input('id');
        if ($id) {
            $title = Title::find( $id );
            if ($title->id == Config::get( 'worktime.icheck' )
                || $title->id == Config::get( 'worktime.check' )) {
                return redirect('title/index');
            }
        } else {
            $title = new Title;
        }

        $row = $req->input('row');

        if (!$row['caty']) {
            return redirect('title/index');
        }

        $name = trim($row['name']);
        if (!$name) {
            return redirect('title/index');
        }

        if ($title->locked) {
            $title->name = $name;
        } else {
            foreach ($row as $key => $value) {
                $title->$key = $value;
            }
        }

        $tag->save( );

        return redirect('title/index');
    }

    public function getDel($id) {
        $title = Title::find( $id );

        if ($title->locked) {
            return redirect('title/index');
        }

        return view('title-del', [
            'title' => $title,
            'titles' => Title::where('caty', $title->caty)->where('id', '<>', $title->id)->get()
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDestroy(Request $req)
    {
        $id = $req->input( 'id' );
        $title = Title::find( $id );
        if ( $title->locked || $title->id == $toid ) {
            return redirect('title/index');
        }

        $toid = $req->input( 'toid' );
        $totitle = Title::find( $toid );

        if ($title->caty != $totitle->caty) {
            return redirect('title/index');
        }

        if ($title->caty == Config::get('worktime.caty')) {
            DB::table('tasks')->where('caty', $id)->update(['caty' => $toid]);
        } elseif ($title->caty == Config::get('worktime.department')) {
            DB::table('users')->where('department', $id)->update(['department' => $toid]);
            DB::table('tasks')->where('department', $id)->update(['department' => $toid]);
        } else {
            return redirect('title/index');
        }

        DB::table('titles')->where('id', $id)->delete();

        return redirect('title/index');
    }
}
