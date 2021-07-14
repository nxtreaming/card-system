<?php
namespace App\Http\Controllers\Merchant; use App\Library\Helper; use App\Library\Response; use App\System; use Illuminate\Http\Request; use App\Http\Controllers\Controller; class Category extends Controller { function get(Request $sp147552) { $sp95caec = (int) $sp147552->input('current_page', 1); $sp11fa7d = (int) $sp147552->input('per_page', 20); $spd10097 = $this->authQuery($sp147552, \App\Category::class); $spa1f2d3 = $sp147552->input('search', false); $spa55e11 = $sp147552->input('val', false); if ($spa1f2d3 && $spa55e11) { if ($spa1f2d3 == 'simple') { return Response::success($spd10097->get(array('id', 'name'))); } elseif ($spa1f2d3 == 'id') { $spd10097->where('id', $spa55e11); } else { $spd10097->where($spa1f2d3, 'like', '%' . $spa55e11 . '%'); } } $sp23f506 = $sp147552->input('enabled'); if (strlen($sp23f506)) { $spd10097->whereIn('enabled', explode(',', $sp23f506)); } $sp8b8475 = $spd10097->withCount('products')->orderBy('sort')->paginate($sp11fa7d, array('*'), 'page', $sp95caec); foreach ($sp8b8475->items() as $sp62ae3e) { $sp62ae3e->setAppends(array('url')); } return Response::success($sp8b8475); } function sort(Request $sp147552) { $this->validate($sp147552, array('id' => 'required|integer')); $sp62ae3e = $this->authQuery($sp147552, \App\Category::class)->findOrFail($sp147552->post('id')); $sp62ae3e->sort = (int) $sp147552->post('sort', 1000); $sp62ae3e->save(); return Response::success(); } function edit(Request $sp147552) { $this->validate($sp147552, array('name' => 'required|string|max:128')); $sp1a236c = $sp147552->post('name'); $sp23f506 = (int) $sp147552->post('enabled'); $spbcdd44 = $sp147552->post('sort'); $spbcdd44 = $spbcdd44 === NULL ? 1000 : (int) $spbcdd44; if (System::_getInt('filter_words_open') === 1) { $spefb75f = explode('|', System::_get('filter_words')); if (($sp296935 = Helper::filterWords($sp1a236c, $spefb75f)) !== false) { return Response::fail('提交失败! 分类名称包含敏感词: ' . $sp296935); } } if ($spbcdd44 < 0 || $spbcdd44 > 1000000) { return Response::fail('排序需要在0-1000000之间'); } $sp27b440 = $sp147552->post('password'); $sp493ecd = $sp147552->post('password_open') === 'true'; if ((int) $sp147552->post('id')) { $sp62ae3e = $this->authQuery($sp147552, \App\Category::class)->findOrFail($sp147552->post('id')); } else { $sp62ae3e = new \App\Category(); $sp62ae3e->user_id = $this->getUserIdOrFail($sp147552); } $sp62ae3e->name = $sp1a236c; $sp62ae3e->sort = $spbcdd44; $sp62ae3e->password = $sp27b440; $sp62ae3e->password_open = $sp493ecd; $sp62ae3e->enabled = $sp23f506; $sp62ae3e->saveOrFail(); return Response::success(); } function enable(Request $sp147552) { $this->validate($sp147552, array('ids' => 'required|string', 'enabled' => 'required|integer|between:0,1')); $sp548f2b = $sp147552->post('ids', ''); $sp23f506 = (int) $sp147552->post('enabled'); $this->authQuery($sp147552, \App\Category::class)->whereIn('id', explode(',', $sp548f2b))->update(array('enabled' => $sp23f506)); return Response::success(); } function delete(Request $sp147552) { $this->validate($sp147552, array('ids' => 'required|string')); $sp548f2b = $sp147552->post('ids', ''); $this->authQuery($sp147552, \App\Category::class)->whereIn('id', explode(',', $sp548f2b))->delete(); return Response::success(); } }