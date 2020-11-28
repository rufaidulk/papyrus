const state = {
	user: null
};

const getters = {
	isAuthenticated: (state) => !!state.user,
	user: state => state.user
};

const mutations = {
	setUser: (state, user) => {
		state.user = user;	
	},
	unsetUser: state => {
		state.user = null;
	}
};

const actions = {
	login: async({commit}, user) => {
		await commit("setUser", user);
	},
	logout: async({commit}) => {
		await commit("unsetUser");
	},
};

export default {
	state,
	getters,
	actions,
	mutations,
};