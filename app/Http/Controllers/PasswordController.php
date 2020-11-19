<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Site;
use Auth;
use Illuminate\Http\Request;

class PasswordController extends Controller
{

    /**
     * 获取账号列表
     *
     * @method  accounts
     * @author  雷行  songzhp@yoozoo.com  2020-09-02T12:18:06+0800
     * @return  array
     */
    public function accounts()
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

    /**
     * 获取密码
     *
     * @method  password
     * @author  雷行  songzhp@yoozoo.com  2020-09-02T12:14:11+0800
     * @param   Request   $request
     * @return  string
     */
    public function password(Request $request)
    {
        if (Auth::check()) {
            return $this->getUserPassword($request);
        } else {
            $account = $request->input('account');
            $host    = $request->input('host');
            $mask    = $request->input('mask');
            return $this->getPassword($account, $host, $mask);
        }
    }

    /**
     * 按账号获取密码
     *
     * @method  getUserPassword
     * @author  雷行  songzhp@yoozoo.com  2020-09-02T12:14:53+0800
     * @param   Request   $request
     * @return  string
     */
    protected function getUserPassword($request)
    {

        if ($accountId = $request->input('accountId')) {
            $account = Account::with('site')->find($accountId);
        } else {
            $accountName = $request->input('account');
            $host        = $request->input('host');
            $account     = $this->createAccount($accountName, $host);
        }

        $mask = $request->input('mask');

        return $this->getPassword($account->account, $account->site->host, $mask);
    }

    /**
     * 直接获取密码
     *
     * @method  getPassword
     * @author  雷行  songzhp@yoozoo.com  2020-09-02T12:15:18+0800
     * @param   string       $account  账号
     * @param   string       $host     域名
     * @param   string       $mask     唯一码
     * @return  string
     */
    protected function getPassword($account, $host, $mask)
    {
        $pos      = [1, 4, 7, 10, 13, 16, 19, 22, 25, 28, 31];
        $upperPos = 15;
        $letter   = [];

        $str = md5($account . "@" . $host . ":" . $mask);

        foreach ($pos as $p) {
            $letter[] = $p > $upperPos ? strtoupper($str[$p]) : $str[$p];
        }

        return implode('', $letter);
    }

    /**
     * 创建账号
     *
     * @method  createAccount
     * @author  雷行  songzhp@yoozoo.com  2020-09-02T12:15:52+0800
     * @param   string         $account  账号
     * @param   string         $host     域名
     * @return  App\Models\Account::class
     */
    protected function createAccount($account, $host)
    {
        $site = Site::where('host', $host)->first();
        if (!$site) {
            $site = Site::create([
                'name'        => $host,
                'host'        => $host,
                'description' => $host,
            ]);
        }

        $user = Auth::user();

        $account = Account::with('site')
            ->where('account', $account)
            ->where('site_id', $site->id)
            ->where('user_id', $user->id)
            ->first();

        if ($account) {
            return $account;
        }

        $account = Account::create([
            'account' => $account,
            'site_id' => $site->id,
            'user_id' => $user->id,
        ]);

        return $account;
    }
}
