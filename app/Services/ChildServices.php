<?php

namespace App\Services;

use App\Models\CognifyChild;
use App\Models\CognifyParent;
use App\Jobs\ProcessChildFiles;
use App\Http\Requests\CognifyChildRequest;
use App\Http\Resources\ParentResource;

class ChildServices
{
    public function processChildCognify(CognifyChildRequest $request): array
    {
        try {
            $parent = auth()->user();

            if (!$parent) {
                return [
                    'success' => false,
                    'message' => __('messages.unauthorized')
                ];
            }

            $data = $request->validated();
            $data['parent_id'] = $parent->id;

            // معالجة الملفات وتخزينها مؤقتاً
            $photoPath = $this->handleFileUpload($request, 'childPhoto', 'children/images');
            $audioPath = $this->handleFileUpload($request, 'voiceRecording', 'children/audio');

            $data['childPhoto'] = $photoPath;
            $data['voiceRecording'] = $audioPath;

            $child = CognifyChild::create($data);

            if ($child) {
                $this->updateParentRole($parent);
                ProcessChildFiles::dispatch($child, $photoPath, $audioPath);

                return [
                    'success' => true,
                    'data' => [
                        'child' => $this->formatChildData($child),
                        'step' => $parent->step,
                    ]
                ];
            }

            return ['success' => false];

        } catch (\Exception $e) {
            Log::error('Child cognify error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => __('messages.server_error')
            ];
        }
    }

    private function handleFileUpload($request, string $fieldName, string $storagePath): ?string
    {
        if ($request->hasFile($fieldName)) {
            return $request->file($fieldName)->store($storagePath, 'public');
        }

        return null;
    }

    private function updateParentRole($parent): void
    {
        $updateData = ['step' => 3];

        if ($parent->role === 'public-user') {
            $updateData['role'] = 'parent';
        }

        $parent->update($updateData);
    }

    private function formatChildData(CognifyChild $child): array
    {
        return [
            'id' => $child->id,
            'childPhoto' => $child->childPhoto ? asset('storage/' . $child->childPhoto) : null,
            'fullName' => $child->fullName,
            'age' => $child->age,
            'schoolName' => $child->schoolName,
            'schoolAddress' => $child->schoolAddress,
            'homeAddress' => $child->homeAddress,
            'gender' => $child->gender,
            'voiceRecording' => $child->voiceRecording ? asset('storage/' . $child->voiceRecording) : null,
            'textDescription' => $child->textDescription,
            'foodAllergies' => $child->foodAllergies,
            'environmentalAllergies' => $child->environmentalAllergies,
            'severityLevels' => $child->severityLevels,
            'medicationAllergies' => $child->medicationAllergies,
            'medicalConditions' => $child->medicalConditions,
            'created_at' => $child->created_at->toDateTimeString(),
        ];
    }


  public function details()
  {
        $parent = auth()->user();
        if (!$parent) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    
        $parent = CognifyParent::with([
            'review',
            'children.sentReports',
        ])->where('id', $parent->id)->first();
    
        return new ParentResource($parent);
    }




}