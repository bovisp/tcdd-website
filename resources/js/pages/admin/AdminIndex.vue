<template>
    <div class="w-full lg:w-8/12 py-16 mx-auto">
        <template v-if="hasRole(['administrator'])">
            <h2 class="font-normal text-3xl mb-2">
                {{ trans('js_pages_admin_adminindex.generalsettings') }}
            </h2>

            <ul class="ml-2 mb-6">
                <li>
                    <a :href="`${urlBase}/users`">
                        {{ trans('generic.users') }}
                    </a>
                </li>

                <li>
                    <a :href="`${urlBase}/permissions`">
                        {{ trans('generic.permissions') }}
                    </a>
                </li>

                <li>
                    <a :href="`${urlBase}/admin/sections`">
                        {{ trans('js_pages_admin_adminindex.sectionsettings') }}
                    </a>
                </li>

                <li>
                    <a :href="`${urlBase}/admin/tags`">
                        {{ trans('js_pages_admin_adminindex.managesitewidetags') }}
                    </a>
                </li>
            </ul>

            <h2 class="font-normal text-3xl mb-2">
                {{ trans('js_pages_admin_adminindex.trainingportalsettings') }}
            </h2>

            <ul class="ml-2 mb-6">
                <li>
                    <a :href="`${urlBase}/admin/portal/languages`">
                        {{ trans('js_pages_admin_adminindex.managecourselanguages') }}
                    </a>
                </li>

                <li>
                    <a :href="`${urlBase}/admin/portal/categories`">
                        {{ trans('js_pages_admin_adminindex.managecoursecategories') }}
                    </a>
                </li>

                <li>
                    <a :href="`${urlBase}/admin/portal/courses`">
                        {{ trans('js_pages_admin_adminindex.managecourses') }}
                    </a>
                </li>
            </ul>
        </template>
        
        <template v-if="$can('manage assessments')">
            <h2 class="font-normal text-3xl mb-2">
                {{ trans('generic.assessments') }}
            </h2>

            <ul class="ml-2 mb-6">
                <li v-if="hasRole(['administrator'])">
                    <a :href="`${urlBase}/questions/types`">
                        {{ trans('js_pages_admin_adminindex.questiontypes') }}
                    </a>
                </li>

                <li>
                    <a :href="`${urlBase}/questions/categories`">
                        {{ trans('js_pages_admin_adminindex.questionbankcategories') }}
                    </a>
                </li>

                <li>
                    <a :href="`${urlBase}/questions`">
                        {{ trans('js_pages_admin_adminindex.questionbank') }}
                    </a>
                </li>

                <li v-if="hasRole(['administrator'])">
                    <a :href="`${urlBase}/assessments/assessment-types`">
                        {{ trans('generic.assessmenttypes') }}
                    </a>
                </li>

                <li>
                    <a :href="`${urlBase}/assessments`">
                        {{ trans('generic.assessments') }}
                    </a>
                </li>
            </ul>
        </template>

        <h2 
            class="font-normal text-3xl mb-2"
            v-if="hasRole(['administrator'])"
        >
            {{ trans('generic.reports') }}
        </h2>

        <ul class="ml-2 mb-6">
             <li>
                <a :href="`${urlBase}/admin/reports`">
                    {{ trans('js_pages_admin_adminindex.viewsandcompletionmetrics') }}
                </a>
            </li>
        </ul>

        <h2 class="font-normal text-3xl mb-2">
            Blog
        </h2>

        <ul class="ml-2 mb-6">
             <li>
                <a :href="`${urlBase}/blog`">
                    Blog
                </a>
            </li>
        </ul>
    </div>
</template>