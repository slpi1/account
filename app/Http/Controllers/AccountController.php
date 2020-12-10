<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountCreateRequest;
use App\Models\Account;
use App\Models\Site;
use Auth;

class AccountController extends Controller
{

    /**
     * 获取账号列表
     *
     * @method  index
     * @author  雷行  songzhp@yoozoo.com  2020-09-02T12:18:06+0800
     * @return  array
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $accounts = Site::with('accounts')
                ->whereHas('accounts', function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                })
                ->get();

            return $accounts;
        } else {
            return [];
        }
    }

    public function create(AccountCreateRequest $request)
    {
        $user = Auth::user();
        return Account::create([
            'user_id' => $user->id,
            'account' => $request->input('account'),
            'site_id' => $request->input('siteId'),
        ]);
    }

    public function delete($id)
    {
        $user = Auth::user();

        $account = Account::where('user_id', $user->id)->where('id', $id)->first();
        if ($account) {
            $account->delete();
            return [];
        }
        abort(500, '数据错误');
    }
}
