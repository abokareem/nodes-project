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

class LeaveFromNodeLargeJob implements ShouldQueue
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
        $neededAmount = $invest->amount;
        $sameUsers = $this->sameNode->investments()->orderBy('created_at', 'desc');
        $mainUsers = $this->mainNode->investments;

        $transferInvestors = [];

        foreach ($sameUsers as $sameUser) {
            if ($neededAmount === 0) {
                break;
            }

            if ((int)$this->math->comparison($neededAmount, $sameUser->amount) ===
                MathInterface::EQUAL
            ) {
                $sameUsers->node_id = $this->mainNode->id;
                $transferInvestors[] = $sameUsers;
                break;
            }

            if ((int)$this->math->comparison($neededAmount, $sameUser->amount) ===
                MathInterface::LARGE
            ) {
                $sameUsers->node_id = $this->mainNode->id;
                $transferInvestors[] = $sameUsers;
                $neededAmount = $this->math->sub($neededAmount, $sameUser->amount);
                continue;
            }

            if ((int)$this->math->comparison($neededAmount, $sameUser->amount) ===
                MathInterface::LESS
            ) {
                $sameUsers->node_id = $this->mainNode->id;
                $sameUsers->amount = $this->math->sub($sameUser->amount, $neededAmount);
                $transferInvestors[] = $sameUsers;
                $neededAmount = 0;
                break;
            }
        }
        $newInvesters = [];
        foreach ($transferInvestors as $transferInvestor) {
            $newInvesters[] = $transferInvestor;
            foreach ($mainUsers as $mainUser) {
                if ($transferInvestor->user_id === $mainUser->user_id) {
                    $this->updateInvestors($transferInvestor, $mainUser);
                    array_pop($newInvesters);
                    continue;
                }
            }
        }

        Investment::createMany($newInvesters);

        $userBill = $this->user->bills()->where('currency_id',
            $this->mainNode->bill->currency_id)->first();

        $userBill->amount = $this->math->add($userBill->amount, $invest->amount);

        $userBill->save();

        $invest->delete();
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
