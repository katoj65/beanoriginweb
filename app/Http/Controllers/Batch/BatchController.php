<?php

namespace App\Http\Controllers\Batch;

use App\Http\Controllers\Controller;
use App\Http\Resources\BatchResource;
use App\Models\Batch;
use App\Models\CropGrade;
use App\Models\Crops;
use App\Models\UserProfile;
use App\Services\Blockchain\BatchChainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display the create form.
     */
    public function create(Request $request)
    {
        $crops = Crops::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $grades = CropGrade::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('BatchCreate', [
            'crops' => $crops,
            'grades' => $grades,
            'status_options' => ['created', 'processing', 'processed', 'hulled', 'graded', 'listed', 'sold'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, BatchChainService $batchChainService)
    {
        $validated = $request->validate([
            'batch_code' => ['required', 'string', 'max:255', 'unique:batches,batch_code'],
            'commodity_name' => ['required', 'string', 'max:255', 'exists:crops,name'],
            'commodity_type' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'numeric', 'min:0.01'],
            'grade' => ['required', 'string', 'max:100', 'exists:crop_grades,name'],
            'moisture' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'warehouse' => ['required', 'string', 'max:255'],
        ]);

        $batch = DB::transaction(function () use ($request, $validated, $batchChainService) {
            $batch = Batch::create([
                'owner_id' => $request->user()->id,
                'batch_code' => $validated['batch_code'],
                'commodity_name' => $validated['commodity_name'],
                'commodity_type' => $validated['commodity_type'],
                'weight' => $validated['weight'],
                'grade' => $validated['grade'],
                'moisture' => $validated['moisture'] ?? null,
                'warehouse' => $validated['warehouse'],
                'is_on_chain' => false,
                'status' => 'created',
            ]);

            $batchChainService->addBlock($batch);
            $batch->update(['is_on_chain' => true]);

            return $batch;
        });

        return redirect()
            ->route('cooperative.batches.show', ['id' => $batch->id])
            ->with('success', 'Batch created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $batch = Batch::query()
            ->with('owner:id,fname,lname,email')
            ->where('owner_id', auth()->id())
            ->where('is_on_chain', 1)
            ->findOrFail($id);

        // Query profile using the user who owns this batch.
        $ownerProfile = UserProfile::query()
            ->where('user_id', $batch->owner_id)
            ->first(['id', 'user_id', 'tel', 'address']);

        if ($batch->owner) {
            $batch->owner->setRelation('userProfile', $ownerProfile);
        }

        return Inertia::render('BatchDetailsPage', [
            'batch' => new BatchResource($batch),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }




    public function batchVerificationAction(Request $request)
    {
        $validate = $request->validate([
            'batch_number' => ['required', 'string', 'max:255'],
            'batch_action' => ['required', 'string', 'max:255'],
        ]);

        $batch = Batch::query()
            ->where('batch_code', $validate['batch_number'])
            ->where('owner_id', auth()->id())
            ->where('status', 'created')
            ->first();

        if (!$batch) {
            return back()->withErrors([
                'batch_number' => 'Batch not found.',
            ])->withInput();
        }

        return redirect()->route('cooperative.batches.action.page', [
            'batch_id' => $batch->id,
            'batch_action' => $validate['batch_action'],
        ]);
    }








    // Render summary page for selected batch and intended action.
    public function BatchActionPage(Request $request)
    {
        $batchId = (int) $request->input('batch_id');
        $selectedAction = (string) $request->input('batch_action', '');

        $batch = Batch::query()
            ->where('id', $batchId)
            ->where('owner_id', auth()->id())
            ->where('status','!=', 'bought')
            ->first();

        if (!$batch) {
            return redirect()
                ->route('cooperative.batches.listed')
                ->withErrors(['batch_number' => 'Batch not found for action summary.']);
        }

        return Inertia::render('BatchActionPage', [
            'selection' => [
                'selected_action' => $selectedAction,
                'batch_number' => $batch->batch_code,
            ],
            'batch' => new BatchResource($batch),
        ]);
    }


















    
}
