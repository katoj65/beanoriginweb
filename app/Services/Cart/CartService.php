<?php

namespace App\Services\Blockchain;

use App\Models\Batch;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartService{

/**
 * Add a batch to the logged-in user's active cart.
 */
static function createCart(Request $request){
// Validate required batch id and requested quantity.
$validated = $request->validate([
'batch_id' => ['required', 'integer', 'exists:batches,id'],
'quantity' => ['required', 'integer', 'min:1'],
]);

// Resolve request values and load the tokenized batch stock.
$userId = (int) $request->user()->id;
$batchId = (int) $validated['batch_id'];
$quantity = (int) $validated['quantity'];
$batch = Batch::query()
->select(['id', 'quantity'])
// ->where('status','tokenised')
->where('market_type','!=','saved')
->findOrFail($batchId);
$availableQuantity = (float) ($batch->quantity ?? 0);

// Prevent adding more than the currently available batch quantity.
if ($quantity > $availableQuantity) {
throw ValidationException::withMessages([
'quantity' => 'Requested quantity cannot be greater than the available batch quantity.',
]);
}

// Reuse existing cart line for this user + batch, otherwise create one.
$cartItem = ShoppingCart::query()->firstOrNew([
'user_id' => $userId,
'batch_id' => $batchId,
]);

// Increase existing quantity, or initialize a new active cart row.
if ($cartItem->exists) {

$batchQty = (int) $batch->quantity;
$cartQty = (int) $cartItem->quantity;
$cartQtySum = $cartQty + $quantity;

if($quantity==0){
throw ValidationException::withMessages([
'quantity' => 'Quantity cannot be 0'
]);
}

if($cartQtySum>$batchQty){
throw ValidationException::withMessages([
'quantity' => 'You have '.$cartQty.'/'.$batchQty. ' of this batch in your cart.',
]);

}

$cartItem->quantity = ((int) $cartItem->quantity) + $quantity;

} else {
$cartItem->quantity = $quantity;
$cartItem->status = 'active';
}

$cartItem->save();
return back()->with('success', 'Batch added to cart successfully.');
}












}
