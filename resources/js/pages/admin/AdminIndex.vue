<template>
    <div class="w-full lg:w-8/12 py-16 mx-auto">
        <template v-if="hasRole(['administrator'])">
            <h2 class="font-normal text-3xl mb-2">
                General Settings
            </h2>

            <ul class="ml-2 mb-6">
                <li>
                    <a :href="`${urlBase}/permissions`">
                        Permissions
                    </a>
                </li>

                <li>
                    <a :href="`${urlBase}/admin/sections`">
                        Section settings
                    </a>
                </li>
            </ul>
        </template>

        <h2 class="font-normal text-3xl mb-2">
            Training Portal Settings
        </h2>

        <ul class="ml-2 mb-6">
            <li>
                <a :href="`${urlBase}/admin/portal/languages`">
                    Manage course languages
                </a>
            </li>

            <li>
                <a :href="`${urlBase}/admin/portal/categories`">
                    Manage course categories
                </a>
            </li>

            <li>
                <a :href="`${urlBase}/admin/portal/courses`">
                    Manage courses
                </a>
            </li>
        </ul>

        
        <h2 class="font-normal text-3xl mb-2">
            Assessments
        </h2>

        <ul class="ml-2 mb-6">
            <template v-if="hasRole(['administrator'])">
                <li>
                    <a :href="`${urlBase}/assessments/assessment-types`">
                        Assessment types
                    </a>
                </li>
            </template>
        </ul>
    </div>
</template>