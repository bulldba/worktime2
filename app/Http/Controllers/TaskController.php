<?php

namespace App\Http\Controllers;

use DB;
use Config;
use Auth;
use App\Task;
use App\Tag;
use App\User;
use App\Feedback;
use App\Pro;
use Input;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex(Request $request) {
        return $this->formatlist($request, []);
    }

    public function getIdo(Request $request) {
        return $this->formatlist($request, ['leader' => Auth::user()->id]);
    }

    public function getIcommit(Request $request) {
        return $this->formatlist($request, ['author' => Auth::user()->id]);
    }

    public function formatlist(Request $request, $searchargs)
    {
        $ids = $request->input('ids');
        if ($ids) {
            $updates = array();
            foreach ($request->input('changeto') as $key => $value) {
                if ($value > 0) {
                    $updates[$key] = $value;
                }
            }

            if (isset($updates['tag'])) {
                $tag = Tag::find( $updates['tag'] );
                $updates['pro'] = $tag->pro;
            }

            if ($updates) {
                DB::table('tasks')->whereIn('id', $ids)->update($updates);
            }
        }

        $query = DB::table('tasks');

        $options = array();
        $search = $request->input('search');
        if (!$search) {
            $search = array();
        }

        $search = array_merge($search, $searchargs);

        foreach ($search as $key => $value) {
            if ($value > 0) {
                $options[$key] = $value;
                $query->where( $key, '=', $value );
            }
        }

        $title = $request->input('title');
        if ($title) {
            $options['title'] = $title;
            $query->where( "title", 'like', '%'.$title.'%' );
        }

        $totalnum = $query->count();
        $curpage = $request->input( 'page', 1 );
        $perpage = 20;
        $offset = $this->page_get_start($curpage, $perpage, $totalnum);

        $tasks = $query->orderBy('status')
        ->orderBy('tag', 'desc')
        ->orderBy('priority', 'desc')
        ->orderBy('id', 'desc')
        ->skip($offset)->take($perpage)
        ->get( );

        $tpl = 'task-list';
        if ($request->ajax()) {
            $tpl = 'task-list-content';
        }

        return view($tpl, [
            'tasks' => $tasks,
            'pros' => Pro::all( )->keyBy('id'),
            'users' => User::all()->keyBy( 'id' ),
            'tags' => Tag::orderBy( 'id', 'desc' )->get( )->keyBy( 'id' ),
            'status' => Config::get('worktime.status'),
            'catys' => Config::get('worktime.caty'),
            'prioritys' => Config::get('worktime.priority'),
            'departments' => Config::get('worktime.department'),
            'options' => $options,
            'totalnum' => $totalnum,
            'curpage' => $curpage,
            'perpage' => $perpage
            ]);
    }

    public function page_get_start($page, $ppp, $totalnum) {
        $totalpage = ceil($totalnum / $ppp);
        $page =  max(1, min($totalpage, intval($page)));
        return ($page - 1) * $ppp;
    }

    public function getCreate()
    {
        return view('task-commit', [
            'task' => new Task,
            'users' => User::all(),
            'pros' => Pro::all( )->keyBy('id'),
            'tags' => Tag::orderBy( 'id', 'desc' )->get( )
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postStore(Request $request)
    {
        $id = $request->input('id');
        if ($id) {
            $task = Task::find($id);
        } else {
            $task = new Task;
            $me = Auth::user();
            $task->author = $me->id;
            $task->status = 12;
        }

        $row = $request->input('row');
        foreach ($row as $key => $value) {
            $task->$key = $value;
        }

        $tag = Tag::find( $task->tag );
        $task->pro = $tag->pro;

        $task->save( );

        if ($request->ajax()) {
            return '';
        } else {
            return redirect('task/show/'.$task->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id)
    {
        return view('task-show', [
            'task' => Task::find( $id ),
            'feedbacks' => Feedback::where( 'pid', $id )->get( ),
            'users' => User::all()->keyBy( 'id' ),
            'pros' => Pro::all( )->keyBy('id'),
            'tags' => Tag::all( )
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
        return view('task-commit', [
            'task' => Task::find( $id ),
            'users' => User::all(),
            'pros' => Pro::all( )->keyBy('id'),
            'tags' => Tag::orderBy( 'id', 'desc' )->get( )
            ]);
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

    public function postUpload( )
    {
        $a = array( 'err' => 'do not recive file.' );

        $file = Input::file('file');
        if ($file && $file->isValid()) {
            $filename = time() . '_' . rand( 100, 999 ) . '.' . $file->getClientOriginalExtension( );

            if (!file_exists('upload/' . $filename)) {
                $file->move('upload', $filename);
            }
            $a['path'] = '/upload/' . $filename;
        }

        return response()->json( $a );
    }

}
