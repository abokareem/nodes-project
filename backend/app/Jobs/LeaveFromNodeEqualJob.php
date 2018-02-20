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
use Illuminate\Support\Collection;

class LeaveFromNodeEqualJob implements ShouldQueue
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
        $mainInvestors = $this->mainNode->investments;
        $sameInvestors = $this->sameNode->investments;

        $invest = $this->user->investments()->where('node_id',
            $this->mainNode->id)->firstOrFail();

        $updatedUsers = $this->getUpdatedUsers($sameInvestors, $mainInvestors);

        $newInvestors = $this->sameNode->investments()->whereNotIn('user_id', $updatedUsers)->get();

        foreach ($newInvestors as $newInvestor) {
            $newInvestor->node_id = $this->mainNode->id;
        }

        Investment::createMany($newInvestors);

        $userBill = $this->user->bills()->where('currency_id',
            $this->mainNode->bill->currency_id)->first();

        $userBill->amount = $this->math->add($userBill->amount, $invest->amount);

        $userBill->save();

        $invest->delete();

        $this->sameNode->bill()->delete();
        $this->sameNode->delete();
    }

    private function getUpdatedUsers(Collection $sameInvestors, Collection $mainInvestors)
    {
        $updatedUsers = [];
        foreach ($sameInvestors as $sameInvestor) {
            foreach ($mainInvestors as $mainInvestor) {
                if ($sameInvestor->user_id === $mainInvestor->user_id) {
                    $this->updateInvestors($sameInvestor, $mainInvestor);
                    $updatedUsers[] = $sameInvestor->user_id;
                    continue;
                }
            }
        }

        return $updatedUsers;
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
