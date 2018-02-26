@component('mail::message')
# New Withdrawal

Coin : {{$currency->name}} <br>
Withdrawal id : {{$withdrawal->id}} <br>
Amount : {{$withdrawal->amount}} <br>

@component('mail::button', ['url' => route('show.node',['node' => $withdrawal->node->id])])
Masternode
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
