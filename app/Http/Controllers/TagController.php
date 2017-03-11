<?php

namespace App\Http\Controllers;

use DB;
use Config;
use Auth;
use App\Tag;
use App\User;
use App\Pro;
use App\Title;

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

    public function postStats(Request $req)
    {
        $s_department = array();
        $departments = Title::where('caty', 1)->get( )->keyBy('id');
        $status = Config::get('worktime.status');
        $default_status = array();
        foreach ($status as $status_id => $value) {
            $default_status[$status_id] = 0;
        }
        $default_status['new'] = 0;

        $t = array(
            $req->input('start'),
            $req->input('end')
        );

        $a = DB::table('tasks')
        ->select(DB::raw('count(*) as num, department as tname, status'))
        ->whereBetween('updated_at', $t)
        ->groupBy('department')
        ->groupBy('status')
        ->get();

        $s_all = $default_status;
        foreach ($a as $row) {
            $s_all[$row->status] += $row->num;
        }

        $aa = DB::table('tasks')
        ->select(DB::raw('count(*) as num, department as tname'))
        ->whereBetween('created_at', $t)
        ->whereNotIn('status', [90, 99])
        ->groupBy('department')
        ->get();
        foreach ($aa as $row) {
            $s_all['new'] += $row->num;
        }

        $s_department = $this->getdata( $a, $aa, $default_status );

        $a = DB::table('tasks')
        ->select(DB::raw('count(*) as num, leader as tname, status'))
        ->whereBetween('updated_at', $t)
        ->groupBy('leader')
        ->groupBy('status')
        ->orderBy('department')
        ->get();

        $aa = DB::table('tasks')
        ->select(DB::raw('count(*) as num, leader as tname'))
        ->whereBetween('created_at', $t)
        ->whereNotIn('status', [90, 99])
        ->groupBy('leader')
        ->get();

        $s_leader = $this->getdata( $a, $aa, $default_status );

        $a = DB::table('tasks')
        ->select(DB::raw('count(*) as num, pro as tname, status'))
        ->whereBetween('updated_at', $t)
        ->groupBy('pro')
        ->groupBy('status')
        ->get();

        $aa = DB::table('tasks')
        ->select(DB::raw('count(*) as num, pro as tname'))
        ->whereBetween('created_at', $t)
        ->whereNotIn('status', [90, 99])
        ->groupBy('pro')
        ->get();

        $s_pro = $this->getdata( $a, $aa, $default_status );

        $tag = new Tag( );
        $tag->name = '时间统计';
        $tag->t_start = $t[0];
        $tag->t_end = $t[1];


        return view('tag-statistics', [
            'tag' => $tag,
            'users' => User::all()->keyBy( 'id' ),
            'departments' => Title::where('caty', 1)->get( )->keyBy('id'),
            'pros' => Pro::all()->keyBy('id'),
            's_all' => $s_all,
            's_department' => $s_department,
            's_pro' => $s_pro,
            's_leader' => $s_leader
        ]);

    }

    private function getdata( $a, $aa, $default_status ) {
        $rtn = array();
        foreach ($a as $row) {
            if (!isset($rtn[$row->tname])) {
                $rtn[$row->tname] = $default_status;
            }
            $rtn[$row->tname][$row->status] = $row->num;
        }
        foreach ($aa as $row) {
            $rtn[$row->tname]['new'] = $row->num;
        }
        return $rtn;
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
