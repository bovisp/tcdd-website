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
            'content_builder_type_id' => 'required|exists:content_builder_types,id',
            'tabs' => 'required|array|min:1',
            'tabs.*' => [
                function ($attribute, $value, $fail) {
                    if (empty($value['content'])) {
                        $fail('You must add some content to the tab: ' . $value['label'] . '');
                    }
                },
            ]
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

        foreach (request('tabs') as $section) {
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
        request()->validate([
            'title' => 'nullable|min:3',
            'caption' => 'nullable|min:3',
            'tabs' =>'required|array|min:1',
            'tabs.*' => [
                function ($attribute, $value, $fail) {
                    if (empty($value['content'])) {
                        $fail('You must add some content to the tab: ' . $value['label'] . '');
                    }
                },
            ]
        ], [
            'title.min' =>  __('app_http_controllers_contentbuilder_api_mediapart.title_min'),
            'caption.min' => __('app_http_controllers_contentbuilder_api_mediapart.caption_min'),
            'tabs.required' => __('app_http_controllers_contentbuilder_api_tabpart.tabSections_required'),
            'tabs.array' => __('app_http_controllers_contentbuilder_api_tabpart.tabSections_array'),
            'tabs.min' => __('app_http_controllers_contentbuilder_api_tabpart.tabSections_min'),
        ]);

        $tabPart = TabPart::wherePartId($part->id)->first();

        $tabPart->update([
            'title' => request('title'),
            'caption' => request('caption')
        ]);

        $tabSectionIds = $tabPart->tabSections->pluck('id')->toArray();

        $tabSectionIdsFromRequest = array_map(function ($section) {
            return $section['id'];
        }, request('tabs'));

        $tabSectionsToBeDeleted = array_diff($tabSectionIds, $tabSectionIdsFromRequest);

        $tabSectionsToBeUpdated = array_intersect($tabSectionIds, $tabSectionIdsFromRequest);

        $tabSectionsToBeAdded = array_filter(request('tabs'), function ($tab) {
            return !is_numeric($tab['id']);
        });

        foreach ($tabSectionsToBeUpdated as $sectionId) {
            $section = TabPartSection::find($sectionId);

            $section->update([
                'title' => Arr::first(request('tabs'), function ($value, $key) use ($sectionId) {
                    return $value['id'] === $sectionId;
                })['label']
            ]);

            $contentClass = 'App\\' . ucfirst($section->type) . 'Part';

            $contentModel = $contentClass::find($section->content_id);

            $arrayItem = Arr::first(request('tabs'), function ($value, $key) use ($sectionId) {
                return $value['id'] === $sectionId;
            });

            if ($arrayItem['type'] === 'animation') {
                $arrayItem['content']['data']['images'] = serialize($arrayItem['content']['data']['images']);
            }

            if ($arrayItem['type'] === 'media') {
                $arrayItem['content']['data']['filename'] = serialize($arrayItem['content']['data']['filename']);
            }

            $contentModel->update($arrayItem['content']['data']);
        }

        foreach ($tabSectionsToBeDeleted as $sectionId) {
            $section = TabPartSection::find($sectionId);
            
            $sectionPartTypeClassName = 'App\\' . ucfirst($section->type) . 'Part';

            $sectionPartType = $sectionPartTypeClassName::find($section->content_id);

            $destroyClassName = 'App\Classes\ContentTypes\Destroy' . ucfirst($section->type);

            (new $destroyClassName($sectionPartType))->delete();

            $section->delete();
        }

        foreach ($tabSectionsToBeAdded as $tab) {
            TabPartSection::create([
                'title' => $tab['label'],
                'tab_part_id' => $tabPart->id,
                'content_id' => $tab['content']['data']['id'],
                'type' => $tab['type'],
                'order' => (int) $tab['order']
            ]);
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
