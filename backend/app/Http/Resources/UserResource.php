<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class User
 *
 * @SWG\Definition(
 *     definition="User",
 *     title="User",
 *     @SWG\Property(
 *      property="email",
 *      type="string",
 *      description="",
 *      example="test@example.com"
 *     ),
 *     @SWG\Property(
 *      property="email_confirmed",
 *      type="boolean",
 *      description="",
 *      example=true
 *     ),
 *     @SWG\Property(
 *      property="two_fa",
 *      type="boolean",
 *      description="",
 *      example=false
 *     ),
 *     @SWG\Property(
 *      property="invest",
 *      description="",
 *      ref="#/definitions/UserInvestments"
 *     ),
 *     @SWG\Property(
 *      property="actions",
 *      description="",
 *      ref="#/definitions/UserActions"
 *     ),
 *     @SWG\Property(
 *      property="bills",
 *      description="",
 *      ref="#/definitions/UserBills"
 *     ),
 * )
 *
 * @mixin \App\User
 */
class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'group' => $this->group_id,
            'email_confirmed' => (bool)$this->email_confirmed,
            'two_fa' => (bool)$this->two_fa,
            'invest' => MasternodeResource::collection($this->nodes),
            'actions' => UserActionsResource::collection($this->getActions()),
            'bills' => UserBillsResource::collection($this->bills)
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getActions()
    {
        return $this->actions()->latest()->limit(5)->get();
    }
}
