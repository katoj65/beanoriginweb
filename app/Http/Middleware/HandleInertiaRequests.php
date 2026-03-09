<?php

namespace App\Http\Middleware;

use App\Models\Cooperative;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\ShoppingCart;
use App\Models\BatchBid;

class HandleInertiaRequests extends Middleware
{
/**
 * The root template that's loaded on the first page visit.
 *
 * @see https://inertiajs.com/server-side-setup#root-template
 *
 * @var string
 */
protected $rootView = 'app';

/**
 * Determines the current asset version.
 *
 * @see https://inertiajs.com/asset-versioning
 */
public function version(Request $request): ?string
{
return parent::version($request);
}

/**
 * Define the props that are shared by default.
 *
 * @see https://inertiajs.com/shared-data
 *
 * @return array<string, mixed>
 */
public function share(Request $request): array
{
return [
...parent::share($request),
'user' => fn () => $request->user() ? $request->user() : null,
'cooperative' => fn () => $request->user() && $request->user()->role === 'cooperative'
? Cooperative::where('user_id', $request->user()->id)->first(): null,
'flash' => [
'success' => $request->session()->get('success'),
'error' => $request->session()->get('error'),
],

'shoppingCart' => fn () => $request->user()
? (int) ShoppingCart::query()
->where('user_id', $request->user()->id)
->where('status', 'active')
->count()
: 0,

'bidNotification' => fn () => $request->user() ? (int) BatchBid::query()->where('user_id',$request->user()->id)->where('status','pending')
->count() : 0,













];
}
}
