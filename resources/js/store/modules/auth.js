const state = {
	user: {},
};

const getters = {
	isAuthenticated: (state) => Object.keys(state.user).length !== 0,
	user: state => state.user
};

const mutations = {
	setUser: (state, user) => {
		state.user = user;	
	},
	unsetUser: state => {
		state.user = {};
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