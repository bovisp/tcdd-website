import { mapGetters } from 'vuex'
import { find, forEach } from 'lodash-es'

export default {
	computed: {
		...mapGetters({
			authUser: 'user/me'
		})
	},

	methods: {
		hasRole(roles) {
			if (find(this.authUser.roles, ['name', 'administrator'])) {
				return true
			}

			let hasRole = false
			
			forEach(roles, role => {
				if (find(this.authUser.roles, r => r.name === role)) {
					hasRole = true
				}
			})

			return hasRole
		},

		$can(permissionName) {
			if (find(this.authUser.roles, ['name', 'administrator'])) {
				return true
			}
			
			if (typeof this.authUser.permissions !== 'undefined') {
				return this.authUser.permissions.indexOf(permissionName) !== -1
			}
		},
	}
}