<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request, Topic $topic)
	{
		// 用 with() 预加载 关联属性('user' 和 'category')
		// $topics = Topic::with('user','category')->paginate(30);

		// 增加了排序功能，注释掉上面一行代码
		$topics = $topic->withOrder($request->order)->paginate(20);
		return view('topics.index', compact('topics'));
	}

    public function show(Topic $topic)
    {
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
		return redirect()->route('topics.show', $topic->id)->with('message', 'Created successfully.');
		
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
		return view('topics.create_and_edit', compact('topic'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
	}
}