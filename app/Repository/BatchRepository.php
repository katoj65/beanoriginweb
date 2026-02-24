<?php

namespace App\Repository;
use App\Models\Block;

class BatchRepository
{
public static function getBatchWithLatestBlock()
{
return Block::query()
->select(
'batches.id',
'batches.commodity_name',
'batches.batch_code',
'blocks.created_at',
'blocks.price',
'blocks.weight',
'blocks.event_type',
'blocks.event_data'
)
->join('batches', 'blocks.batch_id', '=', 'batches.id')
->where('blocks.event_type', 'listed')
->whereIn('blocks.block_index', function ($query) {
$query->from('blocks')
->selectRaw('MAX(block_index)')
->where('event_type', 'listed')
->groupBy('batch_id');
})
->get();
}



static function getSoldBatches(){
return Block::query()->where('status', 'sold')
->where('owner_id', auth()->id())
->get();
}


static function getListedBatches(){
return Block::query()->where('status', 'listed')
->where('owner_id', auth()->id())
->get();
}



static function getBatchById($id){
return Block::query()->where('batch_id', $id)->get();
}

















}

