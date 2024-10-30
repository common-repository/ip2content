export default {
    namespaced: true,

    state: {
        show: false,
        timeout: 4000,
        messages: [],
    },

    mutations: {
        async show(state, payload) {
            let index = state.messages.length;

            if (index) {
                index = state.messages[index - 1].index + 1;
            }

            state.messages.push({ ...payload, index });

            if (!payload.hasOwnProperty("withoutClosing")) {
                setTimeout(() => {
                    state.messages.splice(
                        state.messages.findIndex(
                            (item) => item.index === index
                        ),
                        1
                    );
                }, state.timeout);
            }

            state.show = true;
        },
        hide(state, index) {
            state.messages.splice(index, 1);
        },
        hideAll(state) {
            state.show = false;
        },
    },

    actions: {
        /**
         * @param commit
         * @param {Object} payload - Alert object
         * @param {string} payload.text
         * @param {string} payload.color
         * @param {string} payload.icon
         * @param {boolean} payload.withoutClosing
         * @param {boolean} payload.enableSound
         * @param {Function} payload.onClick
         * @param {string} payload.title
         * @param {Array} payload.list
         * */
        showSuccess({ commit }, payload) {
            if (typeof payload === "string") {
                commit("show", { text: payload, type: "success" });
            } else {
                commit("show", { ...payload, type: "success" });
            }
        },
        showError({ commit }, payload) {
            if (typeof payload === "string") {
                commit("show", { text: payload, type: "error" });
            } else {
                commit("show", { ...payload, type: "error" });
            }
        },
        showWarning({ commit }, payload) {
            if (typeof payload === "string") {
                commit("show", { text: payload, type: "warning" });
            } else {
                commit("show", { ...payload, type: "warning" });
            }
        },
        showInfo({ commit }, payload) {
            if (typeof payload === "string") {
                commit("show", { text: payload, type: "info" });
            } else {
                commit("show", { ...payload, type: "info" });
            }
        },
        hide({ commit }, index) {
            commit("hide", index);
        },
        hideAll({ commit }) {
            commit("hide");
        },
    },

    getters: {
        show: (state) => state.show,
        messages: (state) => state.messages,
        timeout: (state) => state.timeout,
    },
};
