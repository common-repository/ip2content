import Vue from "vue";
import Vuex from "vuex";
import app from "./modules/app";
import alert from "./modules/alert";
import settings from "./modules/settings";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        app,
        alert,
        settings,
    },
});
