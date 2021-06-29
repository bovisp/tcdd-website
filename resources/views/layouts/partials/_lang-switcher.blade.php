<div class="py-3 flex">
	<strong class="mr-2">{{ __('langswitcher.switch_to') }}</strong>

	<a href="{{ url('lang/en') }}" class="{{ app()->getLocale() === 'en' ? 'hidden' : 'block' }}">
		{{ __('views_layouts_partials_langswitcher.english') }}
	</a>

	<a href="{{ url('lang/fr') }}" class="{{ app()->getLocale() === 'fr' ? 'hidden' : 'block' }}">
		{{ __('views_layouts_partials_langswitcher.french') }}
	</a>
</div>