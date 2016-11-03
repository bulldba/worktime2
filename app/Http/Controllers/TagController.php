<?php

namespace App\Http\Controllers;

use DB;
use Config;
use Auth;
use App\Tag;
use App\User;
use App\Pro;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TagController extends Controller
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
        return view('tag-list', [
            'tags' => Tag::all(),
            'pros' => Pro::all( )
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
    public function postStore(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            $tag = Tag::find( $id );
        } else {
            $tag = new Tag;
        }

        $oldpro = $tag->pro;
        foreach ($request->input('row') as $key => $value) {
            $tag->$key = $value;
        }

        if ($tag->pro != $oldpro) {
            DB::table('tasks')->where('tag', '=', $tag->id)->update(['pro' => $tag->pro]);
        }

        $tag->save( );

        return redirect('tag/index');
    }

    public function postShow(Request $req)
    {
        $t = array(
            $req->input('start'),
            $req->input('end')
        );

        $a = DB::table('tasks')
        ->select(DB::raw('count(*) as num, department, status'))
        ->whereBetween('created_at', $t)
        ->groupBy('department')
        ->groupBy('status')
        ->get();

        $b = DB::table('tasks')
        ->select(DB::raw('count(*) as num, leader, status'))
        ->whereBetween('created_at', $t)
        ->groupBy('leader')
        ->groupBy('status')
        ->orderBy('department')
        ->get();

        $tag = new Tag( );
        $tag->name = '时间统计';
        $tag->t_start = $t[0];
        $tag->t_end = $t[1];
        return $this->ppp($a, $b, $tag);
    }

    public function getShow($id)
    {
        $a = DB::table('tasks')
        ->select(DB::raw('count(*) as num, department, status'))
        ->where('tag', '=', $id)
        ->groupBy('department')
        ->groupBy('status')
        ->get();

        $b = DB::table('tasks')
        ->select(DB::raw('count(*) as num, leader, status'))
        ->where('tag', '=', $id)
        ->groupBy('leader')
        ->groupBy('status')
        ->orderBy('department')
        ->get();
        return $this->ppp($a, $b, Tag::find( $id ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    private function ppp($a, $b, $tag)
    {
        $s_department = array();
        $departments = Config::get('worktime.department');
        $status = Config::get('worktime.status');
        $default_status = array();
        foreach ($status as $status_id => $value) {
            $default_status[$status_id] = 0;
        }

        $s_all = $default_status;

        foreach ($departments as $department_id => $name) {
            $s_department[$department_id] = $default_status;
        }
        foreach ($a as $row) {
            $s_department[$row->department][$row->status] = $row->num;

            $s_all[$row->status] += $row->num;
        }

        $s_leader = array();
        foreach ($b as $row) {
            if (!isset($s_leader[$row->leader])) {
                $s_leader[$row->leader] = $default_status;
            }
            $s_leader[$row->leader][$row->status] = $row->num;
        }

        return view('tag-statistics', [
            'tag' => $tag,
            'users' => User::all()->keyBy( 'id' ),
            's_all' => $s_all,
            's_department' => $s_department,
            's_leader' => $s_leader
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit($id)
    {
        return view('tag-edit', [
            'tag' => Tag::find( $id )
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
