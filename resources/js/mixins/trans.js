export default {
	methods: {
		trans(key) {
			let paths = key.split('.'),
				current = window.trans,
			i;

			for (i = 0; i < paths.length; ++i) {
				if (current[paths[i]] == undefined) {
					return undefined;
				} else {
					current = current[paths[i]];
				}
			}
			
			return current;
		}
	}
}