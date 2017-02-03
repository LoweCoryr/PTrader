<?php

namespace App\Http\Controllers\Bet;

use Illuminate\Http\Request;
use App\Http\Controllers\Scrape\ScrapeController;
use Log;

use App\Account;
use App\Market;
use App\Contract;
use App\Trade;
use App\Session;
use App\Tweet;

class BetController extends ScrapeController
{
    protected $baseUri  = 'https://www.predictit.org/';

    public function test() 
    {
        dispatch(new \App\Jobs\PerformTrade(Tweet::find(1)));
    }

    public function placeBet($twitterId) 
    {
        if(!$market = $this->findMarket($twitterId)) {
            return;
        }

        if(!$contract = $market->findBestContract()) {
            return;
        }

        $account = $this->chooseAccount();

        if(!$contract->bet($account)) {
            return;
        }
    }

    private function findMarket($twitterId)
    {
        $market = Market::select(['ticker_symbol', 'market_id'])
                    ->where('twitter_id', $twitterId)
                    ->where('active', true)
                    ->where('status', true)
                    ->first();

        if(!$market) {
            return null;
        }

        return $market;
    }

    private function chooseAccount()
    {
        return Account::select('id')->where('id', 1)->first();
    }
}
