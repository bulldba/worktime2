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
use App\Title;
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

    public function getChecks(Request $request) {
        return $this->formatlist($request, ['caty' => Config::get('worktime.check')]);
    }

    public function getIcommit(Request $request) {
        return $this->formatlist($request, ['author' => Auth::user()->id]);
    }

    public function getItest(Request $request) {
        return $this->formatlist($request, ['tester' => Auth::user()->id]);
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
            if (isset($updates['leader'])) {
                $leader = User::find( $updates['leader'] );
                $updates['department'] = $leader->department;
            }

            if ($updates) {
                $updates['updated_at'] = date('Y-m-d H:i:s');
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

        $query->orderBy('status');
        $orderby = $request->input('orderby');
        if ($orderby) {
            if ('updated_at' == $orderby) {
                $query->orderBy('updated_at', 'desc');
            } elseif ('deadline' == $orderby) {
                $query->orderBy('deadline');
            }
        } else {
            $orderby = '';
        }

        $query->orderBy('tag', 'desc')
        ->orderBy('priority', 'desc');

        $tasks = $query->skip($offset)->take($perpage)
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
            'catys' => Title::where('caty', 2)->get( )->keyBy('id'),
            'prioritys' => Config::get('worktime.priority'),
            'departments' => Title::where('caty', 1)->get(  )->keyBy('id'),
            'options' => $options,
            'orderby' => $orderby,
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
            'tags' => Tag::orderBy( 'id', 'desc' )->get( ),
            'catys' => Title::where('caty', 2)->get(  )->keyBy('id'),
            'departments' => Title::where('caty', 1)->get(  )->keyBy('id')
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
        $row = $request->input('row');

        if ($id) {
            $task = Task::find($id);
        } else {
            $me = Auth::user();
            $task = new Task;
            $task->author = $me->id;
            $task->status = 12;
        }

        $oldcaty = $task->caty;


        foreach ($row as $key => $value) {
            $task->$key = $value;
        }
        $task->deadline = strtotime($task->deadline);

        //check类型的类型不能更改
        $donotchange = [Config::get( 'worktime.check' ), Config::get( 'worktime.icheck' )];
        if (in_array($oldcaty, $donotchange) || in_array($task->caty, $donotchange)) {
            $task->caty = $oldcaty;
        }

        $this->onChange( $task );

        $task->save( );

        $this->add2svn( $task );

        if ($request->ajax()) {
            return '';
        } else {
            return redirect('task/show/'.$task->id);
        }
    }

    private function onChange( $task ) {
        $tag = Tag::find( $task->tag );
        $task->pro = $tag->pro;

        $leader = User::find( $task->leader );
        $task->department = $leader->department;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id)
    {
        $task = Task::find( $id );

        $tpl = 'task-show';
        if ($task->caty == Config::get('worktime.check')) {
            $tpl = 'check-show';
        } elseif ($task->caty == Config::get('worktime.icheck')) {
            $tpl = 'task-check';
        }

        return view($tpl, [
            'task' => Task::find( $id ),
            'feedbacks' => Feedback::where( 'pid', $id )->get( ),
            'users' => User::all()->keyBy( 'id' ),
            'pros' => Pro::all( )->keyBy('id'),
            'tags' => Tag::all( ),
            'catys' => Title::where('caty', 2)->get(  )->keyBy('id'),
            'departments' => Title::where('caty', 1)->get(  )->keyBy('id')
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
            'tags' => Tag::orderBy( 'id', 'desc' )->get( ),
            'catys' => Title::where('caty', 2)->get(  )->keyBy('id'),
            'departments' => Title::where('caty', 1)->get(  )->keyBy('id')
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

            $dir = 'upload/'.date('Ym');
            if (!is_dir($dir)) {
                mkdir($dir);
            }

            $path = $dir . '/' . $filename;
            if (!file_exists($path)) {
                $file->move($dir, $filename);
            }
            $a['path'] = '/' . $path;
        }

        return response()->json( $a );
    }

    public function getCheck( $id = 0 ) {
        return view('check-commit', [
            'task' => $id ? Task::find($id) : new Task(),
            'departments' => Title::where('caty', 1)->get(  )->keyBy('id')
            ]);
    }

    public function postCheck(Request $request) {
        $id = $request->input('id');
        if ($id) {
            $task = Task::find($id);
        } else {
            $task = new Task;
            $me = Auth::user();
            $task->author = $me->id;
            $task->leader = $me->id;
            $task->status = 12;
            $task->priority = 10;
            $task->caty = Config::get( 'worktime.check' );
        }

        $row = $request->input('row');
        foreach ($row as $key => $value) {
            $task->$key = $value;
        }

        $checklist = $request->input('checklist');
        $task->content = json_encode($checklist, JSON_UNESCAPED_UNICODE);

        $task->save( );

        return redirect('task/show/'.$task->id);

    }


    public function postPublishcheck(Request $request) {
        $id = $request->input('id');
        $task = Task::find( $id );

        $newtask = new Task( );
        $row = $request->input('row');
        foreach ($row as $key => $value) {
            $newtask->$key = $value;
        }

        $me = Auth::user();
        $newtask->author = $me->id;

        $newtask->status = 12;
        $newtask->caty = Config::get( 'worktime.icheck' );
        $ccc = [];
        foreach (json_decode($task->content) as $key => $value) {
            $ccc[] = [$value, 0];
        }

        $newtask->content = json_encode($ccc, JSON_UNESCAPED_UNICODE);

        $this->onChange( $newtask );

        $newtask->save( );

        return redirect('task/show/'.$newtask->id);

    }

    public function postIcheck(Request $request) {
        $id = $request->input('id');
        $task = Task::find( $id );

        $iid = $request->input('iid');
        $passk = $request->input('passk');

        $ccc = json_decode($task->content);
        if (!isset($ccc[$iid])) {
            return 'error';
        }

        $ccc[$iid][1] = $passk;

        $task->content = json_encode($ccc, JSON_UNESCAPED_UNICODE);
        $task->save( );
        return 'ok';
    }

    public function getResetcheck($id) {
        $task = Task::find( $id );
        $ccc = [];
        foreach (json_decode($task->content) as $key => $value) {
            $ccc[] = [$value, 0];
        }
        $task->content = json_encode($ccc, JSON_UNESCAPED_UNICODE);
        $task->save( );

        return redirect('task/show/'.$task->id);
    }



    private function add2svn( $task ) {
        if ( !env('SVN_LOG', false) ) {
            return;
        }

        $me = Auth::user();
        $leader = User::find( $task->leader );
        $catys = Title::where('caty', 2)->get(  )->keyBy('id');
        $caty = $catys[$task->caty]->name;
        $priority = Config::get('worktime.priority')[$task->task];
        $departments = Title::where('caty', 1)->get( )->keyBy('id');
        $department = $departments[$task->department];
        $status = Config::get('worktime.status')[$task->status];

        $tag = Tag::find( $task->tag );
        $pro = Tag::find( $task->pro );

        $svncontent = <<<EOT
changed: $me->name
leader: $leader->name
caty: $caty
priority: $priority
department: $department
status: $status
pro: $pro->name
tag: $tag->name
title: $task->title
#####content#####
$task->content
EOT;

        file_put_contents('/home/www/tasks/' . $task->id, $svncontent);
    }

    public function getHr( Request $req ) {
        $query = DB::table('tasks');
        $options = array();

        $leader = $req->input( 'leader' );
        if (!$leader) {
            $leader = Auth::user()->id;
        }

        $options['leader'] = $leader;
        $query->where('leader', $options['leader'] );

        $t_start = $req->input( 't_start' );
        if ($t_start) {
            $options['t_start'] = $t_start;
            $query->where('updated_at', '>=', $t_start . ' 00:00:00' );
        }

        $t_end = $req->input( 't_end' );
        if ($t_end) {
            $options['t_end'] = $t_end;
            $query->where('updated_at', '<=', $t_end . ' 23:59:59' );
        }

        $query->orderBy('status');
        $tasks = $query->paginate(30);

        return view('hr-list', [
            'options' => $options,
            'pros' => Pro::all( )->keyBy('id'),
            'tags' => Tag::all( )->keyBy('id'),
            'catys' => Title::where('caty', 2)->get( )->keyBy('id'),
            'departments' => Title::where('caty', 1)->get( )->keyBy('id'),
            'users' => User::all( ),
            'tasks' => $tasks
        ]);
    }

}

