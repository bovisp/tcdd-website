import axios from 'axios'
import store from '../store'

axios.interceptors.response.use(
	response => response,
	error => {
		if (error.response.status === 422) {
			store.dispatch('setErrors', error.response.data.errors)
		}

		if (error.response.status === 403) {
			window.events.$emit('errors-general', error.response.data.data.message)
		}

		return Promise.reject(error)
	}
)

axios.interceptors.request.use(
	config =>  {
		store.dispatch('clearErrors')

		return config
	}, 

	error =>  {
		return Promise.reject(error)
	}
);