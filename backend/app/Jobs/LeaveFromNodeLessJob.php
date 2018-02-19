<?php

namespace App\Jobs;

use App\Investment;
use App\Masternode;
use App\Services\Math\MathInterface;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LeaveFromNodeLessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $sameNode;
    private $mainNode;
    /**
     * @var MathInterface
     */
    private $math;

    /**
     * Create a new job instance.
     *
     * @param Masternode $sameNode
     * @param Masternode $mainNode
     * @param User $user
     */
    public function __construct(Masternode $sameNode, Masternode $mainNode, User $user)
    {
        $this->user = $user;
        $this->sameNode = $sameNode;
        $this->mainNode = $mainNode;
        $this->math = app(MathInterface::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invest = $this->user->investments()->where('node_id',
            $this->mainNode->id)->firstOrFail();

        $invest->amount = $this->math->sub($invest->amount, $this->sameNode->bill->amount);
        $invest->save();

        $userBill = $this->user->bills()->where('currency_id',
            $this->mainNode->bill->currency_id)->first();

        $userBill->amount = $this->math->add($userBill->amount, $this->sameNode->bill->amount);

        $userBill->save();

        $sameInvestors = $this->sameNode->investments;
        $mainUsers = $this->mainNode->investments;

        $newInvesters = [];
        foreach ($sameInvestors as $sameInvestor) {
            $newInvesters[] = $sameInvestor;
            foreach ($mainUsers as $mainUser) {
                if ($sameInvestor->user_id === $mainUser->user_id) {
                    $this->updateInvestors($sameInvestor, $mainUser);
                    array_pop($newInvesters);
                    continue;
                }
            }
        }

        Investment::createMany($newInvesters);
    }

    private function updateInvestors(Investment $sameInvestor, Investment $mainInvestor)
    {
        $mainInvestor->getConnection()->transaction(
            function () use ($sameInvestor, $mainInvestor) {

                $mainInvestor->amount = $this->math
                    ->add($mainInvestor->amount, $sameInvestor->amount);

                $mainInvestor->save();

                $sameInvestor->delete();
            });
    }
}
