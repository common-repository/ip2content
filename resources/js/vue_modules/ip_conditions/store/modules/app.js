export default {
    namespaced: true,

    state: () => ({
        language: null,
    }),
    mutations: {
        updateLanguage(state, language) {
            state.language = language;
            localStorage.setItem("wmip2c_language", language);
        },
    },
    actions: {
        updateLanguage({ commit }, language) {
            commit("updateLanguage", language);
        },
    },
    getters: {
        getLanguage: (state) => {
            if (state.language) {
                return state.language;
            }

            return "en";
        },
        getDataBridge: () => {
            return JSON.parse(localStorage.getItem("wmip2cDataBridge"));
        },
    },
};
