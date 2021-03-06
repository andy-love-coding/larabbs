<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Auth;
use App\Handlers\ImageUploadHandler;
use App\Models\User;
use App\Models\Link;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request, Topic $topic, User $user, Link $link)
	{
		// 用 with() 预加载 关联属性('user' 和 'category')
		// $topics = Topic::with('user','category')->paginate(30);

		// 增加了排序功能，注释掉上面一行代码
		$topics = $topic->withOrder($request->order)->paginate(20);
		$active_users = $user->getActiveUsers();
		$links = $link->getAllCached();
		// dd($links);
		return view('topics.index', compact('topics', 'active_users', 'links'));
	}
	
	public function show(Request $request, Topic $topic)
    {
        // URL 矫正（第一次执行 show() 方法时，若 URL 不正确，矫正后，会再执行 show() 方法）
        if ( ! empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);  // 301 永久重定向到正确的 URL
        }

		// 第一次执行 show() 方法时 URL 就正确，或 矫正后第二次执行 show() 方法时，执行以下 return 语句
        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
		$categories = Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function store(TopicRequest $request, Topic $topic)  // 先创建一个空白的 Topic 的实例 $topic
	{
		// $topic = Topic::create($request->all());
		$topic->fill($request->all()); // 获取所有用户的请求数据数组，如 ['title' => '标题', 'body' => '内容', ... ]，填充到实例$topic
		$topic->user_id = Auth::id(); // 给实例 $topic 加一个属性 user_id ，并赋值为当前登录用户的id
		$topic->save(); // 保存到数据库中, 
		// dd($topic);		// 同时生成的id等也会返回更新到 $topic 实例中
		// return redirect()->route('topics.show', $topic->id)->with('success', '创建话题成功');
		return redirect()->to($topic->link())->with('success', '创建话题成功');
		
	}

	public function edit(Topic $topic)
	{
		$this->authorize('update', $topic);
		$categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		// return redirect()->route('topics.show', $topic->id)->with('success', '更新成功');
		return redirect()->to($topic->link())->with('success', '更新成功');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('success', '删除成功');
	}

	public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if ($result) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }
        return $data;
    }
}