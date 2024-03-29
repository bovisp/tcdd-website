<?php

namespace App\Http\Controllers\Admin\Portal\Categories\Api;

use App\PortalCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\Portal\PortalCategoryResource;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator'])->except(['index']);
    }

    public function index()
    {
        return PortalCategoryResource::collection(
            PortalCategory::all()
        );
    }

    public function store()
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'moodle_course_category_id' => 'integer|unique:portal_categories,id|exists:mysql2.mdl_course_categories,id',
            'moodle_parent_course_category_id' => 'nullable|integer|exists:mysql2.mdl_course_categories,id'
        ]);

        PortalCategory::create([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'moodle_course_category_id' => request('moodle_course_category_id'),
            'moodle_parent_course_category_id' => request('moodle_parent_course_category_id')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_admin_portal_categories_api_categories.store_message')
            ]
        ], 200);
    }

    public function update(PortalCategory $category)
    {
        request()->validate([
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'moodle_course_category_id' => [
                'integer',
                'exists:mysql2.mdl_course_categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    if ((int) request('moodle_course_category_id') === $category->moodle_course_category_id) {
                        return true;
                    }

                    $categoryExists = PortalCategory::where('moodle_course_category_id', (int) request('moodle_course_category_id'))->exists();

                    if ($categoryExists) {
                        $fail(__('app_http_controllers_admin_portal_categories_api_categories.coursecategoryexists'));
                    }
                }
            ],
            'moodle_parent_course_category_id' => 'integer|exists:mysql2.mdl_course_categories,id'
        ]);

        $category->update([
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'moodle_course_category_id' => request('moodle_course_category_id'),
            'moodle_parent_course_category_id' => request('moodle_parent_course_category_id')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_admin_portal_categories_api_categories.update_message')
            ]
        ], 200);
    }

    public function destroy(PortalCategory $category)
    {
        if ($category->id === 1) {
            abort(422, __('app_http_controllers_admin_portal_categories_api_categories.destroy_youcannotdelete'));
        }

        $category->courses->each->update([
            'portal_category_id' => 1
        ]);

        $category->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => __('app_http_controllers_admin_portal_categories_api_categories.destroy_message')
            ]
        ], 200);
    }
}
