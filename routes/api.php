<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\V1\Game\GameController;
use App\Http\Controllers\Api\V1\Game\LaunchGameController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\PaymentType\PaymentTypeController;
use App\Http\Controllers\Api\V1\Player\DepositController;
use App\Http\Controllers\Api\V1\Player\TransactionController;
use App\Http\Controllers\Api\V1\Player\UserPaymentControler;
use App\Http\Controllers\Api\V1\Player\WagerController;
use App\Http\Controllers\Api\V1\Player\WithDrawController;
use App\Http\Controllers\Api\V1\PromotionController;
use App\Http\Controllers\Api\V1\ThreeD\LottoPrizeWinnerHistoryController;
use App\Http\Controllers\Api\V1\ThreeD\OneWeekRecWihtSlipController;
use App\Http\Controllers\Api\V1\ThreeD\PlayController;
use App\Http\Controllers\Api\V1\TwoD\AllWinnerPrizeSentController;
use App\Http\Controllers\Api\V1\TwoD\ApiSlipController;
use App\Http\Controllers\Api\V1\TwoD\EveningWinPrizeController;
use App\Http\Controllers\Api\V1\TwoD\InternetModernController;
use App\Http\Controllers\Api\V1\TwoD\MorningWinPrizeController;
use App\Http\Controllers\Api\V1\TwoD\TwoDLotteryController;
use App\Http\Controllers\Api\V1\TwoD\UserEveningHistoryController;
use App\Http\Controllers\Api\V1\TwoD\UserMorningHistoryController;
use App\Http\Controllers\Api\V1\Webhook\BonusController;
use App\Http\Controllers\Api\V1\Webhook\BuyInController;
use App\Http\Controllers\Api\V1\Webhook\BuyOutController;
use App\Http\Controllers\Api\V1\Webhook\CancelBetController;
use App\Http\Controllers\Api\V1\Webhook\GameResultController;
use App\Http\Controllers\Api\V1\Webhook\GetBalanceController;
use App\Http\Controllers\Api\V1\Webhook\JackPotController;
use App\Http\Controllers\Api\V1\Webhook\MobileLoginController;
use App\Http\Controllers\Api\V1\Webhook\PlaceBetController;
use App\Http\Controllers\Api\V1\Webhook\PushBetController;
use App\Http\Controllers\Api\V1\Webhook\RollbackController;
use App\Http\Controllers\LiveChat\MessageController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

//login route post
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// logout
// Route::post('/logout', [AuthController::class, 'logout']);
Route::get('promotion', [PromotionController::class, 'index']);
Route::get('banner', [BannerController::class, 'index']);
Route::get('bannerText', [BannerController::class, 'bannerText']);
Route::get('v1/validate', [AuthController::class, 'callback']);
Route::get('gameTypeProducts/{id}', [GameController::class, 'gameTypeProducts']);
Route::get('allGameProducts', [GameController::class, 'allGameProducts']);
Route::get('gameType', [GameController::class, 'gameType']);
Route::get('gamelist/{product_id}/{game_type_id}', [GameController::class, 'gameList']);
Route::post('Seamless/PullReport', [LaunchGameController::class, 'pullReport']);

Route::get('/test', TestController::class);

Route::group(['prefix' => 'Seamless'], function () {
    Route::post('GetBalance', [GetBalanceController::class, 'getBalance']);

    // Route::group(["middleware" => ["webhook_log"]], function(){
    Route::post('GetGameList', [LaunchGameController::class, 'getGameList']);
    Route::post('GameResult', [GameResultController::class, 'gameResult']);
    Route::post('Rollback', [RollbackController::class, 'rollback']);
    Route::post('PlaceBet', [PlaceBetController::class, 'placeBet']);
    Route::post('CancelBet', [CancelBetController::class, 'cancelBet']);
    Route::post('BuyIn', [BuyInController::class, 'buyIn']);
    Route::post('BuyOut', [BuyOutController::class, 'buyOut']);
    Route::post('PushBet', [PushBetController::class, 'pushBet']);
    Route::post('Bonus', [BonusController::class, 'bonus']);
    Route::post('Jackpot', [JackPotController::class, 'jackPot']);
    Route::post('MobileLogin', [MobileLoginController::class, 'MobileLogin']);
    // });
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    //home page
    Route::get('/home', [HomeController::class, 'home']);

    Route::get('wager-logs', [WagerController::class, 'index']);
    Route::post('exchange-main-to-game', [TransactionController::class, 'MainToGame']);
    Route::post('exchange-game-to-main', [TransactionController::class, 'GameToMain']);

    //logout
    Route::get('user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('changePassword', [AuthController::class, 'changePassword']);
    Route::post('profile', [AuthController::class, 'profile']);
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('user-payment', [UserPaymentControler::class, 'index']);
    Route::post('user-payment-create', [UserPaymentControler::class, 'create']);
    Route::get('agent-payment-type', [UserPaymentControler::class, 'agentPayment']);

    Route::group(['prefix' => 'transaction'], function () {
        Route::post('withdraw', [WithDrawController::class, 'withdraw']);
        Route::post('deposit', [DepositController::class, 'deposit']);
        Route::get('exchange-transactionlog', [TransactionController::class, 'exchangeTransactionLog']);
        Route::get('deposit-requestlog', [TransactionController::class, 'depositRequestLog']);
        Route::get('withdraw-requestlog', [TransactionController::class, 'withDrawRequestLog']);
    });

    Route::get('payment-type', [PaymentTypeController::class, 'all']);

    Route::group(['prefix' => 'game'], function () {
        Route::post('Seamless/LaunchGame', [LaunchGameController::class, 'launchGame']);
    });
    Route::group(['prefix' => '2d'], function () {
        Route::get('user/all-2-digit', [TwoDLotteryController::class, 'get_towdigit']);
        Route::post('two-d-play', [TwoDLotteryController::class, 'store']);
        //morning history
        Route::get('user/morning-history', [UserMorningHistoryController::class, 'index']);
        Route::get('user/evening-history', [UserEveningHistoryController::class, 'index']);
        Route::get('user/morning-winner-prize', [MorningWinPrizeController::class, 'getMorningPrizeSent']);
        Route::get('user/evening-winner-prize', [EveningWinPrizeController::class, 'getEveningPrizeSent']);
        Route::get('user/all-winner-prize', [AllWinnerPrizeSentController::class, 'getAllWinnerPrizeSent']);
        Route::get('user/current-prize-digit', [InternetModernController::class, 'CurrentPrizeindex']);
        Route::get('user/internet-digit', [InternetModernController::class, 'index']);
        Route::get('user/modern-digit', [InternetModernController::class, 'Modernindex']);

        // morning slip
        Route::get('/user-morning-slip', [ApiSlipController::class, 'index']);
        Route::get('/user-2d-slip/{slipNo}', [ApiSlipController::class, 'show']);
        // evening slip
        Route::get('/user-evening-slip', [ApiSlipController::class, 'Eveningindex']);
        Route::get('/user-eveningslip/{slipNo}', [ApiSlipController::class, 'Eveningshow']);

    });

    Route::group(['prefix' => '3d'], function () {
        Route::get('/user/all-3-digit', [PlayController::class, 'get_threedigit']);
        Route::post('/user/three-d-play', [PlayController::class, 'store']);
        Route::get('/user/one-week-rec-slip', [OneWeekRecWihtSlipController::class, 'index'])->name('OneWeekRecSlipIndex');
        Route::get('/user/one-week-slip-no/{userId}/{slipNo}', [OneWeekRecWihtSlipController::class, 'show'])->name('SlipDetail');
        // first winner
        Route::get('/first-prize-winner', [LottoPrizeWinnerHistoryController::class, 'FirstPrizeWinnerApi']);
        Route::get('/first-prize-show/{running_match}', [LottoPrizeWinnerHistoryController::class, 'getFirstPrizeShowByRunningMatch']);
        // second winner
        Route::get('/second-prize-winner', [LottoPrizeWinnerHistoryController::class, 'SecondPrizeWinnerApi']);
        Route::get('/second-prize-show/{running_match}', [LottoPrizeWinnerHistoryController::class, 'getSecondPrizeShowByRunningMatch']);
        // third prize winner
        Route::get('/third-prize-winner', [LottoPrizeWinnerHistoryController::class, 'ThirdPrizeWinnerApi']);
        Route::get('/third-prize-show/{running_match}', [LottoPrizeWinnerHistoryController::class, 'getThirdPrizeShowByRunningMatch']);
        Route::get('/first-winners-history', [LottoPrizeWinnerHistoryController::class, 'ThreeDFirstWinnerHistory']);

    });
    Route::group(['prefix' => 'live-chat'], function () {
        Route::post('/messages', [MessageController::class, 'store']);
    });
});
