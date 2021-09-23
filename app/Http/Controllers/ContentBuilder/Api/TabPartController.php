<?php

namespace App\Http\Controllers\ContentBuilder\Api;

use App\Part;
use App\TabPart;
use App\ContentBuilder;
use App\TabPartSection;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ContentBuilder\PartResource;

class TabPartController extends Controller
{
    public function store(ContentBuilder $contentBuilder)
    {
        request()->validate([
            'title' => 'nullable|min:3',
            'caption' => 'nullable|min:3',
            'content_builder_type_id' => 'required|exists:content_builder_types,id'
        ], [
            'content_builder_type_id.required' => __('app_http_controllers_contentbuilder_api_contentpart.content_builder_type_id_required'),
            'content_builder_type_id.exists' => __('app_http_controllers_contentbuilder_api_contentpart.content_builder_type_id_exists'),
            'title.min' => __('app_http_controllers_contentbuilder_api_mediapart.title_min'),
            'caption.min' => __('app_http_controllers_contentbuilder_api_mediapart.caption_min')
        ]);

        $part = Part::create([
            'content_builder_id' => $contentBuilder->id,
            'sort_order' => count($contentBuilder->parts) ? $contentBuilder->parts->last()->sort_order + 1 : 1,
            'content_builder_type_id' => request('content_builder_type_id')
        ]);

        $tabPart = TabPart::create([
            'part_id' => $part->id,
            'title' => request('title'),
            'caption' => request('caption')
        ]);

        foreach (request('tabSections') as $section) {
            TabPartSection::create([
                'title' => $section['label'],
                'tab_part_id' => $tabPart->id,
                'content_id' => $section['content']['data']['id'],
                'type' => $section['type'],
                'order' => (int) $section['order']
            ]);
        }

        return new PartResource($part);
    }

    public function update(Part $part)
    {
        $sectionDataErrors = [];

        foreach (request('tabSections') as $key => $sectionData) {
            if ($sectionData['data']['type'] === 'content' && is_int($sectionData['id'])) {
                $validator = Validator::make($sectionData['data'], [
                    'content' => 'required'
                ]);

                if ($validator->fails()) {
                    $sectionDataErrors['tabSections.' . $key . '.data'] = [
                        'id' => $sectionData['data']['id'],
                        'error' => $validator->messages()->toArray()
                    ];
                }
            }

            if ($sectionData['data']['type'] === 'animation' && is_int($sectionData['id'])) {
                $validator = Validator::make($sectionData['data'], [
                    'title' => 'nullable|min:3',
                    'caption' => 'nullable|min:3'
                ]);

                if ($validator->fails()) {
                    $sectionDataErrors['tabSections.' . $key . '.data'] = [
                        'id' => $sectionData['data']['id'],
                        'error' => $validator->messages()->toArray()
                    ];
                }
            }

            if ($sectionData['data']['type'] === 'media' && is_int($sectionData['id'])) {
                $validator = Validator::make($sectionData['data'], [
                    'title' => 'nullable|min:3',
                    'caption' => 'nullable|min:3'
                ]);

                if ($validator->fails()) {
                    $sectionDataErrors['tabSections.' . $key . '.data'] = [
                        'id' => $sectionData['data']['id'],
                        'error' => $validator->messages()->toArray()
                    ];
                }
            }
        }

        if (count($sectionDataErrors)) {
            return response()->json([
                'errors' => $sectionDataErrors
            ], 422);
        }

        request()->validate([
            'title' => 'nullable|min:3',
            'caption' => 'nullable|min:3',
            'tabSections' => 'required|array|min:1',
            'tabSections.*.id' => 'required|exists:tab_part_sections,id',
            'tabSections.*.title' => 'required'
        ], [
            'title.min' =>  __('app_http_controllers_contentbuilder_api_mediapart.title_min'),
            'caption.min' => __('app_http_controllers_contentbuilder_api_mediapart.caption_min'),
            'tabSections.required' => __('app_http_controllers_contentbuilder_api_tabpart.tabSections_required'),
            'tabSections.array' => __('app_http_controllers_contentbuilder_api_tabpart.tabSections_array'),
            'tabSections.min' => __('app_http_controllers_contentbuilder_api_tabpart.tabSections_min'),
            'tabSections.*.id.required' => __('app_http_controllers_contentbuilder_api_tabpart.tabSections_id_required'),
            'tabSections.*.id.exists' => __('app_http_controllers_contentbuilder_api_tabpart.tabSections_id_exists'),
            'tabSections.*.title.required' => __('app_http_controllers_contentbuilder_api_tabpart.tabSections_title_required')
        ]);

        $tabPart = TabPart::wherePartId($part->id)->first();

        $tabPart->update([
            'title' => request('title'),
            'caption' => request('caption')
        ]);

        $tabSectionIds = $tabPart->tabSections->pluck('id')->toArray();

        $tabSectionIdsFromRequest = array_map(function ($section) {
            return $section['id'];
        }, request('tabSections'));

        $tabSectionsToBeDeleted = array_diff($tabSectionIds, $tabSectionIdsFromRequest);

        $tabSectionsToBeUpdated = array_intersect($tabSectionIds, $tabSectionIdsFromRequest);

        foreach ($tabSectionsToBeUpdated as $sectionId) {
            $section = TabPartSection::find($sectionId);

            $section->update([
                'title' => Arr::first(request('tabSections'), function ($value, $key) use ($sectionId) {
                    return $value['id'] === $sectionId;
                })['title']
            ]);

            $contentClass = 'App\\' . ucfirst($section->type) . 'Part';

            $contentModel = $contentClass::find($section->content_id);

            $arrayItem = Arr::first(request('tabSections'), function ($value, $key) use ($sectionId) {
                return $value['id'] === $sectionId;
            });

            if ($arrayItem['data']['type'] === 'animation') {
                $arrayItem['data']['images'] = serialize($arrayItem['data']['images']);
            }

            if ($arrayItem['data']['type'] === 'media') {
                $arrayItem['data']['filename'] = serialize($arrayItem['data']['filename']);
            }

            $contentModel->update($arrayItem['data']);
        }

        foreach ($tabSectionsToBeDeleted as $sectionId) {
            $section = TabPartSection::find($sectionId);
            
            $sectionPartTypeClassName = 'App\\' . ucfirst($section->type) . 'Part';

            $sectionPartType = $sectionPartTypeClassName::find($section->content_id);

            $destroyClassName = 'App\Classes\ContentTypes\Destroy' . ucfirst($section->type);

            (new $destroyClassName($sectionPartType))->delete();

            $section->delete();
        }

        return new PartResource($part);
    }

    public function destroyData()
    {
        $typeClassName = 'App\\' . ucfirst(request('tab')['type']) . 'Part';

        $partType = $typeClassName::find(request('tab')['content']['data']['id']);

        $destroyClassName = 'App\Classes\ContentTypes\Destroy' . ucfirst(request('tab')['type']);

        (new $destroyClassName($partType))->delete();

        return;
    }
}
