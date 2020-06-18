<div class="py-3 flex">
	<strong class="mr-2">{{ __('langswitcher.switch_to') }}</strong>

	<a href="{{ url('lang/en') }}" class="{{ app()->getLocale() === 'en' ? 'hidden' : 'block' }}">
		English
	</a>

	<a href="{{ url('lang/fr') }}" class="{{ app()->getLocale() === 'fr' ? 'hidden' : 'block' }}">
		French
	</a>
</div>