export default {
	data () {
		return {
			authUser: window.User
		}
	},

	methods: {
		hasRole(roles) {
			if (this.authUser.role === 'administrator') {
				return true
			}
			
			return roles.indexOf(this.authUser.role) > -1
		}
	}
}